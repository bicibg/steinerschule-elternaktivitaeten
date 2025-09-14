<?php

namespace App\Http\Controllers;

use App\Models\Activity;
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
        $activities = Activity::published()
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
            ->with('shifts')
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

        // Process production activities (show on every day within range)
        foreach ($activities->where('activity_type', 'production') as $activity) {
            if ($activity->start_at && $activity->end_at) {
                $current = $activity->start_at->copy()->startOfDay();
                $end = $activity->end_at->copy()->endOfDay();

                while ($current <= $end) {
                    if ($current->month == $currentMonth && $current->year == $currentYear) {
                        $calendarItems->push([
                            'type' => 'production',
                            'date' => $current->copy(),
                            'title' => $activity->title,
                            'activity' => $activity,
                            'color' => 'bg-yellow-400',
                            'note' => $activity->participation_note,
                        ]);
                    }
                    $current->addDay();
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

        // Process flexible help activities
        foreach ($activities->where('activity_type', 'flexible_help') as $activity) {
            if ($activity->start_at) {
                $activityDate = $activity->start_at->copy();
                if ($activityDate->month == $currentMonth && $activityDate->year == $currentYear) {
                    $calendarItems->push([
                        'type' => 'flexible',
                        'date' => $activityDate,
                        'title' => $activity->title,
                        'activity' => $activity,
                        'color' => 'bg-green-400',
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
                    return $item['date']->isFuture() &&
                           (!$item['shift']->needed || $item['shift']->filled < $item['shift']->needed);
                }
                return $item['date']->isFuture();
            })
            ->sortBy('date')
            ->take(10);

        return view('calendar.index', compact('itemsByDate', 'upcomingItems', 'date', 'currentMonth', 'currentYear', 'activities'));
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
        // Define colors for different activities/shifts
        $colors = [
            'Helfer für Märit - Aufbau und Standbetreuung' => [
                'Aufbau Freitag' => 'bg-blue-500',
                'Blumenstand Vormittag' => 'bg-green-500',
                'Cafeteria-Team' => 'bg-yellow-500',
                'Kinderbetreuung' => 'bg-purple-500',
                'Abbau-Team' => 'bg-red-500',
            ],
            'Helferteam für Kerzenziehen gesucht' => [
                'Wachsvorbereitung' => 'bg-indigo-500',
                'Betreuung Kerzenzieh-Station' => 'bg-pink-500',
                'Verkaufsstand' => 'bg-teal-500',
                'Aufräumen und Reinigung' => 'bg-orange-500',
            ],
            'Helfer für Adventskranzbinden' => [
                'Material vorbereiten' => 'bg-cyan-500',
                'Kranzbinden Donnerstag' => 'bg-lime-500',
            ],
            'Team für Elternkafi am Schulsamstag' => [
                'Kafi-Aufbau' => 'bg-amber-500',
                'Kafi-Betreuung Vormittag' => 'bg-rose-500',
            ],
        ];

        if ($shiftRole && isset($colors[$activity->title][$shiftRole])) {
            return $colors[$activity->title][$shiftRole];
        }

        return 'bg-gray-500';
    }
}