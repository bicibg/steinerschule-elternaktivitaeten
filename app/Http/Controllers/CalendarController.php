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

        // Get all activities with shifts in the current month
        $activities = Activity::published()
            ->with(['shifts' => function($query) use ($currentYear, $currentMonth) {
                // We'll filter shifts by parsing their time string
            }])
            ->whereHas('shifts')
            ->get();

        // Parse shift dates and filter by month
        $shiftsInMonth = collect();
        foreach ($activities as $activity) {
            foreach ($activity->shifts as $shift) {
                $shiftDate = $this->parseShiftDate($shift->time);
                if ($shiftDate && $shiftDate->year == $currentYear && $shiftDate->month == $currentMonth) {
                    $shift->parsed_date = $shiftDate;
                    $shift->activity = $activity;
                    $shiftsInMonth->push($shift);
                }
            }
        }

        // Group shifts by date
        $shiftsByDate = $shiftsInMonth->groupBy(function($shift) {
            return $shift->parsed_date->format('Y-m-d');
        });

        // Get upcoming shifts
        $upcomingShifts = collect();
        foreach ($activities as $activity) {
            foreach ($activity->shifts as $shift) {
                $shiftDate = $this->parseShiftDate($shift->time);
                if ($shiftDate && $shiftDate->isFuture() && $shift->filled < $shift->needed) {
                    $shift->parsed_date = $shiftDate;
                    $shift->activity = $activity;
                    $upcomingShifts->push($shift);
                }
            }
        }
        $upcomingShifts = $upcomingShifts->sortBy('parsed_date')->take(5);

        return view('calendar.index', compact('shiftsByDate', 'upcomingShifts', 'date', 'currentMonth', 'currentYear', 'activities'));
    }

    private function parseShiftDate($timeString)
    {
        // Parse German date format from shift time string
        // Example: "Samstag, 09.11.2024, 09:00 - 11:00 Uhr"
        if (preg_match('/(\d{2})\.(\d{2})\.(\d{4})/', $timeString, $matches)) {
            return Carbon::createFromFormat('d.m.Y', $matches[0]);
        }

        // For simpler format: "Samstag, 09:00 - 11:00 Uhr"
        // Try to get from activity date
        return null;
    }
}
