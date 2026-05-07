<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'setting_name',
        'setting_value'
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = static::query()->where('setting_name', $key)->value('setting_value');

        return $value !== null ? $value : $default;
    }

    public static function put(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(
            ['setting_name' => $key],
            ['setting_value' => $value]
        );
    }

    public static function getDecoded(string $key, mixed $default = null): mixed
    {
        $value = static::query()->where('setting_name', $key)->value('setting_value');

        if ($value === null) {
            return $default;
        }

        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }

    public static function putEncoded(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(
            ['setting_name' => $key],
            ['setting_value' => json_encode($value)]
        );
    }
}
