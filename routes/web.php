<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengaturanProfilController;

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
            return view('admins.manajemen-keuangan.index');
        })->name('admins.manajemen-keuangan');

        Route::get('/manajemen-laporan', function () {
            return view('admins.manajemen-laporan.index');
        })->name('admins.manajemen-laporan');

        // Pengaturan Profil Routes
        Route::prefix('pengaturan-profil')->group(function () {
            Route::get('/', [PengaturanProfilController::class, 'index'])
                ->name('admins.pengaturan-profil');
            
            Route::post('/update', [PengaturanProfilController::class, 'update'])
                ->name('admins.pengaturan-profil.update');
        });
    });
});

// Fallback route untuk 404
Route::fallback(function () {
    return redirect()->route('index');
});