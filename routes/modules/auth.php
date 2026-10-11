<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/* Public */
Route::get('/', [AuthController::class, 'showLoginForm']);

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login.form');
    Route::post('/login', 'login')->middleware('throttle:10,1')->name('login');

    Route::get('/register', 'showRegisterForm')->name('register.form');
    Route::post('/register', 'register')->name('register');

    Route::get('/forgot-password', 'showForgotForm')->name('forgot.form');
    Route::post('/forgot-password', 'forgotPassword')->middleware('throttle:5,1')->name('password.email');

    Route::post('/logout', 'logout')->middleware('auth')->name('logout');
});

/* Profile */
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/', [AuthController::class, 'profileForm'])->middleware('permission:profile.view')->name('show');
    Route::get('/edit', [AuthController::class, 'profileEditForm'])->middleware('permission:profile.view')->name('edit');
    Route::post('/update', [AuthController::class, 'updateProfile'])->middleware('permission:profile.update')->name('update');
    Route::get('/change-password', [AuthController::class, 'passwordForm'])->middleware('permission:profile.update')->name('password');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('permission:profile.update')->name('change-password');
});
