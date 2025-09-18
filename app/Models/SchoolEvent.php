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
                $baseSlug = Str::slug($event->title);
                $slug = $baseSlug;
                $counter = 1;

                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $event->slug = $slug;
            }
        });

        static::updating(function ($event) {
            if ($event->isDirty('title') && !$event->isDirty('slug')) {
                $baseSlug = Str::slug($event->title);
                $slug = $baseSlug;
                $counter = 1;

                while (static::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $event->slug = $slug;
            }
        });
    }
}