<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementDismissal;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function dismiss(Request $request, Announcement $notification)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        AnnouncementDismissal::firstOrCreate([
            'notification_id' => $notification->id,
            'user_id' => auth()->id(),
        ], [
            'dismissed_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
