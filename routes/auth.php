<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Web pages (simple forms)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('forgot.form');

// API endpoints
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
        Route::post('profile', [AuthController::class, 'updateProfile']);

        // admin
        Route::post('users/{id}/assign-role', [AuthController::class, 'assignRole']);
        Route::post('users/{id}/revoke-role', [AuthController::class, 'revokeRole']);
        Route::post('users/{id}/activate', [AuthController::class, 'activate']);
        Route::post('users/{id}/deactivate', [AuthController::class, 'deactivate']);
        Route::get('users/statuses', [AuthController::class, 'statuses']);
    });
});
