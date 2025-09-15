<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::active();

        // Filter by category if provided
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $activities = $query->ordered()->get();

        $categories = Activity::getCategories();
        $selectedCategory = $request->get('category', 'all');

        // Get counts per category
        $categoryCounts = Activity::active()
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        $totalCount = Activity::active()->count();

        return view('activities.index', compact('activities', 'categories', 'selectedCategory', 'categoryCounts', 'totalCount'));
    }

    public function show($slug)
    {
        $activity = Activity::where('slug', $slug)
            ->active()
            ->with(['posts' => function ($query) {
                $query->visible()->with('comments');
            }])
            ->firstOrFail();

        return view('activities.show', compact('activity'));
    }
}