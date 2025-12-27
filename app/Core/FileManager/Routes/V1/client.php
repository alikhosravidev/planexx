<?php

declare(strict_types=1);

use App\Core\FileManager\Http\Controllers\V1\Client\ClientFileController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/client')
    ->name('api.v1.client.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::prefix('files')
            ->group(function () {
                Route::apiResource('files', ClientFileController::class)
                    ->only(['index', 'show']);
            });
    });
