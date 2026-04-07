<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\Contracts\GuideRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

/**
 * Бизнес-логика пользователей и контрибьюторов в админке.
 */
class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private GuideRepositoryInterface $guideRepository,
    ) {}

    /**
     * Список контрибьюторов для страницы /admin/users (массив для Inertia).
     *
     * @return list<array<string, mixed>>
     */
    public function getContributorsForAdminIndex(): array
    {
        return $this->userRepository->getContributors()
            ->map(fn (User $u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'guides_count' => $u->guides_count,
                'created_at' => $u->created_at?->toIso8601String(),
            ])
            ->values()
            ->all();
    }

    /**
     * Создать контрибьютора (пароль хэшируется здесь).
     *
     * @param  array{name: string, email: string, password: string}  $data
     */
    public function createContributor(array $data): User
    {
        return $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => UserRole::Contributor,
        ]);
    }

    /**
     * Удалить контрибьютора; гайды остаются с автором «Deleted user». Админа удалить нельзя.
     */
    public function deleteContributor(User $user): void
    {
        if ($user->isAdmin()) {
            abort(403);
        }

        abort_unless($user->role === UserRole::Contributor, 404);

        $this->guideRepository->orphanGuidesForUserId($user->id);
        $this->userRepository->delete($user);
    }
}
