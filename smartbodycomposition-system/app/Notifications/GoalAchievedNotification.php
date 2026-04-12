<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
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
        $channels = ['database'];

        if ($notifiable->email_alerts_enabled) {
            $channels[] = 'mail';
        }

        return $channels;
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

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('You achieved a health goal')
            ->greeting('Hello ' . ($notifiable->name ?? 'there') . ',')
            ->line("You reached your {$this->metricLabel} goal.")
            ->line('Review your progress and set the next target when you are ready.')
            ->action('View Goals', url('/goals'));
    }
}
