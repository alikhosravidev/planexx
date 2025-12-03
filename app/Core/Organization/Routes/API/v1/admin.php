<?php

declare(strict_types=1);

use App\Core\Organization\Http\Controllers\API\V1\Admin\AddressAPIController;
use App\Core\Organization\Http\Controllers\API\V1\Admin\AuthAPIController;
use App\Core\Organization\Http\Controllers\API\V1\Admin\CityAPIController;
use App\Core\Organization\Http\Controllers\API\V1\Admin\DepartmentAPIController;
use App\Core\Organization\Http\Controllers\API\V1\Admin\JobPositionAPIController;
use App\Core\Organization\Http\Controllers\API\V1\Admin\PermissionAPIController;
use App\Core\Organization\Http\Controllers\API\V1\Admin\RoleAPIController;
use App\Core\Organization\Http\Controllers\API\V1\Admin\UserAPIController;
use App\Core\Organization\Http\Controllers\API\V1\Admin\UserRoleAPIController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/admin')
    ->name('api.v1.admin.')
    ->group(function () {
        Route::middleware('auth:sanctum')
            ->name('org.')
            ->prefix('org')
            ->group(function () {
                Route::apiResource('users', UserAPIController::class);
                Route::apiResource('departments', DepartmentAPIController::class);
                Route::apiResource('roles', RoleAPIController::class);
                Route::apiResource('permissions', PermissionAPIController::class)->only(['index', 'show', 'keyValList']);

                Route::apiResource('addresses', AddressAPIController::class);

                Route::get('cities', [CityAPIController::class, 'index'])->name('cities.index');
                Route::get('cities/{city}', [CityAPIController::class, 'show'])->name('cities.show');

                Route::apiResource('job-positions', JobPositionAPIController::class);

                // User sub-resources
                Route::get('users/{user}/roles', [UserRoleAPIController::class, 'show'])
                    ->name('users.roles.show');
                Route::put('users/{user}/roles', [UserRoleAPIController::class, 'update'])
                    ->name('users.roles.update');
            });

        Route::middleware(['throttle:' . config('authService.auth_max_attempts')])
            ->group(static function (): void {
                Route::get('auth', [AuthAPIController::class, 'initiateAuth'])
                    ->name('user.initiate.auth');
                Route::post('auth', [AuthAPIController::class, 'auth'])
                    ->name('user.auth');

                Route::get('reset-password', [AuthAPIController::class, 'initiateResetPassword'])
                    ->name('user.initiate.resetPassword');
                Route::put('reset-password', [AuthAPIController::class, 'resetPassword'])
                    ->name('user.resetPassword');

                Route::middleware(['auth:sanctum', 'throttle:20'])
                    ->group(static function (): void {
                        Route::post('logout', [AuthAPIController::class, 'logout'])->name('user.logout');
                    });
            });
    });
