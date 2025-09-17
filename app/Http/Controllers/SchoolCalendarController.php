<?php

namespace App\Http\Controllers;

use App\Models\SchoolEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SchoolCalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $date = Carbon::createFromDate($year, $month, 1);
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Get all events that overlap with this month
        $events = SchoolEvent::where(function ($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                  ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                  ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                      $q->where('start_date', '<=', $startOfMonth)
                        ->where('end_date', '>=', $endOfMonth);
                  });
        })->orderBy('start_date')->get();

        // Organize events by date for the calendar grid
        $eventsByDate = collect();

        foreach ($events as $event) {
            $current = $event->start_date->copy()->startOfDay();
            $end = $event->end_date ? $event->end_date->copy()->endOfDay() : $current->copy()->endOfDay();

            while ($current <= $end) {
                $dateKey = $current->format('Y-m-d');

                if (!$eventsByDate->has($dateKey)) {
                    $eventsByDate->put($dateKey, collect());
                }

                $eventsByDate->get($dateKey)->push([
                    'event' => $event,
                    'is_start' => $current->isSameDay($event->start_date),
                    'is_end' => $event->end_date && $current->isSameDay($event->end_date),
                    'is_middle' => !$current->isSameDay($event->start_date) &&
                                  ($event->end_date && !$current->isSameDay($event->end_date)),
                ]);

                $current->addDay();
            }
        }

        // If it's an AJAX request, return only the calendar content
        if ($request->ajax()) {
            return view('school-calendar.partials.content', compact('date', 'eventsByDate', 'events', 'month', 'year'));
        }

        return view('school-calendar.index', compact('date', 'eventsByDate', 'events', 'month', 'year'));
    }

    public function show(SchoolEvent $schoolEvent)
    {
        return view('school-calendar.show', compact('schoolEvent'));
    }

    public function create()
    {
        if (!auth()->user() || !auth()->user()->is_super_admin) {
            abort(403);
        }

        return view('school-calendar.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user() || !auth()->user()->is_super_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'event_type' => 'nullable|string',
            'all_day' => 'boolean',
        ]);

        $event = SchoolEvent::create($validated);

        return redirect()->route('school-calendar.index')
                        ->with('success', 'Veranstaltung wurde erfolgreich erstellt.');
    }

    public function edit(SchoolEvent $schoolEvent)
    {
        if (!auth()->user() || !auth()->user()->is_super_admin) {
            abort(403);
        }

        return view('school-calendar.edit', compact('schoolEvent'));
    }

    public function update(Request $request, SchoolEvent $schoolEvent)
    {
        if (!auth()->user() || !auth()->user()->is_super_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'event_type' => 'nullable|string',
            'all_day' => 'boolean',
        ]);

        $schoolEvent->update($validated);

        return redirect()->route('school-calendar.index')
                        ->with('success', 'Veranstaltung wurde erfolgreich aktualisiert.');
    }

    public function destroy(SchoolEvent $schoolEvent)
    {
        if (!auth()->user() || !auth()->user()->is_super_admin) {
            abort(403);
        }

        $schoolEvent->delete();

        return redirect()->route('school-calendar.index')
                        ->with('success', 'Veranstaltung wurde erfolgreich gelöscht.');
    }
}