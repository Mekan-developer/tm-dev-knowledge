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
        $category = (string) ($filters['category'] ?? 'All');
        $userId = $filters['user_id'] ?? null;
        $perPage = (int) ($filters['per_page'] ?? 12);

        return $this->baseFilteredQuery($q, $category, $userId)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * {@inheritdoc}
     */
    public function getById(string $id): Guide
    {
        return Guide::query()->findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getByUser(User $user): Collection
    {
        return Guide::query()
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
    public function search(string $query, ?string $category): LengthAwarePaginator
    {
        return $this->getAll([
            'q' => $query,
            'category' => $category ?? 'All',
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
    private function baseFilteredQuery(string $q, string $category, mixed $userId): Builder
    {
        return Guide::query()
            ->when($userId !== null && $userId !== '', function (Builder $query) use ($userId) {
                $query->where('user_id', (int) $userId);
            })
            ->when($category !== '' && $category !== 'All', fn (Builder $query) => $query->where('category', $category))
            ->when($q !== '', function (Builder $query) use ($q) {
                $query->where(function (Builder $inner) use ($q) {
                    $inner->where('title', 'like', '%'.$q.'%')
                        ->orWhere('description', 'like', '%'.$q.'%')
                        ->orWhereJsonContains('tags', $q);
                });
            });
    }
}
