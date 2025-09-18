<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    public function togglePost(Post $post, Request $request)
    {
        if ($post->trashed()) {
            $post->restore();
            $post->update(['deletion_reason' => null]);
            $message = 'Beitrag wurde wieder sichtbar gemacht.';
        } else {
            $post->update(['deletion_reason' => 'inappropriate']);
            $post->delete();
            $message = 'Beitrag wurde versteckt.';
        }

        return back()->with('success', $message);
    }

    public function toggleComment(Comment $comment, Request $request)
    {
        if ($comment->trashed()) {
            $comment->restore();
            $comment->update(['deletion_reason' => null]);
            $message = 'Kommentar wurde wieder sichtbar gemacht.';
        } else {
            $comment->update(['deletion_reason' => 'inappropriate']);
            $comment->delete();
            $message = 'Kommentar wurde versteckt.';
        }

        return back()->with('success', $message);
    }
}
