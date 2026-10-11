<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Support\RouteHelper as R;
use Illuminate\Support\Facades\Route;

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('permission:dashboard.view')->name('dashboard');

/* User Management */
Route::prefix('users')->name('users.')->group(function () {
    $c = UserManagementController::class;

    Route::get('/', [$c, 'index'])->middleware('permission:users.view')->name('index');
    Route::get('/create', [$c, 'create'])->middleware('permission:users.create')->name('create');
    Route::post('/', [$c, 'store'])->middleware('permission:users.create')->name('store');
    Route::get('/{user}', [$c, 'show'])->middleware('permission:users.view')->name('show');
    Route::get('/{user}/edit', [$c, 'edit'])->middleware('permission:users.edit')->name('edit');
    Route::put('/{user}', [$c, 'update'])->middleware('permission:users.edit')->name('update');
    Route::delete('/{user}', [$c, 'destroy'])->middleware('permission:users.delete')->name('destroy');

    Route::post('/{user}/assign-role', [$c, 'assignRole'])->middleware('permission:users.edit')->name('assign-role');
    Route::post('/{user}/revoke-role', [$c, 'revokeRole'])->middleware('permission:users.edit')->name('revoke-role');
    Route::post('/{user}/activate', [$c, 'activate'])->middleware('permission:users.edit')->name('activate');
    Route::post('/{user}/deactivate', [$c, 'deactivate'])->middleware('permission:users.edit')->name('deactivate');
    Route::get('/{user}/audits', [$c, 'audits'])->middleware('permission:audit.view')->name('audits');
});

/* Role Management */
R::permissions(Route::resource('roles', RoleController::class), 'roles');

/* Activity Logs */
Route::get('activity-logs', [ActivityLogController::class, 'index'])
    ->name('activity-logs.index')->middleware('permission:audit.view');
Route::get('activity-logs/{activityLog}', [ActivityLogController::class, 'show'])
    ->name('activity-logs.show')->middleware('permission:audit.view');
