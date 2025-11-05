<?php

declare(strict_types=1);

use App\Core\Organization\Http\Controllers\V1\Admin\DepartmentController;
use App\Core\Organization\Http\Controllers\V1\Admin\JobPositionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('organization')->name('organization.')->group(function () {
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('job-positions', JobPositionController::class);
});
