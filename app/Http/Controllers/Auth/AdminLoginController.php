<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Вход администратора: /admin/login и POST /admin/logout.
 */
class AdminLoginController extends Controller
{
    /**
     * Форма входа администратора.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Login');
    }

    /**
     * Аутентификация только с ролью admin.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();
        if ($user === null || $user->role !== UserRole::Admin) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Выход из админки.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user === null || ! $user->isAdmin()) {
            return redirect()->route('home');
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
