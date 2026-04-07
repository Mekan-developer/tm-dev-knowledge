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
 * Вход контрибьютора: /login и POST /logout (для контрибьютора).
 */
class LoginController extends Controller
{
    /**
     * Форма входа.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ContributorLogin');
    }

    /**
     * Аутентификация только с ролью contributor.
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
        if ($user === null || $user->role !== UserRole::Contributor) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    /**
     * Выход контрибьютора.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user === null || ! $user->isContributor()) {
            return redirect()->route('admin.dashboard');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
