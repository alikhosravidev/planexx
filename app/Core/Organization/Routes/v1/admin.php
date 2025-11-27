<?php

declare(strict_types=1);

use App\Core\Organization\Http\Controllers\V1\Admin\AddressController;
use App\Core\Organization\Http\Controllers\V1\Admin\AuthController;
use App\Core\Organization\Http\Controllers\V1\Admin\CityController;
use App\Core\Organization\Http\Controllers\V1\Admin\DepartmentController;
use App\Core\Organization\Http\Controllers\V1\Admin\JobPositionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('organization')->name('organization.')->group(function () {
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('job-positions', JobPositionController::class);
});


Route::middleware('auth:sanctum')->prefix('location')->group(function () {
    Route::apiResource('addresses', AddressController::class);

    Route::get('cities', [CityController::class, 'index'])->name('cities.index');
    Route::get('cities/{city}', [CityController::class, 'show'])->name('cities.show');
});

Route::prefix('api')
    ->middleware(['throttle:' . config('authService.auth_max_attempts')])
    ->name('api.')
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
