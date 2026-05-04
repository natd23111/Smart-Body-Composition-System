<?php

namespace Database\Seeders;

use App\Models\BodyComposition;
use App\Models\Goal;
use App\Models\User;
use App\Services\RecommendationEngine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds one realistic demo user for FYP presentation.
 *
 * Account: ahmad.fadhil@example.com / password
 *
 * Profile: Ahmad Fadhil, 24-year-old male, 173 cm.
 * Scenario: overweight at the start, consistently losing body fat and
 * gaining lean muscle over ~3 months through gym training and diet.
 * Two active goals are attached.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $engine = app(RecommendationEngine::class);

        $user = User::updateOrCreate(
            ['email' => 'ahmad.fadhil@example.com'],
            [
                'name'              => 'Ahmad Fadhil',
                'password'          => Hash::make('password'),
                'role'              => 'user',
                'age'               => 24,
                'gender'            => 'Male',
                'height_cm'         => 173,
                'email_verified_at' => now(),
            ]
        );

        // Clear existing data so re-seeding is idempotent
        $user->bodyCompositions()->delete();
        $user->recommendations()->delete();
        $user->goals()->delete();

        // ── Body composition measurements (weekly, Jan – Apr 2026) ──────────
        // Weight: 88 kg → 82 kg  (-6 kg over ~3 months)
        // Body fat: 28.4 % → 22.7 % (steady fat loss)
        // Muscle mass: 33.5 kg → 36.1 kg (lean mass gain from resistance training)
        // Visceral fat: 11 → 8 (reducing metabolic risk)

        $measurements = [
            [
                'measurement_date'   => '2026-01-06',
                'measurement_time'   => '07:30',
                'weight_kg'          => 88.2,
                'body_fat_percent'   => 28.4,
                'body_fat_kg'        => 25.1,
                'body_water_percent' => 48.2,
                'muscle_mass'        => 33.5,
                'physical_rating'    => 3,
                'bone_mass'          => 3.1,
                'kcal'               => 2520,
                'body_age'           => 32,
                'visceral_fat'       => 11,
            ],
            [
                'measurement_date'   => '2026-01-20',
                'measurement_time'   => '07:30',
                'weight_kg'          => 87.0,
                'body_fat_percent'   => 27.9,
                'body_fat_kg'        => 24.3,
                'body_water_percent' => 48.5,
                'muscle_mass'        => 33.8,
                'physical_rating'    => 3,
                'bone_mass'          => 3.1,
                'kcal'               => 2495,
                'body_age'           => 32,
                'visceral_fat'       => 11,
            ],
            [
                'measurement_date'   => '2026-02-03',
                'measurement_time'   => '07:30',
                'weight_kg'          => 86.1,
                'body_fat_percent'   => 27.2,
                'body_fat_kg'        => 23.4,
                'body_water_percent' => 49.1,
                'muscle_mass'        => 34.3,
                'physical_rating'    => 4,
                'bone_mass'          => 3.1,
                'kcal'               => 2473,
                'body_age'           => 32,
                'visceral_fat'       => 10,
            ],
            [
                'measurement_date'   => '2026-02-17',
                'measurement_time'   => '07:30',
                'weight_kg'          => 85.3,
                'body_fat_percent'   => 26.5,
                'body_fat_kg'        => 22.6,
                'body_water_percent' => 49.7,
                'muscle_mass'        => 34.7,
                'physical_rating'    => 4,
                'bone_mass'          => 3.2,
                'kcal'               => 2455,
                'body_age'           => 32,
                'visceral_fat'       => 10,
            ],
            [
                'measurement_date'   => '2026-03-03',
                'measurement_time'   => '07:30',
                'weight_kg'          => 84.5,
                'body_fat_percent'   => 25.8,
                'body_fat_kg'        => 21.8,
                'body_water_percent' => 50.4,
                'muscle_mass'        => 35.1,
                'physical_rating'    => 5,
                'bone_mass'          => 3.2,
                'kcal'               => 2437,
                'body_age'           => 32,
                'visceral_fat'       => 9,
            ],
            [
                'measurement_date'   => '2026-03-17',
                'measurement_time'   => '07:30',
                'weight_kg'          => 83.8,
                'body_fat_percent'   => 25.0,
                'body_fat_kg'        => 21.0,
                'body_water_percent' => 51.0,
                'muscle_mass'        => 35.5,
                'physical_rating'    => 5,
                'bone_mass'          => 3.2,
                'kcal'               => 2420,
                'body_age'           => 32,
                'visceral_fat'       => 9,
            ],
            [
                'measurement_date'   => '2026-03-31',
                'measurement_time'   => '07:30',
                'weight_kg'          => 83.1,
                'body_fat_percent'   => 24.2,
                'body_fat_kg'        => 20.1,
                'body_water_percent' => 51.7,
                'muscle_mass'        => 35.8,
                'physical_rating'    => 6,
                'bone_mass'          => 3.2,
                'kcal'               => 2403,
                'body_age'           => 32,
                'visceral_fat'       => 8,
            ],
            [
                'measurement_date'   => '2026-04-10',
                'measurement_time'   => '07:30',
                'weight_kg'          => 82.4,
                'body_fat_percent'   => 23.5,
                'body_fat_kg'        => 19.4,
                'body_water_percent' => 52.3,
                'muscle_mass'        => 36.1,
                'physical_rating'    => 6,
                'bone_mass'          => 3.2,
                'kcal'               => 2387,
                'body_age'           => 32,
                'visceral_fat'       => 8,
            ],
        ];

        foreach ($measurements as $data) {
            BodyComposition::create(array_merge($data, ['user_id' => $user->id]));
        }

        // ── Goals ─────────────────────────────────────────────────────────────
        $goals = [
            [
                'metric'       => 'weight_kg',
                'target_value' => 78.0,
                'start_value'  => 88.2,
                'deadline'     => '2026-07-31',
                'notes'        => 'Target healthy BMI range (< 25) by end of July through caloric deficit and consistent training.',
                'status'       => 'active',
            ],
            [
                'metric'       => 'body_fat_percent',
                'target_value' => 18.0,
                'start_value'  => 28.4,
                'deadline'     => '2026-09-30',
                'notes'        => 'Reach athletic body fat range. Currently on track — losing ~0.5% per fortnight.',
                'status'       => 'active',
            ],
            [
                'metric'       => 'muscle_mass',
                'target_value' => 40.0,
                'start_value'  => 33.5,
                'deadline'     => '2026-12-31',
                'notes'        => 'Progressive overload programme — 3 days strength, 2 days cardio per week.',
                'status'       => 'active',
            ],
            [
                'metric'       => 'visceral_fat',
                'target_value' => 6.0,
                'start_value'  => 11.0,
                'deadline'     => '2026-10-31',
                'notes'        => 'Reduce metabolic risk. Avoiding processed food and sugary drinks.',
                'status'       => 'active',
            ],
        ];

        foreach ($goals as $goalData) {
            Goal::create(array_merge($goalData, ['user_id' => $user->id]));
        }

        $engine->syncForUser($user);
    }
}
