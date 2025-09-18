<?php

namespace App\Repositories;

use App\Models\Shift;
use App\Models\ShiftVolunteer;
use App\Models\User;
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
     * @param int $limit Maximum number of shifts to return
     *
     * @return Collection<int, Shift> Shifts with available spots
     */
    public function findNeedingVolunteers(int $limit = 10): Collection
    {
        return Shift::with(['bulletinPost', 'volunteers'])
            ->whereRaw('(offline_filled + (SELECT COUNT(*) FROM shift_volunteers WHERE shift_id = shifts.id)) < needed')
            ->whereHas('bulletinPost', function ($query) {
                $query->published();
            })
            ->orderByRaw("STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(time, ',', 2), ' ', -1), '%d.%m.%Y')")
            ->limit($limit)
            ->get();
    }

    /**
     * Get shifts for a specific user.
     *
     * Retrieves all shifts where the user has volunteered, including
     * the parent bulletin post for context.
     *
     * @param User $user User to get shifts for
     *
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
     * Retrieves all shifts for a bulletin post, parsing and ordering
     * by the German date format in the time field.
     *
     * @param int $bulletinPostId Bulletin post ID
     *
     * @return Collection<int, Shift> Ordered shifts with volunteers
     */
    public function getByBulletinPost(int $bulletinPostId): Collection
    {
        return Shift::where('bulletin_post_id', $bulletinPostId)
            ->with('volunteers.user')
            ->orderByRaw("STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(time, ',', 2), ' ', -1), '%d.%m.%Y')")
            ->get();
    }

    /**
     * Get upcoming shifts within date range.
     *
     * Retrieves shifts occurring within a specified date range,
     * parsing the German date format for comparison.
     *
     * @param \Carbon\Carbon $startDate Range start date
     * @param \Carbon\Carbon $endDate   Range end date
     *
     * @return Collection<int, Shift> Shifts within date range
     */
    public function getInDateRange($startDate, $endDate): Collection
    {
        $startStr = $startDate->format('d.m.Y');
        $endStr = $endDate->format('d.m.Y');

        return Shift::with(['bulletinPost', 'volunteers'])
            ->whereRaw("STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(time, ',', 2), ' ', -1), '%d.%m.%Y') BETWEEN ? AND ?", [$startStr, $endStr])
            ->whereHas('bulletinPost', function ($query) {
                $query->published();
            })
            ->orderByRaw("STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(time, ',', 2), ' ', -1), '%d.%m.%Y')")
            ->get();
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
        return Shift::selectRaw('
                bulletin_posts.category,
                COUNT(shifts.id) as total_shifts,
                SUM(shifts.needed) as total_needed,
                SUM(shifts.offline_filled) as total_offline,
                (SELECT COUNT(*) FROM shift_volunteers WHERE shift_id IN (SELECT id FROM shifts WHERE bulletin_post_id = bulletin_posts.id)) as total_online
            ')
            ->join('bulletin_posts', 'shifts.bulletin_post_id', '=', 'bulletin_posts.id')
            ->where('bulletin_posts.status', 'published')
            ->groupBy('bulletin_posts.category')
            ->get()
            ->map(function ($stat) {
                $totalFilled = $stat->total_offline + $stat->total_online;
                $fillRate = $stat->total_needed > 0
                    ? round(($totalFilled / $stat->total_needed) * 100, 2)
                    : 0;

                return [
                    'category' => $stat->category,
                    'total_shifts' => $stat->total_shifts,
                    'total_needed' => $stat->total_needed,
                    'total_filled' => $totalFilled,
                    'fill_rate' => $fillRate,
                ];
            });
    }

    /**
     * Find shift with all relationships.
     *
     * Retrieves a single shift with all nested relationships loaded.
     *
     * @param int $shiftId Shift ID
     *
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
     * @param User $user    User to get history for
     * @param int  $limit   Maximum records to return
     *
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
}