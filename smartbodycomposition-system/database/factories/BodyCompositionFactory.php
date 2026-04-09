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
        // 30-day realistic dataset for a single user
        static $day = 0;
        $dataset = [
            [52.9, 9.3, 4.91, 59.6, 45.4, 7, 2.5, 1350, 18, 1.0],
            [52.8, 9.2, 4.86, 59.7, 45.3, 7, 2.5, 1352, 18, 1.0],
            [52.9, 9.3, 4.92, 59.5, 45.4, 7, 2.5, 1351, 18, 1.0],
            [53.0, 9.4, 4.98, 59.4, 45.5, 7, 2.5, 1353, 18, 1.0],
            [52.7, 9.2, 4.85, 59.8, 45.2, 7, 2.5, 1349, 18, 1.0],
            [52.8, 9.3, 4.90, 59.6, 45.3, 7, 2.5, 1350, 18, 1.0],
            [52.9, 9.3, 4.91, 59.7, 45.4, 7, 2.5, 1351, 18, 1.0],
            [53.1, 9.4, 4.98, 59.5, 45.6, 7, 2.5, 1354, 18, 1.0],
            [53.0, 9.3, 4.93, 59.6, 45.5, 7, 2.5, 1352, 18, 1.0],
            [52.8, 9.2, 4.86, 59.8, 45.3, 7, 2.5, 1350, 18, 1.0],
            [52.7, 9.1, 4.80, 59.9, 45.2, 7, 2.5, 1348, 18, 1.0],
            [52.9, 9.3, 4.91, 59.7, 45.4, 7, 2.5, 1351, 18, 1.0],
            [53.0, 9.4, 4.98, 59.5, 45.5, 7, 2.5, 1353, 18, 1.0],
            [53.1, 9.5, 5.05, 59.4, 45.6, 7, 2.5, 1355, 18, 1.0],
            [52.9, 9.3, 4.91, 59.6, 45.4, 7, 2.5, 1351, 18, 1.0],
            [52.8, 9.2, 4.86, 59.7, 45.3, 7, 2.5, 1350, 18, 1.0],
            [52.7, 9.1, 4.80, 59.8, 45.2, 7, 2.5, 1348, 18, 1.0],
            [52.9, 9.3, 4.91, 59.6, 45.4, 7, 2.5, 1351, 18, 1.0],
            [53.0, 9.4, 4.98, 59.5, 45.5, 7, 2.5, 1353, 18, 1.0],
            [53.1, 9.5, 5.05, 59.4, 45.6, 7, 2.5, 1355, 18, 1.0],
            [52.9, 9.3, 4.91, 59.6, 45.4, 7, 2.5, 1351, 18, 1.0],
            [52.8, 9.2, 4.86, 59.7, 45.3, 7, 2.5, 1350, 18, 1.0],
            [52.7, 9.1, 4.80, 59.8, 45.2, 7, 2.5, 1348, 18, 1.0],
            [52.9, 9.3, 4.91, 59.6, 45.4, 7, 2.5, 1351, 18, 1.0],
            [53.0, 9.4, 4.98, 59.5, 45.5, 7, 2.5, 1353, 18, 1.0],
            [53.1, 9.5, 5.05, 59.4, 45.6, 7, 2.5, 1355, 18, 1.0],
            [52.9, 9.3, 4.91, 59.6, 45.4, 7, 2.5, 1351, 18, 1.0],
            [52.8, 9.2, 4.86, 59.7, 45.3, 7, 2.5, 1350, 18, 1.0],
            [52.7, 9.1, 4.80, 59.8, 45.2, 7, 2.5, 1348, 18, 1.0],
            [52.9, 9.3, 4.91, 59.6, 45.4, 7, 2.5, 1351, 18, 1.0],
        ];
        $entry = $dataset[$day % 30];
        $day++;
        return [
            'user_id' => User::factory(),
            'measurement_date' => now()->subDays(30 - $day),
            'measurement_time' => '08:00',
            'weight_kg' => $entry[0],
            'body_fat_percent' => $entry[1],
            'body_fat_kg' => $entry[2],
            'body_water_percent' => $entry[3],
            'muscle_mass' => $entry[4],
            'physical_rating' => $entry[5],
            'bone_mass' => $entry[6],
            'kcal' => $entry[7],
            'bmr' => $entry[8],
            'visceral_fat' => $entry[9],
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
