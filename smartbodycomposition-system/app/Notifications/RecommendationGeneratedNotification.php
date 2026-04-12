<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RecommendationGeneratedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly int $count,
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
            'kind' => 'recommendation_generated',
            'title' => 'New recommendations are ready',
            'message' => $this->count === 1
                ? 'A new recommendation was generated from your latest measurement.'
                : "{$this->count} new recommendations were generated from your latest measurement.",
            'action_url' => '/ai-tips',
            'dedupe_key' => $this->dedupeKey,
            'priority' => 'medium',
        ];
    }
}
