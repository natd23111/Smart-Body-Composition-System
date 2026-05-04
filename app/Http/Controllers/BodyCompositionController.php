<?php

namespace App\Http\Controllers;

use App\Models\BodyComposition;
use App\Services\GoalProgressService;
use App\Services\RecommendationEngine;
use App\Services\UserNotificationService;
use Illuminate\Http\Request;

class BodyCompositionController extends Controller
{
    public function __construct(
        private readonly RecommendationEngine $recommendationEngine,
        private readonly UserNotificationService $notificationService,
        private readonly GoalProgressService $goalProgressService,
    ) {
    }

    // Display all records for logged-in user
    public function index(Request $request)
    {
        $data = BodyComposition::where('user_id', $request->user()->id)->latest()->get();
        return response()->json($data);
    }

    // Store new record
    public function store(Request $request)
    {
        $validated = $request->validate([
            'measurement_date' => 'required|date',
            'measurement_time' => 'nullable',
            'weight_kg' => 'nullable|numeric',
            'body_fat_percent' => 'nullable|numeric',
            'body_fat_kg' => 'nullable|numeric',
            'body_water_percent' => 'nullable|numeric',
            'muscle_mass' => 'nullable|numeric',
            'physical_rating' => 'nullable|numeric',
            'bone_mass' => 'nullable|numeric',
            'kcal' => 'nullable|numeric',
            'body_age' => 'nullable|numeric',
            'visceral_fat' => 'nullable|numeric',
        ]);

        $validated['user_id'] = $request->user()->id;

        $record = BodyComposition::create($validated);
        $user = $request->user();

        $recommendations = $this->recommendationEngine->syncForUser($user);
        $this->notificationService->notifyRecommendationsGenerated(
            $user,
            count($recommendations['data'] ?? []),
            'measurement-' . $record->id
        );
        $this->goalProgressService->evaluateLatestMeasurementGoals($user, $this->notificationService);

        return response()->json([
            'message' => 'Record saved successfully',
            'data' => $record
        ], 201);
    }

    // Show single record
    public function show(Request $request, $id)
    {
        $record = BodyComposition::findOrFail($id);

        // Security check
        if ($record->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($record);
    }

    // Update record
    public function update(Request $request, $id)
    {
        $record = BodyComposition::findOrFail($id);

        if ($record->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $record->update($request->all());
        $this->goalProgressService->evaluateLatestMeasurementGoals($request->user(), $this->notificationService);

        return response()->json([
            'message' => 'Updated successfully',
            'data' => $record
        ]);
    }

    // Delete record
    public function destroy(Request $request, $id)
    {
        $record = BodyComposition::findOrFail($id);

        if ($record->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $record->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}
