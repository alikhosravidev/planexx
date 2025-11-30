<?php

use App\Http\Controllers\API\V1\Admin\EnumController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/admin')
    ->name('api.v1.admin.')
    ->middleware('auth:api')
    ->group(static function (): void {
        Route::get('enums/{enum}', [EnumController::class, 'show'])
            ->name('enums.show');
        Route::get('enums/{enum}/key-value-list', [EnumController::class, 'keyValList'])
            ->name('enums.keyValList');
    });
