<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\WelcomeNewUserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class WelcomeEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_email_sent_when_user_created(): void
    {
        Notification::fake();

        $user = new User([
            'name' => 'Test Benutzer',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);
        $user->save();

        Notification::assertSentTo($user, WelcomeNewUserNotification::class);
    }

    public function test_welcome_email_not_sent_on_self_registration(): void
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'Selbst Registriert',
            'email' => 'self@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'honeypot_field' => '',
            'honeypot_time' => now()->subSeconds(5)->timestamp,
        ]);

        Notification::assertNotSentTo(
            User::where('email', 'self@example.com')->first(),
            WelcomeNewUserNotification::class,
        );
    }

    public function test_welcome_email_not_sent_for_factory_users(): void
    {
        Notification::fake();

        User::factory()->create();

        Notification::assertNothingSent();
    }

    public function test_welcome_email_contains_password_reset_link(): void
    {
        Notification::fake();

        $user = new User([
            'name' => 'Link Test',
            'email' => 'link@example.com',
            'password' => 'password123',
        ]);
        $user->save();

        Notification::assertSentTo($user, WelcomeNewUserNotification::class, function ($notification) use ($user) {
            $mail = $notification->toMail($user);

            return str_contains($mail->actionUrl, 'reset-password')
                && str_contains($mail->subject, 'Willkommen');
        });
    }
}
