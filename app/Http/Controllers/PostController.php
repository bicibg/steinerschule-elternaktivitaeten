<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class PostController extends Controller
{
    public function store($slug, Request $request)
    {
        $activity = Activity::where('slug', $slug)->published()->firstOrFail();

        $key = 'post-' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 1)) {
            return back()->withErrors(['rate_limit' => 'Bitte warten Sie 30 Sekunden vor dem nächsten Beitrag.']);
        }

        if ($request->filled('website')) {
            return back();
        }

        $validated = $request->validate([
            'author_name' => 'required|string|max:100',
            'body' => 'required|string|max:2000',
            'captcha' => 'required|numeric',
        ]);

        $captchaAnswer = session('captcha_answer');
        if ($validated['captcha'] != $captchaAnswer) {
            return back()->withErrors(['captcha' => 'Die Antwort ist falsch.'])->withInput();
        }

        $post = $activity->posts()->create([
            'author_name' => $validated['author_name'],
            'body' => $validated['body'],
            'ip_hash' => hash('sha256', $request->ip()),
        ]);

        RateLimiter::hit($key, 30);

        session()->forget('captcha_answer');

        return redirect()->route('activities.show', $activity->slug)
            ->with('success', 'Ihr Beitrag wurde erfolgreich veröffentlicht.');
    }

    public function storeComment(Post $post, Request $request)
    {
        $key = 'comment-' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 1)) {
            return back()->withErrors(['rate_limit' => 'Bitte warten Sie 30 Sekunden vor dem nächsten Kommentar.']);
        }

        if ($request->filled('website')) {
            return back();
        }

        $validated = $request->validate([
            'author_name' => 'required|string|max:100',
            'body' => 'required|string|max:800',
            'captcha' => 'required|numeric',
        ]);

        $captchaAnswer = session('captcha_answer_comment_' . $post->id);
        if ($validated['captcha'] != $captchaAnswer) {
            return back()->withErrors(['captcha' => 'Die Antwort ist falsch.'])->withInput();
        }

        $comment = $post->comments()->create([
            'author_name' => $validated['author_name'],
            'body' => $validated['body'],
            'ip_hash' => hash('sha256', $request->ip()),
        ]);

        RateLimiter::hit($key, 30);

        session()->forget('captcha_answer_comment_' . $post->id);

        return redirect()->route('activities.show', $post->activity->slug)
            ->with('success', 'Ihr Kommentar wurde erfolgreich veröffentlicht.');
    }
}
