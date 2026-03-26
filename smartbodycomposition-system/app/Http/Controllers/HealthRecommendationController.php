<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthRecommendation;
use App\Models\BodyComposition;

class HealthRecommendationController extends Controller
{
    // Get recommendations for user
    public function index()
    {
        $data = HealthRecommendation::where('user_id', auth()->id())->latest()->get();
        return response()->json($data);
    }

    // Generate recommendation (your AI logic)
    public function generate()
    {
        $latest = BodyComposition::where('user_id', auth()->id())->latest()->first();

        if (!$latest) {
            return response()->json(['message' => 'No data available']);
        }

        $recommendation = $this->generateRecommendation($latest);

        $saved = HealthRecommendation::create([
            'user_id' => auth()->id(),
            'recommendation_text' => $recommendation
        ]);

        return response()->json($saved);
    }

    // RULE-BASED AI ENGINE
    private function generateRecommendation($data)
    {
        if ($data->body_fat_percent > 25) {
            return "Reduce calorie intake and increase cardio exercise.";
        }

        if ($data->muscle_mass < 40) {
            return "Increase protein intake and strength training.";
        }

        if ($data->body_water_percent < 50) {
            return "Increase water intake.";
        }

        return "Your body composition is within a healthy range.";
    }
}
