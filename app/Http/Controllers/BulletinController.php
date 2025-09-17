<?php

namespace App\Http\Controllers;

use App\Models\BulletinPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BulletinController extends Controller
{
    public function index(Request $request)
    {
        $query = BulletinPost::published();

        // Filter by category if provided
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $bulletinPosts = $query
            ->orderByRaw("CASE
                WHEN label = 'urgent' THEN 1
                WHEN label = 'important' THEN 2
                WHEN label = 'featured' THEN 3
                WHEN label = 'last_minute' THEN 4
                ELSE 5
            END")
            ->orderByRaw('COALESCE(start_at, end_at) ASC')
            ->with(['posts', 'shifts.volunteers'])
            ->get();

        $categories = BulletinPost::getAvailableCategories();
        $selectedCategory = $request->get('category', 'all');

        // Get counts per category
        $categoryCounts = BulletinPost::published()
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        $totalCount = BulletinPost::published()->count();

        return view('bulletin.index', compact('bulletinPosts', 'categories', 'selectedCategory', 'categoryCounts', 'totalCount'));
    }

    public function show($slug)
    {
        $bulletinPost = BulletinPost::where('slug', $slug)
            ->published()
            ->with([
                'posts' => function ($query) {
                    $query->with('comments');
                },
                'shifts.volunteers.user'
            ])
            ->firstOrFail();

        return view('bulletin.show', compact('bulletinPost'));
    }

    public function edit($slug, Request $request)
    {
        $bulletinPost = BulletinPost::where('slug', $slug)->firstOrFail();

        return view('bulletin.edit', compact('bulletinPost'));
    }

    public function update($slug, Request $request)
    {
        $bulletinPost = BulletinPost::where('slug', $slug)->firstOrFail();

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

        if ($validated['title'] !== $bulletinPost->title) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(6);
        }

        $validated['has_forum'] = $request->has('has_forum');
        $validated['has_shifts'] = $request->has('has_shifts');

        $bulletinPost->update($validated);

        return redirect()->route('bulletin.edit', $bulletinPost->slug)
            ->with('success', 'Eintrag erfolgreich aktualisiert.');
    }
}
