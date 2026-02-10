<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityForumController extends Controller
{
    public function storePost(Request $request, string $slug): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Nicht angemeldet'], 401);
        }

        $activity = Activity::where('slug', $slug)->active()->firstOrFail();

        if (!$activity->has_forum) {
            return response()->json(['error' => 'Forum ist für diese Aktivität nicht aktiviert'], 403);
        }

        // Honeypot spam check
        if ($request->filled('website') || $request->filled('email_confirm')) {
            return response()->json(['error' => 'Spam detected'], 400);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $post = $activity->posts()->create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
            'ip_hash' => hash('sha256', $request->ip()),
        ]);

        return response()->json([
            'success' => true,
            'post' => [
                'id' => $post->id,
                'author_name' => $post->user->name,
                'body' => nl2br(e($post->body)),
                'created_at' => $post->created_at->format('d.m.Y H:i'),
            ],
        ]);
    }

    public function storeComment(Request $request, ActivityPost $post): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Nicht angemeldet'], 401);
        }

        if (!$post->activity->has_forum) {
            return response()->json(['error' => 'Forum ist für diese Aktivität nicht aktiviert'], 403);
        }

        // Honeypot spam check
        if ($request->filled('website') || $request->filled('email_confirm')) {
            return response()->json(['error' => 'Spam detected'], 400);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:800',
        ]);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
            'ip_hash' => hash('sha256', $request->ip()),
        ]);

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'author_name' => $comment->user->name,
                'body' => nl2br(e($comment->body)),
                'created_at' => $comment->created_at->format('d.m.Y H:i'),
            ],
        ]);
    }
}
