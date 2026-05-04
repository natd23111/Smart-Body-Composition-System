<?php

use App\Mail\PasswordResetMail;
use App\Support\AdminSecuritySettings;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BodyCompositionController;
use App\Http\Controllers\HealthRecommendationController;
use App\Http\Controllers\TrendsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

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

    $loginKey = 'login:' . Str::lower($request->string('email')->toString()) . '|' . $request->ip();
    $maxAttempts = AdminSecuritySettings::maxLoginAttempts();

    if (RateLimiter::tooManyAttempts($loginKey, $maxAttempts)) {
        $seconds = RateLimiter::availableIn($loginKey);

        return response()->json([
            'message' => 'Too many login attempts. Try again in ' . $seconds . ' seconds.',
        ], 429);
    }

    if (!Auth::attempt($request->only('email', 'password'))) {
        RateLimiter::hit($loginKey, 900);

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    RateLimiter::clear($loginKey);

    /** @var \App\Models\User $user */
    $user = Auth::user();
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
})->middleware(['auth:sanctum', 'session.timeout']);

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $user = \App\Models\User::where('email', $request->email)->first();

    // Always return success to avoid email enumeration
    if (!$user) {
        return response()->json(['message' => 'If that email exists, a reset link has been sent.']);
    }

    $token = \Illuminate\Support\Str::random(64);

    \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $request->email],
        ['token' => hash('sha256', $token), 'created_at' => now()]
    );

    $resetUrl = config('app.url') . '/reset-password?token=' . $token . '&email=' . urlencode($request->email);

    try {
        \Illuminate\Support\Facades\Mail::to($user->email, $user->name)
            ->send(new PasswordResetMail($user, $resetUrl));
    } catch (\Throwable $exception) {
        Log::error('Failed to send password reset email.', [
            'email' => $request->email,
            'message' => $exception->getMessage(),
        ]);

        return response()->json([
            'error' => 'Unable to send the reset email right now. Please try again shortly.',
        ], 500);
    }

    return response()->json(['message' => 'If that email exists, a reset link has been sent.']);
});

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token'                 => 'required|string',
        'email'                 => 'required|email',
        'password'              => 'required|string|min:8|confirmed',
    ]);

    $record = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();

    if (!$record || !hash_equals($record->token, hash('sha256', $request->token))) {
        return response()->json(['error' => 'Invalid or expired reset token.'], 422);
    }

    if (now()->diffInMinutes($record->created_at) > 60) {
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        return response()->json(['error' => 'This reset link has expired. Please request a new one.'], 422);
    }

    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['error' => 'User not found.'], 422);
    }

    $user->update(['password' => bcrypt($request->password)]);
    $user->tokens()->delete(); // Invalidate all existing sessions

    \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    return response()->json(['message' => 'Password reset successfully. You can now log in.']);
});

// User Profile Routes
Route::middleware(['auth:sanctum', 'session.timeout'])->group(function () {
    Route::get('/user/profile', function (Request $request) {
        return response()->json($request->user());
    });

    Route::get('/user/notification-preferences', function (Request $request) {
        $user = $request->user();

        return response()->json([
            'notifications' => (bool) $user->notifications_enabled,
            'emailAlerts' => (bool) $user->email_alerts_enabled,
            'weeklyReport' => (bool) $user->weekly_reports_enabled,
            'goalReminders' => (bool) $user->measurement_reminders_enabled,
        ]);
    });

    Route::put('/user/notification-preferences', function (Request $request) {
        $validated = $request->validate([
            'notifications' => 'required|boolean',
            'emailAlerts' => 'required|boolean',
            'weeklyReport' => 'required|boolean',
            'goalReminders' => 'required|boolean',
        ]);

        $user = $request->user();
        $user->update([
            'notifications_enabled' => $validated['notifications'],
            'email_alerts_enabled' => $validated['emailAlerts'],
            'weekly_reports_enabled' => $validated['weeklyReport'],
            'measurement_reminders_enabled' => $validated['goalReminders'],
        ]);

        return response()->json([
            'message' => 'Notification preferences updated successfully',
            'preferences' => [
                'notifications' => (bool) $user->notifications_enabled,
                'emailAlerts' => (bool) $user->email_alerts_enabled,
                'weeklyReport' => (bool) $user->weekly_reports_enabled,
                'goalReminders' => (bool) $user->measurement_reminders_enabled,
            ],
        ]);
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
Route::middleware(['auth:sanctum', 'session.timeout'])->group(function () {

    // Body Composition
    Route::apiResource('body-compositions', BodyCompositionController::class);

    // Trends
    Route::get('trends', [TrendsController::class, 'index']);

    // Goals
    Route::get('goals', [GoalController::class, 'index']);
    Route::post('goals', [GoalController::class, 'store']);
    Route::put('goals/{goal}', [GoalController::class, 'update']);
    Route::delete('goals/{goal}', [GoalController::class, 'destroy']);

    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead']);
    Route::post('notifications/{id}/read', [NotificationController::class, 'markRead']);

    // Recommendations
    Route::get('recommendations', [HealthRecommendationController::class, 'index']);
    Route::post('recommendations/generate', [HealthRecommendationController::class, 'generate']);
    Route::put('recommendations/{recommendation}/status', [HealthRecommendationController::class, 'updateStatus']);
});

// Admin Routes (admin-only)
Route::middleware(['auth:sanctum', 'session.timeout', 'admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('admin/users', [AdminController::class, 'users']);
    Route::post('admin/users', [AdminController::class, 'storeUser']);
    Route::put('admin/users/{id}', [AdminController::class, 'updateUser']);
    Route::delete('admin/users/{id}', [AdminController::class, 'deleteUser']);
    Route::get('admin/records', [AdminController::class, 'records']);
    Route::get('admin/settings', [AdminController::class, 'settings']);
    Route::put('admin/settings', [AdminController::class, 'updateSettings']);
    Route::post('admin/settings/test-email', [AdminController::class, 'sendTestEmail']);

    // Recommendation Templates CRUD
    Route::get('admin/templates', [AdminController::class, 'templates']);
    Route::post('admin/templates', [AdminController::class, 'storeTemplate']);
    Route::put('admin/templates/{id}', [AdminController::class, 'updateTemplate']);
    Route::delete('admin/templates/{id}', [AdminController::class, 'destroyTemplate']);
});
