<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * When true, skip sending the welcome email on creation.
     * Used for self-registration where the user already knows their password.
     */
    public bool $skipWelcomeEmail = false;

    protected static function booted(): void
    {
        static::created(function (User $user) {
            if (! $user->skipWelcomeEmail) {
                $user->notify(new \App\Notifications\WelcomeNewUserNotification);
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'remarks',
        'is_admin',
        'is_super_admin',
        'hide_contact_details',
        'email_notifications',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'deleted_at' => 'datetime',
            'anonymized_at' => 'datetime',
            'deletion_requested_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_super_admin' => 'boolean',
            'hide_contact_details' => 'boolean',
            'email_notifications' => 'boolean',
        ];
    }

    public function maskedName(): string
    {
        $parts = explode(' ', $this->name, 2);

        if (count($parts) === 1) {
            return $parts[0];
        }

        $firstName = $parts[0];
        $lastName = $parts[1];

        return $firstName.' '.mb_substr($lastName, 0, 1).str_repeat('*', max(1, mb_strlen($lastName) - 1));
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Super admins always have access, or regular admins
        return (bool) $this->is_super_admin || (bool) $this->is_admin;
    }

    /**
     * Anonymize the user's personal data (GDPR compliance)
     */
    public function anonymize(?int $adminId = null): void
    {
        static::withTrashed()->where('id', $this->id)->update([
            'name' => 'Anonymer Benutzer '.$this->id,
            'email' => 'deleted-'.$this->id.'@anonymous.local',
            'password' => bcrypt(Str::random(64)),
            'phone' => null,
            'remarks' => null,
            'remember_token' => null,
            'anonymized_at' => now(),
            'anonymized_by' => $adminId,
        ]);

        $this->refresh();
    }

    /**
     * Check if user is anonymized
     */
    public function isAnonymized(): bool
    {
        return ! is_null($this->anonymized_at);
    }

    /**
     * Check if user has requested account deletion
     */
    public function isDeletionRequested(): bool
    {
        return ! is_null($this->deletion_requested_at);
    }

    /**
     * Request account deletion (self-service)
     */
    public function requestDeletion(): void
    {
        static::withTrashed()->where('id', $this->id)->update([
            'deletion_requested_at' => now(),
            'deleted_at' => now(),
        ]);

        UserDeletionLog::logAction($this, 'self_deletion_requested');

        $this->refresh();
    }

    /**
     * Cancel a pending deletion request (self-reactivation)
     */
    public function cancelDeletion(): void
    {
        static::withTrashed()->where('id', $this->id)->update([
            'deletion_requested_at' => null,
            'deleted_at' => null,
        ]);

        UserDeletionLog::logAction($this, 'self_reactivated');

        $this->refresh();
    }

    /**
     * Days remaining until auto-anonymization
     */
    public function daysUntilAnonymization(): int
    {
        if (! $this->deletion_requested_at) {
            return 0;
        }

        return max(0, (int) now()->diffInDays($this->deletion_requested_at->addDays(30), false));
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }
}
