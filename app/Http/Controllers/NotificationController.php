<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationDismissal;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function dismiss(Request $request, Notification $notification)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        NotificationDismissal::firstOrCreate([
            'notification_id' => $notification->id,
            'user_id' => auth()->id(),
        ], [
            'dismissed_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}