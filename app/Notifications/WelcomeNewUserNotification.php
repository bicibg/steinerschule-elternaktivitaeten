<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Password;

class WelcomeNewUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $token = Password::broker()->createToken($notifiable);

        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Willkommen bei Steinerschule Elternaktivitäten')
            ->greeting('Hallo '.$notifiable->name.'!')
            ->line('Es wurde ein Konto für Sie auf der Plattform Steinerschule Elternaktivitäten erstellt.')
            ->line('Über diese Plattform können Sie sich für Helfereinsätze anmelden, Elternaktivitäten einsehen und den Schulkalender nutzen.')
            ->line('Bitte klicken Sie auf den folgenden Link, um Ihr Passwort festzulegen und Ihr Konto zu aktivieren:')
            ->action('Passwort festlegen', $url)
            ->line('Dieser Link ist 60 Minuten gültig. Falls er abgelaufen ist, können Sie auf der Anmeldeseite ein neues Passwort anfordern.')
            ->line('Falls Sie diese E-Mail unerwartet erhalten haben, können Sie sie ignorieren.')
            ->salutation('Freundliche Grüsse, Steinerschule Elternaktivitäten');
    }
}
