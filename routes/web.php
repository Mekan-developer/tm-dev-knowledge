<?php

use App\Http\Controllers\Admin\AdminGuideController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\GuideController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GuideController::class, 'index'])->name('home');

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

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/guides/create', [AdminGuideController::class, 'create'])->name('guides.create');
    Route::post('/guides', [AdminGuideController::class, 'store'])->name('guides.store');
    Route::get('/guides/{guide}/edit', [AdminGuideController::class, 'edit'])->name('guides.edit');
    Route::put('/guides/{guide}', [AdminGuideController::class, 'update'])->name('guides.update');
    Route::delete('/guides/{guide}', [AdminGuideController::class, 'destroy'])->name('guides.destroy');
});
