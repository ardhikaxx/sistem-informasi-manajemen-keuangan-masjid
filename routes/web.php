<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Public routes
Route::get('/', function () {
    return view('index');
})->name('index');

// Admin authentication routes
Route::prefix('admin')->group(function () {
    // Login routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('auth.login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('auth.login.post');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('auth.logout');

    // Protected admin routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])
            ->name('admins.dashboard');

        Route::get('/manajemen-keuangan', function () {

            return view('admins.manajemen-keuangan.index', compact('admin'));
        })->name('admins.manajemen-keuangan');

        Route::get('/manajemen-laporan', function () {

            return view('admins.manajemen-laporan.index', compact('admin'));
        })->name('admins.manajemen-laporan');

        Route::get('/pengaturan-profil', function () {

            return view('admins.pengaturan-profil.index', compact('admin'));
        })->name('admins.pengaturan-profil');
    });
});