<?php

namespace Database\Seeders;

use App\Models\BodyComposition;
use App\Models\Goal;
use App\Models\User;
use App\Services\RecommendationEngine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds a dedicated demo user whose measurement history triggers
 * every rule path across the Trend Analysis module.
 *
 * Account: trend.demo@example.com / password
 *
 * Profile: Trend Demo User, 28-year-old male, 175 cm.
 *
 * Scenario — 12 bi-weekly measurements over ~5 months:
 *  Phase 1 (Jan–Feb): Obese baseline, high visceral fat, low hydration
 *  Phase 2 (Mar):     Setback — temporary weight/fat gain (unhealthy_gain signal)
 *  Phase 3 (Apr–May): Steady improvement, crossing overweight→healthy threshold
 *  Phase 4 (Jun):     Healthy range achieved (body fat = 18.5 %, visceral = 7)
 *
 * By switching the trend period (7 / 14 / 30 / 90 days) the examiner can
 * observe the full transformation arc, including the unhealthy-gain setback
 * that disappears from shorter windows — demonstrating the importance of
 * choosing an appropriate analysis period.
 */
class TrendDemoSeeder extends Seeder
{
    public function run(): void
    {
        $engine = app(RecommendationEngine::class);

        $user = User::updateOrCreate(
            ['email' => 'trend.demo@example.com'],
            [
                'name'              => 'Trend Demo User',
                'password'          => Hash::make('password'),
                'role'              => 'user',
                'age'               => 28,
                'gender'            => 'Male',
                'height_cm'         => 175,
                'email_verified_at' => now(),
            ]
        );

        $user->bodyCompositions()->delete();
        $user->recommendations()->delete();
        $user->goals()->delete();

        $measurements = [

            // Phase 1 — Unhealthy Baseline (Jan – Feb 2026) ─────────────────

            [
                'measurement_date'   => '2026-01-05',
                'measurement_time'   => '07:30',
                'weight_kg'          => 94.0,
                'body_fat_percent'   => 31.0,
                'body_fat_kg'        => 29.1,
                'body_water_percent' => 45.0,
                'muscle_mass'        => 31.0,
                'physical_rating'    => 2,
                'bone_mass'          => 2.9,
                'kcal'               => 2320,
                'body_age'           => 45,
                'visceral_fat'       => 16,
            ],
            [
                'measurement_date'   => '2026-01-19',
                'measurement_time'   => '07:30',
                'weight_kg'          => 93.0,
                'body_fat_percent'   => 30.5,
                'body_fat_kg'        => 28.4,
                'body_water_percent' => 45.5,
                'muscle_mass'        => 31.3,
                'physical_rating'    => 2,
                'bone_mass'          => 2.9,
                'kcal'               => 2300,
                'body_age'           => 44,
                'visceral_fat'       => 15,
            ],
            [
                'measurement_date'   => '2026-02-02',
                'measurement_time'   => '07:30',
                'weight_kg'          => 91.5,
                'body_fat_percent'   => 29.5,
                'body_fat_kg'        => 27.0,
                'body_water_percent' => 46.2,
                'muscle_mass'        => 31.8,
                'physical_rating'    => 3,
                'bone_mass'          => 3.0,
                'kcal'               => 2260,
                'body_age'           => 42,
                'visceral_fat'       => 15,
            ],
            [
                'measurement_date'   => '2026-02-16',
                'measurement_time'   => '07:30',
                'weight_kg'          => 90.0,
                'body_fat_percent'   => 28.5,
                'body_fat_kg'        => 25.7,
                'body_water_percent' => 47.0,
                'muscle_mass'        => 32.2,
                'physical_rating'    => 3,
                'bone_mass'          => 3.0,
                'kcal'               => 2230,
                'body_age'           => 41,
                'visceral_fat'       => 14,
            ],

            // Phase 2 — SETBACK (Mar 2026) ─────────────────────────────────
            // Weight and fat INCREASE while muscle DECREASES.
            // This triggers the weight-trend "unhealthy_gain" signal
            // and demonstrates that the engine distinguishes productive
            // bulking from regress.

            [
                'measurement_date'   => '2026-03-02',
                'measurement_time'   => '07:30',
                'weight_kg'          => 91.2,
                'body_fat_percent'   => 29.0,
                'body_fat_kg'        => 26.4,
                'body_water_percent' => 46.5,
                'muscle_mass'        => 31.9,
                'physical_rating'    => 3,
                'bone_mass'          => 3.0,
                'kcal'               => 2255,
                'body_age'           => 42,
                'visceral_fat'       => 14.5,
            ],
            [
                'measurement_date'   => '2026-03-16',
                'measurement_time'   => '07:30',
                'weight_kg'          => 89.5,
                'body_fat_percent'   => 28.0,
                'body_fat_kg'        => 25.1,
                'body_water_percent' => 47.5,
                'muscle_mass'        => 32.5,
                'physical_rating'    => 4,
                'bone_mass'          => 3.1,
                'kcal'               => 2210,
                'body_age'           => 41,
                'visceral_fat'       => 13,
            ],

            // Phase 3 — Steady Improvement (Mar – Apr 2026) ───────────────
            // Body fat crosses from Obese (≥25 %) into Overweight (19–24.9 %).
            // Visceral fat drops from High (≥15) to Elevated (13–14).
            // Body water rises above the male minimum of 50 %.
            // Physical rating reaches "Standard" (5).

            [
                'measurement_date'   => '2026-03-30',
                'measurement_time'   => '07:30',
                'weight_kg'          => 88.0,
                'body_fat_percent'   => 27.0,
                'body_fat_kg'        => 23.8,
                'body_water_percent' => 48.0,
                'muscle_mass'        => 33.0,
                'physical_rating'    => 4,
                'bone_mass'          => 3.1,
                'kcal'               => 2180,
                'body_age'           => 40,
                'visceral_fat'       => 13,
            ],
            [
                'measurement_date'   => '2026-04-13',
                'measurement_time'   => '07:30',
                'weight_kg'          => 86.5,
                'body_fat_percent'   => 26.0,
                'body_fat_kg'        => 22.5,
                'body_water_percent' => 48.5,
                'muscle_mass'        => 33.5,
                'physical_rating'    => 5,
                'bone_mass'          => 3.2,
                'kcal'               => 2145,
                'body_age'           => 39,
                'visceral_fat'       => 12,
            ],
            [
                'measurement_date'   => '2026-04-27',
                'measurement_time'   => '07:30',
                'weight_kg'          => 85.0,
                'body_fat_percent'   => 24.8,
                'body_fat_kg'        => 21.1,
                'body_water_percent' => 49.5,
                'muscle_mass'        => 34.2,
                'physical_rating'    => 5,
                'bone_mass'          => 3.2,
                'kcal'               => 2110,
                'body_age'           => 38,
                'visceral_fat'       => 11,
            ],

            // Phase 4 — Achieving Healthy Range (May – Jun 2026) ──────────
            // Body fat crosses into Healthy (<19 %).
            // Visceral fat enters healthy zone (<13).
            // Body water stabilises above 50 %.
            // Physical rating reaches "Muscular" (7).

            [
                'measurement_date'   => '2026-05-11',
                'measurement_time'   => '07:30',
                'weight_kg'          => 83.5,
                'body_fat_percent'   => 23.5,
                'body_fat_kg'        => 19.6,
                'body_water_percent' => 50.5,
                'muscle_mass'        => 35.0,
                'physical_rating'    => 6,
                'bone_mass'          => 3.3,
                'kcal'               => 2080,
                'body_age'           => 36,
                'visceral_fat'       => 10,
            ],
            [
                'measurement_date'   => '2026-05-25',
                'measurement_time'   => '07:30',
                'weight_kg'          => 82.0,
                'body_fat_percent'   => 22.0,
                'body_fat_kg'        => 18.0,
                'body_water_percent' => 51.5,
                'muscle_mass'        => 35.5,
                'physical_rating'    => 6,
                'bone_mass'          => 3.3,
                'kcal'               => 2050,
                'body_age'           => 34,
                'visceral_fat'       => 9,
            ],
            [
                'measurement_date'   => '2026-06-07',
                'measurement_time'   => '07:30',
                'weight_kg'          => 80.5,
                'body_fat_percent'   => 20.5,
                'body_fat_kg'        => 16.5,
                'body_water_percent' => 52.5,
                'muscle_mass'        => 36.0,
                'physical_rating'    => 7,
                'bone_mass'          => 3.4,
                'kcal'               => 2020,
                'body_age'           => 32,
                'visceral_fat'       => 8,
            ],
        ];

        foreach ($measurements as $data) {
            BodyComposition::create(array_merge($data, ['user_id' => $user->id]));
        }

        // Goals — positioned to show real progress toward targets
        $goals = [
            [
                'metric'       => 'weight_kg',
                'target_value' => 78.0,
                'start_value'  => 94.0,
                'deadline'     => '2026-08-31',
                'notes'        => 'Target weight < 80 kg to reach healthy BMI range. Steady deficit of ~500 kcal/day.',
                'status'       => 'active',
            ],
            [
                'metric'       => 'body_fat_percent',
                'target_value' => 18.0,
                'start_value'  => 31.0,
                'deadline'     => '2026-09-30',
                'notes'        => 'Goal is to drop from Obese to Healthy body fat %. Currently losing ~0.8 % per fortnight.',
                'status'       => 'active',
            ],
            [
                'metric'       => 'visceral_fat',
                'target_value' => 6.0,
                'start_value'  => 16.0,
                'deadline'     => '2026-10-31',
                'notes'        => 'Reduce visceral fat to healthy level (< 13). Avoiding processed food and refined carbs.',
                'status'       => 'active',
            ],
            [
                'metric'       => 'muscle_mass',
                'target_value' => 40.0,
                'start_value'  => 31.0,
                'deadline'     => '2026-12-31',
                'notes'        => 'Gain lean muscle while cutting body fat. Resistance training 3×/week.',
                'status'       => 'active',
            ],
        ];

        foreach ($goals as $goalData) {
            Goal::create(array_merge($goalData, ['user_id' => $user->id]));
        }

        $engine->syncForUser($user);
    }
}
