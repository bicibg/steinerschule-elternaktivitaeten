<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ModerationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/aktivitaeten');
});

Route::get('/aktivitaeten', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/aktivitaeten/{slug}', [ActivityController::class, 'show'])->name('activities.show');

Route::post('/aktivitaeten/{slug}/posts', [PostController::class, 'store'])->name('posts.store');
Route::post('/posts/{post}/comments', [PostController::class, 'storeComment'])->name('comments.store');

Route::middleware(['verify.edit.token'])->group(function () {
    Route::get('/aktivitaeten/{slug}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/aktivitaeten/{slug}', [ActivityController::class, 'update'])->name('activities.update');

    Route::post('/moderation/posts/{post}/hide', [ModerationController::class, 'togglePost'])->name('moderation.post.toggle');
    Route::post('/moderation/comments/{comment}/hide', [ModerationController::class, 'toggleComment'])->name('moderation.comment.toggle');
});
