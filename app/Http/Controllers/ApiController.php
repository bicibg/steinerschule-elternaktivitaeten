<?php

namespace App\Http\Controllers;

use App\Models\BulletinPost;
use App\Models\Post;
use App\Models\Shift;
use App\Models\ShiftVolunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ApiController extends Controller
{
    public function shiftSignup(Request $request, Shift $shift)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Nicht angemeldet'], 401);
        }

        if ($shift->filled >= $shift->needed) {
            return response()->json(['error' => 'Schicht ist bereits voll besetzt'], 400);
        }

        $existing = ShiftVolunteer::where('shift_id', $shift->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return response()->json(['error' => 'Bereits angemeldet'], 400);
        }

        $volunteer = ShiftVolunteer::create([
            'shift_id' => $shift->id,
            'user_id' => auth()->id(),
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ]);

        $shift->increment('filled');

        return response()->json([
            'success' => true,
            'volunteer' => [
                'id' => $volunteer->id,
                'name' => $volunteer->name,
                'user_id' => $volunteer->user_id,
            ],
            'filled' => $shift->filled,
            'needed' => $shift->needed,
        ]);
    }

    public function shiftWithdraw(Shift $shift)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Nicht angemeldet'], 401);
        }

        $volunteer = ShiftVolunteer::where('shift_id', $shift->id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$volunteer) {
            return response()->json(['error' => 'Nicht angemeldet für diese Schicht'], 400);
        }

        $volunteer->delete();
        $shift->decrement('filled');

        return response()->json([
            'success' => true,
            'filled' => $shift->fresh()->filled,
            'needed' => $shift->needed,
        ]);
    }

    public function storePost(Request $request, $slug)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Nicht angemeldet'], 401);
        }

        $helpRequest = BulletinPost::where('slug', $slug)->published()->firstOrFail();

        $key = 'post-' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 1)) {
            return response()->json(['error' => 'Bitte warten Sie 30 Sekunden'], 429);
        }

        if ($request->filled('website') || $request->filled('email_confirm')) {
            return response()->json(['error' => 'Spam detected'], 400);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $post = $helpRequest->posts()->create([
            'author_name' => auth()->user()->name,
            'body' => $validated['body'],
            'ip_hash' => hash('sha256', $request->ip()),
        ]);

        RateLimiter::hit($key, 30);

        return response()->json([
            'success' => true,
            'post' => [
                'id' => $post->id,
                'author_name' => $post->author_name,
                'body' => nl2br(e($post->body)),
                'created_at' => $post->created_at->format('d.m.Y H:i'),
            ],
        ]);
    }

    public function storeComment(Request $request, Post $post)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Nicht angemeldet'], 401);
        }

        $key = 'comment-' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 1)) {
            return response()->json(['error' => 'Bitte warten Sie 30 Sekunden'], 429);
        }

        if ($request->filled('website') || $request->filled('email_confirm')) {
            return response()->json(['error' => 'Spam detected'], 400);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:800',
        ]);

        $comment = $post->comments()->create([
            'author_name' => auth()->user()->name,
            'body' => $validated['body'],
            'ip_hash' => hash('sha256', $request->ip()),
        ]);

        RateLimiter::hit($key, 30);

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'author_name' => $comment->author_name,
                'body' => nl2br(e($comment->body)),
                'created_at' => $comment->created_at->format('d.m.Y H:i'),
            ],
        ]);
    }
}
