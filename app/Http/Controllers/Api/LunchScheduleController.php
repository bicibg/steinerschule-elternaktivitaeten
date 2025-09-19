<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LunchShift;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LunchScheduleController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $currentMonth = Carbon::createFromFormat('Y-m', $month);

        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();
        $startOfCalendar = $startOfMonth->copy()->startOfWeek();
        $endOfCalendar = $endOfMonth->copy()->endOfWeek();

        // Get all shifts for the calendar period
        $shifts = LunchShift::with('user')
            ->whereBetween('date', [$startOfCalendar, $endOfCalendar])
            ->get()
            ->map(function ($shift) {
                return [
                    'id' => $shift->id,
                    'date' => $shift->date->format('Y-m-d'),
                    'cook_name' => auth()->check() || $shift->cook_name
                        ? $shift->cook_display_name
                        : ($shift->is_filled ? 'Besetzt' : null),
                    'is_filled' => $shift->is_filled,
                    'expected_meals' => $shift->expected_meals,
                    'notes' => $shift->notes,
                    'is_mine' => auth()->check() && $shift->user_id === auth()->id(),
                    'can_signup' => auth()->check() && !$shift->is_filled && !$shift->isPast(),
                    'can_cancel' => auth()->check() && $shift->user_id === auth()->id() && !$shift->isPast(),
                ];
            })
            ->keyBy('date');

        // Build calendar weeks
        $calendar = [];
        $date = $startOfCalendar->copy();

        while ($date <= $endOfCalendar) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $dateKey = $date->format('Y-m-d');
                $week[] = [
                    'date' => $date->format('Y-m-d'),
                    'day' => $date->day,
                    'isCurrentMonth' => $date->month === $currentMonth->month,
                    'isToday' => $date->isToday(),
                    'isWeekend' => $date->isWeekend(),
                    'isPast' => $date->isPast(),
                    'shift' => $shifts->get($dateKey),
                ];
                $date->addDay();
            }
            $calendar[] = $week;
        }

        // Get upcoming shifts that need volunteers
        $needsVolunteers = LunchShift::with('user')
            ->upcoming()
            ->needingVolunteers()
            ->limit(5)
            ->get()
            ->map(function ($shift) {
                return [
                    'id' => $shift->id,
                    'date' => $shift->date->format('Y-m-d'),
                    'formatted_date' => $shift->short_day_name . ', ' . $shift->date->format('d.m.Y'),
                ];
            });

        // Only allow previous month navigation if it's not in the past
        $previousMonth = $currentMonth->copy()->subMonth();
        $canNavigatePrevious = $previousMonth->endOfMonth()->isFuture() || $previousMonth->endOfMonth()->isToday();

        return response()->json([
            'calendar' => $calendar,
            'currentMonth' => $currentMonth->format('Y-m'),
            'currentMonthFormatted' => $currentMonth->translatedFormat('F Y'),
            'previousMonth' => $canNavigatePrevious ? $previousMonth->format('Y-m') : null,
            'nextMonth' => $currentMonth->copy()->addMonth()->format('Y-m'),
            'needsVolunteers' => $needsVolunteers,
            'canNavigatePrevious' => $canNavigatePrevious,
        ]);
    }

    public function signup(Request $request, LunchShift $shift)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Nicht angemeldet'], 401);
        }

        if ($shift->is_filled) {
            return response()->json(['error' => 'Diese Schicht ist bereits besetzt.'], 422);
        }

        if ($shift->isPast()) {
            return response()->json(['error' => 'Sie können sich nicht für vergangene Tage anmelden.'], 422);
        }

        $shift->signUp(auth()->user());

        return response()->json([
            'success' => true,
            'message' => 'Sie haben sich erfolgreich für den Küchendienst am ' . $shift->date->format('d.m.Y') . ' angemeldet.',
            'shift' => [
                'id' => $shift->id,
                'cook_name' => $shift->cook_display_name,
                'is_filled' => true,
                'is_mine' => true,
            ],
        ]);
    }

    public function cancel(LunchShift $shift)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Nicht angemeldet'], 401);
        }

        if ($shift->user_id !== auth()->id()) {
            return response()->json(['error' => 'Sie können sich nur von Ihren eigenen Diensten abmelden.'], 422);
        }

        if ($shift->isPast()) {
            return response()->json(['error' => 'Sie können sich nicht von vergangenen Diensten abmelden.'], 422);
        }

        $shift->cancelSignup();

        return response()->json([
            'success' => true,
            'message' => 'Sie haben sich erfolgreich abgemeldet.',
            'shift' => [
                'id' => $shift->id,
                'is_filled' => false,
                'cook_name' => null,
            ],
        ]);
    }
}