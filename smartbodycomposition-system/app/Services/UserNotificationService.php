<?php

namespace App\Services;

use App\Models\Goal;
use App\Models\User;
use App\Notifications\GoalAchievedNotification;
use App\Notifications\MeasurementReminderNotification;
use App\Notifications\RecommendationGeneratedNotification;
use App\Notifications\WeeklyReportNotification;
use Carbon\CarbonInterface;

class UserNotificationService
{
    public function notifyRecommendationsGenerated(User $user, int $count, string $sourceKey): void
    {
        if (!$user->notifications_enabled) {
            return;
        }

        if ($count <= 0) {
            return;
        }

        $dedupeKey = "recommendations:{$sourceKey}";

        if ($this->hasNotificationSince($user, RecommendationGeneratedNotification::class, $dedupeKey, now()->subDays(2))) {
            return;
        }

        $user->notify(new RecommendationGeneratedNotification($count, $dedupeKey));
    }

    public function notifyGoalAchieved(User $user, Goal $goal, string $metricLabel): void
    {
        if (!$user->notifications_enabled) {
            return;
        }

        $dedupeKey = "goal-achieved:{$goal->id}";

        if ($this->hasNotificationSince($user, GoalAchievedNotification::class, $dedupeKey, now()->subYears(5))) {
            return;
        }

        $user->notify(new GoalAchievedNotification($metricLabel, $dedupeKey));
    }

    public function notifyWeeklyReport(User $user, string $summary, CarbonInterface $weekAnchor): void
    {
        if (!$user->notifications_enabled || !$user->weekly_reports_enabled) {
            return;
        }

        $dedupeKey = 'weekly-report:' . $weekAnchor->copy()->startOfWeek()->toDateString();

        if ($this->hasNotificationSince($user, WeeklyReportNotification::class, $dedupeKey, $weekAnchor->copy()->startOfWeek())) {
            return;
        }

        $user->notify(new WeeklyReportNotification($summary, $dedupeKey));
    }

    public function notifyMeasurementReminder(User $user, string $message, CarbonInterface $windowStart): void
    {
        if (!$user->notifications_enabled || !$user->measurement_reminders_enabled) {
            return;
        }

        $dedupeKey = 'measurement-reminder:' . $windowStart->copy()->startOfWeek()->toDateString();

        if ($this->hasNotificationSince($user, MeasurementReminderNotification::class, $dedupeKey, $windowStart->copy()->startOfWeek())) {
            return;
        }

        $user->notify(new MeasurementReminderNotification($message, $dedupeKey));
    }

    private function hasNotificationSince(User $user, string $type, string $dedupeKey, CarbonInterface $since): bool
    {
        return $user->notifications()
            ->where('type', $type)
            ->where('created_at', '>=', $since)
            ->get()
            ->contains(fn ($notification) => ($notification->data['dedupe_key'] ?? null) === $dedupeKey);
    }
}
