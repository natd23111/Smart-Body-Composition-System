<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MeasurementReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $message,
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
            'kind' => 'measurement_reminder',
            'title' => 'Reminder to log a new measurement',
            'message' => $this->message,
            'action_url' => '/body-composition',
            'dedupe_key' => $this->dedupeKey,
            'priority' => 'medium',
        ];
    }
}
