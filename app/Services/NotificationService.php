<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class NotificationService
{
    /**
     * Notify contact users and contact_email fallback for a contactable model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $contactable  BulletinPost or Activity with contactUsers() and contact_email
     * @param  Notification  $notification  The notification to send
     * @param  int|null  $excludeUserId  User ID to exclude (the acting user)
     */
    public function notifyContacts($contactable, Notification $notification, ?int $excludeUserId = null): void
    {
        $contactUsers = $contactable->contactUsers()->get();
        $notifiedEmails = [];

        foreach ($contactUsers as $user) {
            if ($excludeUserId && $user->id === $excludeUserId) {
                continue;
            }
            $user->notify(clone $notification);
            $notifiedEmails[] = $user->email;
        }

        // Send to contact_email if it's not already covered by contactUsers
        if ($contactable->contact_email && ! in_array($contactable->contact_email, $notifiedEmails)) {
            if (! $excludeUserId || ! User::where('id', $excludeUserId)->where('email', $contactable->contact_email)->exists()) {
                NotificationFacade::route('mail', $contactable->contact_email)
                    ->notify(clone $notification);
            }
        }
    }

    /**
     * Notify a specific user if they have notifications enabled and are not the acting user.
     */
    public function notifyUser(User $user, Notification $notification, ?int $excludeUserId = null): void
    {
        if ($excludeUserId && $user->id === $excludeUserId) {
            return;
        }

        $user->notify($notification);
    }
}
