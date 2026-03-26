<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BodyCompositionController;
use App\Http\Controllers\HealthRecommendationController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

// Public Auth Routes
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!auth()->attempt($request->only('email', 'password'))) {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    $user = auth()->user();
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'message' => 'Login successful',
        'token' => $token,
        'user' => $user,
    ]);
});

Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logged out']);
})->middleware('auth:sanctum');

// Protected Routes
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
