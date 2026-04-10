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
        'bmi'                => 'BMI',
        'visceral_fat'       => 'Visceral Fat',
        'body_water_percent' => 'Body Water',
    ];

    private const METRIC_UNITS = [
        'weight_kg'          => 'kg',
        'body_fat_percent'   => '%',
        'muscle_mass'        => 'kg',
        'bmi'                => 'kg/m²',
        'visceral_fat'       => '',
        'body_water_percent' => '%',
    ];

    // Fields where lower is better (progress = moving target downward)
    private const LOWER_IS_BETTER = ['weight_kg', 'body_fat_percent', 'bmi', 'visceral_fat'];

    public function index(Request $request)
    {
        $user = $request->user();

        $goals = Goal::where('user_id', $user->id)
            ->orderByRaw("FIELD(status, 'active', 'achieved', 'abandoned')")
            ->orderBy('created_at', 'desc')
            ->get();

        $latest = $user->bodyCompositions()
            ->orderByDesc('measurement_date')
            ->orderByDesc('created_at')
            ->first();

        return response()->json([
            'data' => $goals->map(fn($goal) => $this->serialize($goal, $latest)),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'metric'       => 'required|in:weight_kg,body_fat_percent,muscle_mass,bmi,visceral_fat,body_water_percent',
            'target_value' => 'required|numeric|min:0',
            'deadline'     => 'nullable|date|after:today',
            'notes'        => 'nullable|string|max:500',
        ]);

        $user = $request->user();

        // Capture current value as start_value if available
        $latest = $user->bodyCompositions()
            ->orderByDesc('measurement_date')
            ->orderByDesc('created_at')
            ->first();

        $validated['user_id']    = $user->id;
        $validated['start_value'] = $latest ? $latest->{$validated['metric']} : null;
        $validated['status']     = 'active';

        $goal = Goal::create($validated);

        return response()->json([
            'message' => 'Goal created successfully',
            'data'    => $this->serialize($goal, $latest),
        ], 201);
    }

    public function update(Request $request, Goal $goal)
    {
        abort_unless($goal->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'metric'       => 'sometimes|in:weight_kg,body_fat_percent,muscle_mass,bmi,visceral_fat,body_water_percent',
            'target_value' => 'sometimes|numeric|min:0',
            'deadline'     => 'nullable|date',
            'notes'        => 'nullable|string|max:500',
            'status'       => 'sometimes|in:active,achieved,abandoned',
        ]);

        $goal->update($validated);

        $latest = $request->user()->bodyCompositions()
            ->orderByDesc('measurement_date')
            ->orderByDesc('created_at')
            ->first();

        return response()->json([
            'message' => 'Goal updated successfully',
            'data'    => $this->serialize($goal->fresh(), $latest),
        ]);
    }

    public function destroy(Request $request, Goal $goal)
    {
        abort_unless($goal->user_id === $request->user()->id, 403);

        $goal->delete();

        return response()->json(['message' => 'Goal deleted successfully']);
    }

    // ─── Serializer ──────────────────────────────────────────────────────────

    private function serialize(Goal $goal, $latestMeasurement): array
    {
        $current = $latestMeasurement ? (float) $latestMeasurement->{$goal->metric} : null;
        $start   = $goal->start_value;
        $target  = $goal->target_value;

        $progress = null;
        if ($current !== null && $start !== null && $start != $target) {
            $totalRange = abs($target - $start);
            $moved      = abs($current - $start);
            $direction  = ($target > $start) ? 1 : -1;
            $actualMove = ($current - $start) * $direction;
            $progress   = max(0, min(100, round(($actualMove / $totalRange) * 100)));
        }

        $lowerIsBetter  = in_array($goal->metric, self::LOWER_IS_BETTER);
        $isOnTrack      = null;
        if ($current !== null) {
            $isOnTrack = $lowerIsBetter
                ? $current <= ($start ?? $current)
                : $current >= ($start ?? $current);
        }

        return [
            'id'              => $goal->id,
            'metric'          => $goal->metric,
            'metric_label'    => self::METRIC_LABELS[$goal->metric] ?? $goal->metric,
            'metric_unit'     => self::METRIC_UNITS[$goal->metric] ?? '',
            'target_value'    => $target,
            'start_value'     => $start,
            'current_value'   => $current,
            'progress'        => $progress,
            'lower_is_better' => $lowerIsBetter,
            'deadline'        => $goal->deadline?->toDateString(),
            'notes'           => $goal->notes,
            'status'          => $goal->status,
            'created_at'      => $goal->created_at->toIso8601String(),
            'updated_at'      => $goal->updated_at->toIso8601String(),
        ];
    }
}
