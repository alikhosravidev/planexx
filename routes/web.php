<?php

declare(strict_types=1);

use App\Core\Organization\Http\Controllers\V1\Web\UsersWebController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth'])
        ->name('dashboard');

    // Alias for components expecting dashboard.index
    Route::get('/dashboard/index', function () {
        return redirect()->route('dashboard');
    })->middleware(['auth'])->name('dashboard.index');

    Route::get('/test-components', function () {
        return view('test-components');
    })->name('test.components');

    Route::prefix('org')->middleware(['auth'])->name('org.')->group(function () {
        Route::get('users', [UsersWebController::class, 'index'])->name('users.index');
    });
});
