<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthRecommendation;
use App\Services\RecommendationEngine;

class HealthRecommendationController extends Controller
{
    public function __construct(private RecommendationEngine $recommendationEngine)
    {
    }

    public function index(Request $request)
    {
        return response()->json($this->recommendationEngine->syncForUser($request->user()));
    }

    public function generate(Request $request)
    {
        return response()->json($this->recommendationEngine->syncForUser($request->user()));
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
