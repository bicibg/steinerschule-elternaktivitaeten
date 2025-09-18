<?php

namespace App\Http\Controllers;

use App\Services\CalendarService;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    private CalendarService $calendarService;

    public function __construct(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    public function index(Request $request)
    {
        $currentMonth = (int) $request->get('month', now()->month);
        $currentYear = (int) $request->get('year', now()->year);

        // Get calendar data from service
        $data = $this->calendarService->getCalendarItems($currentMonth, $currentYear);

        // If it's an AJAX request, return only the calendar content
        if ($request->ajax()) {
            return view('calendar.partials.content', $data);
        }

        return view('calendar.index', $data);
    }
}