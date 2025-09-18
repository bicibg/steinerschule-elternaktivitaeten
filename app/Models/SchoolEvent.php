<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SchoolEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'start_date',
        'end_date',
        'event_time',
        'location',
        'event_type',
        'color',
        'all_day',
        'is_recurring',
        'recurrence_pattern',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'all_day' => 'boolean',
        'is_recurring' => 'boolean',
    ];

    public static function getEventTypes()
    {
        return [
            'festival' => 'Fest',
            'meeting' => 'Versammlung',
            'performance' => 'Aufführung',
            'holiday' => 'Ferien',
            'sports' => 'Sport',
            'excursion' => 'Ausflug',
            'other' => 'Sonstiges',
        ];
    }

    public function getEventTypeLabel()
    {
        $types = self::getEventTypes();
        return $types[$this->event_type] ?? $this->event_type;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($event) {
            if (empty($event->slug)) {
                // Generate slug with random suffix like other models
                $event->slug = Str::slug($event->title) . '-' . Str::random(6);
            }
        });

        static::updating(function ($event) {
            if ($event->isDirty('title') && !$event->isDirty('slug')) {
                // Generate new slug with random suffix when title changes
                $event->slug = Str::slug($event->title) . '-' . Str::random(6);
            }
        });
    }
}