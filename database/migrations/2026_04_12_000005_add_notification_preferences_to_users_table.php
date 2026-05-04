<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notifications_enabled')->default(true)->after('account_status');
            $table->boolean('email_alerts_enabled')->default(true)->after('notifications_enabled');
            $table->boolean('weekly_reports_enabled')->default(true)->after('email_alerts_enabled');
            $table->boolean('measurement_reminders_enabled')->default(true)->after('weekly_reports_enabled');
        });

        DB::table('users')->update([
            'notifications_enabled' => true,
            'email_alerts_enabled' => true,
            'weekly_reports_enabled' => true,
            'measurement_reminders_enabled' => true,
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'notifications_enabled',
                'email_alerts_enabled',
                'weekly_reports_enabled',
                'measurement_reminders_enabled',
            ]);
        });
    }
};
