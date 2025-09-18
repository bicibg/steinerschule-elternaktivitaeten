<?php

namespace App\Services;

use App\Models\Shift;
use App\Models\ShiftVolunteer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ShiftService
{
    /**
     * Sign up a user for a shift.
     *
     * Performs a transactional signup to prevent race conditions when multiple
     * users attempt to sign up simultaneously. Validates capacity and duplicate
     * signups before creating the volunteer record.
     *
     * @param Shift $shift Target shift to sign up for
     * @param User  $user  User attempting to sign up
     *
     * @return ShiftVolunteer Created volunteer record
     *
     * @throws \Exception When shift is at capacity or user already signed up
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
     * Withdraw a user from a shift.
     *
     * Removes the user's volunteer signup from the specified shift.
     * Validates that the user is actually signed up before attempting deletion.
     *
     * @param Shift $shift Shift to withdraw from
     * @param User  $user  User requesting withdrawal
     *
     * @return bool True if withdrawal successful
     *
     * @throws \Exception When user is not signed up for the shift
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
     * Check if a shift has reached capacity.
     *
     * Combines both online volunteers (registered through platform) and
     * offline volunteers (manually tracked) to determine if the shift
     * has reached its needed capacity.
     *
     * @param Shift $shift Shift to check capacity for
     *
     * @return bool True if shift is at or over capacity
     */
    public function isShiftFull(Shift $shift): bool
    {
        $onlineCount = $shift->volunteers()->count();
        $totalRegistered = $shift->offline_filled + $onlineCount;

        return $totalRegistered >= $shift->needed;
    }

    /**
     * Check if a user is already signed up for a shift.
     *
     * Queries the shift_volunteers table to determine if a signup
     * record exists for the given user and shift combination.
     *
     * @param Shift $shift Shift to check
     * @param User  $user  User to check for existing signup
     *
     * @return bool True if user has already signed up
     */
    public function isUserSignedUp(Shift $shift, User $user): bool
    {
        return ShiftVolunteer::where('shift_id', $shift->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Calculate remaining available volunteer spots.
     *
     * Returns the number of additional volunteers needed, accounting for
     * both online and offline registrations. Never returns negative values.
     *
     * @param Shift $shift Shift to calculate availability for
     *
     * @return int Number of available spots (0 if full or overfilled)
     */
    public function getAvailableSpots(Shift $shift): int
    {
        $onlineCount = $shift->volunteers()->count();
        $totalRegistered = $shift->offline_filled + $onlineCount;

        return max(0, $shift->needed - $totalRegistered);
    }

    /**
     * Get all volunteers signed up for a shift.
     *
     * Retrieves volunteer records with eager loaded user relationships
     * for efficient display in volunteer lists.
     *
     * @param Shift $shift Shift to get volunteers for
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, ShiftVolunteer>
     */
    public function getShiftVolunteers(Shift $shift)
    {
        return $shift->volunteers()->with('user')->get();
    }

    /**
     * Get all shifts a user has volunteered for.
     *
     * Retrieves all shift records where the user has an active volunteer
     * signup, including the parent bulletin post for context.
     *
     * @param User $user User to get shift signups for
     *
     * @return \Illuminate\Support\Collection<int, Shift> Collection of shifts
     */
    public function getUserShifts(User $user)
    {
        return ShiftVolunteer::where('user_id', $user->id)
            ->with(['shift.bulletinPost'])
            ->get()
            ->pluck('shift');
    }

    /**
     * Determine if a user is eligible to sign up for a shift.
     *
     * Performs validation checks and returns both the eligibility status
     * and a human-readable reason if signup is not allowed.
     *
     * @param Shift $shift Shift to check signup eligibility for
     * @param User  $user  User requesting signup
     *
     * @return array{can_signup: bool, reason: string|null} Signup eligibility and reason
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
     * Generate comprehensive statistics for a shift.
     *
     * Calculates various metrics including online/offline volunteer counts,
     * fill percentage, and availability status. Useful for dashboards and
     * reporting features.
     *
     * @param Shift $shift Shift to generate statistics for
     *
     * @return array{
     *     online_volunteers: int,
     *     offline_volunteers: int,
     *     total_filled: int,
     *     needed: int,
     *     available_spots: int,
     *     is_full: bool,
     *     fill_percentage: int
     * } Comprehensive shift statistics
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