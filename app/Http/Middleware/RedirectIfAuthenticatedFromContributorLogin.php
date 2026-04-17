<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Для страницы /login контрибьютора: уже вошедших перенаправляет в нужный кабинет.
 */
class RedirectIfAuthenticatedFromContributorLogin
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        if ($user->role === UserRole::Admin) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }
}
