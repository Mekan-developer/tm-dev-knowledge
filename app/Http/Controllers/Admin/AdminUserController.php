<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreContributorRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Управление контрибьюторами (/admin/users).
 */
class AdminUserController extends Controller
{
    public function __construct(
        private UserService $userService,
    ) {}

    /**
     * Таблица контрибьюторов.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Users', [
            'contributors' => $this->userService->getContributorsForAdminIndex(),
        ]);
    }

    /**
     * Создание контрибьютора.
     */
    public function store(StoreContributorRequest $request): RedirectResponse
    {
        $this->userService->createContributor($request->validated());

        return redirect()->route('admin.users.index');
    }

    /**
     * Удаление контрибьютора.
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->userService->deleteContributor($user);

        return redirect()->route('admin.users.index');
    }
}
