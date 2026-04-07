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
        $weight = $this->faker->randomFloat(1, 50, 150);
        $bodyFatPercent = $this->faker->randomFloat(1, 10, 40);
        $bodyWaterPercent = $this->faker->randomFloat(1, 45, 75);

        return [
            'user_id' => User::factory(),
            'measurement_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'measurement_time' => $this->faker->optional(0.7)->time('H:i'),
            'weight_kg' => $weight,
            'body_fat_percent' => $bodyFatPercent,
            'body_fat_kg' => round($weight * ($bodyFatPercent / 100), 1),
            'body_water_percent' => $bodyWaterPercent,
            'muscle_mass' => round($weight * (1 - ($bodyFatPercent / 100)), 1),
            'bone_mass' => $this->faker->randomFloat(1, 2.5, 4.5),
            'visceral_fat' => $this->faker->randomFloat(1, 2, 15),
            'kcal' => $this->faker->numberBetween(1400, 2500),
            'bmr' => $this->faker->numberBetween(1200, 2200),
            'physical_rating' => $this->faker->numberBetween(1, 9),
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
