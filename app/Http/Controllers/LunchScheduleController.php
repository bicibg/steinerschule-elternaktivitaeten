<?php

namespace App\Http\Controllers;

use App\Models\LunchShift;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LunchScheduleController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = Carbon::now();

        if ($request->has('month')) {
            $currentMonth = Carbon::createFromFormat('Y-m', $request->month);
        }

        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();
        $startOfCalendar = $startOfMonth->copy()->startOfWeek();
        $endOfCalendar = $endOfMonth->copy()->endOfWeek();

        // Get all shifts for the calendar period
        $shifts = LunchShift::with('user')
            ->whereBetween('date', [$startOfCalendar, $endOfCalendar])
            ->get()
            ->keyBy(function ($shift) {
                return $shift->date->format('Y-m-d');
            });

        // Build calendar weeks
        $calendar = [];
        $date = $startOfCalendar->copy();

        while ($date <= $endOfCalendar) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $dateKey = $date->format('Y-m-d');
                $week[] = [
                    'date' => $date->copy(),
                    'isCurrentMonth' => $date->month === $currentMonth->month,
                    'isToday' => $date->isToday(),
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
            ->get();

        return view('lunch-schedule.index', compact(
            'calendar',
            'currentMonth',
            'needsVolunteers'
        ));
    }

    public function signup(Request $request, LunchShift $shift)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Sie müssen angemeldet sein, um sich für den Küchendienst anzumelden.');
        }

        if ($shift->is_filled) {
            return back()->with('error', 'Diese Schicht ist bereits besetzt.');
        }

        if ($shift->isPast()) {
            return back()->with('error', 'Sie können sich nicht für vergangene Tage anmelden.');
        }

        $shift->signUp(auth()->user());

        return back()->with('success', 'Sie haben sich erfolgreich für den Küchendienst am ' . $shift->date->format('d.m.Y') . ' angemeldet.');
    }

    public function cancel(LunchShift $shift)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if ($shift->user_id !== auth()->id()) {
            return back()->with('error', 'Sie können sich nur von Ihren eigenen Diensten abmelden.');
        }

        if ($shift->isPast()) {
            return back()->with('error', 'Sie können sich nicht von vergangenen Diensten abmelden.');
        }

        $shift->cancelSignup();

        return back()->with('success', 'Sie haben sich erfolgreich abgemeldet.');
    }
}
