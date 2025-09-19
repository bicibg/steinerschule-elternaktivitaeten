<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LunchShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'user_id',
        'cook_name',
        'expected_meals',
        'notes',
        'is_filled',
    ];

    protected $casts = [
        'date' => 'date',
        'expected_meals' => 'integer',
        'is_filled' => 'boolean',
    ];

    /**
     * Get the cook user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get display name for cook.
     */
    public function getCookDisplayNameAttribute(): string
    {
        if ($this->cook_name) {
            return $this->cook_name;
        }
        if ($this->user) {
            return $this->user->name;
        }
        return 'Noch offen';
    }

    /**
     * Check if shift is in the past.
     */
    public function isPast(): bool
    {
        return $this->date->isPast();
    }

    /**
     * Check if shift is today.
     */
    public function isToday(): bool
    {
        return $this->date->isToday();
    }

    /**
     * Check if shift is in the future.
     */
    public function isFuture(): bool
    {
        return $this->date->isFuture();
    }

    /**
     * Update filled status based on assignment.
     */
    public function updateFilledStatus(): void
    {
        $this->is_filled = ($this->user_id !== null || $this->cook_name !== null);
        $this->save();
    }

    /**
     * Scope for upcoming shifts.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', Carbon::today())
                     ->orderBy('date');
    }

    /**
     * Scope for open shifts.
     */
    public function scopeNeedingVolunteers($query)
    {
        return $query->where('is_filled', false);
    }

    /**
     * Get day name in German.
     */
    public function getGermanDayNameAttribute(): string
    {
        $days = [
            'Monday' => 'Montag',
            'Tuesday' => 'Dienstag',
            'Wednesday' => 'Mittwoch',
            'Thursday' => 'Donnerstag',
            'Friday' => 'Freitag',
            'Saturday' => 'Samstag',
            'Sunday' => 'Sonntag',
        ];

        return $days[$this->date->format('l')] ?? $this->date->format('l');
    }

    /**
     * Get short day name in German.
     */
    public function getShortDayNameAttribute(): string
    {
        $days = [
            'Mon' => 'Mo',
            'Tue' => 'Di',
            'Wed' => 'Mi',
            'Thu' => 'Do',
            'Fri' => 'Fr',
            'Sat' => 'Sa',
            'Sun' => 'So',
        ];

        return $days[$this->date->format('D')] ?? $this->date->format('D');
    }

    /**
     * Sign up for this lunch shift.
     */
    public function signUp(User $user): bool
    {
        if ($this->is_filled) {
            return false;
        }

        $this->user_id = $user->id;
        $this->cook_name = null; // Clear offline name if user signs up
        $this->is_filled = true;
        return $this->save();
    }

    /**
     * Cancel signup for this lunch shift.
     */
    public function cancelSignup(): bool
    {
        $this->user_id = null;
        $this->cook_name = null;
        $this->is_filled = false;
        return $this->save();
    }
}