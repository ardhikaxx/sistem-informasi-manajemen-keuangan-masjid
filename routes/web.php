<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengaturanProfilController;
use App\Http\Controllers\ManajemenKeuanganController;
use App\Http\Controllers\ManajemenLaporanController;

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

        // Manajemen Keuangan Routes
        Route::prefix('manajemen-keuangan')->group(function () {
            Route::get('/', [ManajemenKeuanganController::class, 'index'])
                ->name('admins.manajemen-keuangan');

            Route::post('/store', [ManajemenKeuanganController::class, 'store'])
                ->name('admins.manajemen-keuangan.store');

            Route::get('/edit/{id}', [ManajemenKeuanganController::class, 'edit'])
                ->name('admins.manajemen-keuangan.edit');

            Route::put('/update/{id}', [ManajemenKeuanganController::class, 'update'])
                ->name('admins.manajemen-keuangan.update');

            Route::delete('/delete/{id}', [ManajemenKeuanganController::class, 'destroy'])
                ->name('admins.manajemen-keuangan.delete');

            Route::get('/statistics', [ManajemenKeuanganController::class, 'getStatistics'])
                ->name('admins.manajemen-keuangan.statistics');
        });

        Route::prefix('manajemen-laporan')->group(function () {
            Route::get('/', [ManajemenLaporanController::class, 'index'])
                ->name('admins.manajemen-laporan');
            // Route untuk export PDF akan ditambahkan nanti
            // Route::post('/export', [ManajemenLaporanController::class, 'exportPDF'])
            //      ->name('admins.manajemen-laporan.export');
        });

        Route::get('/manajemen-laporan/print', function () {
            return view('admins.manajemen-laporan.print');
        })->name('admins.manajemen-laporan.print');

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