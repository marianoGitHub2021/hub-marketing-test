<?php

use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/accesso', [SessionController::class, 'create'])->name('login');
    Route::get('/auth/google/redirect', [GoogleLoginController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleLoginController::class, 'callback'])->name('auth.google.callback');
    Route::post('/accesso/sviluppo', [SessionController::class, 'devLogin'])->name('auth.dev');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/', [PageController::class, 'overview'])->name('overview');
    Route::get('/clienti', [PageController::class, 'clients'])->name('clients');
    Route::get('/allerte', [PageController::class, 'alerts'])->name('alerts');
    Route::get('/impostazioni', [PageController::class, 'settings'])->name('settings')->can('admin');
    Route::post('/esci', [SessionController::class, 'destroy'])->name('logout');
});
