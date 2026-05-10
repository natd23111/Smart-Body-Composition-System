<?php

namespace App\Services;

use App\Models\HealthRecommendation;
use App\Models\RecommendationTemplate;
use App\Models\User;
use Illuminate\Support\Collection;

class RecommendationEngine
{
    private const VALID_STATUSES = ['pending', 'in-progress', 'completed'];

    public function buildTrendAnalysis(User $user, int $periodDays): array
    {
        $since = now()->subDays($periodDays)->startOfDay();

        $measurements = $user->bodyCompositions()
            ->where('measurement_date', '>=', $since)
            ->orderBy('measurement_date')
            ->orderBy('created_at')
            ->get();

        $profile = $this->resolveProfile($user);
        $ranges  = $this->resolveReferenceRanges($profile);
        $count   = $measurements->count();

        if ($count < 2) {
            return [
                'data' => [],
                'meta' => [
                    'has_data'          => false,
                    'period_days'       => $periodDays,
                    'measurement_count' => $count,
                    'profile'           => $profile,
                ],
            ];
        }

        $first = $measurements->first();
        $last  = $measurements->last();

        $heightM = $user->height_cm ? $user->height_cm / 100 : null;

        $insights = array_filter([
            $this->trendInsightBodyFat($first, $last, $measurements, $profile, $ranges),
            $this->trendInsightMuscleMass($first, $last, $measurements, $profile, $ranges),
            $this->trendInsightBodyWater($first, $last, $measurements, $profile, $ranges),
            $this->trendInsightVisceralFat($first, $last, $measurements, $ranges),
            $this->trendInsightBoneMass($first, $last, $measurements, $profile),
            $this->trendInsightPhysicalRating($first, $last, $measurements),
            $this->trendInsightWeight($first, $last, $measurements, $heightM),
        ]);

        $insights = array_values($insights);

        return [
            'data' => $insights,
            'meta' => [
                'has_data'          => true,
                'period_days'       => $periodDays,
                'measurement_count' => $count,
                'profile'           => $profile,
                'summary'           => $this->trendSummary($insights, $count),
                'ai_insight'        => $this->trendAIInsight($insights),
            ],
        ];
    }

    // ─── Individual metric trend builders ────────────────────────────────────

    private function trendInsightBodyFat($first, $last, $measurements, array $profile, array $ranges): ?array
    {
        if ($first->body_fat_percent === null || $last->body_fat_percent === null) return null;

        $before    = (float) $first->body_fat_percent;
        $after     = (float) $last->body_fat_percent;
        $absChange = round($after - $before, 1);
        $direction = $this->trendDirection($absChange);

        $classification = 'Healthy';
        $severity = 'low';

        if ($after >= $ranges['body_fat_high_min']) {
            $classification = 'Obese';
            $severity = 'high';
        } elseif ($after >= $ranges['body_fat_elevated_min']) {
            $classification = 'Overweight';
            $severity = 'moderate';
        }

        $minDisplay = $profile['gender_key'] === 'female' ? 14 : 6;

        $conclusion = ($severity !== 'low')
            ? "Your current body fat of {$after}% is classified as \"{$classification}\". " . ($direction === 'up' ? "The upward trend suggests a need to review caloric intake." : "While trending {$direction}, it remains above the healthy limit of {$ranges['body_fat_healthy_max']}%.")
            : "Your body fat is within the healthy range. " . match($direction) { 'up' => 'Monitoring the slight increase is advised.', 'down' => 'Great progress on reduction.', default => 'Maintain your current habits.' };

        $recs = $direction === 'up'
            ? [
                ['icon' => '🏃', 'title' => 'Increase Cardiovascular Activity', 'description' => 'Add 30 min cardio 4–5 times per week to support fat reduction'],
                ['icon' => '🥗', 'title' => 'Adjust Caloric Intake', 'description' => 'Focus on whole foods and reduce processed, high-calorie options'],
              ]
            : [
                ['icon' => '💪', 'title' => 'Continue Strength Training', 'description' => 'Maintain resistance training to support continued fat reduction'],
                ['icon' => '🥗', 'title' => 'Sustain Nutrition Habits', 'description' => 'Keep your current nutritional approach to continue positive progress'],
              ];

        return [
            'key'          => 'body_fat_percent',
            'label'        => 'Body Fat Percentage',
            'unit'         => '%',
            'before'       => $before,
            'after'        => $after,
            'abs_change'   => $absChange,
            'change_percent' => $before > 0 ? round(($absChange / $before) * 100, 1) : 0,
            'direction'    => $direction,
            'severity'     => $severity,
            'classification' => $classification,
            'normal_range' => [
                'min'     => $minDisplay,
                'max'     => $ranges['body_fat_healthy_max'],
                'context' => "Personalised for {$profile['gender']}, age {$profile['age']}. Activity level '{$profile['activity_level']}' is inferred from your physical rating history.",
            ],
            'observation'  => $this->buildTrendObservation('Body Fat Percentage', $before, $after, $absChange, '%'),
            'rule_applied' => "Classification: {$classification}\n"
                            . "IF body fat % >= {$ranges['body_fat_elevated_min']}% (elevated threshold for your profile) → moderate severity\n"
                            . "IF body fat % >= {$ranges['body_fat_high_min']}% (high threshold for your profile) → high severity\n"
                            . "Thresholds sourced from config/recommendations.php → body_fat_percent.{$profile['gender_key']}",
            'conclusion'      => $conclusion,
            'recommendations' => $recs,
            'data_points'     => $measurements
                ->filter(fn ($m) => $m->body_fat_percent !== null)
                ->map(fn ($m) => ['date' => $m->measurement_date, 'value' => (float) $m->body_fat_percent])
                ->values()->all(),
        ];
    }

    private function trendInsightMuscleMass($first, $last, $measurements, array $profile, array $ranges): ?array
    {
        if ($first->muscle_mass === null || $last->muscle_mass === null
            || $last->weight_kg === null || $last->weight_kg == 0) return null;

        $before    = (float) $first->muscle_mass;
        $after     = (float) $last->muscle_mass;
        $absChange = round($after - $before, 1);
        $direction = $this->trendDirection($absChange);

        $muscleRatioAfter = round(($after / (float) $last->weight_kg) * 100, 1);
        $ratioBefore      = $first->weight_kg && $first->weight_kg > 0
            ? round(($before / (float) $first->weight_kg) * 100, 1)
            : null;

        $severity = match (true) {
            $muscleRatioAfter < ($ranges['muscle_ratio_minimum'] - 4) => 'high',
            $muscleRatioAfter < $ranges['muscle_ratio_minimum']       => 'moderate',
            default                                                   => 'low',
        };

        $conclusion = match ($direction) {
            'down' => "Your muscle mass has decreased by " . abs($absChange) . " kg over this period. This may indicate insufficient protein intake or reduced training volume. Prioritise resistance training and distribute protein throughout the day.",
            'up'   => "Your muscle mass has increased by {$absChange} kg over this period. Your resistance training and nutrition are effectively supporting muscle growth — keep it up.",
            default => 'Your muscle mass has remained stable, reflecting consistent training and nutrition habits.',
        };

        $recs = $direction === 'down'
            ? [
                ['icon' => '🏋️', 'title' => 'Increase Resistance Training', 'description' => 'Prioritise compound movements at least 3 times per week'],
                ['icon' => '🥩', 'title' => 'Boost Protein Intake', 'description' => 'Aim for 1.6–2.2 g of protein per kg body weight daily'],
              ]
            : [
                ['icon' => '💪', 'title' => 'Maintain Training Volume', 'description' => 'Keep up your resistance training to sustain muscle growth'],
                ['icon' => '😴', 'title' => 'Prioritise Recovery', 'description' => 'Adequate sleep and rest days support continued muscle adaptation'],
              ];

        return [
            'key'          => 'muscle_mass',
            'label'        => 'Muscle Mass',
            'unit'         => ' kg',
            'before'       => $before,
            'after'        => $after,
            'abs_change'   => $absChange,
            'change_percent' => $before > 0 ? round(($absChange / $before) * 100, 1) : 0,
            'direction'    => $direction,
            'severity'     => $severity,
            'normal_range' => [
                'min'     => round($ranges['muscle_ratio_minimum'] / 100 * (float) $last->weight_kg, 1),
                'max'     => round(($ranges['muscle_ratio_minimum'] + 15) / 100 * (float) $last->weight_kg, 1),
                'context' => "Minimum muscle ratio {$ranges['muscle_ratio_minimum']}% of body weight — personalised for your profile",
            ],
            'observation'  => $this->buildTrendObservation('Muscle Mass', $before, $after, $absChange, ' kg'),
            'rule_applied' => "Muscle ratio = muscle mass / body weight × 100\n"
                            . "IF ratio < {$ranges['muscle_ratio_minimum']}% (profile minimum) → moderate severity\n"
                            . "IF ratio < " . ($ranges['muscle_ratio_minimum'] - 4) . "% → high severity\n"
                            . "Current ratio after: {$muscleRatioAfter}%\n"
                            . "Threshold from config/recommendations.php → muscle_ratio.{$profile['gender_key']} with activity/age adjustments",
            'conclusion'      => $conclusion,
            'recommendations' => $recs,
            'data_points'     => $measurements
                ->filter(fn ($m) => $m->muscle_mass !== null)
                ->map(fn ($m) => ['date' => $m->measurement_date, 'value' => (float) $m->muscle_mass])
                ->values()->all(),
        ];
    }

    private function trendInsightBodyWater($first, $last, $measurements, array $profile, array $ranges): ?array
    {
        if ($first->body_water_percent === null || $last->body_water_percent === null) return null;

        $before    = (float) $first->body_water_percent;
        $after     = (float) $last->body_water_percent;
        $absChange = round($after - $before, 1);
        $direction = $this->trendDirection($absChange);
        $minimum   = $ranges['body_water_minimum'];

        $severity = match (true) {
            $after < ($minimum - 5) => 'high',
            $after < $minimum       => 'moderate',
            default                 => 'low',
        };

        $conclusion = $after < $minimum
            ? "Your body water percentage is below the personalised minimum of {$minimum}%. Chronic underhydration can impair physical performance, metabolism, and recovery. Prioritise consistent fluid intake throughout the day."
            : match ($direction) {
                'down'  => "Your body water has decreased over this period. Ensure you are drinking enough fluids, especially around exercise and in warm conditions.",
                'up'    => 'Your body water levels have improved, which reflects better hydration habits. Continue your current fluid intake routine.',
                default => "Your body water levels are stable and at or above the personalised minimum of {$minimum}%. Your hydration habits appear consistent.",
            };

        return [
            'key'          => 'body_water_percent',
            'label'        => 'Body Water',
            'unit'         => '%',
            'before'       => $before,
            'after'        => $after,
            'abs_change'   => $absChange,
            'change_percent' => $before > 0 ? round(($absChange / $before) * 100, 1) : 0,
            'direction'    => $direction,
            'severity'     => $severity,
            'normal_range' => [
                'min'     => $minimum,
                'max'     => 65,
                'context' => "Personalised minimum of {$minimum}% based on gender and activity level ({$profile['activity_level']})",
            ],
            'observation'  => $this->buildTrendObservation('Body Water', $before, $after, $absChange, '%'),
            'rule_applied' => "Minimum body water threshold = {$minimum}% (base for gender + activity adjustment)\n"
                            . "IF current value < {$minimum}% → moderate severity\n"
                            . "IF current value < " . ($minimum - 5) . "% → high severity\n"
                            . "Threshold from config/recommendations.php → body_water_percent.{$profile['gender_key']} + activity_adjustment.{$profile['activity_level']}",
            'conclusion'      => $conclusion,
            'recommendations' => [
                ['icon' => '💧', 'title' => 'Increase Daily Fluid Intake', 'description' => 'Aim for 2–2.5 litres of water per day and more during exercise'],
                ['icon' => '🥦', 'title' => 'Eat Water-Rich Foods', 'description' => 'Fruits and vegetables contribute significantly to overall hydration'],
            ],
            'data_points' => $measurements
                ->filter(fn ($m) => $m->body_water_percent !== null)
                ->map(fn ($m) => ['date' => $m->measurement_date, 'value' => (float) $m->body_water_percent])
                ->values()->all(),
        ];
    }

    private function trendInsightVisceralFat($first, $last, $measurements, array $ranges): ?array
    {
        if ($first->visceral_fat === null || $last->visceral_fat === null) return null;

        $before    = (float) $first->visceral_fat;
        $after     = (float) $last->visceral_fat;
        $absChange = round($after - $before, 1);
        $direction = $this->trendDirection($absChange);

        $severity = match (true) {
            $after >= $ranges['visceral_fat_high_min']     => 'high',
            $after >= $ranges['visceral_fat_elevated_min'] => 'moderate',
            default                                        => 'low',
        };

        $conclusion = match (true) {
            $after >= $ranges['visceral_fat_high_min']     => "Your visceral fat level is significantly above the recommended threshold. High visceral fat is associated with metabolic health risks. A combination of regular cardiovascular exercise and a balanced, lower-calorie diet is the most effective approach.",
            $after >= $ranges['visceral_fat_elevated_min'] => "Your visceral fat is in the elevated range. Addressing this now with consistent cardio and reduced processed food intake is beneficial before it reaches a high level.",
            $direction === 'down'                          => "Your visceral fat has decreased over this period, which is a significant positive health marker. Continue your current cardiovascular and dietary habits.",
            default                                        => "Your visceral fat level is within the healthy range and has remained stable. Continue your current lifestyle habits.",
        };

        $recs = $after >= $ranges['visceral_fat_elevated_min']
            ? [
                ['icon' => '🏃', 'title' => 'Add Regular Cardio Sessions', 'description' => 'Cardiovascular exercise is the most effective way to reduce visceral fat'],
                ['icon' => '🥗', 'title' => 'Reduce Processed Food Intake', 'description' => 'Limit refined carbohydrates and saturated fats in your daily diet'],
              ]
            : [
                ['icon' => '🏃', 'title' => 'Continue Cardiovascular Training', 'description' => 'Sustain your cardio habits to keep visceral fat at healthy levels'],
                ['icon' => '😴', 'title' => 'Manage Stress and Sleep', 'description' => 'Cortisol from poor sleep and stress contributes to visceral fat'],
              ];

        return [
            'key'          => 'visceral_fat',
            'label'        => 'Visceral Fat',
            'unit'         => '',
            'before'       => $before,
            'after'        => $after,
            'abs_change'   => $absChange,
            'change_percent' => $before > 0 ? round(($absChange / $before) * 100, 1) : 0,
            'direction'    => $direction,
            'severity'     => $severity,
            'normal_range' => [
                'min'     => 1,
                'max'     => (int) ($ranges['visceral_fat_elevated_min'] - 1),
                'context' => "Elevated from level {$ranges['visceral_fat_elevated_min']}, high from level {$ranges['visceral_fat_high_min']}",
            ],
            'observation'  => $this->buildTrendObservation('Visceral Fat', $before, $after, $absChange, ''),
            'rule_applied' => "IF visceral fat level >= {$ranges['visceral_fat_high_min']} → high severity\n"
                            . "IF visceral fat level >= {$ranges['visceral_fat_elevated_min']} → moderate severity\n"
                            . "ELSE → low severity\n"
                            . "Thresholds from config/recommendations.php → visceral_fat",
            'conclusion'      => $conclusion,
            'recommendations' => $recs,
            'data_points'     => $measurements
                ->filter(fn ($m) => $m->visceral_fat !== null)
                ->map(fn ($m) => ['date' => $m->measurement_date, 'value' => (float) $m->visceral_fat])
                ->values()->all(),
        ];
    }

    private function trendInsightBoneMass($first, $last, $measurements, array $profile): ?array
    {
        if ($first->bone_mass === null || $last->bone_mass === null || $last->weight_kg === null) return null;

        $before    = (float) $first->bone_mass;
        $after     = (float) $last->bone_mass;
        $absChange = round($after - $before, 2);
        $direction = $this->trendDirection($absChange);

        $gender = $profile['gender_key'];
        $boneBands = config("recommendations.bone_mass.{$gender}", config('recommendations.bone_mass.male'));
        $threshold = 0;
        foreach ($boneBands as $band) {
            if ($last->weight_kg <= $band['max_weight']) {
                $threshold = (float) $band['healthy_min'];
                break;
            }
        }
        if (!$threshold) $threshold = (float) end($boneBands)['healthy_min'];

        $severity = $after < $threshold ? 'moderate' : 'low';

        $conclusion = match ($direction) {
            'up'   => "Your bone mass has slightly increased. This is a positive trend often supported by weight-bearing exercise and adequate mineral intake.",
            'down' => "Your bone mass has decreased by " . abs($absChange) . " kg. Ensure you are consuming enough calcium and vitamin D, and including resistance training in your routine.",
            default => 'Your bone mass has remained stable within your normal range.',
        };

        return [
            'key'          => 'bone_mass',
            'label'        => 'Bone Mass',
            'unit'         => ' kg',
            'before'       => $before,
            'after'        => $after,
            'abs_change'   => $absChange,
            'change_percent' => $before > 0 ? round(($absChange / $before) * 100, 1) : 0,
            'direction'    => $direction,
            'severity'     => $severity,
            'normal_range' => [
                'min'     => $threshold,
                'max'     => round($threshold + 1, 1),
                'context' => "Healthy minimum of {$threshold} kg for your current weight category",
            ],
            'observation'  => $this->buildTrendObservation('Bone Mass', $before, $after, $absChange, ' kg'),
            'rule_applied' => "Threshold = {$threshold} kg based on weight of {$last->weight_kg} kg\n"
                            . "IF bone mass < threshold → moderate severity",
            'conclusion'      => $conclusion,
            'recommendations' => [
                ['icon' => '🦴', 'title' => 'Prioritise Micronutrients', 'description' => 'Ensure adequate Calcium and Vitamin D intake'],
                ['icon' => '🏋️', 'title' => 'Weight-Bearing Exercise', 'description' => 'Resistance training helps maintain and improve bone density'],
            ],
            'data_points' => $measurements
                ->filter(fn ($m) => $m->bone_mass !== null)
                ->map(fn ($m) => ['date' => $m->measurement_date, 'value' => (float) $m->bone_mass])
                ->values()->all(),
        ];
    }

    private function trendInsightPhysicalRating($first, $last, $measurements): ?array
    {
        if ($first->physical_rating === null || $last->physical_rating === null) return null;

        $before    = (int) $first->physical_rating;
        $after     = (int) $last->physical_rating;
        $absChange = $after - $before;
        $direction = $this->trendDirection((float) $absChange);

        $breakpoints = config('recommendations.activity_levels.physical_rating_breakpoints');
        $severity = match (true) {
            $after <= $breakpoints['low_max']      => 'high',
            $after <= $breakpoints['moderate_max'] => 'moderate',
            default                                => 'low',
        };

        $labels = config('recommendations.physical_rating');
        $currentLabel = $labels[$after]['label'] ?? 'Unknown';

        return [
            'key'          => 'physical_rating',
            'label'        => 'Physical Rating',
            'unit'         => '',
            'before'       => $before,
            'after'        => $after,
            'abs_change'   => (float) $absChange,
            'change_percent' => $before > 0 ? round(($absChange / $before) * 100, 1) : 0,
            'direction'    => $direction,
            'severity'     => $severity,
            'normal_range' => [
                'min'     => $breakpoints['moderate_max'] + 1,
                'max'     => 9,
                'context' => "A rating of " . ($breakpoints['moderate_max'] + 1) . " or higher indicates a standard to athletic build",
            ],
            'observation'  => "Physical rating " . ($direction === 'stable' ? "remained at {$after}" : ($direction === 'up' ? "improved" : "decreased") . " from {$before} to {$after}"),
            'rule_applied' => "Current: {$after} ({$currentLabel})\n"
                            . "IF rating <= {$breakpoints['low_max']} → high severity\n"
                            . "IF rating <= {$breakpoints['moderate_max']} → moderate severity",
            'conclusion'      => match ($direction) {
                'up'   => "Your physical rating has improved to '{$currentLabel}'. This reflects positive changes in your body composition, typically an improved muscle-to-fat ratio.",
                'down' => "Your physical rating has dropped. This may indicate a loss of muscle mass or an increase in body fat percentage.",
                default => "Your physical rating remains stable at '{$currentLabel}'.",
            },
            'recommendations' => [
                ['icon' => '📈', 'title' => 'Focus on Body Recomposition', 'description' => 'Aim to increase muscle while managing fat for a higher rating'],
                ['icon' => '🎯', 'title' => 'Consistency is Key', 'description' => 'Maintain your exercise schedule for long-term improvements'],
            ],
            'data_points' => $measurements
                ->filter(fn ($m) => $m->physical_rating !== null)
                ->map(fn ($m) => ['date' => $m->measurement_date, 'value' => (float) $m->physical_rating])
                ->values()->all(),
        ];
    }

    private function trendInsightWeight($first, $last, $measurements, ?float $heightM): ?array
    {
        if ($first->weight_kg === null || $last->weight_kg === null) return null;

        $before    = (float) $first->weight_kg;
        $after     = (float) $last->weight_kg;
        $absChange = round($after - $before, 2);
        $direction = $this->trendDirection($absChange);
        $gainThreshold = (float) config('recommendations.weight_trend.gain_threshold_kg', 2.0);
        $lossThreshold = (float) config('recommendations.weight_trend.loss_threshold_kg', 2.0);

        $muscleGainMinForBulk = (float) config('recommendations.weight_trend.muscle_gain_min_kg_for_healthy_bulk', 0.4);
        $maxBodyFatGainForBulk = (float) config('recommendations.weight_trend.max_body_fat_gain_percent_for_healthy_bulk', 1.0);
        $maxMuscleLossForCut = (float) config('recommendations.weight_trend.max_muscle_loss_kg_for_healthy_cut', 0.3);
        $minBodyFatLossForCut = (float) config('recommendations.weight_trend.min_body_fat_loss_percent_for_healthy_cut', 0.5);

        $muscleDelta = ($first->muscle_mass !== null && $last->muscle_mass !== null)
            ? round((float) $last->muscle_mass - (float) $first->muscle_mass, 1)
            : null;
        $bodyFatDelta = ($first->body_fat_percent !== null && $last->body_fat_percent !== null)
            ? round((float) $last->body_fat_percent - (float) $first->body_fat_percent, 1)
            : null;

        $weightHealthSignal = 'neutral';

        if ($direction === 'up') {
            $isHealthyBulk = $muscleDelta !== null && $bodyFatDelta !== null
                && $muscleDelta >= $muscleGainMinForBulk
                && $bodyFatDelta <= $maxBodyFatGainForBulk;

            $isUnhealthyGain = ($bodyFatDelta !== null && $bodyFatDelta > $maxBodyFatGainForBulk)
                || ($muscleDelta !== null && $muscleDelta <= 0);

            $weightHealthSignal = $isHealthyBulk
                ? 'healthy_gain'
                : ($isUnhealthyGain ? 'unhealthy_gain' : 'mixed_gain');
        } elseif ($direction === 'down') {
            $isHealthyCut = $muscleDelta !== null && $bodyFatDelta !== null
                && $muscleDelta >= -$maxMuscleLossForCut
                && $bodyFatDelta <= -$minBodyFatLossForCut;

            $isUnhealthyLoss = $muscleDelta !== null && $muscleDelta < -$maxMuscleLossForCut;

            $weightHealthSignal = $isHealthyCut
                ? 'healthy_loss'
                : ($isUnhealthyLoss ? 'unhealthy_loss' : 'mixed_loss');
        }

        $severity = match ($weightHealthSignal) {
            'healthy_gain', 'healthy_loss', 'neutral' => 'low',
            'mixed_gain', 'mixed_loss' => 'moderate',
            'unhealthy_gain' => $absChange > ($gainThreshold * 2) ? 'high' : 'moderate',
            'unhealthy_loss' => abs($absChange) > ($lossThreshold * 2) ? 'high' : 'moderate',
            default => 'low',
        };

        $normalMin = $heightM ? round(18.5 * $heightM * $heightM, 1) : null;
        $normalMax = $heightM ? round(25.0 * $heightM * $heightM, 1) : null;

        $changeMagnitude = abs($absChange);

        $conclusion = match ($weightHealthSignal) {
            'healthy_gain' => "Your weight increased by {$changeMagnitude} kg with signs of productive gain (muscle up and body fat controlled). This pattern is generally consistent with a healthy bulking phase.",
            'unhealthy_gain' => "Your weight increased by {$changeMagnitude} kg, but composition trends suggest excess fat gain and/or limited muscle gain. Consider tightening calorie quality and improving training progression.",
            'mixed_gain' => "Your weight increased by {$changeMagnitude} kg with mixed composition signals. Keep monitoring muscle and body fat changes before increasing calories further.",
            'healthy_loss' => "Your weight decreased by {$changeMagnitude} kg while body fat trended down and muscle was largely preserved. This is a healthier fat-loss pattern.",
            'unhealthy_loss' => "Your weight decreased by {$changeMagnitude} kg, but muscle loss appears higher than expected. Slow the deficit and prioritise resistance training and protein intake.",
            'mixed_loss' => "Your weight decreased by {$changeMagnitude} kg with mixed composition signals. Aim for a slower cut and track muscle retention closely.",
            default => 'Your weight has remained relatively stable over this period, suggesting your current habits are consistent.',
        };

        $recommendations = match ($weightHealthSignal) {
            'healthy_gain' => [
                ['icon' => '🏋️', 'title' => 'Continue Progressive Overload', 'description' => 'Keep adding small strength progressions to direct weight gain toward muscle'],
                ['icon' => '🥗', 'title' => 'Keep Surplus Controlled', 'description' => 'Use a moderate calorie surplus and review body fat trend weekly'],
            ],
            'unhealthy_gain' => [
                ['icon' => '🥗', 'title' => 'Tighten Calorie Quality', 'description' => 'Reduce ultra-processed calorie sources and improve meal structure'],
                ['icon' => '🏃', 'title' => 'Add Conditioning Volume', 'description' => 'Introduce 2-3 cardio sessions weekly while keeping strength work in place'],
            ],
            'mixed_gain' => [
                ['icon' => '📈', 'title' => 'Track Composition Weekly', 'description' => 'Check both body fat and muscle trend before raising calories further'],
                ['icon' => '🥩', 'title' => 'Prioritise Protein Consistency', 'description' => 'Spread protein intake evenly across meals to support lean gains'],
            ],
            'healthy_loss' => [
                ['icon' => '💪', 'title' => 'Preserve Current Strength Plan', 'description' => 'Keep resistance training frequency stable to maintain lean tissue'],
                ['icon' => '🥩', 'title' => 'Maintain Protein Intake', 'description' => 'Aim for adequate daily protein while continuing gradual fat loss'],
            ],
            'unhealthy_loss' => [
                ['icon' => '🥩', 'title' => 'Increase Protein and Recovery', 'description' => 'Higher protein and better recovery can reduce further muscle loss'],
                ['icon' => '⚖️', 'title' => 'Reduce Deficit Aggressiveness', 'description' => 'Use a smaller calorie deficit to improve muscle retention'],
            ],
            'mixed_loss' => [
                ['icon' => '📉', 'title' => 'Slow the Cutting Pace', 'description' => 'A slower weekly loss target often improves body-composition outcomes'],
                ['icon' => '🏋️', 'title' => 'Keep Strength Training Priority', 'description' => 'Maintain progressive resistance work during fat-loss phases'],
            ],
            default => [
                ['icon' => '🥩', 'title' => 'Maintain Protein Intake', 'description' => 'Adequate protein preserves lean mass during weight changes'],
                ['icon' => '💧', 'title' => 'Stay Hydrated', 'description' => 'Hydration supports healthy metabolism and body composition'],
            ],
        };

        $muscleDeltaLine = $muscleDelta === null
            ? 'Muscle mass change unavailable (missing measurements)'
            : "Muscle mass change: {$muscleDelta} kg";
        $bodyFatDeltaLine = $bodyFatDelta === null
            ? 'Body fat change unavailable (missing measurements)'
            : "Body fat change: {$bodyFatDelta}%";

        return [
            'key'          => 'weight_kg',
            'label'        => 'Weight',
            'unit'         => ' kg',
            'before'       => $before,
            'after'        => $after,
            'abs_change'   => $absChange,
            'change_percent' => $before > 0 ? round(($absChange / $before) * 100, 1) : 0,
            'direction'    => $direction,
            'severity'     => $severity,
            'weight_health_signal' => $weightHealthSignal,
            'composition_context' => [
                'muscle_change_kg' => $muscleDelta,
                'body_fat_change_percent' => $bodyFatDelta,
            ],
            'normal_range' => [
                'min'     => $normalMin,
                'max'     => $normalMax,
                'context' => $heightM ? 'Based on BMI 18.5–25 range for your height' : 'Set height in your profile for personalised range',
            ],
            'observation'  => $this->buildTrendObservation('Weight', $before, $after, $absChange, ' kg'),
            'rule_applied' => "Weight trend uses both scale change and composition context\n"
                            . "{$muscleDeltaLine}\n"
                            . "{$bodyFatDeltaLine}\n"
                            . "Healthy gain hint: muscle +{$muscleGainMinForBulk} kg and body fat <= +{$maxBodyFatGainForBulk}%\n"
                            . "Healthy loss hint: body fat <= -{$minBodyFatLossForCut}% and muscle loss >= -{$maxMuscleLossForCut} kg\n"
                            . "Large gain threshold: +{$gainThreshold} kg; large loss threshold: -{$lossThreshold} kg\n"
                            . "Thresholds from config/recommendations.php → weight_trend",
            'conclusion'      => $conclusion,
            'recommendations' => $recommendations,
            'data_points' => $measurements
                ->filter(fn ($m) => $m->weight_kg !== null)
                ->map(fn ($m) => ['date' => $m->measurement_date, 'value' => (float) $m->weight_kg])
                ->values()->all(),
        ];
    }

    // ─── Trend helpers ────────────────────────────────────────────────────────

    private function trendDirection(float $absChange): string
    {
        if ($absChange > 0.05) return 'up';
        if ($absChange < -0.05) return 'down';
        return 'stable';
    }

    private function buildTrendObservation(string $label, float $before, float $after, float $absChange, string $unit): string
    {
        if (abs($absChange) <= 0.05) {
            return "{$label} remained stable at {$after}{$unit}";
        }
        $sign = $absChange > 0 ? '+' : '';
        return "{$label} " . ($absChange > 0 ? 'increased' : 'decreased') . " from {$before}{$unit} to {$after}{$unit} ({$sign}{$absChange}{$unit})";
    }

    private function trendSummary(array $insights, int $count): string
    {
        $high     = array_filter($insights, fn ($i) => $i['severity'] === 'high');
        $moderate = array_filter($insights, fn ($i) => $i['severity'] === 'moderate');

        $parts = [];
        if ($high) $parts[] = implode(' and ', array_column($high, 'label')) . ' require' . (count($high) === 1 ? 's' : '') . ' immediate attention';
        if ($moderate) $parts[] = implode(' and ', array_column($moderate, 'label')) . ' show' . (count($moderate) === 1 ? 's' : '') . ' a trend worth monitoring';
        if (!$parts) $parts[] = 'all tracked metrics are within acceptable ranges';

        return '📊 Overall: ' . implode(', and ', $parts) . ". Based on {$count} measurement" . ($count === 1 ? '' : 's') . ' over the selected period.';
    }

    private function trendAIInsight(array $insights): string
    {
        $high     = collect($insights)->firstWhere('severity', 'high');
        $moderate = collect($insights)->firstWhere('severity', 'moderate');

        if ($high) {
            return $this->pickInsightMessage([
                "Your top priority right now is {$high['label']}. It changed the most against your personalised thresholds, so focus on steady exercise and nutrition adjustments over the next 2-4 weeks.",
                "{$high['label']} is the main area to act on first. A calm, consistent plan for training, meals, and sleep over the next 2-4 weeks should help bring this trend back toward your target range.",
                "The biggest signal in this period is {$high['label']}. Start with one or two practical changes this week, then keep them consistent for 2-4 weeks before reassessing.",
            ], $high['key'] ?? $high['label']);
        }

        if ($moderate) {
            return $this->pickInsightMessage([
                "Your trends are generally manageable. Keep a closer watch on {$moderate['label']} and use small, consistent habit changes rather than aggressive resets.",
                "No major red flags, but {$moderate['label']} is worth monitoring. Gentle adjustments to routine and nutrition should be enough at this stage.",
                "You're in a workable range overall. Keep tracking {$moderate['label']} and make gradual improvements week by week.",
            ], $moderate['key'] ?? $moderate['label']);
        }

        return $this->pickInsightMessage([
            'Your trends look positive overall. Keep your current routine steady and continue logging measurements so progress stays visible.',
            'Most markers are moving in a healthy direction. Stay consistent with the habits that are already working for you.',
            'Current patterns are stable and encouraging. Keep doing the basics well and review again after a few more check-ins.',
        ], 'stable');
    }

    private function pickInsightMessage(array $messages, string $seed): string
    {
        if ($messages === []) {
            return '';
        }

        $index = abs(crc32($seed)) % count($messages);
        return $messages[$index];
    }

    // ─── Existing syncForUser continues below ────────────────────────────────

    public function syncForUser(User $user): array
    {
        $recentMeasurements = $user->bodyCompositions()
            ->orderByDesc('measurement_date')
            ->orderByDesc('created_at')
            ->take(2)
            ->get();

        $latestMeasurement = $recentMeasurements->first();

        if (!$latestMeasurement) {
            return [
                'data' => [],
                'meta' => [
                    'has_measurement' => false,
                    'measurement' => null,
                    'available_statuses' => self::VALID_STATUSES,
                    'engine_version' => config('recommendations.version'),
                    'guidance_scope' => config('recommendations.guidance_scope'),
                    'disclaimer' => config('recommendations.disclaimer'),
                    'synced_at' => now()->toIso8601String(),
                ],
            ];
        }

        $previousMeasurement = $recentMeasurements->count() >= 2 ? $recentMeasurements->last() : null;

        $storedByTemplate = $this->mapStoredRecommendations($user->recommendations()->latest()->get());
        $profile = $this->resolveProfile($user);
        $referenceRanges = $this->resolveReferenceRanges($profile);
        $measurementSnapshot = $this->formatMeasurementSnapshot($user, $latestMeasurement, $profile);

        // Load admin-managed templates from DB for content overrides and archived filtering
        $dbTemplates = RecommendationTemplate::all()->keyBy('template_id');

        $engineTemplates = collect($this->buildTemplatesForMeasurement($latestMeasurement, $previousMeasurement, $measurementSnapshot, $profile, $referenceRanges))
            ->filter(function (array $tpl) use ($dbTemplates) {
                $db = $dbTemplates->get($tpl['template_id']);
                return !($db && $db->status === 'Archived');
            })
            ->map(function (array $tpl) use ($dbTemplates) {
                $db = $dbTemplates->get($tpl['template_id']);
                if ($db) {
                    $tpl['title']    = $db->title;
                    $tpl['summary']  = $db->summary;
                    $tpl['details']  = $db->details;
                    $tpl['priority'] = $db->priority;
                }
                return $tpl;
            })
            ->sortBy(fn (array $tpl) => $this->priorityRank($tpl['priority']))
            ->values();

        $cards = $engineTemplates
            ->map(function (array $template) use ($user, $storedByTemplate, $measurementSnapshot, $profile) {
                $stored = $storedByTemplate->get($template['template_id']);
                $existingRecord = $stored['record'] ?? null;
                $existingPayload = $stored['payload'] ?? [];
                $status = $this->sanitizeStatus($existingPayload['status'] ?? 'pending');

                $payload = array_merge($template, [
                    'status' => $status,
                    'measurement_snapshot' => $measurementSnapshot,
                    'profile_snapshot' => $profile,
                    'engine_version' => config('recommendations.version'),
                    'guidance_scope' => config('recommendations.guidance_scope'),
                    'disclaimer' => config('recommendations.disclaimer'),
                    'last_synced_at' => now()->toIso8601String(),
                    'version' => 1,
                ]);

                if ($existingRecord) {
                    if ($existingRecord->recommendation_text !== $this->encodePayload($payload)) {
                        $existingRecord->update([
                            'recommendation_text' => $this->encodePayload($payload),
                        ]);
                        $existingRecord->refresh();
                    }

                    return $this->serializeRecommendation($existingRecord, $payload);
                }

                $record = HealthRecommendation::create([
                    'user_id' => $user->id,
                    'recommendation_text' => $this->encodePayload($payload),
                ]);

                return $this->serializeRecommendation($record, $payload);
            })
            ->values()
            ->all();

        return [
            'data' => $cards,
            'meta' => [
                'has_measurement' => true,
                'measurement' => $measurementSnapshot,
                'profile' => $profile,
                'available_statuses' => self::VALID_STATUSES,
                'engine_version' => config('recommendations.version'),
                'guidance_scope' => config('recommendations.guidance_scope'),
                'disclaimer' => config('recommendations.disclaimer'),
                'synced_at' => now()->toIso8601String(),
            ],
        ];
    }

    public function updateStatus(User $user, HealthRecommendation $recommendation, string $status): array
    {
        abort_unless($recommendation->user_id === $user->id, 403, 'Unauthorized');

        $payload = $this->parsePayload($recommendation) ?? [
            'template_id' => 'legacy-' . $recommendation->id,
            'template_code' => 'TMPL-LEGACY-' . $recommendation->id,
            'recommendation_type' => 'General',
            'title' => 'Legacy Recommendation',
            'summary' => $recommendation->recommendation_text,
            'details' => [$recommendation->recommendation_text],
            'metric_basis' => [],
            'priority' => 'medium',
            'icon' => 'heart',
            'confidence' => 'low',
            'guidance_scope' => config('recommendations.guidance_scope'),
            'disclaimer' => config('recommendations.disclaimer'),
            'engine_version' => config('recommendations.version'),
            'measurement_snapshot' => null,
            'profile_snapshot' => null,
            'version' => 1,
        ];

        $payload['status'] = $this->sanitizeStatus($status);
        $payload['last_status_updated_at'] = now()->toIso8601String();

        $recommendation->update([
            'recommendation_text' => $this->encodePayload($payload),
        ]);

        $recommendation->refresh();

        return $this->serializeRecommendation($recommendation, $payload);
    }

    private function buildTemplatesForMeasurement($measurement, $previousMeasurement, array $snapshot, array $profile, array $ranges): array
    {
        $templates = [];

        $bodyFat = $measurement->body_fat_percent;

        $bodyFatLabel = 'Healthy';
        if ($bodyFat >= $ranges['body_fat_high_min']) {
            $bodyFatLabel = 'Obese';
        } elseif ($bodyFat >= $ranges['body_fat_elevated_min']) {
            $bodyFatLabel = 'Overweight';
        }

        $bodyWater = $measurement->body_water_percent;
        $muscleMass = $measurement->muscle_mass;
        $visceralFat = $measurement->visceral_fat;
        $weight = $measurement->weight_kg;
        $boneMass = $measurement->bone_mass;
        $physicalRating = $measurement->physical_rating;
        $muscleRatio = $weight && $muscleMass ? round(($muscleMass / $weight) * 100, 1) : null;
        $bmi = $snapshot['bmi'];

        if ($bodyWater !== null && $bodyWater < $ranges['body_water_minimum']) {
            $templates[] = [
                'template_id' => 'hydration-foundation',
                'template_code' => 'TMPL-HYD-001',
                'recommendation_type' => 'Hydration',
                'title' => 'Improve Daily Hydration Consistency',
                'summary' => 'Your body water percentage is below the personalized general wellness range for your profile and activity level.',
                'details' => [
                    'Spread fluid intake more evenly across the day instead of catching up late.',
                    'Increase hydration attention around exercise, hot weather, and long gaps between meals.',
                    'Use water-rich foods and a consistent routine to make hydration easier to maintain.',
                ],
                'metric_basis' => [
                    $this->metricBasis('Body water', $bodyWater . '%', 'Your body water is below the recommended minimum of ' . $ranges['body_water_minimum'] . '% for your gender and activity level. This means your body may not be getting enough fluids to function properly.'),
                ],
                'priority' => 'high',
                'confidence' => 'high',
                'icon' => 'droplet',
            ];
        }

        if ($bodyFat !== null && $bodyFat >= $ranges['body_fat_elevated_min']) {
            $templates[] = [
                'template_id' => 'body-fat-reduction',
                'template_code' => 'TMPL-NUT-001',
                'recommendation_type' => 'Nutrition',
                'title' => "Address {$bodyFatLabel} Body Composition",
                'summary' => "Your body fat percentage is currently in the **{$bodyFatLabel}** range. Prioritizing nutrition quality and consistency is recommended.",
                'details' => [
                    'Build meals around protein, fiber, and minimally processed foods.',
                    'Use gradual, sustainable calorie control instead of aggressive restriction.',
                    'Track changes across several weeks rather than reacting to a single measurement.',
                ],
                'metric_basis' => [
                    $this->metricBasis('Body fat', $bodyFat . '%', "Your current level is classified as **{$bodyFatLabel}**. The healthy upper limit for your profile is {$ranges['body_fat_healthy_max']}%."),
                    $this->metricBasis('BMI', $bmi !== null ? (string) $bmi : 'N/A', 'Your BMI is shown here for extra context. It helps give a fuller picture alongside your body fat reading.'),
                ],
                'priority' => $bodyFat >= $ranges['body_fat_high_min'] ? 'high' : 'medium',
                'confidence' => 'high',
                'icon' => 'apple',
            ];
        }

        if (($visceralFat !== null && $visceralFat >= $ranges['visceral_fat_elevated_min'])
            || ($bodyFat !== null && $bodyFat >= $ranges['body_fat_high_min'])) {
            $templates[] = [
                'template_id' => 'cardio-conditioning',
                'template_code' => 'TMPL-EXE-001',
                'recommendation_type' => 'Exercise',
                'title' => 'Improve Cardiovascular Conditioning',
                'summary' => 'Visceral fat and overall composition suggest adding or strengthening regular cardio work to support general wellness.',
                'details' => [
                    'Accumulate moderate cardio across the week in a way you can sustain consistently.',
                    'Choose walking, cycling, rowing, or similar lower-barrier activities if adherence is the main issue.',
                    'Keep strength training in place so cardio complements overall body composition goals.',
                ],
                'metric_basis' => [
                    $this->metricBasis('Visceral fat', $visceralFat !== null ? (string) $visceralFat : 'N/A', 'A visceral fat reading of ' . $ranges['visceral_fat_elevated_min'] . ' or above is considered elevated. Visceral fat surrounds your organs and is linked to cardiovascular and metabolic health risks.'),
                    $this->metricBasis('Activity level', ucfirst($profile['activity_level']), 'Your current activity level is relatively low. Adding regular cardio can help burn visceral fat more effectively.'),
                ],
                'priority' => $visceralFat !== null && $visceralFat >= $ranges['visceral_fat_high_min'] ? 'high' : 'medium',
                'confidence' => 'medium',
                'icon' => 'activity',
            ];
        }

        if ($muscleRatio !== null && $muscleRatio < $ranges['muscle_ratio_minimum']) {
            $templates[] = [
                'template_id' => 'muscle-preservation',
                'template_code' => 'TMPL-EXE-002',
                'recommendation_type' => 'Exercise',
                'title' => 'Protect Lean Muscle Mass',
                'summary' => 'Muscle mass ratio is below the personalized reference point, so resistance training quality and protein consistency should remain central.',
                'details' => [
                    'Keep resistance training focused on major muscle groups at least a few times per week.',
                    'Progress training gradually instead of changing volume and intensity at the same time.',
                    'Distribute protein intake through the day to better support lean mass retention.',
                ],
                'metric_basis' => [
                    $this->metricBasis('Muscle ratio', $muscleRatio . '%', 'Your muscle-to-body-weight ratio is below the recommended ' . $ranges['muscle_ratio_minimum'] . '% for your gender, age, and activity level. A low muscle ratio means your body carries less functional lean tissue than is ideal.'),
                    $this->metricBasis('Muscle mass', $muscleMass . ' kg', 'Muscle mass is measured against your total body weight — having more muscle relative to your size is a strong marker of health and physical function.'),
                ],
                'priority' => 'medium',
                'confidence' => 'medium',
                'icon' => 'trending-up',
            ];
        }

        // Rule: Weight gain trend (dietary)
        if ($previousMeasurement !== null && $weight !== null && $previousMeasurement->weight_kg !== null) {
            $weightDelta = round($weight - $previousMeasurement->weight_kg, 2);
            $gainThreshold = config('recommendations.weight_trend.gain_threshold_kg', 2.0);

            if ($weightDelta > $gainThreshold) {
                $templates[] = [
                    'template_id' => 'weight-gain-trend',
                    'template_code' => 'TMPL-NUT-002',
                    'recommendation_type' => 'Nutrition',
                    'title' => 'Review Your Dietary Intake',
                    'summary' => 'Your weight has increased by ' . $weightDelta . ' kg since your last measurement. This may indicate a caloric surplus worth reviewing.',
                    'details' => [
                        'Review portion sizes and total daily calorie intake relative to your activity level.',
                        'Prioritise whole foods and reduce intake of highly processed, calorie-dense options.',
                        'Focus on consistent eating patterns rather than making dramatic short-term cuts.',
                        'Consider tracking meals for 1–2 weeks to identify key patterns.',
                    ],
                    'metric_basis' => [
                        $this->metricBasis('Weight change', '+' . $weightDelta . ' kg', 'You have gained more than ' . $gainThreshold . ' kg since your last measurement. A gain of this size in a short period often points to a caloric surplus worth reviewing.'),
                        $this->metricBasis('Previous weight', $previousMeasurement->weight_kg . ' kg', 'This was your weight at your last measurement session.'),
                        $this->metricBasis('Current weight', $weight . ' kg', 'This is your most recently recorded weight.'),
                    ],
                    'priority' => $weightDelta > ($gainThreshold * 2) ? 'high' : 'medium',
                    'confidence' => 'high',
                    'icon' => 'trending-up',
                ];
            }
        }

        // Rule: Low physical rating (fitness foundation)
        if ($physicalRating !== null && $physicalRating <= config('recommendations.activity_levels.physical_rating_breakpoints.low_max', 3)) {
            $templates[] = [
                'template_id' => 'fitness-foundation',
                'template_code' => 'TMPL-FIT-001',
                'recommendation_type' => 'Exercise',
                'title' => 'Build a Structured Fitness Foundation',
                'summary' => 'Your physical rating indicates a low current fitness level. Starting with consistent, low-intensity exercise is the most effective first step.',
                'details' => [
                    'Begin with 20–30 minute sessions of low-intensity cardio 3–4 times per week.',
                    'Introduce light resistance exercises targeting major muscle groups twice per week.',
                    'Prioritise consistency over intensity — showing up regularly matters more than training hard early on.',
                    'Progress gradually by adding 5–10% volume or intensity every two weeks.',
                ],
                'metric_basis' => [
                    $this->metricBasis('Physical rating', (string) $physicalRating, 'A rating of ' . config('recommendations.activity_levels.physical_rating_breakpoints.low_max', 3) . ' or below indicates a low current fitness level. The scale typically runs from 1 (very low) to 9 (athletic), so there is room to improve with consistent effort.'),
                    $this->metricBasis('Activity level', ucfirst($profile['activity_level']), 'Your activity level is estimated from your physical rating history. Building a regular exercise habit is the most effective way to raise this over time.'),
                ],
                'priority' => 'high',
                'confidence' => 'high',
                'icon' => 'activity',
            ];
        }

        // Rule: Low bone mass (dietary support)
        if ($boneMass !== null && $weight !== null && $weight > 0) {
            $gender = $profile['gender_key'];
            $boneBands = config("recommendations.bone_mass.{$gender}", config('recommendations.bone_mass.male'));
            $boneThreshold = 0;
            foreach ($boneBands as $band) {
                if ($weight <= $band['max_weight']) {
                    $boneThreshold = $band['healthy_min'];
                    break;
                }
            }
            if (!$boneThreshold) {
                $boneThreshold = end($boneBands)['healthy_min'];
            }

            if ($boneMass < $boneThreshold) {
                $templates[] = [
                    'template_id' => 'bone-health',
                    'template_code' => 'TMPL-NUT-003',
                    'recommendation_type' => 'Nutrition',
                    'title' => 'Support Bone Density Through Diet',
                    'summary' => 'Your bone mass is below the reference level for your body weight. Nutritional support for bone density is beneficial at every age.',
                    'details' => [
                        'Ensure adequate calcium intake from dairy, leafy greens, or fortified foods.',
                        'Maintain sufficient vitamin D levels through sunlight exposure and dietary sources.',
                        'Include weight-bearing exercise such as walking, jogging, or resistance training regularly.',
                        'Limit excessive sodium and caffeine, which can impair calcium absorption over time.',
                    ],
                    'metric_basis' => [
                        $this->metricBasis('Bone mass', $boneMass . ' kg', "For your weight category, the healthy minimum bone mass is {$boneThreshold} kg."),
                        $this->metricBasis('Body weight', $weight . ' kg', 'Your weight determines the reference range for bone mass.'),
                    ],
                    'priority' => 'medium',
                    'confidence' => 'medium',
                    'icon' => 'apple',
                ];
            }
        }

        // Rule: Underweight BMI (caloric/nutritional intake)
        if ($bmi !== null && $bmi < config('recommendations.bmi.underweight_max', 18.5)) {
            $templates[] = [
                'template_id' => 'underweight-nutrition',
                'template_code' => 'TMPL-NUT-004',
                'recommendation_type' => 'Nutrition',
                'title' => 'Increase Caloric and Nutritional Intake',
                'summary' => 'Your BMI is below the general wellness threshold, which may indicate insufficient caloric intake or underlying nutritional needs.',
                'details' => [
                    'Increase total daily calorie intake through nutrient-dense foods rather than processed snacks.',
                    'Prioritise protein, healthy fats, and complex carbohydrates at every meal.',
                    'Eat more frequently — 4–5 smaller meals per day can help increase overall intake.',
                    'Consider consulting a dietitian if healthy weight gain proves difficult over several weeks.',
                ],
                'metric_basis' => [
                    $this->metricBasis('BMI', (string) $bmi, 'A BMI below ' . config('recommendations.bmi.underweight_max', 18.5) . ' is classified as underweight.'),
                    $this->metricBasis('Weight', $weight . ' kg', 'Current weight used in BMI calculation alongside height.'),
                ],
                'priority' => 'high',
                'confidence' => 'medium',
                'icon' => 'apple',
            ];
        }

        if ($templates === []) {
            $templates[] = [
                'template_id' => 'steady-progress',
                'template_code' => 'TMPL-REC-001',
                'recommendation_type' => 'Recovery',
                'title' => 'Maintain Your Current Wellness Habits',
                'summary' => 'Your latest body-composition markers are broadly within the personalized wellness ranges used by the system.',
                'details' => [
                    'Keep routines consistent before making large changes to training or nutrition.',
                    'Continue tracking measurements regularly so future guidance stays relevant.',
                    'Use gradual habit adjustments instead of chasing short-term fluctuations.',
                ],
                'metric_basis' => [
                    $this->metricBasis('Profile context', ucfirst($profile['activity_level']) . ' activity', 'Personalized ranges were applied using available gender, age, and inferred activity level data.'),
                ],
                'priority' => 'low',
                'confidence' => 'medium',
                'icon' => 'heart',
            ];
        }

        return collect($templates)
            ->unique('template_id')
            ->sortBy(fn (array $template) => $this->priorityRank($template['priority']))
            ->values()
            ->all();
    }

    private function resolveProfile(User $user): array
    {
        return [
            'gender' => $user->gender ?: 'Unspecified',
            'gender_key' => $this->normalizeGender($user->gender),
            'age' => $user->age,
            'activity_level' => $this->resolveActivityLevel($user),
            'height_cm' => $user->height_cm,
        ];
    }

    private function resolveActivityLevel(User $user): string
    {
        $avgPhysicalRating = $user->bodyCompositions()
            ->orderByDesc('measurement_date')
            ->orderByDesc('created_at')
            ->limit(config('recommendations.history.activity_lookback_records', 5))
            ->pluck('physical_rating')
            ->filter(fn ($value) => $value !== null)
            ->avg();

        if ($avgPhysicalRating === null) {
            return config('recommendations.activity_levels.default', 'moderate');
        }

        $breakpoints = config('recommendations.activity_levels.physical_rating_breakpoints');

        return match (true) {
            $avgPhysicalRating <= $breakpoints['low_max'] => 'low',
            $avgPhysicalRating <= $breakpoints['moderate_max'] => 'moderate',
            default => 'high',
        };
    }

    private function resolveReferenceRanges(array $profile): array
    {
        $bodyFatBand = $this->bodyFatBand($profile['gender_key'], $profile['age']);
        $muscleRatioMinimum = $this->muscleRatioMinimum($profile['gender_key'], $profile['age']);
        $bodyWaterMinimum = $this->bodyWaterMinimum($profile['gender_key'], $profile['activity_level']);
        $visceralFat = config('recommendations.visceral_fat');

        return [
            'body_fat_healthy_max' => (float) $bodyFatBand['healthy_max'],
            'body_fat_elevated_min' => (float) $bodyFatBand['overweight_min'],
            'body_fat_high_min' => (float) $bodyFatBand['obese_min'],
            'body_water_minimum' => $bodyWaterMinimum,
            'muscle_ratio_minimum' => $muscleRatioMinimum,
            'visceral_fat_elevated_min' => (int) $visceralFat['excess_min'],
            'visceral_fat_high_min' => (int) ($visceralFat['excess_min'] + 2), // High threshold estimated
        ];
    }

    private function bodyFatBand(string $genderKey, ?int $age): array
    {
        $bands = config('recommendations.body_fat_percent.' . $genderKey)
            ?? config('recommendations.body_fat_percent.male');

        foreach ($bands as $band) {
            if (($age ?? 35) <= $band['max_age']) {
                return $band;
            }
        }

        return end($bands);
    }

    private function bodyWaterMinimum(string $genderKey, string $activityLevel): float
    {
        $type = ($activityLevel === 'high') ? 'athletic' : 'standard';
        $config = config("recommendations.body_water_percent.{$genderKey}.{$type}")
               ?? config("recommendations.body_water_percent.male.{$type}");

        return (float) $config['minimum'];
    }

    private function muscleRatioMinimum(string $genderKey, ?int $age): float
    {
        $bands = config("recommendations.muscle_ratio.{$genderKey}")
               ?? config('recommendations.muscle_ratio.male');

        $age = $age ?? 35;
        $targetBand = null;
        foreach ($bands as $band) {
            if ($age <= $band['max_age']) {
                $targetBand = $band;
                break;
            }
        }
        if (!$targetBand) {
            $targetBand = end($bands);
        }

        return (float) $targetBand['good_min'];
    }

    private function normalizeGender(?string $gender): string
    {
        return match (strtolower((string) $gender)) {
            'male' => 'male',
            'female' => 'female',
            default => 'default',
        };
    }

    private function mapStoredRecommendations(Collection $recommendations): Collection
    {
        return $recommendations
            ->map(function (HealthRecommendation $recommendation) {
                $payload = $this->parsePayload($recommendation);

                if (!is_array($payload) || empty($payload['template_id'])) {
                    return null;
                }

                return [
                    'template_id' => $payload['template_id'],
                    'record' => $recommendation,
                    'payload' => $payload,
                ];
            })
            ->filter()
            ->keyBy('template_id');
    }

    private function parsePayload(HealthRecommendation $recommendation): ?array
    {
        $decoded = json_decode($recommendation->recommendation_text, true);

        return is_array($decoded) ? $decoded : null;
    }

    private function encodePayload(array $payload): string
    {
        return json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private function serializeRecommendation(HealthRecommendation $recommendation, array $payload): array
    {
        return [
            'id' => $recommendation->id,
            'template_id' => $payload['template_id'],
            'template_code' => $payload['template_code'],
            'recommendation_type' => $payload['recommendation_type'],
            'title' => $payload['title'],
            'summary' => $payload['summary'],
            'details' => $payload['details'] ?? [],
            'metric_basis' => $payload['metric_basis'] ?? [],
            'priority' => $payload['priority'] ?? 'medium',
            'status' => $this->sanitizeStatus($payload['status'] ?? 'pending'),
            'confidence' => $payload['confidence'] ?? 'medium',
            'icon' => $payload['icon'] ?? 'heart',
            'measurement_snapshot' => $payload['measurement_snapshot'] ?? null,
            'profile_snapshot' => $payload['profile_snapshot'] ?? null,
            'guidance_scope' => $payload['guidance_scope'] ?? config('recommendations.guidance_scope'),
            'disclaimer' => $payload['disclaimer'] ?? config('recommendations.disclaimer'),
            'engine_version' => $payload['engine_version'] ?? config('recommendations.version'),
            'last_synced_at' => $payload['last_synced_at'] ?? null,
            'last_status_updated_at' => $payload['last_status_updated_at'] ?? null,
            'created_at' => optional($recommendation->created_at)->toIso8601String(),
            'updated_at' => optional($recommendation->updated_at)->toIso8601String(),
        ];
    }

    private function formatMeasurementSnapshot(User $user, $measurement, array $profile): array
    {
        $heightMeters = $user->height_cm ? $user->height_cm / 100 : null;
        $bmi = $heightMeters && $measurement->weight_kg
            ? round($measurement->weight_kg / ($heightMeters * $heightMeters), 1)
            : null;

        return [
            'measurement_date' => optional($measurement->measurement_date)->format('Y-m-d') ?? $measurement->measurement_date,
            'weight_kg' => $measurement->weight_kg,
            'body_fat_percent' => $measurement->body_fat_percent,
            'body_water_percent' => $measurement->body_water_percent,
            'muscle_mass' => $measurement->muscle_mass,
            'visceral_fat' => $measurement->visceral_fat,
            'physical_rating' => $measurement->physical_rating,
            'bmi' => $bmi,
            'activity_level' => $profile['activity_level'],
        ];
    }

    private function metricBasis(string $label, string $value, string $insight): array
    {
        return [
            'label' => $label,
            'value' => $value,
            'insight' => $insight,
        ];
    }

    private function sanitizeStatus(string $status): string
    {
        return in_array($status, self::VALID_STATUSES, true) ? $status : 'pending';
    }

    private function priorityRank(string $priority): int
    {
        return match ($priority) {
            'high' => 1,
            'medium' => 2,
            default => 3,
        };
    }
}
