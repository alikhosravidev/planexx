<?php

declare(strict_types=1);

use App\Core\BPMS\Http\Controllers\V1\Admin\AdminTaskController;
use App\Core\BPMS\Http\Controllers\V1\Admin\AdminWorkflowController;
use App\Core\BPMS\Http\Controllers\V1\Admin\AdminWorkflowStateController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/admin')
    ->name('api.v1.admin.')
    ->group(static function (): void {
        Route::middleware('auth:sanctum')
            ->name('bpms.')
            ->prefix('bpms')
            ->group(static function (): void {
                Route::apiResource('workflows', AdminWorkflowController::class);
                Route::apiResource('workflows/{workflow_id}/states', AdminWorkflowStateController::class)
                    ->only(['index', 'keyValList']);
                Route::apiResource('tasks', AdminTaskController::class);
            });
    });
