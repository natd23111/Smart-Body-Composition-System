<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\Role;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'account_status',
        'notifications_enabled',
        'email_alerts_enabled',
        'weekly_reports_enabled',
        'measurement_reminders_enabled',
        'age',
        'gender',
        'height_cm',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
            'notifications_enabled' => 'boolean',
            'email_alerts_enabled' => 'boolean',
            'weekly_reports_enabled' => 'boolean',
            'measurement_reminders_enabled' => 'boolean',
        ];
    }

    public function bodyCompositions()
    {
        return $this->hasMany(BodyComposition::class);
    }

    public function recommendations()
    {
        return $this->hasMany(HealthRecommendation::class);
    }

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }
}
