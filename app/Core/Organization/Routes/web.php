<?php

declare(strict_types=1);

use App\Core\Organization\Http\Controllers\Web\AuthWebController;
use App\Core\Organization\Http\Controllers\Web\OrganizationDashboardController;
use App\Core\Organization\Http\Controllers\Web\UserWebController;
use Illuminate\Support\Facades\Route;

/**
 * Web Routes for Authentication Pages
 * These routes handle both UI rendering and cookie management
 * The Web controller forwards requests to API and manages cookies
 */

Route::middleware(['web', 'guest'])
    ->group(static function (): void {
        Route::get('login', [AuthWebController::class, 'login'])->name('login');
        Route::post('auth', [AuthWebController::class, 'auth'])->name('auth');
    });

Route::middleware(['web', 'auth'])
    ->group(static function (): void {
        Route::post('logout', [AuthWebController::class, 'logout'])->name('logout');
    });

Route::middleware(['web', 'auth'])
    ->prefix('org')
    ->name('web.org.')
    ->group(static function (): void {
        Route::get('dashboard', [OrganizationDashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', UserWebController::class);
    });
