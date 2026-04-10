<?php

return [
    'version' => '1.1.0',
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
            ['max_age' => 39, 'healthy_max' => 20, 'elevated_min' => 21, 'high_min' => 26],
            ['max_age' => 59, 'healthy_max' => 22, 'elevated_min' => 23, 'high_min' => 28],
            ['max_age' => 200, 'healthy_max' => 25, 'elevated_min' => 26, 'high_min' => 30],
        ],
        'female' => [
            ['max_age' => 39, 'healthy_max' => 32, 'elevated_min' => 33, 'high_min' => 39],
            ['max_age' => 59, 'healthy_max' => 33, 'elevated_min' => 34, 'high_min' => 40],
            ['max_age' => 200, 'healthy_max' => 35, 'elevated_min' => 36, 'high_min' => 42],
        ],
        'default' => [
            ['max_age' => 39, 'healthy_max' => 28, 'elevated_min' => 29, 'high_min' => 34],
            ['max_age' => 59, 'healthy_max' => 30, 'elevated_min' => 31, 'high_min' => 36],
            ['max_age' => 200, 'healthy_max' => 32, 'elevated_min' => 33, 'high_min' => 38],
        ],
    ],
    'body_water_percent' => [
        'male' => ['minimum' => 50.0],
        'female' => ['minimum' => 45.0],
        'default' => ['minimum' => 47.0],
        'activity_adjustment' => [
            'low' => 0.0,
            'moderate' => 0.5,
            'high' => 1.0,
        ],
    ],
    'muscle_ratio' => [
        'male' => ['minimum' => 38.0],
        'female' => ['minimum' => 30.0],
        'default' => ['minimum' => 34.0],
        'activity_adjustment' => [
            'low' => -1.0,
            'moderate' => 0.0,
            'high' => 2.0,
        ],
        'age_adjustment' => [
            ['min_age' => 50, 'delta' => -1.0],
            ['min_age' => 65, 'delta' => -2.0],
        ],
    ],
    'visceral_fat' => [
        'optimal_max' => 9.0,
        'elevated_min' => 10.0,
        'high_min' => 15.0,
    ],
    'bmi' => [
        'secondary_context_min' => 27.0,
    ],
];
