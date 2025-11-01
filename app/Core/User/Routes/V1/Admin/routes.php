<?php

declare(strict_types=1);

use App\Core\User\Http\Controllers\V1\Admin\AddressController;
use App\Core\User\Http\Controllers\V1\Admin\CityController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('location')->group(function () {
    Route::apiResource('addresses', AddressController::class);

    Route::get('cities', [CityController::class, 'index'])->name('cities.index');
    Route::get('cities/{city}', [CityController::class, 'show'])->name('cities.show');
});
