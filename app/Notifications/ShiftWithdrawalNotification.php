<?php

namespace App\Notifications;

use App\Models\Shift;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShiftWithdrawalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Shift $shift,
        public User $volunteer,
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
        $bulletinPost = $this->shift->bulletinPost;
        $url = url("/pinnwand/{$bulletinPost->slug}");

        return (new MailMessage)
            ->subject("Abmeldung: {$this->shift->role}")
            ->greeting('Hallo!')
            ->line("{$this->volunteer->name} hat sich von einer Schicht abgemeldet:")
            ->line("**{$bulletinPost->title}** — {$this->shift->role} ({$this->shift->time})")
            ->line("Besetzt: {$this->shift->filled}/{$this->shift->needed}")
            ->action('Beitrag ansehen', $url)
            ->salutation('Freundliche Grüsse, Steinerschule Elternaktivitäten');
    }
}
