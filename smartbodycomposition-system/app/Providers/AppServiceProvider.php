<?php

namespace App\Providers;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->applyDatabaseMailSettings();
    }

    private function applyDatabaseMailSettings(): void
    {
        try {
            if (!Schema::hasTable('system_settings')) {
                return;
            }

            $smtp = SystemSetting::getDecoded('smtp_settings', []);

            if (!is_array($smtp) || empty($smtp['host'])) {
                return;
            }

            $username = $smtp['username'] ?? null;
            $password = $smtp['password'] ?? null;

            if (!empty($password)) {
                try {
                    $password = Crypt::decryptString($password);
                } catch (\Throwable) {
                    $password = null;
                }
            }

            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.host' => $smtp['host'],
                'mail.mailers.smtp.port' => (int) ($smtp['port'] ?? 587),
                'mail.mailers.smtp.username' => $username,
                'mail.mailers.smtp.password' => $password,
                'mail.mailers.smtp.scheme' => ($smtp['encryption'] ?? 'tls') === 'none' ? null : ($smtp['encryption'] ?? 'tls'),
                'mail.from.address' => $smtp['from'] ?? config('mail.from.address'),
                'mail.from.name' => $smtp['from_name'] ?? config('mail.from.name'),
            ]);
        } catch (\Throwable) {
            // Ignore DB-based mail config errors and fall back to environment config.
        }
    }
}
