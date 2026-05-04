<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthRecommendation extends Model
{
    protected $fillable = [
        'user_id',
        'recommendation_text'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
