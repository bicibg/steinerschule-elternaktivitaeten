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
     * Get the total number of filled positions (offline + online)
     */
    public function getTotalFilledAttribute(): int
    {
        $onlineCount = $this->volunteers()->count();
        return $this->offline_filled + $onlineCount;
    }

    /**
     * Get the number of online volunteers
     */
    public function getOnlineFilledAttribute(): int
    {
        return $this->volunteers()->count();
    }

    /**
     * Check if the shift is fully booked
     */
    public function getIsFullAttribute(): bool
    {
        if (!$this->needed) {
            return false; // If no limit, never full
        }
        return $this->total_filled >= $this->needed;
    }

    /**
     * Get remaining spots available
     */
    public function getRemainingAttribute(): int
    {
        if (!$this->needed) {
            return PHP_INT_MAX; // Unlimited if no needed value
        }
        $remaining = $this->needed - $this->total_filled;
        return max(0, $remaining);
    }

    /**
     * Format the shift capacity display
     */
    public function getCapacityDisplayAttribute(): string
    {
        if (!$this->needed) {
            return $this->total_filled . ' angemeldet';
        }
        return $this->total_filled . '/' . $this->needed;
    }
}
