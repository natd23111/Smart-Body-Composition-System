<?php

namespace App\Services;

use App\Models\HealthRecommendation;
use App\Models\User;
use Illuminate\Support\Collection;

class RecommendationEngine
{
    private const VALID_STATUSES = ['pending', 'in-progress', 'completed'];

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

        $cards = collect($this->buildTemplatesForMeasurement($latestMeasurement, $previousMeasurement, $measurementSnapshot, $profile, $referenceRanges))
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
                    $this->metricBasis('Body water', $bodyWater . '%', 'Personalized minimum: ' . $ranges['body_water_minimum'] . '% based on gender and activity level.'),
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
                'title' => 'Rebalance Body Fat Through Sustainable Habits',
                'summary' => 'Body fat percentage is above the personalized wellness range, so nutrition quality and consistency should be prioritized.',
                'details' => [
                    'Build meals around protein, fiber, and minimally processed foods.',
                    'Use gradual, sustainable calorie control instead of aggressive restriction.',
                    'Track changes across several weeks rather than reacting to a single measurement.',
                ],
                'metric_basis' => [
                    $this->metricBasis('Body fat', $bodyFat . '%', 'Personalized elevated threshold: ' . $ranges['body_fat_elevated_min'] . '% based on gender and age.'),
                    $this->metricBasis('BMI', $bmi !== null ? (string) $bmi : 'N/A', 'BMI is included only as secondary context, not the main trigger.'),
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
                    $this->metricBasis('Visceral fat', $visceralFat !== null ? (string) $visceralFat : 'N/A', 'Elevated visceral-fat marker begins at ' . $ranges['visceral_fat_elevated_min'] . '.'),
                    $this->metricBasis('Activity level', ucfirst($profile['activity_level']), 'Lower activity levels increase the value of steady cardio work.'),
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
                    $this->metricBasis('Muscle ratio', $muscleRatio . '%', 'Personalized minimum: ' . $ranges['muscle_ratio_minimum'] . '% based on gender, age, and activity level.'),
                    $this->metricBasis('Muscle mass', $muscleMass . ' kg', 'Muscle mass is interpreted relative to body weight rather than alone.'),
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
                        $this->metricBasis('Weight change', '+' . $weightDelta . ' kg', 'A gain of more than ' . $gainThreshold . ' kg since your last measurement triggers this recommendation.'),
                        $this->metricBasis('Previous weight', $previousMeasurement->weight_kg . ' kg', 'Recorded in your previous measurement session.'),
                        $this->metricBasis('Current weight', $weight . ' kg', 'Your most recently recorded weight.'),
                    ],
                    'priority' => $weightDelta > ($gainThreshold * 2) ? 'high' : 'medium',
                    'confidence' => 'high',
                    'icon' => 'trending-up',
                ];
            }
        }

        // Rule: Low physical rating (fitness foundation)
        if ($physicalRating !== null && $physicalRating <= config('recommendations.physical_rating.low_max', 3)) {
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
                    $this->metricBasis('Physical rating', (string) $physicalRating, 'A rating of ' . config('recommendations.physical_rating.low_max', 3) . ' or below indicates a low fitness baseline.'),
                    $this->metricBasis('Activity level', ucfirst($profile['activity_level']), 'Inferred from recent physical rating history.'),
                ],
                'priority' => 'high',
                'confidence' => 'high',
                'icon' => 'activity',
            ];
        }

        // Rule: Low bone mass (dietary support)
        if ($boneMass !== null && $weight !== null && $weight > 0) {
            $boneRatioMin = config('recommendations.bone_mass.' . $profile['gender_key'] . '.minimum_ratio')
                ?? config('recommendations.bone_mass.default.minimum_ratio', 0.028);
            $boneRatio = round($boneMass / $weight, 4);

            if ($boneRatio < $boneRatioMin) {
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
                        $this->metricBasis('Bone mass', $boneMass . ' kg', 'Bone mass is evaluated relative to total body weight, not as an absolute figure.'),
                        $this->metricBasis('Bone-to-weight ratio', round($boneRatio * 100, 1) . '%', 'Minimum reference ratio: ' . round($boneRatioMin * 100, 1) . '% based on gender.'),
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
        $muscleRatioMinimum = $this->muscleRatioMinimum($profile['gender_key'], $profile['age'], $profile['activity_level']);
        $bodyWaterMinimum = $this->bodyWaterMinimum($profile['gender_key'], $profile['activity_level']);
        $visceralFat = config('recommendations.visceral_fat');

        return [
            'body_fat_healthy_max' => $bodyFatBand['healthy_max'],
            'body_fat_elevated_min' => $bodyFatBand['elevated_min'],
            'body_fat_high_min' => $bodyFatBand['high_min'],
            'body_water_minimum' => $bodyWaterMinimum,
            'muscle_ratio_minimum' => $muscleRatioMinimum,
            'visceral_fat_elevated_min' => $visceralFat['elevated_min'],
            'visceral_fat_high_min' => $visceralFat['high_min'],
        ];
    }

    private function bodyFatBand(string $genderKey, ?int $age): array
    {
        $bands = config('recommendations.body_fat_percent.' . $genderKey)
            ?? config('recommendations.body_fat_percent.default');

        foreach ($bands as $band) {
            if (($age ?? 40) <= $band['max_age']) {
                return $band;
            }
        }

        return end($bands);
    }

    private function bodyWaterMinimum(string $genderKey, string $activityLevel): float
    {
        $base = config('recommendations.body_water_percent.' . $genderKey . '.minimum')
            ?? config('recommendations.body_water_percent.default.minimum');
        $adjustment = config('recommendations.body_water_percent.activity_adjustment.' . $activityLevel, 0.0);

        return round($base + $adjustment, 1);
    }

    private function muscleRatioMinimum(string $genderKey, ?int $age, string $activityLevel): float
    {
        $base = config('recommendations.muscle_ratio.' . $genderKey . '.minimum')
            ?? config('recommendations.muscle_ratio.default.minimum');
        $activityAdjustment = config('recommendations.muscle_ratio.activity_adjustment.' . $activityLevel, 0.0);
        $ageAdjustment = 0.0;

        foreach (config('recommendations.muscle_ratio.age_adjustment', []) as $rule) {
            if (($age ?? 0) >= $rule['min_age']) {
                $ageAdjustment = $rule['delta'];
            }
        }

        return round($base + $activityAdjustment + $ageAdjustment, 1);
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
