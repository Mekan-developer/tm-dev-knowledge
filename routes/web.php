<?php

use App\Http\Controllers\Admin\AdminGuideController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GuideController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

RateLimiter::for('contact', function (Request $request) {
    return Limit::perHour(3)->by($request->ip())->response(function () {
        return back()->withErrors([
            'contact' => 'Hatlar köp. Soňra täzeden synanyşyň.',
        ]);
    });
});

Route::get('/', [GuideController::class, 'index'])->name('home');
Route::post('/contact', [ContactController::class, 'send'])->middleware('throttle:contact')->name('contact.send');

Route::middleware('auth')->group(function () {
    Route::get('/my-guides', [GuideController::class, 'myGuides'])->name('my-guides');

    Route::get('/guides/create', [GuideController::class, 'create'])->name('guides.create');
    Route::post('/guides', [GuideController::class, 'store'])->name('guides.store');
    Route::get('/guides/{guide}/edit', [GuideController::class, 'edit'])->name('guides.edit');
    Route::put('/guides/{guide}', [GuideController::class, 'update'])->name('guides.update');
    Route::delete('/guides/{guide}', [GuideController::class, 'destroy'])->name('guides.destroy');

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

Route::get('/guides/{guide}', [GuideController::class, 'show'])->name('guides.show');

Route::middleware('guest.contributor')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('guest.admin')->group(function () {
    Route::get('/admin/login', [AdminLoginController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AdminLoginController::class, 'store']);
});

Route::middleware(['auth', 'role:admin,contributor'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
    Route::get('/guides/create', [AdminGuideController::class, 'create'])->name('guides.create');
    Route::post('/guides', [AdminGuideController::class, 'store'])->name('guides.store');
    Route::get('/guides/{guide}/edit', [AdminGuideController::class, 'edit'])->name('guides.edit');
    Route::put('/guides/{guide}', [AdminGuideController::class, 'update'])->name('guides.update');
    Route::delete('/guides/{guide}', [AdminGuideController::class, 'destroy'])->name('guides.destroy');
});
