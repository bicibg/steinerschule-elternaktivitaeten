<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BulletinPost extends Model
{
    protected $fillable = [
        'title',
        'description',
        'participation_note',
        'start_at',
        'end_at',
        'location',
        'organizer_name',
        'organizer_phone',
        'organizer_email',
        'slug',
        'status',
        'category',
        'activity_type',
        'recurring_pattern',
        'show_in_calendar',
        'edit_token',
        'has_forum',
        'has_shifts',
        'label',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'show_in_calendar' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($bulletinPost) {
            if (empty($bulletinPost->slug)) {
                $bulletinPost->slug = Str::slug($bulletinPost->title) . '-' . Str::random(6);
            }
            if (empty($bulletinPost->edit_token)) {
                $bulletinPost->edit_token = Str::random(64);
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
        return $query->where(function($q) {
            $q->where('end_at', '>=', now())
              ->orWhere('start_at', '>=', now());
        })->orderBy('start_at');
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
        ];
    }

    public static function getAvailableCategories(): array
    {
        return [
            'anlass' => 'Anlässe',
            'haus_umgebung_taskforces' => 'Haus, Umgebung und Taskforces',
            'produktion' => 'Produktion',
            'organisation' => 'Organisation',
            'verkauf' => 'Verkauf',
        ];
    }

    public static function getActivityTypes(): array
    {
        return [
            'shift_based' => 'Schichtbasiert',
            'production' => 'Produktion',
            'meeting' => 'Regelmässiges Treffen',
            'flexible_help' => 'Flexible Hilfe',
        ];
    }

    public function getLabelColorAttribute(): string
    {
        return match($this->label) {
            'urgent' => 'red',
            'important' => 'yellow',
            'featured' => 'blue',
            'last_minute' => 'orange',
            default => 'gray',
        };
    }

    public function getLabelTextAttribute(): ?string
    {
        $labels = self::getAvailableLabels();
        return $labels[$this->label] ?? null;
    }

    public function getCategoryTextAttribute(): ?string
    {
        $categories = self::getAvailableCategories();
        return $categories[$this->category] ?? null;
    }
}
