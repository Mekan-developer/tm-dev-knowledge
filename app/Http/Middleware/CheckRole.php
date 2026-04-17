<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Проверка роли пользователя (используется для зоны /admin).
 */
class CheckRole
{
    /**
     * @param  Closure(Request): Response  $next
     * @param  string  $role  Ожидаемое значение роли (например admin).
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if ($user === null || $user->role->value !== $role) {
            abort(403);
        }

        return $next($request);
    }
}
