<?php

declare(strict_types=1);

use App\Core\BPMS\Http\Controllers\Web\BPMSDashboardController;
use App\Core\BPMS\Http\Controllers\Web\WorkflowWebController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->name('web.')->group(function () {
    Route::middleware(['auth'])
        ->name('bpms.')
        ->prefix('bpms')
        ->group(function () {
            Route::get('dashboard', [BPMSDashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('workflows', [WorkflowWebController::class, 'index'])
                ->name('workflows.index');

            Route::get('workflows/create', [WorkflowWebController::class, 'create'])
                ->name('workflows.create');

            Route::get('workflows/{workflow}/edit', [WorkflowWebController::class, 'edit'])
                ->name('workflows.edit');
        });
});
