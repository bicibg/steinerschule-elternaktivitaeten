<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_at',
        'end_at',
        'location',
        'organizer_name',
        'organizer_phone',
        'organizer_email',
        'slug',
        'status',
        'edit_token',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($activity) {
            if (empty($activity->slug)) {
                $activity->slug = Str::slug($activity->title) . '-' . Str::random(6);
            }
            if (empty($activity->edit_token)) {
                $activity->edit_token = Str::random(64);
            }
        });
    }

    public function posts()
    {
        return $this->hasMany(Post::class)->where('is_hidden', false)->orderBy('created_at', 'desc');
    }

    public function allPosts()
    {
        return $this->hasMany(Post::class)->orderBy('created_at', 'desc');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_at', '>=', now())->orderBy('start_at');
    }

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }
}
