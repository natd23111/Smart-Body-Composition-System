<?php

namespace Database\Factories;

use App\Models\BodyComposition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BodyComposition>
 */
class BodyCompositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 6-month deterministic progression from obese toward a healthy body.
        static $day = 0;
        $endDate = Carbon::create(2026, 4, 10);
        $startDate = $endDate->copy()->subMonthsNoOverflow(6);
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $index = $day % $totalDays;
        $progress = $index / ($totalDays - 1);
        $fatLossCurve = 1 - pow(1 - $progress, 1.28);
        $wave = sin($index / 10) * 0.18;
        $microTrend = cos($index / 23) * 0.08;

        $weightKg = round(94.2 - (22.4 * $fatLossCurve) + $wave + $microTrend, 1);
        $bodyFatPercent = round(38.6 - (15.2 * $fatLossCurve) + ($wave * 0.25), 1);
        $bodyFatKg = round($weightKg * ($bodyFatPercent / 100), 2);
        $bodyWaterPercent = round(42.7 + (8.5 * $fatLossCurve) - ($wave * 0.18), 1);
        $muscleMass = round(49.1 + (2.3 * $progress) - abs($wave * 0.12), 1);
        $physicalRating = min(7, max(2, (int) round(2 + (5 * $fatLossCurve))));
        $boneMass = round(3.1 + ($progress * 0.2), 1);
        $kcal = (int) round(2320 - (265 * $fatLossCurve) + ($wave * 9));
        $bodyAge = (int) round(42 - (7 * $fatLossCurve) + ($wave * 0.2));
        $visceralFat = round(16.4 - (8.5 * $fatLossCurve) + ($wave * 0.2), 1);

        $day++;

        return [
            'user_id' => User::factory(),
            'measurement_date' => $startDate->copy()->addDays($index),
            'measurement_time' => '08:00',
            'weight_kg' => $weightKg,
            'body_fat_percent' => $bodyFatPercent,
            'body_fat_kg' => $bodyFatKg,
            'body_water_percent' => $bodyWaterPercent,
            'muscle_mass' => $muscleMass,
            'physical_rating' => $physicalRating,
            'bone_mass' => $boneMass,
            'kcal' => $kcal,
            'body_age' => $bodyAge,
            'visceral_fat' => $visceralFat,
        ];
    }

    /**
     * Indicate that the factory should create a body composition for a specific user.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Indicate that the factory should create body composition records with realistic progression.
     */
    public function withProgression(): static
    {
        return $this->sequence(
            [
                'weight_kg' => 94.2,
                'body_fat_percent' => 38.6,
                'body_fat_kg' => 36.36,
                'body_water_percent' => 42.7,
                'muscle_mass' => 49.1,
                'physical_rating' => 2,
                'bone_mass' => 3.1,
                'kcal' => 2320,
                'body_age' => 42,
                'visceral_fat' => 16.4,
                'measurement_date' => Carbon::create(2025, 10, 10),
            ],
            [
                'weight_kg' => 86.8,
                'body_fat_percent' => 33.1,
                'body_fat_kg' => 28.73,
                'body_water_percent' => 45.8,
                'muscle_mass' => 49.8,
                'physical_rating' => 3,
                'bone_mass' => 3.2,
                'kcal' => 2235,
                'body_age' => 40,
                'visceral_fat' => 13.8,
                'measurement_date' => Carbon::create(2025, 12, 10),
            ],
            [
                'weight_kg' => 79.6,
                'body_fat_percent' => 28.2,
                'body_fat_kg' => 22.45,
                'body_water_percent' => 48.4,
                'muscle_mass' => 50.4,
                'physical_rating' => 5,
                'bone_mass' => 3.2,
                'kcal' => 2145,
                'body_age' => 38,
                'visceral_fat' => 11.1,
                'measurement_date' => Carbon::create(2026, 2, 10),
            ],
            [
                'weight_kg' => 71.9,
                'body_fat_percent' => 23.4,
                'body_fat_kg' => 16.82,
                'body_water_percent' => 51.1,
                'muscle_mass' => 51.3,
                'physical_rating' => 7,
                'bone_mass' => 3.3,
                'kcal' => 2055,
                'body_age' => 35,
                'visceral_fat' => 7.9,
                'measurement_date' => Carbon::create(2026, 4, 10),
            ],
        );
    }
}
