<?php

namespace App\Repositories;

use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Реализация запросов к таблице users.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAll(): Collection
    {
        return User::query()->orderBy('id')->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getById(int $id): User
    {
        return User::query()->findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getContributors(): Collection
    {
        return User::query()
            ->where('role', UserRole::Contributor)
            ->withCount('guides')
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): User
    {
        return User::query()->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user->fresh();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(User $user): void
    {
        $user->delete();
    }
}
