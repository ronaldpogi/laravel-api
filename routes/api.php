<?php

use App\Http\Controllers\Saas\AuthController;
use App\Http\Controllers\Saas\DashboardController;
use App\Http\Controllers\Saas\OptionController;
use App\Http\Controllers\Saas\PermissionController;
use App\Http\Controllers\Saas\RoleController;
use App\Http\Controllers\Saas\TenantController;
use App\Http\Controllers\Saas\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('saas')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);

    Route::prefix('dashboard')->controller(DashboardController::class)->group(function () {
        Route::get('/tenants', 'tenants');
        Route::get('/members', 'members');
    });

    // tenanted routes
    Route::tenanted(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::middleware(['api.auth'])->group(function () {
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::apiResource('tenants', TenantController::class);
            Route::apiResource('users', UserController::class);

            Route::prefix('roles')->controller(RoleController::class)->group(function () {
                Route::post('users/{user}/attach', 'attachRolesToUser');
                Route::post('users/{user}/detach', 'detachRolesFromUser');
                Route::post('users/{user}/sync', 'syncRolesForUser');
            });

            Route::prefix('permissions')->controller(PermissionController::class)->group(function () {
                Route::post('roles/{role}/attach', 'attachPermissionsToRole');
                Route::post('roles/{role}/detach', 'detachPermissionsFromRole');
                Route::post('roles/{role}/sync', 'syncPermissionsForRole');
            });

            Route::prefix('options')->controller(OptionController::class)->group(function () {
                Route::get('/roles', 'RoleOptions');
            });
        });
    });
});
