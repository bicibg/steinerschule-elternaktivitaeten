<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'post_id',
        'user_id',
        'body',
        'ip_hash',
        'deletion_reason',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
