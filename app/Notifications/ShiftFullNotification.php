<?php

namespace App\Notifications;

use App\Models\Shift;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShiftFullNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Shift $shift,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function shouldSend(object $notifiable, string $channel): bool
    {
        if ($notifiable instanceof \App\Models\User && ! $notifiable->email_notifications) {
            return false;
        }

        return true;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $bulletinPost = $this->shift->bulletinPost;
        $url = url("/pinnwand/{$bulletinPost->slug}");

        return (new MailMessage)
            ->subject("Schicht voll besetzt: {$this->shift->role}")
            ->greeting('Gute Nachricht!')
            ->line('Eine Schicht ist jetzt voll besetzt:')
            ->line("**{$bulletinPost->title}** — {$this->shift->role} ({$this->shift->time})")
            ->line("Alle {$this->shift->needed} Plätze sind vergeben.")
            ->action('Beitrag ansehen', $url)
            ->salutation('Freundliche Grüsse, Steinerschule Elternaktivitäten');
    }
}
