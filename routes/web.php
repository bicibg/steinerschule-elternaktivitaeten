<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/aktivitaeten');
});

// Authentication routes
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/demo-login', [\App\Http\Controllers\AuthController::class, 'loginDemo'])->name('demo.login');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::get('/aktivitaeten', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/aktivitaeten/{slug}', [ActivityController::class, 'show'])->name('activities.show');

Route::post('/aktivitaeten/{slug}/posts', [PostController::class, 'store'])->name('posts.store');
Route::post('/posts/{post}/comments', [PostController::class, 'storeComment'])->name('comments.store');

Route::post('/shifts/{shift}/signup', [ShiftController::class, 'signup'])->name('shifts.signup')->middleware('auth');
Route::delete('/shifts/{shift}/withdraw', [ShiftController::class, 'withdraw'])->name('shifts.withdraw')->middleware('auth');

Route::middleware(['verify.edit.token'])->group(function () {
    Route::get('/aktivitaeten/{slug}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/aktivitaeten/{slug}', [ActivityController::class, 'update'])->name('activities.update');

    Route::post('/moderation/posts/{post}/hide', [ModerationController::class, 'togglePost'])->name('moderation.post.toggle');
    Route::post('/moderation/comments/{comment}/hide', [ModerationController::class, 'toggleComment'])->name('moderation.comment.toggle');
});
