<?php

declare(strict_types=1);

use App\Core\User\Http\Controllers\V1\Admin\AddressController;
use App\Core\User\Http\Controllers\V1\Admin\AuthController;
use App\Core\User\Http\Controllers\V1\Admin\CityController;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('location')->group(function () {
    Route::apiResource('addresses', AddressController::class);

    Route::get('cities', [CityController::class, 'index'])->name('cities.index');
    Route::get('cities/{city}', [CityController::class, 'show'])->name('cities.show');
});

Route::middleware([StartSession::class, 'throttle:' . config('authService.auth_max_attempts')])
    ->name('user.')
    ->group(static function (): void {
        Route::get('auth', [AuthController::class, 'initiateAuth'])->name('initiate.auth');
        Route::post('auth', [AuthController::class, 'auth'])->name('auth');

        Route::get('reset-password', [AuthController::class, 'initiateResetPassword'])
            ->name('initiate.resetPassword');
        Route::put('reset-password', [AuthController::class, 'resetPassword'])
            ->name('resetPassword');

        Route::middleware(['auth:sanctum', 'throttle:20'])
            ->group(static function (): void {
                Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
            });
    });
