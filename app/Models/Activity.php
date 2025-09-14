<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    protected $fillable = [
        'title',
        'description',
        'end_at',
        'location',
        'organizer_name',
        'organizer_phone',
        'organizer_email',
        'slug',
        'status',
        'edit_token',
        'has_forum',
        'has_shifts',
        'label',
    ];

    protected $casts = [
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
        return $query->where('end_at', '>=', now())->orderBy('end_at');
    }

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public static function getAvailableLabels(): array
    {
        return [
            'urgent' => 'Dringend',
            'important' => 'Wichtig',
            'featured' => 'Hervorgehoben',
            'last_minute' => 'Last Minute',
            'help_needed' => 'Hilfe benötigt',
        ];
    }

    public function getLabelColorAttribute(): string
    {
        return match($this->label) {
            'urgent' => 'red',
            'important' => 'yellow',
            'featured' => 'blue',
            'last_minute' => 'orange',
            'help_needed' => 'purple',
            default => 'gray',
        };
    }

    public function getLabelTextAttribute(): ?string
    {
        $labels = self::getAvailableLabels();
        return $labels[$this->label] ?? null;
    }
}
