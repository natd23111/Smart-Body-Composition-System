<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthRecommendation;
use App\Services\RecommendationEngine;
use App\Services\UserNotificationService;

class HealthRecommendationController extends Controller
{
    public function __construct(
        private RecommendationEngine $recommendationEngine,
        private UserNotificationService $notificationService,
    )
    {
    }

    public function index(Request $request)
    {
        return response()->json($this->recommendationEngine->syncForUser($request->user()));
    }

    public function generate(Request $request)
    {
        $result = $this->recommendationEngine->syncForUser($request->user());

        $latestMeasurement = $request->user()->bodyCompositions()
            ->orderByDesc('measurement_date')
            ->orderByDesc('created_at')
            ->first();

        $sourceKey = $latestMeasurement ? 'measurement-' . $latestMeasurement->id : 'manual-' . now()->toDateString();

        $this->notificationService->notifyRecommendationsGenerated(
            $request->user(),
            count($result['data'] ?? []),
            $sourceKey
        );

        return response()->json($result);
    }

    public function updateStatus(Request $request, HealthRecommendation $recommendation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in-progress,completed',
        ]);

        $updated = $this->recommendationEngine->updateStatus(
            $request->user(),
            $recommendation,
            $validated['status']
        );

        return response()->json([
            'message' => 'Recommendation progress updated successfully',
            'data' => $updated,
        ]);
    }
}
