<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BodyCompositionController;
use App\Http\Controllers\HealthRecommendationController;
use App\Http\Controllers\TrendsController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

// Public Auth Routes
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|min:2',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    try {
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user',
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'token' => $token,
            'user' => $user,
        ], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Registration failed: ' . $e->getMessage()], 422);
    }
});

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

Route::post('/check-email', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
    ]);

    $exists = \App\Models\User::where('email', $request->email)->exists();

    return response()->json([
        'available' => !$exists,
    ]);
});

Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logged out']);
})->middleware('auth:sanctum');

// User Profile Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/profile', function (Request $request) {
        return response()->json($request->user());
    });

    Route::put('/user/profile', function (Request $request) {

        $request->validate([
            'name' => 'required|string|min:2',
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'age' => 'nullable|integer|min:1|max:150',
            'gender' => 'nullable|in:Male,Female,Other',
            'height_cm' => 'nullable|numeric|min:50|max:250',
        ]);

        $user = $request->user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'age' => $request->age,
            'gender' => $request->gender,
            'height_cm' => $request->height_cm,
        ]);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    });

    Route::post('/user/change-password', function (Request $request) {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!password_verify($request->current_password, $user->password)) {
            return response()->json(['error' => 'Current password is incorrect'], 422);
        }

        $user->update([
            'password' => bcrypt($request->new_password),
        ]);

        return response()->json([
            'message' => 'Password changed successfully',
        ]);
    });

    Route::delete('/user/account', function (Request $request) {
        $request->validate([
            'password' => 'required',
        ]);

        $user = $request->user();

        if (!password_verify($request->password, $user->password)) {
            return response()->json(['error' => 'Password is incorrect'], 422);
        }

        // Revoke all tokens before deleting
        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => 'Account deleted successfully']);
    });
});

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {

    // Body Composition
    Route::apiResource('body-compositions', BodyCompositionController::class);

    // Trends
    Route::get('trends', [TrendsController::class, 'index']);

    // Recommendations
    Route::get('recommendations', [HealthRecommendationController::class, 'index']);
    Route::post('recommendations/generate', [HealthRecommendationController::class, 'generate']);
    Route::put('recommendations/{recommendation}/status', [HealthRecommendationController::class, 'updateStatus']);
});

// Admin Routes (admin-only)
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('admin/users', [AdminController::class, 'users']);
    Route::delete('admin/users/{id}', [AdminController::class, 'deleteUser']);
    Route::get('admin/records', [AdminController::class, 'records']);
});
