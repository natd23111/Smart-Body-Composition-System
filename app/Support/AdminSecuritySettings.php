<?php

namespace App\Support;

use App\Models\SystemSetting;

class AdminSecuritySettings
{
    public static function defaults(): array
    {
        return [
            'sessionTimeout' => 120,
            'maxLoginAttempts' => 5,
            'maintenanceMode' => false,
        ];
    }

    public static function all(): array
    {
        $defaults = static::defaults();
        $settings = SystemSetting::getDecoded('admin_security_settings', $defaults);

        if (!is_array($settings)) {
            return $defaults;
        }

        return [
            'sessionTimeout' => max(5, min(1440, (int) ($settings['sessionTimeout'] ?? $defaults['sessionTimeout']))),
            'maxLoginAttempts' => max(3, min(20, (int) ($settings['maxLoginAttempts'] ?? $defaults['maxLoginAttempts']))),
            'maintenanceMode' => (bool) ($settings['maintenanceMode'] ?? $defaults['maintenanceMode']),
        ];
    }

    public static function sessionTimeout(): int
    {
        return static::all()['sessionTimeout'];
    }

    public static function maxLoginAttempts(): int
    {
        return static::all()['maxLoginAttempts'];
    }
}
