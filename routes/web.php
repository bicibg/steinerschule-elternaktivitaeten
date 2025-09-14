<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/aktivitaeten');
});

Route::get('/debug', function () {
    return view('debug');
});

// Authentication routes
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/demo-login', [\App\Http\Controllers\AuthController::class, 'loginDemo'])->name('demo.login');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/aktivitaeten', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/aktivitaeten/{slug}', [ActivityController::class, 'show'])->name('activities.show');

Route::get('/kalender', [CalendarController::class, 'index'])->name('calendar.index');

Route::post('/aktivitaeten/{slug}/posts', [PostController::class, 'store'])->name('posts.store');
Route::post('/posts/{post}/comments', [PostController::class, 'storeComment'])->name('comments.store');

Route::post('/shifts/{shift}/signup', [ShiftController::class, 'signup'])->name('shifts.signup')->middleware('auth');
Route::delete('/shifts/{shift}/withdraw', [ShiftController::class, 'withdraw'])->name('shifts.withdraw')->middleware('auth');

// API routes for Alpine.js
Route::prefix('api')->group(function () {
    Route::post('/shifts/{shift}/signup', [\App\Http\Controllers\ApiController::class, 'shiftSignup'])->name('api.shifts.signup');
    Route::delete('/shifts/{shift}/withdraw', [\App\Http\Controllers\ApiController::class, 'shiftWithdraw'])->name('api.shifts.withdraw');
    Route::post('/activities/{slug}/posts', [\App\Http\Controllers\ApiController::class, 'storePost'])->name('api.posts.store');
    Route::post('/posts/{post}/comments', [\App\Http\Controllers\ApiController::class, 'storeComment'])->name('api.comments.store');
});

Route::middleware(['verify.edit.token'])->group(function () {
    Route::get('/aktivitaeten/{slug}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/aktivitaeten/{slug}', [ActivityController::class, 'update'])->name('activities.update');

    Route::post('/moderation/posts/{post}/hide', [ModerationController::class, 'togglePost'])->name('moderation.post.toggle');
    Route::post('/moderation/comments/{comment}/hide', [ModerationController::class, 'toggleComment'])->name('moderation.comment.toggle');
});
