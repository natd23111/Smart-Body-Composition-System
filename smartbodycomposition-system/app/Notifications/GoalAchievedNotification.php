<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GoalAchievedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $metricLabel,
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
            'kind' => 'goal_achieved',
            'title' => 'Goal achieved',
            'message' => "You reached your {$this->metricLabel} goal. Review your progress and set the next target when you're ready.",
            'action_url' => '/goals',
            'dedupe_key' => $this->dedupeKey,
            'priority' => 'high',
        ];
    }
}
