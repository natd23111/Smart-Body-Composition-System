<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BodyCompositionController;
use App\Http\Controllers\HealthRecommendationController;
use App\Http\Controllers\AdminController;

Route::middleware('auth:sanctum')->group(function () {

    // Body Composition
    Route::apiResource('body-compositions', BodyCompositionController::class);

    // Recommendations
    Route::get('recommendations', [HealthRecommendationController::class, 'index']);
    Route::post('recommendations/generate', [HealthRecommendationController::class, 'generate']);
});

// Admin Routes (admin-only)
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('admin/users', [AdminController::class, 'users']);
    Route::delete('admin/users/{id}', [AdminController::class, 'deleteUser']);
    Route::get('admin/records', [AdminController::class, 'records']);
});
