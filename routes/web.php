<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'app')
    ->middleware(['auth', 'verified'])
    ->name('app');

Route::view('dashboard', 'app')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
