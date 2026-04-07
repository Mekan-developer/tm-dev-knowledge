<?php

namespace App\Repositories\Contracts;

use App\Models\Guide;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Контракт доступа к данным гайдов (только запросы к БД).
 */
interface GuideRepositoryInterface
{
    /**
     * Пагинированный список с фильтрами: q, category, user_id?, per_page.
     */
    public function getAll(array $filters): LengthAwarePaginator;

    /**
     * Гайд по ULID или 404.
     */
    public function getById(string $id): Guide;

    /**
     * Все гайды указанного пользователя.
     */
    public function getByUser(User $user): Collection;

    /**
     * Создание записи.
     */
    public function create(array $data): Guide;

    /**
     * Обновление записи.
     */
    public function update(Guide $guide, array $data): Guide;

    /**
     * Удаление записи.
     */
    public function delete(Guide $guide): void;

    /**
     * Поиск по тексту и опциональной категории (пагинация 12).
     */
    public function search(string $query, ?string $category): LengthAwarePaginator;

    /**
     * Отвязать гайды автора при удалении контрибьютора (author_name = Deleted user).
     */
    public function orphanGuidesForUserId(int $userId): void;
}
