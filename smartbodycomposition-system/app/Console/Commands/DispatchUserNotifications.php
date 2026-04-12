<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\RecommendationEngine;
use App\Services\UserNotificationService;
use Illuminate\Console\Command;

class DispatchUserNotifications extends Command
{
    protected $signature = 'notifications:dispatch-user {--force-weekly : Dispatch weekly reports even when today is not Monday}';

    protected $description = 'Dispatch recurring weekly report and measurement reminder notifications';

    public function __construct(
        private readonly RecommendationEngine $recommendationEngine,
        private readonly UserNotificationService $notificationService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $now = now();
        $forceWeekly = (bool) $this->option('force-weekly');

        $users = User::query()
            ->where('account_status', 'Active')
            ->get();

        $weeklyReportsSent = 0;
        $measurementRemindersSent = 0;

        foreach ($users as $user) {
            $latestMeasurement = $user->bodyCompositions()
                ->orderByDesc('measurement_date')
                ->orderByDesc('created_at')
                ->first();

            if ($forceWeekly || $now->isMonday()) {
                $analysis = $this->recommendationEngine->buildTrendAnalysis($user, 7);

                if (($analysis['meta']['has_data'] ?? false) && !empty($analysis['meta']['summary'])) {
                    $summary = trim(($analysis['meta']['summary'] ?? '') . ' ' . ($analysis['meta']['ai_insight'] ?? ''));
                    $this->notificationService->notifyWeeklyReport($user, $summary, $now);
                    $weeklyReportsSent++;
                }
            }

            if (!$latestMeasurement) {
                $this->notificationService->notifyMeasurementReminder(
                    $user,
                    'Add your first entry to start seeing trends and recommendations.',
                    $now
                );
                $measurementRemindersSent++;
                continue;
            }

            $lastMeasurementAt = optional($latestMeasurement->measurement_date)->copy()?->startOfDay()
                ?? $latestMeasurement->created_at->copy()->startOfDay();

            if ($lastMeasurementAt->diffInDays($now->copy()->startOfDay()) >= 7) {
                $this->notificationService->notifyMeasurementReminder(
                    $user,
                    'It has been at least a week since your last measurement. Log a new one to keep your trends and recommendations up to date.',
                    $now
                );
                $measurementRemindersSent++;
            }
        }

        $this->info(sprintf(
            'User notifications dispatched successfully. Weekly reports attempted: %d. Measurement reminders attempted: %d.',
            $weeklyReportsSent,
            $measurementRemindersSent,
        ));

        return self::SUCCESS;
    }
}
