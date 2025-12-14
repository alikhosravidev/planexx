<?php

declare(strict_types=1);

use Applications\AdminPanel\Controllers\{AuthWebController,
    BPMS\BPMSDashboardController,
    BPMS\WorkflowWebController,
    DashboardController,
    FileManager\DocumentWebController,
    Organization\DepartmentWebController,
    Organization\OrganizationDashboardController,
    Organization\RoleWebController,
    Organization\UserWebController,
    TagWebController,};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->redirectTo('/login');
});

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

Route::middleware(['web', 'auth'])
    ->prefix('org')
    ->name('web.org.')
    ->group(static function (): void {
        Route::get('dashboard', [OrganizationDashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', UserWebController::class);
        Route::get('departments/chart', [DepartmentWebController::class, 'chart'])->name('departments.chart');
        Route::resource('departments', DepartmentWebController::class)->except('show');
        Route::resource('roles', RoleWebController::class);
        Route::get('roles/{role}/permissions', [RoleWebController::class, 'permissions'])->name('roles.permissions');
    });

Route::middleware(['web', 'auth'])
    ->prefix('documents')
    ->name('web.documents.')
    ->group(function () {
        Route::get('/', [DocumentWebController::class, 'index'])->name('index');
        Route::get('/folder/{folderId}', [DocumentWebController::class, 'folder'])->name('folder');
        Route::get('/favorites', [DocumentWebController::class, 'favorites'])->name('favorites');
        Route::get('/recent', [DocumentWebController::class, 'recent'])->name('recent');
        Route::get('/temporary', [DocumentWebController::class, 'temporary'])->name('temporary');

        Route::get('/folders/create', [DocumentWebController::class, 'createFolder'])->name('folders.create');
        Route::get('/folders/{folderId}/edit', [DocumentWebController::class, 'editFolder'])->name('folders.edit');

        Route::get('/files/{id}/download', [DocumentWebController::class, 'download'])->name('files.download');
    });

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
