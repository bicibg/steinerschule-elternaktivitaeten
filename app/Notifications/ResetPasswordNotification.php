<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $token,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Passwort zurücksetzen')
            ->greeting('Hallo!')
            ->line('Sie erhalten diese E-Mail, weil eine Passwortzurücksetzung für Ihr Konto angefordert wurde.')
            ->action('Passwort zurücksetzen', $url)
            ->line('Dieser Link ist 60 Minuten gültig.')
            ->line('Falls Sie keine Passwortzurücksetzung angefordert haben, können Sie diese E-Mail ignorieren.')
            ->salutation('Freundliche Grüsse, Steinerschule Elternaktivitäten');
    }
}
