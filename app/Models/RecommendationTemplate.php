<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendationTemplate extends Model
{
    protected $fillable = [
        'template_id',
        'template_code',
        'type',
        'title',
        'summary',
        'details',
        'priority',
        'status',
        'icon',
    ];

    protected $casts = [
        'details' => 'array',
    ];
}
