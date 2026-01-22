<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/admin/login', function () {
    return view('auth.login');
})->name('auth.login');

Route::get('/admin/dashboard', function () {
    return view('admins.dashboard.index');
})->name('admins.dashboard');

Route::get('/admin/manajemen-keuangan', function () {
    return view('admins.manajemen-keuangan.index');
})->name('admins.manajemen-keuangan');

Route::get('/admin/manajemen-laporan', function () {
    return view('admins.manajemen-laporan.index');
})->name('admins.manajemen-laporan');

Route::get('/admin/pengaturan-profil', function () {
    return view('admins.pengaturan-profil.index');
})->name('admins.pengaturan-profil');