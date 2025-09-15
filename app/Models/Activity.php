<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'contact_name',
        'contact_email',
        'contact_phone',
        'meeting_time',
        'meeting_location',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($activity) {
            if (empty($activity->slug)) {
                $activity->slug = Str::slug($activity->name) . '-' . Str::random(6);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public static function getCategories(): array
    {
        return [
            'anlass' => 'Anlässe & Feste',
            'haus_umgebung' => 'Haus & Umgebung',
            'taskforce' => 'Taskforces',
            'produktion' => 'Produktion',
            'organisation' => 'Organisation & Verwaltung',
            'verkauf' => 'Verkauf & Märkte',
            'paedagogik' => 'Pädagogische Unterstützung',
            'kommunikation' => 'Kommunikation & Öffentlichkeit',
        ];
    }

    public function getCategoryTextAttribute(): ?string
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? null;
    }
}