<?php

return [
    'version' => '2.0.0',
    'guidance_scope' => 'general-wellness',
    'disclaimer' => 'These recommendations support general wellness only and are not medical advice.',
    'history' => [
        'activity_lookback_records' => 5,
    ],
    'activity_levels' => [
        'default' => 'moderate',
        'physical_rating_breakpoints' => [
            'low_max' => 3,
            'moderate_max' => 5,
        ],
    ],
    'body_fat_percent' => [

        'male' => [
            [
                'max_age' => 39,
                'underfat_max' => 8,
                'healthy_min' => 8,
                'healthy_max' => 19,
                'overweight_min' => 19,
                'overweight_max' => 25,
                'obese_min' => 25,
            ],
            [
                'max_age' => 59,
                'underfat_max' => 11,
                'healthy_min' => 11,
                'healthy_max' => 21,
                'overweight_min' => 21,
                'overweight_max' => 28,
                'obese_min' => 28,
            ],
            [
                'max_age' => 79,
                'underfat_max' => 13,
                'healthy_min' => 13,
                'healthy_max' => 25,
                'overweight_min' => 25,
                'overweight_max' => 30,
                'obese_min' => 30,
            ],
        ],

        'female' => [
            [
                'max_age' => 39,
                'underfat_max' => 21,
                'healthy_min' => 21,
                'healthy_max' => 33,
                'overweight_min' => 33,
                'overweight_max' => 39,
                'obese_min' => 39,
            ],
            [
                'max_age' => 59,
                'underfat_max' => 23,
                'healthy_min' => 23,
                'healthy_max' => 34,
                'overweight_min' => 34,
                'overweight_max' => 40,
                'obese_min' => 40,
            ],
            [
                'max_age' => 79,
                'underfat_max' => 24,
                'healthy_min' => 24,
                'healthy_max' => 36,
                'overweight_min' => 36,
                'overweight_max' => 42,
                'obese_min' => 42,
            ],
        ],
    ],
    'body_water_percent' => [

        'male' => [
            'standard' => [
                'minimum' => 50,
                'healthy_max' => 65,
            ],
            'athletic' => [
                'minimum' => 55,
                'healthy_max' => 70,
            ],
        ],

        'female' => [
            'standard' => [
                'minimum' => 45,
                'healthy_max' => 60,
            ],
            'athletic' => [
                'minimum' => 50,
                'healthy_max' => 65,
            ],
        ],
    ],
    'muscle_ratio' => [

        'male' => [
            [
                'max_age' => 39,
                'very_low_max' => 72,
                'low_min' => 72,
                'low_max' => 76,
                'good_min' => 76,
                'good_max' => 88,
                'increased_min' => 88,
            ],

            [
                'max_age' => 59,
                'very_low_max' => 68,
                'low_min' => 68,
                'low_max' => 74,
                'good_min' => 74,
                'good_max' => 85,
                'increased_min' => 85,
            ],

            [
                'max_age' => 79,
                'very_low_max' => 66,
                'low_min' => 66,
                'low_max' => 71,
                'good_min' => 71,
                'good_max' => 83,
                'increased_min' => 83,
            ],
        ],

        'female' => [
            [
                'max_age' => 39,
                'very_low_max' => 56,
                'low_min' => 56,
                'low_max' => 61,
                'good_min' => 63,
                'good_max' => 75,
                'increased_min' => 75,
            ],

            [
                'max_age' => 59,
                'very_low_max' => 55,
                'low_min' => 56,
                'low_max' => 62,
                'good_min' => 62,
                'good_max' => 73,
                'increased_min' => 73,
            ],

            [
                'max_age' => 79,
                'very_low_max' => 54,
                'low_min' => 54,
                'low_max' => 60,
                'good_min' => 60,
                'good_max' => 72,
                'increased_min' => 72,
            ],
        ],
    ],
    'visceral_fat' => [
        'healthy_max' => 12,
        'excess_min' => 13,
    ],
    'bmi' => [
        'secondary_context_min' => 27.0,
        'underweight_max' => 18.5,
    ],
    'weight_trend' => [
        'gain_threshold_kg' => 2.0,
        'loss_threshold_kg' => 2.0,
        'muscle_gain_min_kg_for_healthy_bulk' => 0.4,
        'max_body_fat_gain_percent_for_healthy_bulk' => 1.0,
        'max_muscle_loss_kg_for_healthy_cut' => 0.3,
        'min_body_fat_loss_percent_for_healthy_cut' => 0.5,
    ],
    'physical_rating' => [
        1 => [
            'label' => 'Hidden Excess Fat',
            'description' => 'High Body Fat % with Low Muscle Mass',
        ],
        2 => [
            'label' => 'Medium Frame & Excess Fat',
            'description' => 'High Body Fat %, Moderate Muscle Mass',
        ],
        3 => [
            'label' => 'Solidly Built',
            'description' => 'Large Frame, High Body Fat % & Muscle Mass',
        ],
        4 => [
            'label' => 'Low Muscle',
            'description' => 'Average Body Fat % & Low Muscle Mass',
        ],
        5 => [
            'label' => 'Standard',
            'description' => 'Average Body Fat % & Muscle Mass',
        ],
        6 => [
            'label' => 'Muscular',
            'description' => 'Average Body Fat % & High Muscle Mass',
        ],
        7 => [
            'label' => 'Low Muscle & Low Fat',
            'description' => 'Low Body Fat % & Low Muscle Mass',
        ],
        8 => [
            'label' => 'Thin & Muscular (Athlete)',
            'description' => 'Low Body Fat % & Adequate Muscle Mass',
        ],
        9 => [
            'label' => 'Very Muscular (Athlete)',
            'description' => 'Low Body Fat % & High Muscle Mass',
        ],

    ],
    'bone_mass' => [

        'male' => [
            [
                'max_weight' => 64,
                'healthy_min' => 2.65,
            ],
            [
                'max_weight' => 95,
                'healthy_min' => 3.29,
            ],
            [
                'max_weight' => 200,
                'healthy_min' => 3.69,
            ],
        ],

        'female' => [
            [
                'max_weight' => 49,
                'healthy_min' => 1.95,
            ],
            [
                'max_weight' => 75,
                'healthy_min' => 2.40,
            ],
            [
                'max_weight' => 200,
                'healthy_min' => 2.95,
            ],
        ],
    ],
];
