<?php

namespace App\Repositories\Contracts;

use App\Models\GuideCategory;
use Illuminate\Support\Collection;

/**
 * Контракт доступа к данным категорий гайдов.
 */
interface GuideCategoryRepositoryInterface
{
    /**
     * Получить все категории в порядке сортировки.
     */
    public function getAll(): Collection;

    /**
     * Категории для селектов (кэш 1 час).
     */
    public function getForSelect(): Collection;

    /**
     * Сбросить кэш списка категорий для селектов.
     */
    public function forgetCategoriesForSelectCache(): void;

    /**
     * Найти категорию по id.
     */
    public function getById(int $id): GuideCategory;

    /**
     * Создать категорию.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): GuideCategory;

    /**
     * Обновить категорию.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(GuideCategory $category, array $data): GuideCategory;

    /**
     * Удалить категорию.
     */
    public function delete(GuideCategory $category): void;

    /**
     * Переупорядочить категории по переданным id.
     *
     * @param  list<int>  $ids
     */
    public function reorder(array $ids): void;
}
