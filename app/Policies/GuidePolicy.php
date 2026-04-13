<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Guide;
use App\Models\User;

/**
 * Права на создание и изменение гайдов (админ — всё, контрибьютор — только свои).
 */
class GuidePolicy
{
    /**
     * Создание гайда доступно админу и контрибьютору.
     */
    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin || $user->role === UserRole::Contributor;
    }

    /**
     * Обновление: админ — любой гайд; контрибьютор — только свой (user_id совпадает).
     */
    public function update(User $user, Guide $guide): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        if ($user->role !== UserRole::Contributor) {
            return false;
        }

        return $guide->user_id !== null && (int) $guide->user_id === (int) $user->id;
    }

    /**
     * Удаление: админ или владелец гайда.
     */
    public function delete(User $user, Guide $guide): bool
    {
        return $user->isAdmin() || ($guide->user_id !== null && (int) $guide->user_id === (int) $user->id);
    }
}
