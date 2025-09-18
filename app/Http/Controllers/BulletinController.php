<?php

namespace App\Http\Controllers;

use App\Models\BulletinPost;
use App\Http\Requests\UpdateBulletinRequest;
use App\Repositories\BulletinPostRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BulletinController extends Controller
{
    protected BulletinPostRepository $repository;

    public function __construct(BulletinPostRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        $selectedCategory = $request->get('category', 'all');

        $bulletinPosts = $this->repository->getActiveWithPriority(
            $selectedCategory === 'all' ? null : $selectedCategory
        );

        $categories = BulletinPost::getAvailableCategories();
        $categoryCounts = $this->repository->getCategoryCounts();
        $totalCount = $this->repository->getTotalPublishedCount();

        return view('bulletin.index', compact('bulletinPosts', 'categories', 'selectedCategory', 'categoryCounts', 'totalCount'));
    }

    public function show($slug)
    {
        $bulletinPost = $this->repository->findPublishedBySlug($slug);

        if (!$bulletinPost) {
            abort(404);
        }

        return view('bulletin.show', compact('bulletinPost'));
    }

    public function edit($slug, Request $request)
    {
        $bulletinPost = $this->repository->findBySlug($slug);

        if (!$bulletinPost) {
            abort(404);
        }

        return view('bulletin.edit', compact('bulletinPost'));
    }

    public function update($slug, UpdateBulletinRequest $request)
    {
        $bulletinPost = $this->repository->findBySlug($slug);

        if (!$bulletinPost) {
            abort(404);
        }

        $validated = $request->validated();

        if ($validated['title'] !== $bulletinPost->title) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(6);
        }

        $bulletinPost->update($validated);

        return redirect()->route('bulletin.edit', $bulletinPost->slug)
            ->with('success', 'Eintrag erfolgreich aktualisiert.');
    }
}
