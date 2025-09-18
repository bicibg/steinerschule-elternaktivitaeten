<?php

namespace App\Http\Controllers;

use App\Models\BulletinPost;
use App\Models\Shift;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = (int) $request->get('month', now()->month);
        $currentYear = (int) $request->get('year', now()->year);

        $date = now()->setYear($currentYear)->setMonth($currentMonth)->startOfMonth();
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Get ALL activities that should show in calendar for this month
        $activities = BulletinPost::published()
            ->where('show_in_calendar', true)
            ->where(function($query) use ($startOfMonth, $endOfMonth) {
                // Activities that overlap with this month
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

        // Collect all calendar items (shifts AND activities)
        $calendarItems = collect();

        // Process shift-based activities
        foreach ($activities->where('activity_type', 'shift_based') as $activity) {
            foreach ($activity->shifts as $shift) {
                $shiftDate = $this->parseShiftDate($shift->time);
                if ($shiftDate && $shiftDate->year == $currentYear && $shiftDate->month == $currentMonth) {
                    $calendarItems->push([
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

        // Process production activities (show spanning across dates)
        $productionActivities = collect();
        foreach ($activities->where('activity_type', 'production') as $activity) {
            if ($activity->start_at && $activity->end_at) {
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

                // Only process if activity overlaps with current month
                if ($displayStart <= $endOfMonth && $displayEnd >= $startOfMonth) {
                    $productionActivities->push([
                        'activity' => $activity,
                        'start' => $displayStart,
                        'end' => $displayEnd,
                        'full_start' => $activity->start_at,
                        'full_end' => $activity->end_at,
                    ]);

                    // Add calendar items for each day in the range
                    $current = $displayStart->copy();
                    while ($current <= $displayEnd) {
                        $calendarItems->push([
                            'type' => 'production',
                            'date' => $current->copy(),
                            'title' => $activity->title,
                            'activity' => $activity,
                            'color' => $this->getItemColor($activity),
                            'note' => $activity->participation_note,
                            'date_range' => $displayStart->format('d.m') . '-' . $displayEnd->format('d.m'),
                            'is_start' => $current->isSameDay($displayStart),
                            'is_end' => $current->isSameDay($displayEnd),
                            'is_middle' => !$current->isSameDay($displayStart) && !$current->isSameDay($displayEnd),
                        ]);
                        $current->addDay();
                    }
                }
            }
        }

        // Process meeting activities (show on recurring days)
        foreach ($activities->where('activity_type', 'meeting') as $activity) {
            if ($activity->recurring_pattern && $activity->start_at) {
                $dates = $this->getRecurringDates($activity, $startOfMonth, $endOfMonth);
                foreach ($dates as $meetingDate) {
                    $calendarItems->push([
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

        // Process flexible help activities (spanning activities)
        foreach ($activities->where('activity_type', 'flexible_help') as $activity) {
            if ($activity->start_at && $activity->end_at) {
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

                // Only process if activity overlaps with current month
                if ($displayStart <= $endOfMonth && $displayEnd >= $startOfMonth) {
                    // Add calendar items for each day in the range
                    $current = $displayStart->copy();
                    while ($current <= $displayEnd) {
                        $calendarItems->push([
                            'type' => 'flexible',
                            'date' => $current->copy(),
                            'title' => $activity->title,
                            'activity' => $activity,
                            'color' => $this->getItemColor($activity),
                            'note' => 'Flexible Hilfe',
                            'date_range' => $displayStart->format('d.m') . '-' . $displayEnd->format('d.m'),
                            'is_start' => $current->isSameDay($displayStart),
                            'is_end' => $current->isSameDay($displayEnd),
                            'is_middle' => !$current->isSameDay($displayStart) && !$current->isSameDay($displayEnd),
                        ]);
                        $current->addDay();
                    }
                }
            } elseif ($activity->start_at) {
                // Single day flexible activity
                $activityDate = $activity->start_at->copy();
                if ($activityDate->month == $currentMonth && $activityDate->year == $currentYear) {
                    $calendarItems->push([
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

        // Group items by date
        $itemsByDate = $calendarItems->groupBy(function($item) {
            return $item['date']->format('Y-m-d');
        });

        // Get upcoming items needing help
        $upcomingItems = $calendarItems
            ->filter(function($item) {
                if ($item['type'] === 'shift' && isset($item['shift'])) {
                    return $item['date']->isFuture() && !$item['shift']->is_full;
                }
                return $item['date']->isFuture();
            })
            ->sortBy('date')
            ->take(10);

        // If it's an AJAX request, return only the calendar content
        if ($request->ajax()) {
            return view('calendar.partials.content', compact('itemsByDate', 'upcomingItems', 'date', 'currentMonth', 'currentYear', 'activities', 'productionActivities'));
        }

        return view('calendar.index', compact('itemsByDate', 'upcomingItems', 'date', 'currentMonth', 'currentYear', 'activities', 'productionActivities'));
    }

    private function parseShiftDate($timeString)
    {
        // Parse German date format from shift time string
        // Example: "Samstag, 09.11.2024, 09:00 - 11:00 Uhr"
        if (preg_match('/(\d{2})\.(\d{2})\.(\d{4})/', $timeString, $matches)) {
            return Carbon::createFromFormat('d.m.Y', $matches[0]);
        }
        return null;
    }

    private function getRecurringDates($activity, $startOfMonth, $endOfMonth)
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

    private function getItemColor($activity, $shiftRole = null)
    {
        // Define available colors palette
        $colors = [
            'bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500',
            'bg-pink-500', 'bg-indigo-500', 'bg-orange-500', 'bg-teal-500', 'bg-cyan-500',
            'bg-amber-500', 'bg-lime-500', 'bg-emerald-500', 'bg-sky-500', 'bg-violet-500',
            'bg-fuchsia-500', 'bg-rose-500', 'bg-slate-500', 'bg-gray-500', 'bg-stone-500',
            'bg-red-600', 'bg-blue-600', 'bg-green-600', 'bg-yellow-600', 'bg-purple-600',
            'bg-pink-600', 'bg-indigo-600', 'bg-orange-600', 'bg-teal-600', 'bg-cyan-600',
        ];

        // Generate a consistent hash based on activity ID (so color stays same for each activity)
        // Using CRC32 for better distribution than simple modulo
        $hash = crc32($activity->id . $activity->title);
        $colorIndex = abs($hash) % count($colors);

        return $colors[$colorIndex];
    }
}
