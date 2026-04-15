<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UmumController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengaturanProfilController;
use App\Http\Controllers\ManajemenKeuanganController;
use App\Http\Controllers\ManajemenLaporanController;
use App\Http\Controllers\DashboardController;

// Public routes
Route::get('/', [UmumController::class, 'index'])->name('index');

// Admin authentication routes
Route::prefix('admin')->group(function () {
    // Login routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('auth.login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('auth.login.post');

    // Forgot PIN routes
    Route::get('/forgot-pin', [AuthController::class, 'showForgotPin'])
        ->name('auth.forgot-pin');

    Route::post('/forgot-pin/verify', [AuthController::class, 'verifyPhone'])
        ->name('auth.verify-phone');

    Route::get('/reset-pin', [AuthController::class, 'showResetPin'])
        ->name('auth.reset-pin');

    Route::post('/reset-pin', [AuthController::class, 'resetPin'])
        ->name('auth.reset-pin.post');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('auth.logout');

    // Protected admin routes
    Route::middleware(['admin'])->group(function () {
        // Dashboard routes - gunakan controller
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('admins.dashboard');

        // Route untuk chart data - PENTING: harus berada dalam middleware admin
        Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])
            ->name('admins.dashboard.chart-data');

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

            Route::post('/import', [ManajemenKeuanganController::class, 'import'])
                ->name('admins.manajemen-keuangan.import');

            Route::get('/export', [ManajemenKeuanganController::class, 'export'])
                ->name('admins.manajemen-keuangan.export');

            Route::get('/download-template', [ManajemenKeuanganController::class, 'downloadTemplate'])
                ->name('admins.manajemen-keuangan.download-template');

            Route::post('/preview-import', [ManajemenKeuanganController::class, 'previewImport'])
                ->name('admins.manajemen-keuangan.preview-import');
        });

        Route::prefix('manajemen-laporan')->group(function () {
            Route::get('/', [ManajemenLaporanController::class, 'index'])
                ->name('admins.manajemen-laporan');
            
            Route::get('/export-pdf', [ManajemenLaporanController::class, 'exportPDF'])
                ->name('admins.manajemen-laporan.export-pdf');
            
            Route::get('/export-excel', [ManajemenLaporanController::class, 'exportExcel'])
                ->name('admins.manajemen-laporan.export-excel');
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