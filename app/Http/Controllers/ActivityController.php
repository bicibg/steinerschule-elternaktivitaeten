<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::published()
            ->orderByRaw("CASE
                WHEN label = 'urgent' THEN 1
                WHEN label = 'important' THEN 2
                WHEN label = 'help_needed' THEN 3
                WHEN label = 'featured' THEN 4
                WHEN label = 'last_minute' THEN 5
                ELSE 6
            END")
            ->orderByRaw('COALESCE(start_at, end_at) ASC')
            ->with('posts')
            ->get();

        return view('activities.index', compact('activities'));
    }

    public function show($slug)
    {
        $activity = Activity::where('slug', $slug)
            ->published()
            ->with(['posts' => function ($query) {
                $query->with('comments');
            }])
            ->firstOrFail();

        return view('activities.show', compact('activity'));
    }

    public function edit($slug, Request $request)
    {
        $activity = Activity::where('slug', $slug)->firstOrFail();

        return view('activities.edit', compact('activity'));
    }

    public function update($slug, Request $request)
    {
        $activity = Activity::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'location' => 'required|string|max:255',
            'organizer_name' => 'required|string|max:255',
            'organizer_phone' => 'nullable|string|max:50',
            'organizer_email' => 'nullable|email|max:255',
            'status' => 'required|in:published,archived',
        ]);

        if ($validated['title'] !== $activity->title) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(6);
        }

        $validated['has_forum'] = $request->has('has_forum');
        $validated['has_shifts'] = $request->has('has_shifts');

        $activity->update($validated);

        return redirect()->route('activities.edit', $activity->slug)
            ->with('success', 'Aktivität erfolgreich aktualisiert.');
    }
}
