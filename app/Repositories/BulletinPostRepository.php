<?php

namespace App\Repositories;

use App\Models\BulletinPost;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class BulletinPostRepository
{
    /**
     * Get bulletin posts with priority ordering and eager loading.
     *
     * Retrieves active bulletin posts ordered by priority labels (urgent first)
     * and date, with optional category filtering. Includes eager loading of
     * related posts and shift volunteers for performance.
     *
     * @param string|null $category Optional category filter
     *
     * @return Collection<int, BulletinPost> Ordered bulletin posts with relationships
     */
    public function getActiveWithPriority(?string $category = null): Collection
    {
        $query = BulletinPost::active();

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        return $query
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
    }

    /**
     * Get published post by slug with all relationships.
     *
     * Retrieves a single published bulletin post including nested relationships:
     * posts with comments and shifts with volunteers and users.
     *
     * @param string $slug URL slug of the bulletin post
     *
     * @return BulletinPost|null Post with loaded relationships or null
     */
    public function findPublishedBySlug(string $slug): ?BulletinPost
    {
        return BulletinPost::where('slug', $slug)
            ->published()
            ->with([
                'posts' => function ($query) {
                    $query->with('comments');
                },
                'shifts.volunteers.user'
            ])
            ->first();
    }

    /**
     * Get category statistics for published posts.
     *
     * Calculates the count of published posts per category.
     * Returns an associative array with category names as keys.
     *
     * @return array<string, int> Category counts indexed by category name
     */
    public function getCategoryCounts(): array
    {
        return BulletinPost::published()
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();
    }

    /**
     * Get total count of published posts.
     *
     * @return int Total number of published bulletin posts
     */
    public function getTotalPublishedCount(): int
    {
        return BulletinPost::published()->count();
    }

    /**
     * Find post by slug for editing.
     *
     * Simple retrieval without eager loading, used for edit forms.
     *
     * @param string $slug URL slug of the bulletin post
     *
     * @return BulletinPost|null Post or null if not found
     */
    public function findBySlug(string $slug): ?BulletinPost
    {
        return BulletinPost::where('slug', $slug)->first();
    }

    /**
     * Get posts for calendar display.
     *
     * Retrieves published posts that should appear in the calendar view,
     * with eager loaded shifts and volunteers for availability display.
     *
     * @param bool $withRelations Whether to eager load relationships
     *
     * @return Collection<int, BulletinPost> Calendar-enabled posts
     */
    public function getCalendarPosts(bool $withRelations = true): Collection
    {
        $query = BulletinPost::published()
            ->where('show_in_calendar', true);

        if ($withRelations) {
            $query->with(['shifts.volunteers']);
        }

        return $query->get();
    }

    /**
     * Get upcoming posts needing volunteers.
     *
     * Retrieves posts with unfilled shifts starting in the future,
     * useful for volunteer recruitment displays.
     *
     * @param int $limit Maximum number of posts to return
     *
     * @return Collection<int, BulletinPost> Posts with available volunteer spots
     */
    public function getUpcomingNeedingHelp(int $limit = 5): Collection
    {
        return BulletinPost::published()
            ->whereHas('shifts', function ($query) {
                $query->whereRaw('(offline_filled + (SELECT COUNT(*) FROM shift_volunteers WHERE shift_id = shifts.id)) < needed');
            })
            ->where('start_at', '>', now())
            ->orderBy('start_at')
            ->limit($limit)
            ->with('shifts.volunteers')
            ->get();
    }

    /**
     * Search posts by keyword.
     *
     * Performs a text search across title, description, and participation note.
     *
     * @param string $keyword Search term
     * @param int    $limit   Maximum results to return
     *
     * @return Collection<int, BulletinPost> Matching posts
     */
    public function searchByKeyword(string $keyword, int $limit = 20): Collection
    {
        $searchTerm = '%' . $keyword . '%';

        return BulletinPost::published()
            ->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm)
                    ->orWhere('participation_note', 'like', $searchTerm);
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get posts by category with pagination support.
     *
     * @param string $category Category to filter by
     * @param int    $perPage  Items per page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateByCategory(string $category, int $perPage = 15)
    {
        return BulletinPost::published()
            ->where('category', $category)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}