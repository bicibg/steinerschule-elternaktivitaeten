<?php

use App\Http\Controllers\BulletinController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SchoolCalendarController;
use App\Http\Controllers\LegalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/pinnwand');
});


// Authentication routes
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->middleware(['throttle:5,1', 'guest']);
Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->middleware('guest');
Route::post('/demo-login', [\App\Http\Controllers\AuthController::class, 'loginDemo'])->name('demo.login')->middleware('guest');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request')->middleware('guest');
Route::post('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('guest');
Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset')->middleware('guest');
Route::post('/reset-password', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update')->middleware('guest');

Route::get('/pinnwand', [BulletinController::class, 'index'])->name('bulletin.index');
Route::get('/pinnwand/{slug}', [BulletinController::class, 'show'])->name('bulletin.show');

Route::get('/kalender', [CalendarController::class, 'index'])->name('calendar.index');

// Activity routes
Route::get('/elternaktivitaeten', [App\Http\Controllers\ActivityController::class, 'index'])->name('activities.index');
Route::get('/elternaktivitaeten/{slug}', [App\Http\Controllers\ActivityController::class, 'show'])->name('activities.show');
Route::post('/elternaktivitaeten/{slug}/posts', [App\Http\Controllers\ActivityPostController::class, 'store'])->name('activity-posts.store')->middleware('auth');
Route::post('/activity-posts/{post}/comments', [App\Http\Controllers\ActivityPostController::class, 'storeComment'])->name('activity-comments.store')->middleware('auth');

// School Calendar routes
Route::get('/schulkalender', [SchoolCalendarController::class, 'index'])->name('school-calendar.index');
Route::middleware(['auth'])->group(function () {
    Route::get('/schulkalender/create', [SchoolCalendarController::class, 'create'])->name('school-calendar.create');
    Route::post('/schulkalender', [SchoolCalendarController::class, 'store'])->name('school-calendar.store');
    Route::get('/schulkalender/{schoolEvent}/edit', [SchoolCalendarController::class, 'edit'])->name('school-calendar.edit');
    Route::put('/schulkalender/{schoolEvent}', [SchoolCalendarController::class, 'update'])->name('school-calendar.update');
    Route::delete('/schulkalender/{schoolEvent}', [SchoolCalendarController::class, 'destroy'])->name('school-calendar.destroy');
});
Route::get('/schulkalender/{schoolEvent}', [SchoolCalendarController::class, 'show'])->name('school-calendar.show');

Route::post('/pinnwand/{slug}/posts', [PostController::class, 'store'])->name('posts.store');
Route::post('/posts/{post}/comments', [PostController::class, 'storeComment'])->name('comments.store');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy')->middleware('auth');
Route::delete('/comments/{comment}', [PostController::class, 'destroyComment'])->name('comments.destroy')->middleware('auth');

Route::post('/shifts/{shift}/signup', [ShiftController::class, 'signup'])->name('shifts.signup')->middleware('auth');
Route::delete('/shifts/{shift}/withdraw', [ShiftController::class, 'withdraw'])->name('shifts.withdraw')->middleware('auth');

// Profile routes
Route::get('/profile/{user}', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/my-shifts', [App\Http\Controllers\ProfileController::class, 'shifts'])->name('profile.shifts');
});

// API routes for Alpine.js
Route::prefix('api')->group(function () {
    Route::post('/shifts/{shift}/signup', [\App\Http\Controllers\ApiController::class, 'shiftSignup'])->name('api.shifts.signup');
    Route::delete('/shifts/{shift}/withdraw', [\App\Http\Controllers\ApiController::class, 'shiftWithdraw'])->name('api.shifts.withdraw');
    Route::post('/pinnwand/{slug}/posts', [\App\Http\Controllers\ApiController::class, 'storePost'])->name('api.posts.store');
    Route::post('/posts/{post}/comments', [\App\Http\Controllers\ApiController::class, 'storeComment'])->name('api.comments.store');
    Route::post('/elternaktivitaeten/{slug}/posts', [\App\Http\Controllers\ApiController::class, 'storeActivityPost'])->name('api.activity-posts.store');
    Route::post('/activity-posts/{post}/comments', [\App\Http\Controllers\ApiController::class, 'storeActivityComment'])->name('api.activity-comments.store');
    Route::post('/announcements/{announcement}/dismiss', [\App\Http\Controllers\AnnouncementController::class, 'dismiss'])->name('api.announcements.dismiss')->middleware('auth');
});

Route::middleware(['verify.edit.token'])->group(function () {
    Route::get('/pinnwand/{slug}/edit', [BulletinController::class, 'edit'])->name('bulletin.edit');
    Route::put('/pinnwand/{slug}', [BulletinController::class, 'update'])->name('bulletin.update');

    Route::post('/moderation/posts/{post}/hide', [ModerationController::class, 'togglePost'])->name('moderation.post.toggle')->withTrashed();
    Route::post('/moderation/comments/{comment}/hide', [ModerationController::class, 'toggleComment'])->name('moderation.comment.toggle')->withTrashed();
});

// Legal pages
Route::get('/datenschutz', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/impressum', [LegalController::class, 'impressum'])->name('legal.impressum');
Route::get('/kontakt', [LegalController::class, 'contact'])->name('legal.contact');
