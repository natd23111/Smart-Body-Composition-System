<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RecommendationTemplate;

class RecommendationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'template_id'   => 'hydration-foundation',
                'template_code' => 'TMPL-HYD-001',
                'type'          => 'Hydration',
                'title'         => 'Improve Daily Hydration Consistency',
                'summary'       => 'Your body water percentage is below the personalised general wellness range for your profile and activity level.',
                'details'       => [
                    'Spread fluid intake more evenly across the day instead of catching up late.',
                    'Increase hydration attention around exercise, hot weather, and long gaps between meals.',
                    'Use water-rich foods and a consistent routine to make hydration easier to maintain.',
                ],
                'priority' => 'high',
                'status'   => 'Active',
                'icon'     => 'droplet',
            ],
            [
                'template_id'   => 'body-fat-reduction',
                'template_code' => 'TMPL-NUT-001',
                'type'          => 'Nutrition',
                'title'         => 'Rebalance Body Fat Through Sustainable Habits',
                'summary'       => 'Body fat percentage is above the personalised wellness range, so nutrition quality and consistency should be prioritised.',
                'details'       => [
                    'Build meals around protein, fibre, and minimally processed foods.',
                    'Use gradual, sustainable calorie control instead of aggressive restriction.',
                    'Track changes across several weeks rather than reacting to a single measurement.',
                ],
                'priority' => 'medium',
                'status'   => 'Active',
                'icon'     => 'apple',
            ],
            [
                'template_id'   => 'cardio-conditioning',
                'template_code' => 'TMPL-EXE-001',
                'type'          => 'Exercise',
                'title'         => 'Improve Cardiovascular Conditioning',
                'summary'       => 'Visceral fat and overall composition suggest adding or strengthening regular cardio work to support general wellness.',
                'details'       => [
                    'Accumulate moderate cardio across the week in a way you can sustain consistently.',
                    'Choose walking, cycling, rowing, or similar lower-barrier activities if adherence is the main issue.',
                    'Keep strength training in place so cardio complements overall body composition goals.',
                ],
                'priority' => 'medium',
                'status'   => 'Active',
                'icon'     => 'activity',
            ],
            [
                'template_id'   => 'muscle-preservation',
                'template_code' => 'TMPL-EXE-002',
                'type'          => 'Exercise',
                'title'         => 'Protect Lean Muscle Mass',
                'summary'       => 'Muscle mass ratio is below the personalised reference point, so resistance training quality and protein consistency should remain central.',
                'details'       => [
                    'Keep resistance training focused on major muscle groups at least a few times per week.',
                    'Progress training gradually instead of changing volume and intensity at the same time.',
                    'Distribute protein intake through the day to better support lean mass retention.',
                ],
                'priority' => 'medium',
                'status'   => 'Active',
                'icon'     => 'trending-up',
            ],
            [
                'template_id'   => 'weight-gain-trend',
                'template_code' => 'TMPL-NUT-002',
                'type'          => 'Nutrition',
                'title'         => 'Review Your Dietary Intake',
                'summary'       => 'Your weight has increased noticeably since your last measurement, which may indicate a caloric surplus worth reviewing.',
                'details'       => [
                    'Review portion sizes and total daily calorie intake relative to your activity level.',
                    'Prioritise whole foods and reduce intake of highly processed, calorie-dense options.',
                    'Focus on consistent eating patterns rather than making dramatic short-term cuts.',
                    'Consider tracking meals for 1–2 weeks to identify key patterns.',
                ],
                'priority' => 'medium',
                'status'   => 'Active',
                'icon'     => 'trending-up',
            ],
            [
                'template_id'   => 'fitness-foundation',
                'template_code' => 'TMPL-FIT-001',
                'type'          => 'Exercise',
                'title'         => 'Build a Structured Fitness Foundation',
                'summary'       => 'Your physical rating indicates a low current fitness level. Starting with consistent, low-intensity exercise is the most effective first step.',
                'details'       => [
                    'Begin with 20–30 minute sessions of low-intensity cardio 3–4 times per week.',
                    'Introduce light resistance exercises targeting major muscle groups twice per week.',
                    'Prioritise consistency over intensity — showing up regularly matters more than training hard early on.',
                    'Progress gradually by adding 5–10% volume or intensity every two weeks.',
                ],
                'priority' => 'high',
                'status'   => 'Active',
                'icon'     => 'activity',
            ],
            [
                'template_id'   => 'bone-health',
                'template_code' => 'TMPL-NUT-003',
                'type'          => 'Nutrition',
                'title'         => 'Support Bone Density Through Diet',
                'summary'       => 'Your bone mass is below the reference level for your body weight. Nutritional support for bone density is beneficial at every age.',
                'details'       => [
                    'Ensure adequate calcium intake from dairy, leafy greens, or fortified foods.',
                    'Maintain sufficient vitamin D levels through sunlight exposure and dietary sources.',
                    'Include weight-bearing exercise such as walking, jogging, or resistance training regularly.',
                    'Limit excessive sodium and caffeine, which can impair calcium absorption over time.',
                ],
                'priority' => 'medium',
                'status'   => 'Active',
                'icon'     => 'apple',
            ],
            [
                'template_id'   => 'underweight-nutrition',
                'template_code' => 'TMPL-NUT-004',
                'type'          => 'Nutrition',
                'title'         => 'Increase Caloric and Nutritional Intake',
                'summary'       => 'Your BMI is below the general wellness threshold, which may indicate insufficient caloric intake or underlying nutritional needs.',
                'details'       => [
                    'Increase total daily calorie intake through nutrient-dense foods rather than processed snacks.',
                    'Prioritise protein, healthy fats, and complex carbohydrates at every meal.',
                    'Eat more frequently — 4–5 smaller meals per day can help increase overall intake.',
                    'Consider consulting a dietitian if healthy weight gain proves difficult over several weeks.',
                ],
                'priority' => 'high',
                'status'   => 'Active',
                'icon'     => 'apple',
            ],
            [
                'template_id'   => 'steady-progress',
                'template_code' => 'TMPL-REC-001',
                'type'          => 'Recovery',
                'title'         => 'Maintain Your Current Wellness Habits',
                'summary'       => 'Your latest body-composition markers are broadly within the personalised wellness ranges used by the system.',
                'details'       => [
                    'Keep routines consistent before making large changes to training or nutrition.',
                    'Continue tracking measurements regularly so future guidance stays relevant.',
                    'Use gradual habit adjustments instead of chasing short-term fluctuations.',
                ],
                'priority' => 'low',
                'status'   => 'Active',
                'icon'     => 'heart',
            ],
        ];

        foreach ($templates as $template) {
            RecommendationTemplate::updateOrCreate(
                ['template_id' => $template['template_id']],
                $template
            );
        }
    }
}
