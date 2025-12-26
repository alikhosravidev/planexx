<?php

declare(strict_types=1);

use App\Core\BPMS\Http\Controllers\V1\Client\ClientTaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/client')
    ->name('api.v1.client.')
    ->group(static function (): void {
        Route::middleware('auth:sanctum')
            ->name('bpms.')
            ->prefix('bpms')
            ->group(static function (): void {
                Route::apiResource('tasks', ClientTaskController::class)
                    ->only(['index', 'show']);
            });
    });
