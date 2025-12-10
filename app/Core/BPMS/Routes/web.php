<?php

declare(strict_types=1);

use App\Core\BPMS\Http\Controllers\Web\BPMSDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->name('web.')->group(function () {
    Route::middleware(['auth'])
        ->name('bpms.')
        ->prefix('bpms')
        ->group(function () {
            Route::get('dashboard', [BPMSDashboardController::class, 'index'])
                ->name('dashboard');
        });
});
