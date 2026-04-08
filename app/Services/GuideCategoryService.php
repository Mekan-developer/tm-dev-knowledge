<?php

namespace App\Services;

use App\Models\GuideCategory;
use App\Repositories\Contracts\GuideCategoryRepositoryInterface;
use Exception;
use Illuminate\Support\Collection;

/**
 * Бизнес-логика управления категориями гайдов.
 */
class GuideCategoryService
{
    public function __construct(
        private GuideCategoryRepositoryInterface $guideCategoryRepository,
    ) {}

    /**
     * Получить список категорий для админки.
     */
    public function getAllCategories(): Collection
    {
        return $this->guideCategoryRepository->getAll();
    }

    /**
     * Создать категорию.
     *
     * @param  array<string, mixed>  $data
     */
    public function createCategory(array $data): GuideCategory
    {
        return $this->guideCategoryRepository->create($data);
    }

    /**
     * Обновить категорию.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateCategory(GuideCategory $category, array $data): GuideCategory
    {
        return $this->guideCategoryRepository->update($category, $data);
    }

    /**
     * Удалить категорию, если у нее нет связанных гайдов.
     *
     * @throws Exception
     */
    public function deleteCategory(GuideCategory $category): void
    {
        if ($category->guides()->exists()) {
            throw new Exception('Нельзя удалить категорию, в которой есть гайды.');
        }

        $this->guideCategoryRepository->delete($category);
    }

    /**
     * Сохранить новый порядок категорий.
     *
     * @param  list<int>  $ids
     */
    public function reorderCategories(array $ids): void
    {
        $this->guideCategoryRepository->reorder($ids);
    }
}
