<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'bulletin_post_id',
        'user_id',
        'body',
        'ip_hash',
        'deletion_reason',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function bulletinPost()
    {
        return $this->belongsTo(BulletinPost::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'asc');
    }

    public function allComments()
    {
        return $this->hasMany(Comment::class)->withTrashed()->orderBy('created_at', 'asc');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAuthorNameAttribute()
    {
        return $this->user ? $this->user->name : 'Anonym';
    }
}
