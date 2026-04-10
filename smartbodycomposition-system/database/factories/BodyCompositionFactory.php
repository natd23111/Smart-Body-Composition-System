<?php

namespace Database\Factories;

use App\Models\BodyComposition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        // 30-day deterministic progression trending from obese toward overweight.
        static $day = 0;
        $index = $day % 30;
        $progress = $index / 29;
        $wave = sin($index / 3) * 0.18;

        $weightKg = round(89.4 - (4.6 * $progress) + $wave, 1);
        $bodyFatPercent = round(35.2 - (3.8 * $progress) + ($wave * 0.35), 1);
        $bodyFatKg = round($weightKg * ($bodyFatPercent / 100), 2);
        $bodyWaterPercent = round(44.8 + (2.4 * $progress) - ($wave * 0.25), 1);
        $muscleMass = round(49.6 + (0.9 * $progress) - abs($wave * 0.2), 1);
        $physicalRating = min(5, max(2, (int) round(2 + (3 * $progress))));
        $boneMass = round(3.2 + ($progress * 0.1), 1);
        $kcal = (int) round(2210 - (110 * $progress) + ($wave * 8));
        $bmr = (int) round(1765 - (85 * $progress) + ($wave * 6));
        $visceralFat = round(14.6 - (3.1 * $progress) + ($wave * 0.25), 1);

        $day++;

        return [
            'user_id' => User::factory(),
            'measurement_date' => now()->subDays(30 - $day),
            'measurement_time' => '08:00',
            'weight_kg' => $weightKg,
            'body_fat_percent' => $bodyFatPercent,
            'body_fat_kg' => $bodyFatKg,
            'body_water_percent' => $bodyWaterPercent,
            'muscle_mass' => $muscleMass,
            'physical_rating' => $physicalRating,
            'bone_mass' => $boneMass,
            'kcal' => $kcal,
            'bmr' => $bmr,
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
        $baseWeight = 75;
        $baseBodyFat = 22;

        return $this->sequence(
            [
                'weight_kg' => $baseWeight,
                'body_fat_percent' => $baseBodyFat,
                'measurement_date' => now()->subDays(30),
            ],
            [
                'weight_kg' => $baseWeight - 1.5,
                'body_fat_percent' => $baseBodyFat - 1.2,
                'measurement_date' => now()->subDays(20),
            ],
            [
                'weight_kg' => $baseWeight - 2.8,
                'body_fat_percent' => $baseBodyFat - 2.5,
                'measurement_date' => now()->subDays(10),
            ],
            [
                'weight_kg' => $baseWeight - 3.5,
                'body_fat_percent' => $baseBodyFat - 3.2,
                'measurement_date' => now(),
            ],
        );
    }
}
