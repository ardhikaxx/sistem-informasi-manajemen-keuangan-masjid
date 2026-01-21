<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/admin/login', function () {
    return view('auth.login');
})->name('auth.login');