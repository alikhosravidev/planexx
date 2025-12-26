<?php

declare(strict_types=1);

use App\Core\Organization\Http\Controllers\V1\Admin\AddressAdminController;
use App\Core\Organization\Http\Controllers\V1\Admin\AuthAdminController;
use App\Core\Organization\Http\Controllers\V1\Admin\CityAdminController;
use App\Core\Organization\Http\Controllers\V1\Admin\DepartmentAdminController;
use App\Core\Organization\Http\Controllers\V1\Admin\JobPositionAdminController;
use App\Core\Organization\Http\Controllers\V1\Admin\PermissionAdminController;
use App\Core\Organization\Http\Controllers\V1\Admin\RoleAdminController;
use App\Core\Organization\Http\Controllers\V1\Admin\UserAdminController;
use App\Core\Organization\Http\Controllers\V1\Admin\UserRoleAdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/admin')
    ->name('api.v1.admin.')
    ->group(function () {
        Route::middleware('auth:sanctum')
            ->name('org.')
            ->prefix('org')
            ->group(function () {
                Route::apiResource('users', UserAdminController::class);
                Route::apiResource('departments', DepartmentAdminController::class);
                Route::apiResource('roles', RoleAdminController::class);
                Route::apiResource('permissions', PermissionAdminController::class)->only(['index', 'show', 'keyValList']);

                Route::apiResource('addresses', AddressAdminController::class);

                Route::get('cities', [CityAdminController::class, 'index'])->name('cities.index');
                Route::get('cities/{city}', [CityAdminController::class, 'show'])->name('cities.show');

                Route::apiResource('job-positions', JobPositionAdminController::class);

                // User sub-resources
                Route::get('users/{user}/roles', [UserRoleAdminController::class, 'show'])
                    ->name('users.roles.show');
                Route::put('users/{user}/roles', [UserRoleAdminController::class, 'update'])
                    ->name('users.roles.update');
            });

        Route::middleware(['throttle:' . config('authService.auth_max_attempts')])
            ->group(static function (): void {
                Route::get('auth', [AuthAdminController::class, 'initiateAuth'])
                    ->name('user.initiate.auth');
                Route::post('auth', [AuthAdminController::class, 'auth'])
                    ->name('user.auth');

                Route::get('reset-password', [AuthAdminController::class, 'initiateResetPassword'])
                    ->name('user.initiate.resetPassword');
                Route::put('reset-password', [AuthAdminController::class, 'resetPassword'])
                    ->name('user.resetPassword');

                Route::middleware(['auth:sanctum', 'throttle:20'])
                    ->group(static function (): void {
                        Route::post('logout', [AuthAdminController::class, 'logout'])->name('user.logout');
                    });
            });
    });
