<?php

namespace App\Services;

use App\Models\BulletinPost;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CalendarService
{
    /**
     * Get calendar items for a specific month and year.
     *
     * Processes all activities and shifts for the given month, organizing them
     * into calendar items grouped by date. Handles different activity types:
     * shifts, production, meetings, and flexible help.
     *
     * @param int $month Month number (1-12)
     * @param int $year  Year (e.g., 2024)
     *
     * @return array{
     *     itemsByDate: Collection,
     *     upcomingItems: Collection,
     *     date: Carbon,
     *     currentMonth: int,
     *     currentYear: int,
     *     activities: Collection,
     *     productionActivities: Collection
     * }
     */
    public function getCalendarItems(int $month, int $year): array
    {
        $date = now()->setYear($year)->setMonth($month)->startOfMonth();
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Get activities for this month
        $activities = $this->getActivitiesForMonth($startOfMonth, $endOfMonth);

        // Collect all calendar items
        $calendarItems = collect();

        // Process different activity types
        $calendarItems = $calendarItems->merge($this->processShiftActivities($activities, $year, $month));
        $calendarItems = $calendarItems->merge($this->processProductionActivities($activities, $startOfMonth, $endOfMonth));
        $calendarItems = $calendarItems->merge($this->processMeetingActivities($activities, $startOfMonth, $endOfMonth));
        $calendarItems = $calendarItems->merge($this->processFlexibleActivities($activities, $startOfMonth, $endOfMonth, $year, $month));

        // Group items by date
        $itemsByDate = $calendarItems->groupBy(function($item) {
            return $item['date']->format('Y-m-d');
        });

        // Get upcoming items
        $upcomingItems = $this->getUpcomingItems($calendarItems);

        // Get production activities for display
        $productionActivities = $this->getProductionActivitiesForDisplay($activities, $startOfMonth, $endOfMonth);

        return [
            'itemsByDate' => $itemsByDate,
            'upcomingItems' => $upcomingItems,
            'date' => $date,
            'currentMonth' => $month,
            'currentYear' => $year,
            'activities' => $activities,
            'productionActivities' => $productionActivities,
        ];
    }

    /**
     * Get activities that should appear in calendar for given month.
     *
     * Fetches all published activities that:
     * - Have show_in_calendar enabled
     * - Overlap with the given month period
     * - Includes eager loading of shifts and volunteers
     *
     * @param Carbon $startOfMonth First day of month at 00:00:00
     * @param Carbon $endOfMonth   Last day of month at 23:59:59
     *
     * @return Collection<int, BulletinPost> Activities with loaded relationships
     */
    private function getActivitiesForMonth(Carbon $startOfMonth, Carbon $endOfMonth): Collection
    {
        return BulletinPost::published()
            ->where('show_in_calendar', true)
            ->where(function($query) use ($startOfMonth, $endOfMonth) {
                $query->where(function($q) use ($startOfMonth, $endOfMonth) {
                    $q->whereBetween('start_at', [$startOfMonth, $endOfMonth])
                      ->orWhereBetween('end_at', [$startOfMonth, $endOfMonth])
                      ->orWhere(function($q2) use ($startOfMonth, $endOfMonth) {
                          $q2->where('start_at', '<=', $startOfMonth)
                             ->where('end_at', '>=', $endOfMonth);
                      });
                });
            })
            ->with(['shifts.volunteers'])
            ->get();
    }

    /**
     * Process shift-based activities into calendar items.
     *
     * Converts each shift of shift-based activities into individual calendar items.
     * Parses German date format from shift time strings.
     *
     * @param Collection $activities Collection of BulletinPost models
     * @param int        $year       Target year
     * @param int        $month      Target month (1-12)
     *
     * @return Collection<int, array> Calendar items for shifts
     */
    private function processShiftActivities(Collection $activities, int $year, int $month): Collection
    {
        $items = collect();

        foreach ($activities->where('activity_type', 'shift_based') as $activity) {
            foreach ($activity->shifts as $shift) {
                $shiftDate = $this->parseShiftDate($shift->time);
                if ($shiftDate && $shiftDate->year == $year && $shiftDate->month == $month) {
                    $items->push([
                        'type' => 'shift',
                        'date' => $shiftDate,
                        'title' => $shift->role,
                        'activity' => $activity,
                        'shift' => $shift,
                        'color' => $this->getItemColor($activity, $shift->role),
                    ]);
                }
            }
        }

        return $items;
    }

    /**
     * Process production activities (spanning across dates)
     */
    private function processProductionActivities(Collection $activities, Carbon $startOfMonth, Carbon $endOfMonth): Collection
    {
        $items = collect();

        foreach ($activities->where('activity_type', 'production') as $activity) {
            if ($activity->start_at && $activity->end_at) {
                $range = $this->calculateDisplayRange($activity, $startOfMonth, $endOfMonth);

                if ($range) {
                    $items = $items->merge($this->createSpanningItems(
                        $activity,
                        $range['displayStart'],
                        $range['displayEnd'],
                        'production',
                        $activity->participation_note
                    ));
                }
            }
        }

        return $items;
    }

    /**
     * Process meeting activities (recurring)
     */
    private function processMeetingActivities(Collection $activities, Carbon $startOfMonth, Carbon $endOfMonth): Collection
    {
        $items = collect();

        foreach ($activities->where('activity_type', 'meeting') as $activity) {
            if ($activity->recurring_pattern && $activity->start_at) {
                $dates = $this->getRecurringDates($activity, $startOfMonth, $endOfMonth);
                foreach ($dates as $meetingDate) {
                    $items->push([
                        'type' => 'meeting',
                        'date' => $meetingDate,
                        'title' => $activity->title,
                        'activity' => $activity,
                        'color' => 'bg-blue-400',
                        'note' => $activity->recurring_pattern,
                    ]);
                }
            }
        }

        return $items;
    }

    /**
     * Process flexible help activities
     */
    private function processFlexibleActivities(Collection $activities, Carbon $startOfMonth, Carbon $endOfMonth, int $year, int $month): Collection
    {
        $items = collect();

        foreach ($activities->where('activity_type', 'flexible_help') as $activity) {
            if ($activity->start_at && $activity->end_at) {
                $range = $this->calculateDisplayRange($activity, $startOfMonth, $endOfMonth);

                if ($range) {
                    $items = $items->merge($this->createSpanningItems(
                        $activity,
                        $range['displayStart'],
                        $range['displayEnd'],
                        'flexible',
                        'Flexible Hilfe'
                    ));
                }
            } elseif ($activity->start_at) {
                // Single day flexible activity
                $activityDate = $activity->start_at->copy();
                if ($activityDate->month == $month && $activityDate->year == $year) {
                    $items->push([
                        'type' => 'flexible',
                        'date' => $activityDate,
                        'title' => $activity->title,
                        'activity' => $activity,
                        'color' => $this->getItemColor($activity),
                        'note' => 'Flexible Hilfe',
                    ]);
                }
            }
        }

        return $items;
    }

    /**
     * Calculate display range for spanning activities within current month.
     *
     * Determines the visible date range for activities that span multiple days,
     * clipping to month boundaries when necessary.
     *
     * @param BulletinPost $activity     Activity with start_at and end_at dates
     * @param Carbon       $startOfMonth First day of display month
     * @param Carbon       $endOfMonth   Last day of display month
     *
     * @return array{displayStart: Carbon, displayEnd: Carbon}|null Range or null if no overlap
     */
    private function calculateDisplayRange($activity, Carbon $startOfMonth, Carbon $endOfMonth): ?array
    {
        $actStart = $activity->start_at->copy()->startOfDay();
        $actEnd = $activity->end_at->copy()->endOfDay();

        // Calculate display range within current month
        $displayStart = $actStart->copy();
        if ($displayStart < $startOfMonth) {
            $displayStart = $startOfMonth->copy();
        }

        $displayEnd = $actEnd->copy();
        if ($displayEnd > $endOfMonth) {
            $displayEnd = $endOfMonth->copy();
        }

        // Only return if activity overlaps with current month
        if ($displayStart <= $endOfMonth && $displayEnd >= $startOfMonth) {
            return [
                'displayStart' => $displayStart,
                'displayEnd' => $displayEnd,
            ];
        }

        return null;
    }

    /**
     * Create calendar items for spanning activities
     */
    private function createSpanningItems($activity, Carbon $displayStart, Carbon $displayEnd, string $type, ?string $note = null): Collection
    {
        $items = collect();
        $current = $displayStart->copy();

        while ($current <= $displayEnd) {
            $items->push([
                'type' => $type,
                'date' => $current->copy(),
                'title' => $activity->title,
                'activity' => $activity,
                'color' => $this->getItemColor($activity),
                'note' => $note,
                'date_range' => $displayStart->format('d.m') . '-' . $displayEnd->format('d.m'),
                'is_start' => $current->isSameDay($displayStart),
                'is_end' => $current->isSameDay($displayEnd),
                'is_middle' => !$current->isSameDay($displayStart) && !$current->isSameDay($displayEnd),
            ]);
            $current->addDay();
        }

        return $items;
    }

    /**
     * Get production activities for calendar display
     */
    private function getProductionActivitiesForDisplay(Collection $activities, Carbon $startOfMonth, Carbon $endOfMonth): Collection
    {
        $productionActivities = collect();

        foreach ($activities->where('activity_type', 'production') as $activity) {
            if ($activity->start_at && $activity->end_at) {
                $range = $this->calculateDisplayRange($activity, $startOfMonth, $endOfMonth);

                if ($range) {
                    $productionActivities->push([
                        'activity' => $activity,
                        'start' => $range['displayStart'],
                        'end' => $range['displayEnd'],
                        'full_start' => $activity->start_at,
                        'full_end' => $activity->end_at,
                    ]);
                }
            }
        }

        return $productionActivities;
    }

    /**
     * Get upcoming items that need help
     */
    private function getUpcomingItems(Collection $calendarItems): Collection
    {
        return $calendarItems
            ->filter(function($item) {
                if ($item['type'] === 'shift' && isset($item['shift'])) {
                    return $item['date']->isFuture() && !$item['shift']->is_full;
                }
                return $item['date']->isFuture();
            })
            ->sortBy('date')
            ->take(10);
    }

    /**
     * Parse German date format from shift time string.
     *
     * Extracts date from German formatted shift times.
     * Example input: "Samstag, 09.11.2024, 09:00 - 11:00 Uhr"
     *
     * @param string $timeString Shift time string in German format
     *
     * @return Carbon|null Parsed date or null if parsing fails
     */
    private function parseShiftDate($timeString): ?Carbon
    {
        // Example: "Samstag, 09.11.2024, 09:00 - 11:00 Uhr"
        if (preg_match('/(\d{2})\.(\d{2})\.(\d{4})/', $timeString, $matches)) {
            return Carbon::createFromFormat('d.m.Y', $matches[0]);
        }
        return null;
    }

    /**
     * Get recurring dates based on pattern
     */
    private function getRecurringDates($activity, Carbon $startOfMonth, Carbon $endOfMonth): Collection
    {
        $dates = collect();
        $pattern = strtolower($activity->recurring_pattern);

        // Parse patterns like "jeden Donnerstag", "every Thursday", "wöchentlich"
        if (str_contains($pattern, 'donnerstag') || str_contains($pattern, 'thursday')) {
            $current = $startOfMonth->copy()->next('Thursday');
            while ($current <= $endOfMonth) {
                if ($activity->start_at <= $current && (!$activity->end_at || $activity->end_at >= $current)) {
                    $dates->push($current->copy());
                }
                $current->addWeek();
            }
        }
        // Add more pattern parsing as needed

        return $dates;
    }

    /**
     * Get consistent color for an activity.
     *
     * Generates a deterministic color based on activity ID and title.
     * Uses CRC32 hash for better distribution across color palette.
     * Same activity always gets same color.
     *
     * @param BulletinPost $activity  Activity model
     * @param string|null  $shiftRole Optional shift role (currently unused)
     *
     * @return string Tailwind CSS color class (e.g., 'bg-blue-500')
     */
    private function getItemColor($activity, $shiftRole = null): string
    {
        $colors = [
            'bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500',
            'bg-pink-500', 'bg-indigo-500', 'bg-orange-500', 'bg-teal-500', 'bg-cyan-500',
            'bg-amber-500', 'bg-lime-500', 'bg-emerald-500', 'bg-sky-500', 'bg-violet-500',
            'bg-fuchsia-500', 'bg-rose-500', 'bg-slate-500', 'bg-gray-500', 'bg-stone-500',
            'bg-red-600', 'bg-blue-600', 'bg-green-600', 'bg-yellow-600', 'bg-purple-600',
            'bg-pink-600', 'bg-indigo-600', 'bg-orange-600', 'bg-teal-600', 'bg-cyan-600',
        ];

        // Generate a consistent hash based on activity ID
        $hash = crc32($activity->id . $activity->title);
        $colorIndex = abs($hash) % count($colors);

        return $colors[$colorIndex];
    }
}