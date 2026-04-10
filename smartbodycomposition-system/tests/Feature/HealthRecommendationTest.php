<?php

namespace Tests\Feature;

use App\Models\BodyComposition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class HealthRecommendationTest extends TestCase
{
    use RefreshDatabase;

    public function test_body_fat_thresholds_are_personalized_by_gender_and_age(): void
    {
        $maleUser = User::factory()->create([
            'gender' => 'Male',
            'age' => 30,
            'height_cm' => 175,
        ]);

        $femaleUser = User::factory()->create([
            'gender' => 'Female',
            'age' => 30,
            'height_cm' => 165,
        ]);

        BodyComposition::factory()->forUser($maleUser)->create([
            'measurement_date' => '2026-04-10',
            'weight_kg' => 78.0,
            'body_fat_percent' => 30.0,
            'body_water_percent' => 51.0,
            'muscle_mass' => 31.5,
            'physical_rating' => 4,
            'visceral_fat' => 9.0,
        ]);

        BodyComposition::factory()->forUser($femaleUser)->create([
            'measurement_date' => '2026-04-10',
            'weight_kg' => 62.0,
            'body_fat_percent' => 30.0,
            'body_water_percent' => 48.0,
            'muscle_mass' => 21.0,
            'physical_rating' => 4,
            'visceral_fat' => 8.0,
        ]);

        Sanctum::actingAs($maleUser);
        $maleRecommendations = collect($this->getJson('/api/recommendations')->json('data'))->pluck('template_code');

        Sanctum::actingAs($femaleUser);
        $femaleRecommendations = collect($this->getJson('/api/recommendations')->json('data'))->pluck('template_code');

        $this->assertTrue($maleRecommendations->contains('TMPL-NUT-001'));
        $this->assertFalse($femaleRecommendations->contains('TMPL-NUT-001'));
    }

    public function test_recommendations_are_generated_from_latest_metrics_without_schema_changes(): void
    {
        $user = User::factory()->create([
            'height_cm' => 170,
        ]);

        BodyComposition::factory()->forUser($user)->create([
            'measurement_date' => '2026-04-10',
            'weight_kg' => 88.0,
            'body_fat_percent' => 31.4,
            'body_water_percent' => 46.3,
            'muscle_mass' => 31.0,
            'physical_rating' => 3,
            'visceral_fat' => 14.5,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/recommendations');

        $response
            ->assertOk()
            ->assertJsonPath('meta.has_measurement', true);

        $templateCodes = collect($response->json('data'))->pluck('template_code');

        $this->assertGreaterThanOrEqual(3, $templateCodes->count());

        $this->assertTrue($templateCodes->contains('TMPL-HYD-001'));
        $this->assertTrue($templateCodes->contains('TMPL-NUT-001'));
        $this->assertTrue($templateCodes->contains('TMPL-EXE-001'));
    }

    public function test_recommendation_status_can_be_updated_and_persisted_in_existing_text_column(): void
    {
        $user = User::factory()->create([
            'height_cm' => 168,
        ]);

        BodyComposition::factory()->forUser($user)->create([
            'measurement_date' => '2026-04-10',
            'weight_kg' => 74.0,
            'body_fat_percent' => 24.0,
            'body_water_percent' => 51.2,
            'muscle_mass' => 32.5,
            'physical_rating' => 5,
            'visceral_fat' => 9.5,
        ]);

        Sanctum::actingAs($user);

        $recommendations = $this->getJson('/api/recommendations')->json('data');
        $recommendationId = $recommendations[0]['id'];

        $this->putJson("/api/recommendations/{$recommendationId}/status", [
            'status' => 'completed',
        ])
            ->assertOk()
            ->assertJsonPath('data.status', 'completed');

        $refreshed = $this->getJson('/api/recommendations');

        $this->assertTrue(
            collect($refreshed->json('data'))->contains(fn (array $recommendation) => $recommendation['id'] === $recommendationId && $recommendation['status'] === 'completed')
        );
    }
}
