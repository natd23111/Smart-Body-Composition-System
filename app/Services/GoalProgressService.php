<?php

namespace App\Services;

use App\Models\Goal;
use App\Models\User;

class GoalProgressService
{
    private const METRIC_LABELS = [
        'weight_kg'          => 'Weight',
        'body_fat_percent'   => 'Body Fat',
        'muscle_mass'        => 'Muscle Mass',
        'bmi'                => 'BMI',
        'visceral_fat'       => 'Visceral Fat',
        'body_water_percent' => 'Body Water',
    ];

    public function evaluateLatestMeasurementGoals(
        User $user,
        UserNotificationService $notificationService
    ): void {

        $latest = $user->bodyCompositions()
            ->orderByDesc('measurement_date')
            ->orderByDesc('created_at')
            ->first();

        if (!$latest) {
            return;
        }

        $goals = Goal::where('user_id', $user->id)
            ->where('status', 'active')
            ->get();

        foreach ($goals as $goal) {

            $currentValue = $goal->metric === 'bmi'
                ? $this->resolveBmi($user, $latest->weight_kg)
                : $latest->{$goal->metric};

            if ($currentValue === null) {
                continue;
            }

            if (!$this->goalReached($goal, (float) $currentValue)) {
                continue;
            }

            $goal->update([
                'status' => 'achieved'
            ]);

            $notificationService->notifyGoalAchieved(
                $user,
                $goal->fresh(),
                self::METRIC_LABELS[$goal->metric] ?? $goal->metric
            );
        }
    }

    private function goalReached(
        Goal $goal,
        float $currentValue
    ): bool {

        $start  = $goal->start_value;
        $target = (float) $goal->target_value;

        // Cannot determine direction
        if ($start === null) {
            return false;
        }

        // Gain goal
        if ($target > $start) {
            return $currentValue >= $target;
        }

        // Lose goal
        if ($target < $start) {
            return $currentValue <= $target;
        }

        // Maintain goal
        return abs($currentValue - $target) <= 0.5;
    }

    private function resolveBmi(
        User $user,
        $weightKg
    ): ?float {

        if (!$user->height_cm || !$weightKg) {
            return null;
        }

        $heightMeters = $user->height_cm / 100;

        if ($heightMeters <= 0) {
            return null;
        }

        return round(
            $weightKg / ($heightMeters * $heightMeters),
            1
        );
    }
}
