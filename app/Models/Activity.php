<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'category',
        'contact_name',
        'contact_email',
        'contact_phone',
        'meeting_time',
        'meeting_location',
        'has_forum',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_forum' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($activity) {
            if (empty($activity->slug)) {
                $activity->slug = Str::slug($activity->title) . '-' . Str::random(6);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('title');
    }

    public function posts()
    {
        return $this->hasMany(\App\Models\ActivityPost::class);
    }

    public static function getCategories(): array
    {
        return [
            'anlass' => 'Anlässe',
            'haus_umgebung_taskforces' => 'Haus, Umgebung und Taskforces',
            'produktion' => 'Produktion',
            'organisation' => 'Organisation',
            'verkauf' => 'Verkauf',
        ];
    }

    public function getCategoryTextAttribute(): ?string
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? null;
    }
}
