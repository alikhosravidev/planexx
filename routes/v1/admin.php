<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\Admin\EnumController;
use App\Http\Controllers\V1\Admin\TagAPIController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/admin')
    ->name('api.v1.admin.')
    ->middleware('auth:api')
    ->group(static function (): void {
        Route::get('enums/{enum}', [EnumController::class, 'show'])
            ->name('enums.show');
        Route::get('enums/{enum}/key-value-list', [EnumController::class, 'keyValList'])
            ->name('enums.keyValList');

        Route::apiResource('tags', TagAPIController::class)->except(['create', 'edit']);
    });
