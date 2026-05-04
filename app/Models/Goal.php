<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'user_id',
        'metric',
        'target_value',
        'start_value',
        'deadline',
        'notes',
        'status',
    ];

    protected $casts = [
        'deadline' => 'date',
        'target_value' => 'float',
        'start_value' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
