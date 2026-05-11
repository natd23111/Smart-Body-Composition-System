<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;

class GoalController extends Controller
{
    private const METRIC_LABELS = [
        'weight_kg'          => 'Weight',
        'body_fat_percent'   => 'Body Fat',
        'muscle_mass'        => 'Muscle Mass',
        'visceral_fat'       => 'Visceral Fat',
    ];

    private const METRIC_UNITS = [
        'weight_kg'          => 'kg',
        'body_fat_percent'   => '%',
        'muscle_mass'        => 'kg',
        'visceral_fat'       => '',
    ];

    public function index(Request $request)
    {
        $user = $request->user();

        $goals = Goal::where('user_id', $user->id)
            ->orderByRaw("
                CASE status
                    WHEN 'active' THEN 1
                    WHEN 'achieved' THEN 2
                    WHEN 'abandoned' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('created_at', 'desc')
            ->get();

        $latest = $user->bodyCompositions()
            ->orderByDesc('measurement_date')
            ->orderByDesc('created_at')
            ->first();

        return response()->json([
            'data' => $goals->map(
                fn($goal) => $this->serialize($goal, $latest)
            ),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'metric'       => 'required|in:weight_kg,body_fat_percent,muscle_mass,visceral_fat',
            'target_value' => 'required|numeric|min:0',
            'deadline'     => 'nullable|date|after:today',
            'notes'        => 'nullable|string|max:500',
        ]);

        $user = $request->user();

        $latest = $user->bodyCompositions()
            ->orderByDesc('measurement_date')
            ->orderByDesc('created_at')
            ->first();

        $validated['user_id'] = $user->id;

        $validated['start_value'] = $latest
            ? $latest->{$validated['metric']}
            : null;

        $validated['status'] = 'active';

        $goal = Goal::create($validated);

        return response()->json([
            'message' => 'Goal created successfully',
            'data'    => $this->serialize($goal, $latest),
        ], 201);
    }

    public function update(Request $request, Goal $goal)
    {
        abort_unless(
            $goal->user_id === $request->user()->id,
            403
        );

        $validated = $request->validate([
            'metric'       => 'sometimes|in:weight_kg,body_fat_percent,muscle_mass,visceral_fat',
            'target_value' => 'sometimes|numeric|min:0',
            'deadline'     => 'nullable|date',
            'notes'        => 'nullable|string|max:500',
            'status'       => 'sometimes|in:active,achieved,abandoned',
        ]);

        $goal->update($validated);

        $latest = $request->user()
            ->bodyCompositions()
            ->orderByDesc('measurement_date')
            ->orderByDesc('created_at')
            ->first();

        return response()->json([
            'message' => 'Goal updated successfully',
            'data'    => $this->serialize(
                $goal->fresh(),
                $latest
            ),
        ]);
    }

    public function destroy(Request $request, Goal $goal)
    {
        abort_unless(
            $goal->user_id === $request->user()->id,
            403
        );

        $goal->delete();

        return response()->json([
            'message' => 'Goal deleted successfully'
        ]);
    }

    // ─────────────────────────────────────────────
    // Serializer
    // ─────────────────────────────────────────────

    private function serialize(Goal $goal, $latestMeasurement): array
    {
        $current = $latestMeasurement
            ? (float) $latestMeasurement->{$goal->metric}
            : null;

        $start  = $goal->start_value;
        $target = $goal->target_value;

        $progress = null;
        $goalDirection = null;
        $isAchieved = false;

        if (
            $current !== null &&
            $start !== null
        ) {

            // Determine direction automatically
            if ($target > $start) {
                $goalDirection = 'gain';
            } elseif ($target < $start) {
                $goalDirection = 'lose';
            } else {
                $goalDirection = 'maintain';
            }

            // Calculate progress
            if ($start != $target) {

                $totalDistance = abs($target - $start);

                if ($goalDirection === 'gain') {

                    $moved = $current - $start;

                    $isAchieved = $current >= $target;

                } elseif ($goalDirection === 'lose') {

                    $moved = $start - $current;

                    $isAchieved = $current <= $target;

                } else {

                    $moved = 0;
                }

                $progress = max(
                    0,
                    min(
                        100,
                        round(($moved / $totalDistance) * 100)
                    )
                );

            } else {

                // Maintain goal
                $isAchieved = abs($current - $target) <= 0.5;

                $progress = $isAchieved ? 100 : 0;
            }
        }

        return [
            'id'               => $goal->id,
            'metric'           => $goal->metric,
            'metric_label'     => self::METRIC_LABELS[$goal->metric] ?? $goal->metric,
            'metric_unit'      => self::METRIC_UNITS[$goal->metric] ?? '',
            'goal_direction'   => $goalDirection,
            'target_value'     => $target,
            'start_value'      => $start,
            'current_value'    => $current,
            'progress'         => $progress,
            'is_achieved'      => $isAchieved,
            'deadline'         => $goal->deadline?->toDateString(),
            'notes'            => $goal->notes,
            'status'           => $goal->status,
            'created_at'       => $goal->created_at->toIso8601String(),
            'updated_at'       => $goal->updated_at->toIso8601String(),
        ];
    }
}
