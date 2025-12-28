<?php

declare(strict_types=1);

use Applications\AdminPanel\Controllers\{
    BPMS\PanelBPMSDashboardController,
    BPMS\PanelTaskController,
    BPMS\PanelWorkflowController,
    FileManager\PanelDocumentController,
    Organization\PanelAuthController,
    Organization\PanelDepartmentController,
    Organization\PanelOrganizationDashboardController,
    Organization\PanelRoleController,
    Organization\PanelUserController,
    PanelDashboardController,
    PanelTagController,
};
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])
    ->group(static function () {
        Route::get('/', function () {
            if (Auth::guard('web')->check()) {
                return response()->redirectTo('/dashboard');
            }

            return response()->redirectTo('/login');
        });

        Route::middleware(['web', 'guest'])
            ->group(static function (): void {
                Route::get('login', [PanelAuthController::class, 'login'])->name('login');
                Route::post('auth', [PanelAuthController::class, 'auth'])->name('auth');
            });

        Route::middleware(['web', 'auth'])
            ->group(static function (): void {
                Route::post('logout', [PanelAuthController::class, 'logout'])->name('logout');
            });

        Route::middleware(['web', 'auth'])->name('web.')->group(function () {

            Route::view('/test-components', 'panel::test-components')->name('test.components');

            Route::get('dashboard', [PanelDashboardController::class, 'index'])
                ->name('dashboard');

            Route::name('app')->resource('org/tags', PanelTagController::class)
                ->except(['show', 'destroy', 'store', 'update']);

            Route::prefix('org')
                ->name('org.')
                ->group(static function (): void {
                    Route::get('dashboard', [PanelOrganizationDashboardController::class, 'index'])->name('dashboard');

                    Route::resource('users', PanelUserController::class)
                        ->except(['destroy', 'store', 'update']);

                    Route::get('departments/chart', [PanelDepartmentController::class, 'chart'])
                        ->name('departments.chart');

                    Route::resource('departments', PanelDepartmentController::class)
                        ->except(['destroy', 'store', 'update', 'show']);

                    Route::resource('roles', PanelRoleController::class)
                        ->except(['show', 'destroy', 'store', 'update']);

                    Route::get('roles/{role}/permissions', [PanelRoleController::class, 'permissions'])
                        ->name('roles.permissions');
                });

            Route::prefix('documents')
                ->name('documents.')
                ->group(function () {
                    Route::get('/', [PanelDocumentController::class, 'index'])->name('index');
                    Route::get('/folder/{folderId}', [PanelDocumentController::class, 'folder'])->name('folder');
                    Route::get('/favorites', [PanelDocumentController::class, 'favorites'])->name('favorites');
                    Route::get('/recent', [PanelDocumentController::class, 'recent'])->name('recent');
                    Route::get('/temporary', [PanelDocumentController::class, 'temporary'])->name('temporary');

                    Route::get('/files/{id}/download', [PanelDocumentController::class, 'download'])->name('files.download');
                });

            Route::name('bpms.')
                ->prefix('bpms')
                ->group(function () {
                    Route::get('dashboard', [PanelBPMSDashboardController::class, 'index'])->name('dashboard');

                    Route::resource('workflows', PanelWorkflowController::class)
                        ->except(['destroy', 'store', 'update', 'show']);

                    Route::resource('tasks', PanelTaskController::class)
                        ->except(['create', 'edit', 'destroy', 'store', 'update']);
                });
        });
    });
