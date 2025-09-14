<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CalendarEvent extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'start_time',
        'end_time',
        'type',
        'location',
        'all_day',
    ];

    protected $casts = [
        'date' => 'date',
        'all_day' => 'boolean',
    ];

    public static function getTypeLabels(): array
    {
        return [
            'holiday' => 'Ferien/Feiertag',
            'concert' => 'Konzert',
            'parent_evening' => 'Elternabend',
            'festival' => 'Fest',
            'other' => 'Sonstiges',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        $labels = self::getTypeLabels();
        return $labels[$this->type] ?? $this->type;
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'holiday' => 'gray',
            'concert' => 'purple',
            'parent_evening' => 'blue',
            'festival' => 'green',
            'other' => 'yellow',
            default => 'gray',
        };
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->startOfDay())
                     ->orderBy('date')
                     ->orderBy('start_time');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('date', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])->orderBy('date')->orderBy('start_time');
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('d.m.Y');
    }

    public function getFormattedTimeAttribute(): string
    {
        if ($this->all_day) {
            return 'Ganztägig';
        }

        if ($this->start_time && $this->end_time) {
            return Carbon::parse($this->start_time)->format('H:i') . ' - ' . Carbon::parse($this->end_time)->format('H:i') . ' Uhr';
        } elseif ($this->start_time) {
            return 'ab ' . Carbon::parse($this->start_time)->format('H:i') . ' Uhr';
        }

        return '';
    }
}
