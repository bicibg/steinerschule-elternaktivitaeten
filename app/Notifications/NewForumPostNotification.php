<?php

namespace App\Notifications;

use App\Models\BulletinPost;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewForumPostNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Post $post,
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
        $authorName = $this->post->user?->name ?? 'Jemand';

        return (new MailMessage)
            ->subject("Neuer Forenbeitrag: {$this->bulletinPost->title}")
            ->greeting('Hallo!')
            ->line("{$authorName} hat einen neuen Beitrag im Forum geschrieben:")
            ->line("**{$this->bulletinPost->title}**")
            ->line('> '.str($this->post->body)->limit(200))
            ->action('Forum ansehen', $url)
            ->salutation('Freundliche Grüsse, Steinerschule Elternaktivitäten');
    }
}
