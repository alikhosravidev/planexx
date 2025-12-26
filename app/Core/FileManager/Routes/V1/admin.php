<?php

declare(strict_types=1);

use App\Core\FileManager\Http\Controllers\V1\Admin\FavoriteAPIController;
use App\Core\FileManager\Http\Controllers\V1\Admin\FileAPIController;
use App\Core\FileManager\Http\Controllers\V1\Admin\FolderAPIController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/admin')
    ->name('api.v1.admin.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::prefix('file-manager')
            ->name('file-manager.')
            ->group(function () {
                Route::delete('files/cleanup-temporary', [FileAPIController::class, 'cleanupTemporary'])
                    ->name('files.cleanup-temporary');

                Route::apiResource('folders', FolderAPIController::class);
                Route::apiResource('files', FileAPIController::class);

                Route::get('files/{id}/download', [FileAPIController::class, 'download'])
                    ->name('files.download');

                Route::post('files/{fileId}/favorite', [FavoriteAPIController::class, 'toggleFile'])
                    ->name('files.favorite.toggle');

                Route::post('folders/{folderId}/favorite', [FavoriteAPIController::class, 'toggleFolder'])
                    ->name('folders.favorite.toggle');
            });
    });
