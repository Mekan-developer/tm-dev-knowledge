<?php

namespace App\Repositories;

use App\Models\GuideCategory;
use App\Repositories\Contracts\GuideCategoryRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Реализация запросов к таблице guide_categories.
 */
class GuideCategoryRepository implements GuideCategoryRepositoryInterface
{
    private const CACHE_KEY = 'categories_for_select';

    /**
     * {@inheritdoc}
     */
    public function getAll(): Collection
    {
        return GuideCategory::query()
            ->withCount('guides')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getForSelect(): Collection
    {
        return Cache::remember(self::CACHE_KEY, now()->addHour(), fn () => GuideCategory::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'color', 'slug']));
    }

    /**
     * {@inheritdoc}
     */
    public function forgetCategoriesForSelectCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function getById(int $id): GuideCategory
    {
        return GuideCategory::query()->findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): GuideCategory
    {
        return GuideCategory::query()->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(GuideCategory $category, array $data): GuideCategory
    {
        $category->update($data);

        return $category->fresh();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(GuideCategory $category): void
    {
        $category->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function reorder(array $ids): void
    {
        foreach (array_values($ids) as $index => $id) {
            GuideCategory::query()
                ->whereKey((int) $id)
                ->update(['sort_order' => $index + 1]);
        }
    }
}
