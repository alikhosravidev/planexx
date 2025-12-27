<?php

declare(strict_types=1);

use Applications\PWA\Controllers\{
    PWAAuthController,
    PWADashboardController,
    PWADocumentController,
    PWAProfileController,
    PWATaskController,
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])
    ->domain(config('app.domains.pwa'))
    ->group(static function () {
        Route::get('/', function () {
            if (Auth::guard('web')->check()) {
                return response()->redirectTo('/dashboard');
            }

            return response()->redirectTo('/login');
        });

        Route::middleware(['web', 'guest'])
            ->group(static function (): void {
                Route::get('login', [PWAAuthController::class, 'login'])->name('pwa.login');
                Route::post('auth', [PWAAuthController::class, 'auth'])->name('pwa.auth');
            });

        Route::middleware(['web', 'auth'])
            ->group(static function (): void {
                Route::post('logout', [PWAAuthController::class, 'logout'])->name('pwa.logout');
            });

        Route::middleware(['web', 'auth'])->name('pwa.')->group(function () {
            Route::get('dashboard', [PWADashboardController::class, 'index'])
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
    });
