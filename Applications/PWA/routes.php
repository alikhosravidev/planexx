<?php

declare(strict_types=1);

use Applications\PWA\Controllers\{
    PWAAuthController,
    PWADashboardController,
};
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])
    ->domain(config('app.domains.pwa'))
    ->group(static function () {
        Route::get('/', function () {
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

            // Add more PWA routes here...
        });
    });
