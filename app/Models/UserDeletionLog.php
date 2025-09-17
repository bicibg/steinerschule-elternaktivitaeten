<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDeletionLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'action_type',
        'performed_by',
        'performed_by_name',
        'reason',
    ];

    public static function logAction(User $user, string $actionType, ?string $reason = null): void
    {
        static::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action_type' => $actionType,
            'performed_by' => auth()->id(),
            'performed_by_name' => auth()->user()->name,
            'reason' => $reason,
        ]);
    }
}