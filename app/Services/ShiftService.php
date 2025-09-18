<?php

namespace App\Services;

use App\Models\Shift;
use App\Models\ShiftVolunteer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ShiftService
{
    /**
     * Sign up a user for a shift
     *
     * @throws \Exception if shift is full or user already signed up
     */
    public function signupForShift(Shift $shift, User $user): ShiftVolunteer
    {
        return DB::transaction(function () use ($shift, $user) {
            // Check if shift has capacity
            if ($this->isShiftFull($shift)) {
                throw new \Exception('Diese Schicht ist bereits voll besetzt.');
            }

            // Check if user is already signed up
            if ($this->isUserSignedUp($shift, $user)) {
                throw new \Exception('Sie sind bereits für diese Schicht angemeldet.');
            }

            // Create the volunteer signup
            return ShiftVolunteer::create([
                'shift_id' => $shift->id,
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
        });
    }

    /**
     * Withdraw a user from a shift
     *
     * @throws \Exception if user is not signed up
     */
    public function withdrawFromShift(Shift $shift, User $user): bool
    {
        $volunteer = ShiftVolunteer::where('shift_id', $shift->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$volunteer) {
            throw new \Exception('Sie sind nicht für diese Schicht angemeldet.');
        }

        return $volunteer->delete();
    }

    /**
     * Check if a shift is full
     */
    public function isShiftFull(Shift $shift): bool
    {
        $onlineCount = $shift->volunteers()->count();
        $totalRegistered = $shift->offline_filled + $onlineCount;

        return $totalRegistered >= $shift->needed;
    }

    /**
     * Check if a user is already signed up for a shift
     */
    public function isUserSignedUp(Shift $shift, User $user): bool
    {
        return ShiftVolunteer::where('shift_id', $shift->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Get available spots for a shift
     */
    public function getAvailableSpots(Shift $shift): int
    {
        $onlineCount = $shift->volunteers()->count();
        $totalRegistered = $shift->offline_filled + $onlineCount;

        return max(0, $shift->needed - $totalRegistered);
    }

    /**
     * Get all volunteers for a shift
     */
    public function getShiftVolunteers(Shift $shift)
    {
        return $shift->volunteers()->with('user')->get();
    }

    /**
     * Get all shifts a user is signed up for
     */
    public function getUserShifts(User $user)
    {
        return ShiftVolunteer::where('user_id', $user->id)
            ->with(['shift.bulletinPost'])
            ->get()
            ->pluck('shift');
    }

    /**
     * Check if a user can sign up for a shift
     */
    public function canUserSignup(Shift $shift, User $user): array
    {
        $canSignup = true;
        $reason = null;

        if ($this->isShiftFull($shift)) {
            $canSignup = false;
            $reason = 'Diese Schicht ist bereits voll besetzt.';
        } elseif ($this->isUserSignedUp($shift, $user)) {
            $canSignup = false;
            $reason = 'Sie sind bereits für diese Schicht angemeldet.';
        }

        return [
            'can_signup' => $canSignup,
            'reason' => $reason,
        ];
    }

    /**
     * Get shift statistics
     */
    public function getShiftStatistics(Shift $shift): array
    {
        $onlineCount = $shift->volunteers()->count();
        $offlineCount = $shift->offline_filled;
        $totalFilled = $onlineCount + $offlineCount;
        $needed = $shift->needed;
        $available = max(0, $needed - $totalFilled);

        return [
            'online_volunteers' => $onlineCount,
            'offline_volunteers' => $offlineCount,
            'total_filled' => $totalFilled,
            'needed' => $needed,
            'available_spots' => $available,
            'is_full' => $totalFilled >= $needed,
            'fill_percentage' => $needed > 0 ? round(($totalFilled / $needed) * 100) : 0,
        ];
    }
}