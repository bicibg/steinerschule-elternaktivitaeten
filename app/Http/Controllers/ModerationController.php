<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    public function togglePost(Post $post, Request $request)
    {
        $post->update([
            'is_hidden' => !$post->is_hidden,
            'hidden_reason' => $post->is_hidden ? null : $request->input('reason', 'Vom Moderator versteckt'),
        ]);

        return back()->with('success',
            $post->is_hidden
                ? 'Beitrag wurde versteckt.'
                : 'Beitrag wurde wieder sichtbar gemacht.'
        );
    }

    public function toggleComment(Comment $comment, Request $request)
    {
        $comment->update([
            'is_hidden' => !$comment->is_hidden,
            'hidden_reason' => $comment->is_hidden ? null : $request->input('reason', 'Vom Moderator versteckt'),
        ]);

        return back()->with('success',
            $comment->is_hidden
                ? 'Kommentar wurde versteckt.'
                : 'Kommentar wurde wieder sichtbar gemacht.'
        );
    }
}
