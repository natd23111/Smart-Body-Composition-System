<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
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
        $channels = ['database'];

        if ($notifiable->email_alerts_enabled) {
            $channels[] = 'mail';
        }

        return $channels;
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

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Reminder to log a new measurement')
            ->greeting('Hello ' . ($notifiable->name ?? 'there') . ',')
            ->line($this->message)
            ->action('Log Measurement', url('/body-composition'));
    }
}
