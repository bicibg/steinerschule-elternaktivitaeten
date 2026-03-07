<?php

namespace App\Notifications;

use App\Models\BulletinPost;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewForumCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Comment $comment,
        public BulletinPost $bulletinPost,
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
        $url = url("/pinnwand/{$this->bulletinPost->slug}");
        $authorName = $this->comment->user?->name ?? 'Jemand';

        return (new MailMessage)
            ->subject("Neuer Kommentar: {$this->bulletinPost->title}")
            ->greeting('Hallo!')
            ->line("{$authorName} hat einen Kommentar geschrieben:")
            ->line("**{$this->bulletinPost->title}**")
            ->line('> '.str($this->comment->body)->limit(200))
            ->action('Kommentar ansehen', $url)
            ->salutation('Freundliche Grüsse, Steinerschule Elternaktivitäten');
    }
}
