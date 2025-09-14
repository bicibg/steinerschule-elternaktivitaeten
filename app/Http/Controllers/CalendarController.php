<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = $request->get('month', now()->month);
        $currentYear = $request->get('year', now()->year);

        $date = now()->setYear($currentYear)->setMonth($currentMonth)->startOfMonth();

        $events = CalendarEvent::whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $upcomingEvents = CalendarEvent::upcoming()->take(5)->get();

        return view('calendar.index', compact('events', 'upcomingEvents', 'date', 'currentMonth', 'currentYear'));
    }
}
