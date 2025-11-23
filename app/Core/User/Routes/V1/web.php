<?php

declare(strict_types=1);

use App\Core\User\Http\Controllers\V1\Web\AuthWebController;
use Illuminate\Support\Facades\Route;

/**
 * Web Routes for Authentication Pages
 * These routes display the UI for authentication
 * The actual API calls are handled by the frontend using Axios
 */

// Public authentication pages
Route::middleware(['web'])->group(static function (): void {
    Route::get('login', [AuthWebController::class, 'login'])->name('login');
});
