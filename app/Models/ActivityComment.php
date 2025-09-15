<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityComment extends Model
{
    protected $fillable = [
        'activity_post_id',
        'author_name',
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
}