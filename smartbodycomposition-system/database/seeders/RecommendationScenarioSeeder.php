<?php

namespace Database\Seeders;

use App\Models\BodyComposition;
use App\Models\User;
use App\Services\RecommendationEngine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RecommendationScenarioSeeder extends Seeder
{
    /**
     * Seed realistic users for testing the recommendations page.
     */
    public function run(): void
    {
        $engine = app(RecommendationEngine::class);

        $admin = User::updateOrCreate(
            ['email' => 'admin.demo@example.com'],
            [
                'name' => 'Demo Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'age' => 34,
                'gender' => 'Other',
                'height_cm' => 172,
                'email_verified_at' => now(),
            ]
        );

        $this->seedScenarioUser(
            $engine,
            [
                'name' => 'Hydration Test User',
                'email' => 'hydration.demo@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'age' => 26,
                'gender' => 'Female',
                'height_cm' => 164,
                'email_verified_at' => now(),
            ],
            [
                [
                    'measurement_date' => '2026-04-03',
                    'measurement_time' => '08:00',
                    'weight_kg' => 67.4,
                    'body_fat_percent' => 27.8,
                    'body_fat_kg' => 18.7,
                    'body_water_percent' => 46.5,
                    'muscle_mass' => 24.9,
                    'physical_rating' => 4,
                    'bone_mass' => 2.7,
                    'kcal' => 1875,
                    'body_age' => 32,
                    'visceral_fat' => 9.8,
                ],
                [
                    'measurement_date' => '2026-04-10',
                    'measurement_time' => '08:00',
                    'weight_kg' => 67.0,
                    'body_fat_percent' => 27.2,
                    'body_fat_kg' => 18.2,
                    'body_water_percent' => 45.9,
                    'muscle_mass' => 24.7,
                    'physical_rating' => 4,
                    'bone_mass' => 2.7,
                    'kcal' => 1860,
                    'body_age' => 32,
                    'visceral_fat' => 9.4,
                ],
            ],
            [
                'hydration-foundation' => 'in-progress',
            ]
        );

        $this->seedScenarioUser(
            $engine,
            [
                'name' => 'Full Template Test User',
                'email' => 'alltemplates.demo@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'age' => 31,
                'gender' => 'Male',
                'height_cm' => 175,
                'email_verified_at' => now(),
            ],
            [
                [
                    'measurement_date' => '2026-03-20',
                    'measurement_time' => '07:45',
                    'weight_kg' => 94.5,
                    'body_fat_percent' => 36.8,
                    'body_fat_kg' => 34.8,
                    'body_water_percent' => 43.8,
                    'muscle_mass' => 31.4,
                    'physical_rating' => 2,
                    'bone_mass' => 3.0,
                    'kcal' => 2315,
                    'body_age' => 32,
                    'visceral_fat' => 16.0,
                ],
                [
                    'measurement_date' => '2026-04-10',
                    'measurement_time' => '07:45',
                    'weight_kg' => 93.2,
                    'body_fat_percent' => 35.7,
                    'body_fat_kg' => 33.3,
                    'body_water_percent' => 44.1,
                    'muscle_mass' => 31.1,
                    'physical_rating' => 2,
                    'bone_mass' => 3.1,
                    'kcal' => 2290,
                    'body_age' => 32,
                    'visceral_fat' => 15.4,
                ],
            ],
            [
                'hydration-foundation' => 'in-progress',
                'body-fat-reduction' => 'in-progress',
                'cardio-conditioning' => 'pending',
                'muscle-preservation' => 'completed',
                'sleep-recovery' => 'pending',
            ]
        );

        $this->seedScenarioUser(
            $engine,
            [
                'name' => 'Balanced Test User',
                'email' => 'balanced.demo@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'age' => 29,
                'gender' => 'Female',
                'height_cm' => 168,
                'email_verified_at' => now(),
            ],
            [
                [
                    'measurement_date' => '2026-03-15',
                    'measurement_time' => '09:10',
                    'weight_kg' => 61.8,
                    'body_fat_percent' => 24.6,
                    'body_fat_kg' => 15.2,
                    'body_water_percent' => 50.7,
                    'muscle_mass' => 25.8,
                    'physical_rating' => 6,
                    'bone_mass' => 2.6,
                    'kcal' => 1760,
                    'body_age' => 32,
                    'visceral_fat' => 7.8,
                ],
                [
                    'measurement_date' => '2026-04-10',
                    'measurement_time' => '09:10',
                    'weight_kg' => 61.2,
                    'body_fat_percent' => 23.8,
                    'body_fat_kg' => 14.6,
                    'body_water_percent' => 51.4,
                    'muscle_mass' => 26.1,
                    'physical_rating' => 7,
                    'bone_mass' => 2.7,
                    'kcal' => 1748,
                    'body_age' => 32,
                    'visceral_fat' => 7.2,
                ],
            ],
            [
                'steady-progress' => 'completed',
                'weekly-check-in' => 'in-progress',
            ]
        );

        $admin->forceFill(['updated_at' => now()])->save();
    }

    private function seedScenarioUser(RecommendationEngine $engine, array $userAttributes, array $measurements, array $templateStatuses = []): void
    {
        $user = User::updateOrCreate(
            ['email' => $userAttributes['email']],
            $userAttributes
        );

        $user->bodyCompositions()->delete();
        $user->recommendations()->delete();

        foreach ($measurements as $measurement) {
            BodyComposition::create(array_merge($measurement, [
                'user_id' => $user->id,
            ]));
        }

        $result = $engine->syncForUser($user);

        if ($templateStatuses === []) {
            return;
        }

        $latestRecommendations = $user->recommendations()->latest()->get()->keyBy(function ($recommendation) {
            $payload = json_decode($recommendation->recommendation_text, true);

            return $payload['template_id'] ?? $recommendation->id;
        });

        foreach ($templateStatuses as $templateId => $status) {
            $recommendation = $latestRecommendations->get($templateId);

            if ($recommendation) {
                $engine->updateStatus($user, $recommendation, $status);
            }
        }
    }
}
