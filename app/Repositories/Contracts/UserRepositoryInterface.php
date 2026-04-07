<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Контракт доступа к данным пользователей.
 */
interface UserRepositoryInterface
{
    /**
     * Все пользователи.
     */
    public function getAll(): Collection;

    /**
     * Пользователь по id или 404.
     */
    public function getById(int $id): User;

    /**
     * Пользователи с ролью contributor (с подсчётом гайдов для списка админки).
     */
    public function getContributors(): Collection;

    /**
     * Создание пользователя.
     */
    public function create(array $data): User;

    /**
     * Обновление пользователя.
     */
    public function update(User $user, array $data): User;

    /**
     * Удаление пользователя.
     */
    public function delete(User $user): void;
}
