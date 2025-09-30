<?php

use App\Http\Controllers\Saas\AccessControlController;
use App\Http\Controllers\Saas\AuthController;
use App\Http\Controllers\Saas\DashboardController;
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
            // Access Control routes
            Route::prefix('access-control')->controller(AccessControlController::class)->group(function () {
                // Role <-> Permission
                Route::post('roles/{role}/permissions/attach', 'attachPermissionsToRole');
                Route::post('roles/{role}/permissions/detach', 'detachPermissionsFromRole');
                Route::post('roles/{role}/permissions/sync', 'syncPermissionsForRole');
                // User <-> Role
                Route::post('users/{user}/roles/attach', 'attachRolesToUser');
                Route::post('users/{user}/roles/detach', 'detachRolesFromUser');
                Route::post('users/{user}/roles/sync', 'syncRolesForUser');
            });
        });
    });
});
