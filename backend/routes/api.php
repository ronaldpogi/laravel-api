<?php

use App\Http\Controllers\Saas\AuthController;
use App\Http\Controllers\Saas\TenantController;
use App\Http\Controllers\Saas\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('saas')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);

    // tenanted routes
    Route::tenanted(function () {
        Route::post('/login', [AuthController::class, 'login']);

        Route::middleware(['api.auth'])->group(function () {
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::apiResource('tenants', TenantController::class);
            Route::apiResource('users', UserController::class);
        });
    });
});
