<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ForumCommentController extends Controller
{
    /**
     * Store a new comment on a forum post.
     *
     * POST /api/posts/{post}/comments
     */
    public function store(Request $request, Post $post): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Nicht angemeldet'], 401);
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
        ], 201);
    }

    /**
     * Remove a comment.
     *
     * DELETE /api/comments/{comment}
     */
    public function destroy(Comment $comment): JsonResponse
    {
        if (!auth()->check() || auth()->id() !== $comment->user_id) {
            return response()->json(['error' => 'Nicht berechtigt'], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kommentar gelöscht',
        ]);
    }

    /**
     * List comments for a forum post.
     *
     * GET /api/posts/{post}/comments
     */
    public function index(Post $post): JsonResponse
    {
        $comments = $post->comments()->with('user')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'author_name' => $comment->user ? $comment->user->name : 'Anonym',
                    'body' => nl2br(e($comment->body)),
                    'created_at' => $comment->created_at->format('d.m.Y H:i'),
                    'can_delete' => auth()->check() && auth()->id() === $comment->user_id,
                ];
            }),
        ]);
    }
}
