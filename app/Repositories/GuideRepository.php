<?php

namespace App\Repositories;

use App\Models\Guide;
use App\Models\User;
use App\Repositories\Contracts\GuideRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Реализация запросов к таблице guides.
 */
class GuideRepository implements GuideRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAll(array $filters): LengthAwarePaginator
    {
        $q = (string) ($filters['q'] ?? '');
        $guideCategoryId = isset($filters['guide_category_id']) ? (int) $filters['guide_category_id'] : null;
        $userId = $filters['user_id'] ?? null;
        $perPage = (int) ($filters['per_page'] ?? 12);

        return $this->baseFilteredQuery($q, $guideCategoryId, $userId)
            ->with('category')
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * {@inheritdoc}
     */
    public function getById(string $id): Guide
    {
        return Guide::query()->with('category')->findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getByUser(User $user): Collection
    {
        return Guide::query()
            ->with('category')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): Guide
    {
        return Guide::query()->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Guide $guide, array $data): Guide
    {
        $guide->update($data);

        return $guide->fresh();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Guide $guide): void
    {
        $guide->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function search(string $query, ?int $guideCategoryId): LengthAwarePaginator
    {
        return $this->getAll([
            'q' => $query,
            'guide_category_id' => $guideCategoryId,
            'per_page' => 12,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function orphanGuidesForUserId(int $userId): void
    {
        Guide::query()->where('user_id', $userId)->update([
            'user_id' => null,
            'author_name' => 'Deleted user',
        ]);
    }

    /**
     * Общий запрос фильтрации для списков и поиска.
     */
    private function baseFilteredQuery(string $q, ?int $guideCategoryId, mixed $userId): Builder
    {
        return Guide::query()
            ->when($userId !== null && $userId !== '', function (Builder $query) use ($userId) {
                $query->where('user_id', (int) $userId);
            })
            ->when($guideCategoryId !== null, fn (Builder $query) => $query->where('guide_category_id', $guideCategoryId))
            ->when($q !== '', function (Builder $query) use ($q) {
                $query->where(function (Builder $inner) use ($q) {
                    $inner->where('title', 'like', '%'.$q.'%')
                        ->orWhere('description', 'like', '%'.$q.'%')
                        ->orWhereJsonContains('tags', $q);
                });
            });
    }
}
