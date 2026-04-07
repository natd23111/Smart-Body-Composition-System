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
        'bmr',
        'visceral_fat'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
