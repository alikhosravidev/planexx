<?php

declare(strict_types=1);

use App\Core\Organization\Http\Controllers\V1\Web\AuthWebController;
use App\Core\Organization\Http\Controllers\V1\Web\OrgDashboardController;
use Illuminate\Support\Facades\Route;

/**
 * Web Routes for Authentication Pages
 * These routes handle both UI rendering and cookie management
 * The Web controller forwards requests to API and manages cookies
 */

Route::middleware(['web', 'guest'])->group(static function (): void {
    Route::get('login', [AuthWebController::class, 'login'])->name('login');
    Route::post('auth', [AuthWebController::class, 'auth'])->name('web.auth');
});

Route::middleware(['web', 'auth'])->group(static function (): void {
    Route::get('org', [OrgDashboardController::class, 'index'])->name('org.dashboard');
    Route::post('logout', [AuthWebController::class, 'logout'])->name('web.logout');
});
