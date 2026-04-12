<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WeeklyReportNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $summary,
        private readonly string $dedupeKey,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'weekly_report',
            'title' => 'Your weekly health report is ready',
            'message' => $this->summary,
            'action_url' => '/trends',
            'dedupe_key' => $this->dedupeKey,
            'priority' => 'low',
        ];
    }
}
