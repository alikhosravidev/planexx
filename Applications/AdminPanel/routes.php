<?php

declare(strict_types=1);

use Applications\AdminPanel\Controllers\{BPMS\BPMSDashboardController,
    BPMS\TaskWebController,
    BPMS\WorkflowWebController,
    DashboardController,
    FileManager\DocumentWebController,
    Organization\AuthWebController,
    Organization\DepartmentWebController,
    Organization\OrganizationDashboardController,
    Organization\RoleWebController,
    Organization\UserWebController,
    TagWebController,
};
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])
    ->domain(config('app.domains.admin_panel'))
    ->group(static function () {
        Route::get('/', function () {
            return response()->redirectTo('/login');
        });

        Route::middleware(['web', 'guest'])
            ->group(static function (): void {
                Route::get('login', [AuthWebController::class, 'login'])->name('login');
                Route::post('auth', [AuthWebController::class, 'auth'])->name('auth');
            });

        Route::middleware(['web', 'auth'])
            ->group(static function (): void {
                Route::post('logout', [AuthWebController::class, 'logout'])->name('logout');
            });

        Route::middleware(['web', 'auth'])->name('web.')->group(function () {

            Route::view('/test-components', 'panel::test-components')->name('test.components');

            Route::get('dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

            Route::name('app')->resource('org/tags', TagWebController::class)
                ->except(['destroy', 'store', 'update']);

            Route::prefix('org')
                ->name('org.')
                ->group(static function (): void {
                    Route::get('dashboard', [OrganizationDashboardController::class, 'index'])->name('dashboard');

                    Route::resource('users', UserWebController::class)
                        ->except(['destroy', 'store', 'update']);

                    Route::get('departments/chart', [DepartmentWebController::class, 'chart'])
                        ->name('departments.chart');

                    Route::resource('departments', DepartmentWebController::class)
                        ->except(['destroy', 'store', 'update', 'show']);

                    Route::resource('roles', RoleWebController::class)
                        ->except(['destroy', 'store', 'update']);

                    Route::get('roles/{role}/permissions', [RoleWebController::class, 'permissions'])
                        ->name('roles.permissions');
                });

            Route::prefix('documents')
                ->name('documents.')
                ->group(function () {
                    Route::get('/', [DocumentWebController::class, 'index'])->name('index');
                    Route::get('/folder/{folderId}', [DocumentWebController::class, 'folder'])->name('folder');
                    Route::get('/favorites', [DocumentWebController::class, 'favorites'])->name('favorites');
                    Route::get('/recent', [DocumentWebController::class, 'recent'])->name('recent');
                    Route::get('/temporary', [DocumentWebController::class, 'temporary'])->name('temporary');

                    Route::get('/files/{id}/download', [DocumentWebController::class, 'download'])->name('files.download');
                });

            Route::name('bpms.')
                ->prefix('bpms')
                ->group(function () {
                    Route::get('dashboard', [BPMSDashboardController::class, 'index'])->name('dashboard');

                    Route::resource('workflows', WorkflowWebController::class)
                        ->except(['destroy', 'store', 'update', 'show']);

                    Route::resource('tasks', TaskWebController::class)
                        ->except(['edit', 'destroy', 'store', 'update']);
                });
        });
    });
