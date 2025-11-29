<?php

declare(strict_types=1);

use App\Core\Organization\Http\Controllers\V1\Admin\AddressController;
use App\Core\Organization\Http\Controllers\V1\Admin\AuthController;
use App\Core\Organization\Http\Controllers\V1\Admin\CityController;
use App\Core\Organization\Http\Controllers\V1\Admin\DepartmentController;
use App\Core\Organization\Http\Controllers\V1\Admin\JobPositionController;
use App\Core\Organization\Http\Controllers\V1\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/admin')
    ->name('api.v1.admin.')
    ->group(function () {
        Route::middleware('auth:sanctum')
            ->name('org.')
            ->prefix('org')
            ->group(function () {
                Route::apiResource('users', UserController::class);
                Route::apiResource('departments', DepartmentController::class);

                Route::apiResource('addresses', AddressController::class);

                Route::get('cities', [CityController::class, 'index'])->name('cities.index');
                Route::get('cities/{city}', [CityController::class, 'show'])->name('cities.show');

                Route::apiResource('job-positions', JobPositionController::class);
            });

        Route::middleware(['throttle:' . config('authService.auth_max_attempts')])
            ->group(static function (): void {
                Route::get('auth', [AuthController::class, 'initiateAuth'])
                    ->name('user.initiate.auth');
                Route::post('auth', [AuthController::class, 'auth'])
                    ->name('user.auth');

                Route::get('reset-password', [AuthController::class, 'initiateResetPassword'])
                    ->name('user.initiate.resetPassword');
                Route::put('reset-password', [AuthController::class, 'resetPassword'])
                    ->name('user.resetPassword');

                Route::middleware(['auth:sanctum', 'throttle:20'])
                    ->group(static function (): void {
                        Route::post('logout', [AuthController::class, 'logout'])->name('user.logout');
                    });
            });
    });
