<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Auth
Route::get('/auth/{provider}', [App\Http\Controllers\Auth\AuthController::class, 'provider'])->name('provider');
Route::get('/auth/callback/{provider}', [App\Http\Controllers\Auth\AuthController::class, 'callback'])->name('callback');

Auth::routes();