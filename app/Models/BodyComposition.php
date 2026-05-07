<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodyComposition extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'measurement_date',
        'measurement_time',
        'weight_kg',
        'body_fat_percent',
        'body_fat_kg',
        'body_water_percent',
        'muscle_mass',
        'physical_rating',
        'bone_mass',
        'kcal',
        'body_age',
        'visceral_fat'
    ];

    protected $casts = [
        'measurement_date' => 'date:Y-m-d',
        'weight_kg' => 'float',
        'body_fat_percent' => 'float',
        'body_fat_kg' => 'float',
        'body_water_percent' => 'float',
        'muscle_mass' => 'float',
        'bone_mass' => 'float',
        'visceral_fat' => 'float',
        'physical_rating' => 'integer',
        'kcal' => 'integer',
        'body_age' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
