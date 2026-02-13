<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\V1\Admin\AdminCategoryController;
use Modules\Product\Http\Controllers\V1\Admin\AdminCustomListController;
use Modules\Product\Http\Controllers\V1\Admin\AdminCustomListItemController;
use Modules\Product\Http\Controllers\V1\Admin\AdminProductController;

Route::prefix('api/v1/admin')
    ->name('api.v1.admin.')
    ->group(static function (): void {
        Route::middleware('auth:sanctum')
            ->name('product.')
            ->prefix('product')
            ->group(static function (): void {
                Route::apiResource('products', AdminProductController::class);
                Route::apiResource('categories', AdminCategoryController::class);
                Route::apiResource('custom-lists', AdminCustomListController::class);
                Route::apiResource('custom-lists/{custom_list}/items', AdminCustomListItemController::class);
            });
    });
