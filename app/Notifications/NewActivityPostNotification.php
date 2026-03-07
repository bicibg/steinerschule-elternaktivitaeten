<?php

namespace App\Notifications;

use App\Models\Activity;
use App\Models\ActivityPost;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewActivityPostNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ActivityPost $post,
        public Activity $activity,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function shouldSend(object $notifiable, string $channel): bool
    {
        if ($notifiable instanceof User && ! $notifiable->email_notifications) {
            return false;
        }

        return true;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url("/elternaktivitaeten/{$this->activity->slug}");
        $authorName = $this->post->user?->name ?? 'Jemand';

        return (new MailMessage)
            ->subject("Neuer Beitrag: {$this->activity->title}")
            ->greeting('Hallo!')
            ->line("{$authorName} hat einen neuen Beitrag geschrieben:")
            ->line("**{$this->activity->title}**")
            ->line('> '.str($this->post->body)->limit(200))
            ->action('Beitrag ansehen', $url)
            ->salutation('Freundliche Grüsse, Steinerschule Elternaktivitäten');
    }
}
