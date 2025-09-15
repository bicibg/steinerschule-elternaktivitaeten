<?php

namespace App\Http\Controllers;

use App\Models\BulletinPost;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class PostController extends Controller
{
    public function store($slug, Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Bitte melden Sie sich an, um einen Beitrag zu verfassen.');
        }

        $helpRequest = BulletinPost::where('slug', $slug)->published()->firstOrFail();

        $key = 'post-' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 1)) {
            return back()->withErrors(['rate_limit' => 'Bitte warten Sie 30 Sekunden vor dem nächsten Beitrag.']);
        }

        // Honeypot check
        if ($request->filled('website') || $request->filled('email_confirm')) {
            return back();
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

        return redirect()->route('help-requests.show', $helpRequest->slug)
            ->with('success', 'Ihr Beitrag wurde erfolgreich veröffentlicht.');
    }

    public function storeComment(Post $post, Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Bitte melden Sie sich an, um zu kommentieren.');
        }

        $key = 'comment-' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 1)) {
            return back()->withErrors(['rate_limit' => 'Bitte warten Sie 30 Sekunden vor dem nächsten Kommentar.']);
        }

        // Honeypot check
        if ($request->filled('website') || $request->filled('email_confirm')) {
            return back();
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

        return redirect()->route('help-requests.show', $post->helpRequest->slug)
            ->with('success', 'Ihr Kommentar wurde erfolgreich veröffentlicht.');
    }
}
