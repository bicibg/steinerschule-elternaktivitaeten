<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
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
}