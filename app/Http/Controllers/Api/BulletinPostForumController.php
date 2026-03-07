<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BulletinPost;
use App\Models\Post;
use App\Notifications\NewForumPostNotification;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BulletinPostForumController extends Controller
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

    /**
     * Store a new forum post for a bulletin post.
     *
     * POST /api/pinnwand/{slug}/posts
     */
    public function store(Request $request, string $slug): JsonResponse
    {
        if (! auth()->check()) {
            return response()->json(['error' => 'Nicht angemeldet'], 401);
        }

        $bulletinPost = BulletinPost::where('slug', $slug)->published()->first();

        if (! $bulletinPost) {
            return response()->json(['error' => 'Beitrag nicht gefunden'], 404);
        }

        if (! $bulletinPost->has_forum) {
            return response()->json(['error' => 'Forum nicht aktiviert'], 403);
        }

        // Honeypot spam check
        if ($request->filled('website') || $request->filled('email_confirm')) {
            return response()->json(['error' => 'Spam detected'], 400);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $post = $bulletinPost->posts()->create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
            'ip_hash' => hash('sha256', $request->ip()),
        ]);

        $post->load('user');
        $this->notificationService->notifyContacts(
            $bulletinPost,
            new NewForumPostNotification($post, $bulletinPost),
            auth()->id(),
        );

        return response()->json([
            'success' => true,
            'post' => [
                'id' => $post->id,
                'author_name' => $post->user->name,
                'body' => nl2br(e($post->body)),
                'created_at' => $post->created_at->format('d.m.Y H:i'),
            ],
        ], 201);
    }

    /**
     * List forum posts for a bulletin post.
     *
     * GET /api/bulletin-posts/{slug}/forum
     */
    public function index(string $slug): JsonResponse
    {
        $bulletinPost = BulletinPost::where('slug', $slug)->published()->first();

        if (! $bulletinPost) {
            return response()->json(['error' => 'Beitrag nicht gefunden'], 404);
        }

        $posts = $bulletinPost->posts()
            ->with(['user', 'comments.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'author_name' => $post->author_name,
                    'body' => nl2br(e($post->body)),
                    'created_at' => $post->created_at->format('d.m.Y H:i'),
                    'can_delete' => auth()->check() && auth()->id() === $post->user_id,
                    'comments' => $post->comments->map(function ($comment) {
                        return [
                            'id' => $comment->id,
                            'author_name' => $comment->user ? $comment->user->name : 'Anonym',
                            'body' => nl2br(e($comment->body)),
                            'created_at' => $comment->created_at->format('d.m.Y H:i'),
                            'can_delete' => auth()->check() && auth()->id() === $comment->user_id,
                        ];
                    }),
                ];
            }),
        ]);
    }
}
