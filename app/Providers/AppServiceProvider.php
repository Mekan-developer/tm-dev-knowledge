<?php

namespace App\Providers;

use App\Repositories\Contracts\GuideRepositoryInterface;
use App\Repositories\Contracts\GuideCategoryRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\GuideCategoryRepository;
use App\Repositories\GuideRepository;
use App\Repositories\UserRepository;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
