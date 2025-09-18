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
     * POST /api/forum-posts/{post}/comments
     *
     * @param Request $request
     * @param Post    $post
     *
     * @return JsonResponse
     */
    public function store(Request $request, Post $post): JsonResponse
    {
        if (!$post->bulletinPost->has_forum) {
            return response()->json(['error' => 'Forum nicht aktiviert'], 403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->check() ? auth()->id() : null,
            'name' => auth()->check() ? auth()->user()->name : $request->input('name', 'Anonym'),
            'content' => $validated['content'],
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $comment->id,
                'name' => $comment->name,
                'content' => $comment->content,
                'created_at' => $comment->created_at->format('d.m.Y H:i'),
                'can_delete' => auth()->check() && auth()->id() === $comment->user_id,
            ],
            'message' => 'Kommentar hinzugefügt',
        ], 201);
    }

    /**
     * Remove a comment.
     *
     * DELETE /api/comments/{comment}
     *
     * @param Comment $comment
     *
     * @return JsonResponse
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
     * GET /api/forum-posts/{post}/comments
     *
     * @param Post $post
     *
     * @return JsonResponse
     */
    public function index(Post $post): JsonResponse
    {
        $comments = $post->comments()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'name' => $comment->name,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->format('d.m.Y H:i'),
                    'can_delete' => auth()->check() && auth()->id() === $comment->user_id,
                ];
            }),
        ]);
    }
}