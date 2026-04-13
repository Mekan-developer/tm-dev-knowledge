<?php

namespace App\Providers;

use App\Models\User;
use App\Repositories\Contracts\GuideRepositoryInterface;
use App\Repositories\Contracts\GuideCategoryRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\GuideCategoryRepository;
use App\Repositories\GuideRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GuideRepositoryInterface::class, GuideRepository::class);
        $this->app->bind(GuideCategoryRepositoryInterface::class, GuideCategoryRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        if ($this->app->environment('local')) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Gate::define('viewPulse', fn (?User $user) => $user?->isAdmin() ?? false);
    }
}
