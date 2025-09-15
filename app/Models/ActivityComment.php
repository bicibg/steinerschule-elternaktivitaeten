<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityComment extends Model
{
    protected $fillable = [
        'activity_post_id',
        'user_id',
        'body',
        'ip_hash',
        'is_hidden',
    ];

    protected $casts = [
        'is_hidden' => 'boolean',
    ];

    public function post()
    {
        return $this->belongsTo(ActivityPost::class, 'activity_post_id');
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