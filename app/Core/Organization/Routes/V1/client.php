<?php

declare(strict_types=1);

use App\Core\Organization\Http\Controllers\V1\Client\AuthClientController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/admin')
    ->name('api.v1.admin.')
    ->group(function () {
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
