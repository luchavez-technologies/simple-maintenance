<?php

use Luchavez\SimpleMaintenance\Http\Controllers\GetMaintenanceModeStatusController;
use Luchavez\SimpleMaintenance\Http\Controllers\GetMaintenanceModeStatusesController;
use Luchavez\SimpleMaintenance\Http\Controllers\GetMaintenanceModeTagsController;
use Luchavez\SimpleMaintenance\Http\Controllers\MaintenanceLogController;
use Luchavez\SimpleMaintenance\Http\Controllers\ToggleMaintenanceModeController;
use Illuminate\Support\Facades\Route;

/**
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
Route::name('maintenance.')->middleware(simpleMaintenance()->getDefaultMiddleware())->group(function () {
    Route::post('', ToggleMaintenanceModeController::class)
        ->name('toggle')
        ->middleware(simpleMaintenance()->getToggleMiddleware());
    Route::get('statuses', GetMaintenanceModeStatusesController::class)->name('statuses');
    Route::get('tags', GetMaintenanceModeTagsController::class)->name('tags');
    Route::apiResource('logs', MaintenanceLogController::class)->only([
        'index',
        'show',
        'update',
        'destroy',
    ]);
});

Route::get('', GetMaintenanceModeStatusController::class)
    ->name('maintenance.status')
    ->middleware(simpleMaintenance()->getStatusMiddleware());
