<?php

namespace App\Notifications;

use App\Models\Activity;
use App\Models\ActivityComment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewActivityCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ActivityComment $comment,
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
        $authorName = $this->comment->user?->name ?? 'Jemand';

        return (new MailMessage)
            ->subject("Neuer Kommentar: {$this->activity->title}")
            ->greeting('Hallo!')
            ->line("{$authorName} hat einen Kommentar geschrieben:")
            ->line("**{$this->activity->title}**")
            ->line('> '.str($this->comment->body)->limit(200))
            ->action('Kommentar ansehen', $url)
            ->salutation('Freundliche Grüsse, Steinerschule Elternaktivitäten');
    }
}
