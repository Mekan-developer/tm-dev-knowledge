<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\RedirectIfAuthenticatedFromAdminLogin;
use App\Http\Middleware\RedirectIfAuthenticatedFromContributorLogin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
        $middleware->alias([
            'role' => CheckRole::class,
            'guest.admin' => RedirectIfAuthenticatedFromAdminLogin::class,
            'guest.contributor' => RedirectIfAuthenticatedFromContributorLogin::class,
        ]);
        $middleware->redirectGuestsTo(function (Request $request) {
            if (str_starts_with($request->path(), 'admin')) {
                return route('admin.login');
            }

            return route('login');
        });
        $middleware->redirectUsersTo(fn () => route('home'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
