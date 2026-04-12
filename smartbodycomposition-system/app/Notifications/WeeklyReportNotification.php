<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
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
        $channels = ['database'];

        if ($notifiable->email_alerts_enabled) {
            $channels[] = 'mail';
        }

        return $channels;
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

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Your weekly health report is ready')
            ->greeting('Hello ' . ($notifiable->name ?? 'there') . ',')
            ->line($this->summary)
            ->action('Open Trends', url('/trends'))
            ->line('Review your 7-day trends to see what changed this week.');
    }
}
