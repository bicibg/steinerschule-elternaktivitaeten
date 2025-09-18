<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'bulletin_post_id',
        'role',
        'time',
        'needed',
        'offline_filled',
    ];

    public function bulletinPost()
    {
        return $this->belongsTo(BulletinPost::class);
    }

    public function volunteers()
    {
        return $this->hasMany(ShiftVolunteer::class);
    }

    /**
     * Get the number of filled positions (offline + online)
     */
    public function getFilledAttribute(): int
    {
        // Use loaded relationship if available to avoid N+1 queries
        $onlineCount = $this->relationLoaded('volunteers')
            ? $this->volunteers->count()
            : $this->volunteers()->count();
        return $this->offline_filled + $onlineCount;
    }

    /**
     * Get the number of online volunteers
     */
    public function getOnlineFilledAttribute(): int
    {
        // Use loaded relationship if available to avoid N+1 queries
        return $this->relationLoaded('volunteers')
            ? $this->volunteers->count()
            : $this->volunteers()->count();
    }

    /**
     * Check if the shift is fully booked
     */
    public function getIsFullAttribute(): bool
    {
        if (!$this->needed) {
            return false; // If no limit, never full
        }
        return $this->filled >= $this->needed;
    }

    /**
     * Get remaining spots available
     */
    public function getRemainingAttribute(): int
    {
        if (!$this->needed) {
            return PHP_INT_MAX; // Unlimited if no needed value
        }
        $remaining = $this->needed - $this->filled;
        return max(0, $remaining);
    }

    /**
     * Format the shift capacity display
     */
    public function getCapacityDisplayAttribute(): string
    {
        if (!$this->needed) {
            return $this->filled . ' angemeldet';
        }
        return $this->filled . '/' . $this->needed;
    }
}
