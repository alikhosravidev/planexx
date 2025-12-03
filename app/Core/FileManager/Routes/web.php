<?php

declare(strict_types=1);

use App\Core\FileManager\Http\Controllers\Web\DocumentWebController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])
    ->prefix('documents')
    ->name('web.documents.')
    ->group(function () {
        Route::get('/', [DocumentWebController::class, 'index'])->name('index');
        Route::get('/folder/{folderId}', [DocumentWebController::class, 'folder'])->name('folder');
        Route::get('/favorites', [DocumentWebController::class, 'favorites'])->name('favorites');
        Route::get('/recent', [DocumentWebController::class, 'recent'])->name('recent');
        Route::get('/temporary', [DocumentWebController::class, 'temporary'])->name('temporary');

        Route::get('/folders/create', [DocumentWebController::class, 'createFolder'])->name('folders.create');
        Route::get('/folders/{folderId}/edit', [DocumentWebController::class, 'editFolder'])->name('folders.edit');

        Route::get('/upload', [DocumentWebController::class, 'upload'])->name('upload');

        Route::get('/files/{id}/download', [DocumentWebController::class, 'download'])->name('files.download');
    });
