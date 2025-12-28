<?php

declare(strict_types=1);

use Applications\PWA\Controllers\{
    PWADashboardController,
    PWADocumentController,
    PWAProfileController,
    PWATaskController,
};
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->name('pwa.')->prefix('app')->group(static function () {
    Route::get('', [PWADashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('profile', [PWAProfileController::class, 'index'])
        ->name('profile');

    Route::prefix('tasks')
        ->name('tasks.')
        ->group(function () {
            Route::get('/', [PWATaskController::class, 'index'])->name('index');
            Route::get('/{task}', [PWATaskController::class, 'show'])->name('show');
        });

    Route::prefix('documents')
        ->name('documents.')
        ->group(function () {
            Route::get('/', [PWADocumentController::class, 'index'])->name('index');
            Route::get('/{id}/download', [PWADocumentController::class, 'download'])->name('download');
        });
});
