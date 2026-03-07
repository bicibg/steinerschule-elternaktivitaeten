<?php

namespace App\Repositories;

use App\Models\Shift;
use App\Models\ShiftVolunteer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class ShiftRepository
{
    /**
     * Find shifts needing volunteers.
     *
     * Retrieves shifts that haven't reached their needed capacity,
     * ordered by date. Useful for volunteer recruitment displays.
     *
     * @param  int  $limit  Maximum number of shifts to return
     * @return Collection<int, Shift> Shifts with available spots
     */
    public function findNeedingVolunteers(int $limit = 10): Collection
    {
        $shifts = Shift::with(['bulletinPost', 'volunteers'])
            ->whereHas('bulletinPost', function ($query) {
                $query->published();
            })
            ->get()
            ->filter(function ($shift) {
                return $shift->filled < $shift->needed;
            })
            ->sortBy(function ($shift) {
                return $this->parseShiftDate($shift->time)?->timestamp ?? PHP_INT_MAX;
            })
            ->take($limit)
            ->values();

        return new Collection($shifts->all());
    }

    /**
     * Get shifts for a specific user.
     *
     * Retrieves all shifts where the user has volunteered, including
     * the parent bulletin post for context.
     *
     * @param  User  $user  User to get shifts for
     * @return Collection<int, Shift> User's volunteer shifts
     */
    public function getUserShifts(User $user): Collection
    {
        return Shift::whereHas('volunteers', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with(['bulletinPost', 'volunteers'])
            ->get();
    }

    /**
     * Get shifts for a bulletin post ordered by date.
     *
     * Retrieves all shifts for a bulletin post, sorting by parsed
     * German date format in the time field.
     *
     * @param  int  $bulletinPostId  Bulletin post ID
     * @return Collection<int, Shift> Ordered shifts with volunteers
     */
    public function getByBulletinPost(int $bulletinPostId): Collection
    {
        $shifts = Shift::where('bulletin_post_id', $bulletinPostId)
            ->with('volunteers.user')
            ->get()
            ->sortBy(function ($shift) {
                return $this->parseShiftDate($shift->time)?->timestamp ?? PHP_INT_MAX;
            })
            ->values();

        return new Collection($shifts->all());
    }

    /**
     * Get upcoming shifts within date range.
     *
     * Retrieves shifts occurring within a specified date range,
     * parsing the German date format for comparison.
     *
     * @param  Carbon  $startDate  Range start date
     * @param  Carbon  $endDate  Range end date
     * @return Collection<int, Shift> Shifts within date range
     */
    public function getInDateRange(Carbon $startDate, Carbon $endDate): Collection
    {
        $shifts = Shift::with(['bulletinPost', 'volunteers'])
            ->whereHas('bulletinPost', function ($query) {
                $query->published();
            })
            ->get()
            ->filter(function ($shift) use ($startDate, $endDate) {
                $shiftDate = $this->parseShiftDate($shift->time);
                if (! $shiftDate) {
                    return false;
                }

                return $shiftDate->between($startDate->startOfDay(), $endDate->endOfDay());
            })
            ->sortBy(function ($shift) {
                return $this->parseShiftDate($shift->time)?->timestamp ?? PHP_INT_MAX;
            })
            ->values();

        return new Collection($shifts->all());
    }

    /**
     * Get shift fill statistics by category.
     *
     * Calculates average fill rates for shifts grouped by bulletin post category.
     *
     * @return SupportCollection Statistics grouped by category
     */
    public function getFillStatisticsByCategory(): SupportCollection
    {
        $shifts = Shift::with(['bulletinPost', 'volunteers'])
            ->whereHas('bulletinPost', function ($query) {
                $query->where('status', 'published');
            })
            ->get();

        return $shifts->groupBy(function ($shift) {
            return $shift->bulletinPost->category;
        })->map(function ($categoryShifts, $category) {
            $totalNeeded = $categoryShifts->sum('needed');
            $totalOffline = $categoryShifts->sum('offline_filled');
            $totalOnline = $categoryShifts->sum(function ($shift) {
                return $shift->volunteers->count();
            });
            $totalFilled = $totalOffline + $totalOnline;
            $fillRate = $totalNeeded > 0
                ? round(($totalFilled / $totalNeeded) * 100, 2)
                : 0;

            return [
                'category' => $category,
                'total_shifts' => $categoryShifts->count(),
                'total_needed' => $totalNeeded,
                'total_filled' => $totalFilled,
                'fill_rate' => $fillRate,
            ];
        })->values();
    }

    /**
     * Find shift with all relationships.
     *
     * Retrieves a single shift with all nested relationships loaded.
     *
     * @param  int  $shiftId  Shift ID
     * @return Shift|null Shift with relationships or null
     */
    public function findWithRelations(int $shiftId): ?Shift
    {
        return Shift::with(['bulletinPost', 'volunteers.user'])
            ->find($shiftId);
    }

    /**
     * Get volunteer history for a user.
     *
     * Retrieves all volunteer signups for a user with shift details.
     *
     * @param  User  $user  User to get history for
     * @param  int  $limit  Maximum records to return
     * @return Collection<int, ShiftVolunteer> Volunteer history
     */
    public function getUserVolunteerHistory(User $user, int $limit = 50): Collection
    {
        return ShiftVolunteer::where('user_id', $user->id)
            ->with(['shift.bulletinPost'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Clean up orphaned volunteer records.
     *
     * Removes volunteer records where the associated shift no longer exists.
     *
     * @return int Number of records deleted
     */
    public function cleanOrphanedVolunteers(): int
    {
        return ShiftVolunteer::whereDoesntHave('shift')->delete();
    }

    /**
     * Parse German date format from shift time string.
     *
     * @param  string  $timeString  Shift time string (e.g. "Samstag, 09.11.2024, 09:00 - 11:00 Uhr")
     * @return Carbon|null Parsed date or null if parsing fails
     */
    private function parseShiftDate(string $timeString): ?Carbon
    {
        if (preg_match('/(\d{2})\.(\d{2})\.(\d{4})/', $timeString, $matches)) {
            return Carbon::createFromFormat('d.m.Y', $matches[0]);
        }

        return null;
    }
}
