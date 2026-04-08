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
