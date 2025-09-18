<?php

namespace App\Http\Controllers;

use App\Models\BulletinPost;
use App\Models\Comment;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class PostController extends Controller
{
    public function store($slug, StorePostRequest $request)
    {
        $helpRequest = BulletinPost::where('slug', $slug)->published()->firstOrFail();

        $validated = $request->validated();

        $post = $helpRequest->posts()->create([
            'user_id' => auth()->id(),
            'author_name' => auth()->user()->name,
            'body' => $validated['body'],
            'ip_hash' => hash('sha256', $request->ip()),
        ]);

        RateLimiter::hit('post-' . $request->ip(), 30);

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
            'user_id' => auth()->id(),
            'author_name' => auth()->user()->name,
            'body' => $validated['body'],
            'ip_hash' => hash('sha256', $request->ip()),
        ]);

        RateLimiter::hit($key, 30);

        return redirect()->route('help-requests.show', $post->helpRequest->slug)
            ->with('success', 'Ihr Kommentar wurde erfolgreich veröffentlicht.');
    }

    public function destroy(Post $post)
    {
        // Check if user owns the post or is admin
        if (auth()->id() !== $post->user_id && !auth()->user()->is_admin) {
            abort(403, 'Nicht autorisiert');
        }

        $slug = $post->bulletinPost->slug;
        $post->delete();

        return redirect()->route('bulletin.show', $slug)
            ->with('success', 'Beitrag wurde gelöscht.');
    }

    public function destroyComment(Comment $comment)
    {
        // Check if user owns the comment or is admin
        if (auth()->id() !== $comment->user_id && !auth()->user()->is_admin) {
            abort(403, 'Nicht autorisiert');
        }

        $slug = $comment->post->bulletinPost->slug;
        $comment->delete();

        return redirect()->route('bulletin.show', $slug)
            ->with('success', 'Kommentar wurde gelöscht.');
    }
}
