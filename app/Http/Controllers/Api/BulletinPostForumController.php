<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BulletinPost;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BulletinPostForumController extends Controller
{
    /**
     * Store a new forum post for a bulletin post.
     *
     * POST /api/bulletin-posts/{slug}/forum
     *
     * @param Request $request
     * @param string  $slug
     *
     * @return JsonResponse
     */
    public function store(Request $request, string $slug): JsonResponse
    {
        $bulletinPost = BulletinPost::where('slug', $slug)->published()->first();

        if (!$bulletinPost) {
            return response()->json(['error' => 'Beitrag nicht gefunden'], 404);
        }

        if (!$bulletinPost->has_forum) {
            return response()->json(['error' => 'Forum nicht aktiviert'], 403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $post = Post::create([
            'bulletin_post_id' => $bulletinPost->id,
            'user_id' => auth()->check() ? auth()->id() : null,
            'name' => auth()->check() ? auth()->user()->name : $request->input('name', 'Anonym'),
            'content' => $validated['content'],
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $post->id,
                'name' => $post->name,
                'content' => $post->content,
                'created_at' => $post->created_at->format('d.m.Y H:i'),
                'can_delete' => auth()->check() && auth()->id() === $post->user_id,
            ],
            'message' => 'Beitrag erstellt',
        ], 201);
    }

    /**
     * List forum posts for a bulletin post.
     *
     * GET /api/bulletin-posts/{slug}/forum
     *
     * @param string $slug
     *
     * @return JsonResponse
     */
    public function index(string $slug): JsonResponse
    {
        $bulletinPost = BulletinPost::where('slug', $slug)->published()->first();

        if (!$bulletinPost) {
            return response()->json(['error' => 'Beitrag nicht gefunden'], 404);
        }

        $posts = $bulletinPost->posts()
            ->with(['comments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'name' => $post->name,
                    'content' => $post->content,
                    'created_at' => $post->created_at->format('d.m.Y H:i'),
                    'can_delete' => auth()->check() && auth()->id() === $post->user_id,
                    'comments' => $post->comments->map(function ($comment) {
                        return [
                            'id' => $comment->id,
                            'name' => $comment->name,
                            'content' => $comment->content,
                            'created_at' => $comment->created_at->format('d.m.Y H:i'),
                            'can_delete' => auth()->check() && auth()->id() === $comment->user_id,
                        ];
                    }),
                ];
            }),
        ]);
    }
}