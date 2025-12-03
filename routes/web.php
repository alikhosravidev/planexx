<?php

declare(strict_types=1);

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\TagWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->redirectTo('/login');
});

Route::middleware(['web'])->name('web.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth'])
        ->name('dashboard');

    Route::get('/test-components', function () {
        return view('test-components');
    })->name('test.components');

    Route::middleware(['auth'])
        ->name('app.')
        ->group(function () {
            Route::resource('org/tags', TagWebController::class)->except(['destroy']);
        });
});
