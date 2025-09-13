<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\WidgetController;
use App\Http\Controllers\Api\VersionController;

Route::prefix('v1')->group(function () {
    Route::apiResource('dashboards', DashboardController::class);

    Route::post('dashboards/{dashboard}/widgets', [WidgetController::class, 'store']);
    Route::put('widgets/{widget}', [WidgetController::class, 'update']);
    Route::delete('widgets/{widget}', [WidgetController::class, 'destroy']);

    Route::get('dashboards/{dashboard}/versions', [VersionController::class, 'index']);
    Route::post('dashboards/{dashboard}/rollback/{versionNumber}', [VersionController::class, 'rollback']);
});
