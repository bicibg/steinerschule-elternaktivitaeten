<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'bulletin_post_id',
        'user_id',
        'body',
        'ip_hash',
        'is_hidden',
        'hidden_reason',
    ];

    protected $casts = [
        'is_hidden' => 'boolean',
    ];

    public function bulletinPost()
    {
        return $this->belongsTo(BulletinPost::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->where('is_hidden', false)->orderBy('created_at', 'asc');
    }

    public function allComments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'asc');
    }

    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
