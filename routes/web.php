<?php

declare(strict_types=1);

use App\Http\Controllers\Web\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['web'])->name('web.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth'])
        ->name('dashboard');

    Route::get('/test-components', function () {
        return view('test-components');
    })->name('test.components');
});
