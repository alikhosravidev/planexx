<?php

declare(strict_types=1);

use App\Core\Organization\Http\Controllers\V1\Client\AuthClientController;
use App\Core\Organization\Http\Controllers\V1\Client\UserClientController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/client')
    ->name('api.v1.client.')
    ->group(function () {
        Route::middleware('auth:sanctum')
            ->name('org.')
            ->prefix('org')
            ->group(function () {
                Route::apiResource('users', UserClientController::class)
                    ->only(['show']);
            });

        Route::middleware(['throttle:' . config('authService.auth_max_attempts')])
            ->group(static function (): void {
                Route::get('auth', [AuthClientController::class, 'initiateAuth'])
                    ->name('user.initiate.auth');
                Route::post('auth', [AuthClientController::class, 'auth'])
                    ->name('user.auth');

                Route::get('reset-password', [AuthClientController::class, 'initiateResetPassword'])
                    ->name('user.initiate.resetPassword');
                Route::put('reset-password', [AuthClientController::class, 'resetPassword'])
                    ->name('user.resetPassword');

                Route::middleware(['auth:sanctum', 'throttle:20'])
                    ->group(static function (): void {
                        Route::post('logout', [AuthClientController::class, 'logout'])->name('user.logout');
                    });
            });
    });
