<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityPost;
use Illuminate\Http\Request;

class ActivityPostController extends Controller
{
    public function store($slug, Request $request)
    {
        $activity = Activity::where('slug', $slug)->active()->firstOrFail();

        if (!$activity->has_forum) {
            abort(403, 'Forum ist für diese Aktivität nicht aktiviert.');
        }

        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['ip_hash'] = hash('sha256', $request->ip());

        $post = $activity->posts()->create($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'post' => $post]);
        }

        return redirect()->route('activities.show', $activity->slug . '#post-' . $post->id)
            ->with('success', 'Ihr Beitrag wurde erfolgreich veröffentlicht.');
    }

    public function storeComment(ActivityPost $post, Request $request)
    {
        if (!$post->activity->has_forum) {
            abort(403, 'Forum ist für diese Aktivität nicht aktiviert.');
        }

        $validated = $request->validate([
            'body' => 'required|string|max:800',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['ip_hash'] = hash('sha256', $request->ip());

        $comment = $post->comments()->create($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'comment' => $comment]);
        }

        return redirect()->route('activities.show', $post->activity->slug . '#comment-' . $comment->id)
            ->with('success', 'Ihr Kommentar wurde erfolgreich veröffentlicht.');
    }
}