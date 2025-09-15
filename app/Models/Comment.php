<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'body',
        'ip_hash',
        'is_hidden',
        'hidden_reason',
    ];

    protected $casts = [
        'is_hidden' => 'boolean',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
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
