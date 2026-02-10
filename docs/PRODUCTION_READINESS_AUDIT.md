# Production Readiness Audit Report

**Date:** 2026-02-10
**Project:** Steinerschule Elternaktivitäten
**Stack:** Laravel 12, Filament 3, Alpine.js, Tailwind CSS 4, SQLite/MySQL
**Files Audited:** 96 PHP files, 43 Blade templates, 36 migrations, 2 JS files, all config files

---

## 1. Errors & Bugs

### 1.1 Syntax Error — ShiftVolunteer Model

**File:** `app/Models/ShiftVolunteer.php:24`

Extra closing brace causes a PHP parse error:

```php
public function user()
{
    return $this->belongsTo(User::class);
}} // <-- double closing brace
```

**Severity:** Critical — This file won't parse. Any code path that loads this model will throw a fatal error.

---

### 1.2 AuditLog::log() Crashes in Unauthenticated Context

**File:** `app/Models/AuditLog.php:38`

```php
'performed_by_name' => auth()->user()->name,
```

`auth()->user()` returns `null` when called from console commands, scheduled tasks, or queue workers. This causes `TypeError: Attempt to read property "name" on null`.

**Affected callers:**
- `app/Console/Commands/YearResetCommand.php` (works around this with hardcoded values)
- Any future code calling `AuditLog::log()` from non-HTTP context

---

### 1.3 ShiftRepository Uses MySQL-Only Functions on SQLite Dev Environment

**File:** `app/Repositories/ShiftRepository.php:30,68,89,93`

```php
->orderByRaw("STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(time, ',', 2), ' ', -1), '%d.%m.%Y')")
```

`STR_TO_DATE()` and `SUBSTRING_INDEX()` are MySQL-specific functions. They do not exist in SQLite. Since development uses SQLite (per `.env.example` and `CLAUDE.md`), all methods in this repository will throw SQL errors in development:
- `findNeedingVolunteers()` (line 30)
- `getByBulletinPost()` (line 68)
- `getInDateRange()` (line 89, 93)

---

### 1.4 UpdateExpiredItems — Incorrect Expiry Logic

**File:** `app/Console/Commands/UpdateExpiredItems.php:42-44`

```php
$q->whereNull('end_at')
    ->where('start_at', '<', now()->subDay());
```

Posts without an `end_at` are marked as "ended" if `start_at` is more than 1 day in the past. This means a multi-day event that started 2 days ago but should still be active will be incorrectly marked as ended. The logic should check whether the event's last shift has passed, not whether it started more than 24 hours ago.

---

### 1.5 CalendarService — Silent Date Parsing Failures

**File:** `app/Services/CalendarService.php:346-353`

`parseShiftDate()` returns `null` when it can't parse a date, but many callers don't check for null. The seeder creates shifts with formats like `"03.06.2026 - 05.06.2026"` (date range), `"Montags, 12:00 - 14:00 Uhr"` (weekday only) — only the format `"17.05.2026, 15:00 - 18:00 Uhr"` is parseable. Shifts with unparseable dates silently disappear from the calendar.

---

### 1.6 CalendarService — Incomplete Recurring Pattern Parsing

**File:** `app/Services/CalendarService.php:358-376`

Only the "Donnerstag/Thursday" recurring pattern is implemented. All other weekday patterns silently return empty collections. Activities with "Montags", "Dienstags", "Mittwochs", etc. patterns won't appear on the calendar.

---

### 1.7 Announcement::scopeForUser Returns Collection, Not Query Builder

**File:** `app/Models/Announcement.php:57-71`

The scope returns `$priorityNotifications->concat($regularNotifications)` — a `Collection`, not a query `Builder`. This breaks scope chainability and violates the Eloquent scope contract.

---

### 1.8 YearResetCommand — Hardcoded User ID

**File:** `app/Console/Commands/YearResetCommand.php:109`

```php
'performed_by' => 1, // Assumes user ID 1 exists
```

If user ID 1 doesn't exist or isn't the expected admin, the audit log attributes the action to the wrong person. The `performed_by_name` is correctly set to `'System (Konsole)'`, but the foreign key may reference a non-existent user.

---

## 2. Security Audit

### 2.1 Missing Authentication on Forum/API Routes (CRITICAL)

**File:** `routes/web.php:54-55,74-76,80,84,88-89,95-98`

Multiple POST endpoints accept requests without authentication:

| Route | Line | Issue |
|-------|------|-------|
| `POST /pinnwand/{slug}/posts` | 54 | No auth middleware — anyone can post to forums |
| `POST /posts/{post}/comments` | 55 | No auth middleware — anyone can comment |
| `POST /api/bulletin-posts/{slug}/forum` | 80 | No auth middleware — anonymous forum posts |
| `POST /api/forum-posts/{post}/comments` | 84 | No auth middleware — anonymous comments |
| `POST /api/elternaktivitaeten/{slug}/posts` | 88 | No auth middleware — anonymous activity posts |
| `POST /api/shifts/{shift}/signup` (legacy) | 95 | No auth middleware at route level |
| `GET /api/shifts/{shift}/volunteers` | 76 | Exposes volunteer data to unauthenticated users |

The controllers check `auth()->check()` internally for some operations, but the routes themselves allow unauthenticated access, enabling spam and data exposure.

---

### 2.2 Edit Token Comparison Vulnerable to Timing Attacks

**File:** `app/Http/Middleware/VerifyEditToken.php:28`

```php
if (!$helpRequest || $helpRequest->edit_token !== $token) {
```

Direct string comparison (`!==`) is vulnerable to timing attacks. Should use `hash_equals()` for constant-time comparison of security tokens.

Additionally:
- No rate limiting on token validation attempts (brute force risk)
- Token is passed in query string (logged in server access logs, visible in browser history)
- No token expiration mechanism

---

### 2.3 Moderation Routes Accessible to Non-Admin Token Holders

**File:** `routes/web.php:105-106`

```php
Route::post('/moderation/posts/{post}/hide', [ModerationController::class, 'togglePost'])
    ->name('moderation.post.toggle')->withTrashed();
Route::post('/moderation/comments/{comment}/hide', [ModerationController::class, 'toggleComment'])
    ->name('moderation.comment.toggle')->withTrashed();
```

These moderation routes are inside the `verify.edit.token` middleware group. Anyone with a valid bulletin post edit token can hide/restore ANY post or comment across the entire site — not just those on their bulletin post. The `ModerationController` (`app/Http/Controllers/ModerationController.php:11-39`) has zero authorization checks.

---

### 2.4 Unvalidated Anonymous Name Input (XSS Risk)

**File:** `app/Http/Controllers/Api/BulletinPostForumController.php:42`
**File:** `app/Http/Controllers/Api/ForumCommentController.php:36`

```php
'name' => auth()->check() ? auth()->user()->name : $request->input('name', 'Anonym'),
```

The `name` field for anonymous posts/comments is taken directly from user input without validation. While Blade's `{{ }}` escapes output, this data is also returned in JSON API responses (line 50, 88) and could be used in contexts where escaping doesn't apply.

---

### 2.5 CSP Allows unsafe-inline and unsafe-eval in Production

**File:** `app/Http/Middleware/SecurityHeaders.php:36-42`

The Content Security Policy includes `'unsafe-inline'` and `'unsafe-eval'` for scripts, and `http://localhost:*` / `ws://localhost:*` for all directive types. This CSP is identical in development and production — the localhost exceptions and unsafe directives significantly weaken XSS protection in production.

---

### 2.6 Missing Rate Limiting on Sensitive Endpoints

**File:** `routes/web.php`

Only the login route has rate limiting (`throttle:5,1` at line 19). Missing from:
- Registration (`POST /register`, line 21)
- Password reset (`POST /forgot-password`, line 28; `POST /reset-password`, line 30)
- Demo login/admin (`POST /demo-login`, line 22; `POST /demo-admin-login`, line 23)
- All forum post/comment creation endpoints
- All API endpoints
- Edit token validation routes

---

### 2.7 Demo Admin Account Creates Super Admin on Demand

**File:** `app/Http/Controllers/AuthController.php:70-85`

```php
public function loginDemoAdmin()
{
    $demoAdmin = User::firstOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name' => 'Demo Admin',
            'password' => Hash::make('admin123456'),
            'is_admin' => true,
            'is_super_admin' => true,
        ]
    );
    Auth::login($demoAdmin);
```

This endpoint auto-creates a super admin account with a known password (`admin123456`). While `firstOrCreate` won't reset the password if the account exists, the initial creation uses a trivially guessable password. This route has no rate limiting.

**Note:** The task description states "login as demo user functionality is intentional and should remain." However, the demo admin route creates a **super admin** with full system access, which is a significant risk in production.

---

### 2.8 UserImporter Sets Default Password `12345678` for All Imports

**File:** `app/Filament/Imports/UserImporter.php:70-72`

```php
if (empty($this->data['password'])) {
    $this->data['password'] = '12345678';
}
```

All imported users without an explicit password get the same known, weak password. No mechanism exists to notify users about their temporary password or force a password change.

---

### 2.9 Volunteer Data Exposed to Unauthenticated Users

**File:** `app/Http/Controllers/Api/ShiftVolunteerController.php:95-108`

```php
public function index(Shift $shift): JsonResponse
{
    $volunteers = $this->shiftService->getShiftVolunteers($shift);
    return response()->json([
        'data' => $volunteers->map(function ($volunteer) {
            return [
                'id' => $volunteer->id,
                'name' => auth()->check() ? $volunteer->name : 'Angemeldet',
                'created_at' => $volunteer->created_at->format('d.m.Y H:i'),
            ];
        }),
    ]);
}
```

While names are hidden for unauthenticated users, the endpoint still exposes: volunteer count, volunteer IDs, and signup timestamps. The route at `web.php:76` has no auth middleware.

---

### 2.10 NPM Dependency Vulnerabilities

`npm audit` reports 3 vulnerabilities:

| Package | Severity | Advisory |
|---------|----------|----------|
| `axios <=1.13.4` | **High** | DoS via `__proto__` key in mergeConfig ([GHSA-43fc-jf86-j433](https://github.com/advisories/GHSA-43fc-jf86-j433)) |
| `tar <=7.5.6` | **High** | Arbitrary file overwrite via path traversal ([GHSA-8qq5-rm4j-mr97](https://github.com/advisories/GHSA-8qq5-rm4j-mr97)) |
| `vite 7.1.0-7.1.10` | **Moderate** | server.fs.deny bypass on Windows ([GHSA-93m4-6634-74q7](https://github.com/advisories/GHSA-93m4-6634-74q7)) |

All fixable via `npm audit fix`.

---

### 2.11 School Calendar CRUD Missing Role Authorization at Route Level

**File:** `routes/web.php:45-51`

School calendar CRUD routes only require `auth` middleware, not admin/super_admin. The controller checks `is_super_admin` internally (`SchoolCalendarController`), but the request is still fully processed before the 403 is thrown. This is a defense-in-depth gap.

---

### 2.12 Profile View Accessible Without Authentication

**File:** `routes/web.php:63`

```php
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
```

Any visitor can view any user's public profile page. Depending on what data `profile/show.blade.php` exposes, this could leak personal information.

---

## 3. Code Quality & Cleanup

### 3.1 Legacy Duplicate API Routes

**File:** `routes/web.php:94-98`

```php
// Legacy routes - maintain backward compatibility temporarily
Route::post('/shifts/{shift}/signup', ...);
Route::delete('/shifts/{shift}/withdraw', ...);
Route::post('/pinnwand/{slug}/posts', ...);
Route::post('/posts/{post}/comments', ...);
```

Four duplicate routes exist alongside the newer RESTful routes (lines 74-89). These map to `ApiController` methods that duplicate logic already in dedicated controllers (`ShiftVolunteerController`, `BulletinPostForumController`). The comment says "temporarily" but these are technical debt that should be removed.

---

### 3.2 Inconsistent Authentication Patterns

Three different auth patterns are used:
1. **Route middleware:** `->middleware('auth')` (e.g., line 40, 56, 59)
2. **Controller check:** `auth()->check()` with manual 401 response (e.g., `ShiftVolunteerController.php:32`)
3. **No auth at all:** (e.g., lines 54, 55, 80, 84)

This inconsistency makes it difficult to audit which endpoints are actually protected.

---

### 3.3 Console.error Statements in Production Views

Debug logging in multiple Blade templates:

| File | Lines |
|------|-------|
| `resources/views/bulletin/show.blade.php` | 155, 274, 408 |
| `resources/views/activities/show.blade.php` | 111, 204 |
| `resources/views/calendar/index.blade.php` | 84, 117 |
| `resources/views/school-calendar/index.blade.php` | 83, 117 |

These expose error details to end users via browser console.

---

### 3.4 Inline Import in Controller

**File:** `app/Http/Controllers/ProfileController.php:46`

```php
$volunteers = \App\Models\ShiftVolunteer::where('user_id', $user->id)
```

Uses fully-qualified class name inline instead of an `use` import at the top of the file.

---

### 3.5 ModerationController — No Authorization Logic

**File:** `app/Http/Controllers/ModerationController.php:11-39`

Both `togglePost()` and `toggleComment()` perform their action without checking:
- Whether the current user owns the post/comment
- Whether the current user is an admin
- Whether the moderation action is related to the bulletin post whose edit token was provided

The entire authorization is delegated to the `VerifyEditToken` middleware, which only verifies the token is valid for *some* bulletin post — not that the post/comment being moderated belongs to that bulletin post.

---

### 3.6 Filament Resources Missing Consistent Authorization

Authorization methods are inconsistently defined across Filament resources:

| Resource | canViewAny | canCreate | canEdit | canDelete |
|----------|-----------|----------|--------|-----------|
| UserResource | super_admin | — | — | super_admin |
| ActivityResource | admin | admin | admin | admin |
| BulletinPostResource | **missing** | **missing** | **missing** | super_admin |
| PostResource | **missing** | **missing** | **missing** | admin |
| CommentResource | **missing** | **missing** | **missing** | admin |
| AnnouncementResource | super_admin | super_admin | super_admin | super_admin |
| SchoolEventResource | super_admin | — | — | super_admin |
| AuditLogResource | super_admin | false | false | false |

Resources without `canViewAny()` rely entirely on the `FilamentAdminMiddleware` (which checks `is_admin`). Regular admins can view/create/edit bulletin posts, forum posts, and comments through the admin panel without intended authorization.

---

## 4. Performance

### 4.1 N+1 Query in ApiController

**File:** `app/Http/Controllers/ApiController.php:22,52`

```php
$onlineCount = $shift->volunteers()->count();  // Query 1
// ... later ...
'online_count' => $shift->volunteers()->count(),  // Query 2 (same data)
```

The `volunteers()->count()` query runs twice for the same shift in the same request.

---

### 4.2 Missing Database Indexes

Several frequently queried columns lack indexes:

| Table | Column(s) | Used By |
|-------|----------|---------|
| `shift_volunteers` | `user_id` | User shift lookups |
| `shift_volunteers` | `(shift_id, user_id)` unique | Prevent duplicate signups |
| `school_events` | `start_date` | Calendar date range queries |
| `school_events` | `event_type` | Type-based filtering |
| `bulletin_posts` | `edit_token` | Token lookup in VerifyEditToken middleware |
| `bulletin_posts` | `end_at` | Expiry queries in UpdateExpiredItems |
| `bulletin_posts` | `category` | Category filtering |
| `announcements` | `(starts_at, expires_at)` | Active announcement queries |

---

### 4.3 Missing Unique Constraint — Duplicate Volunteer Signups

**File:** `database/migrations/2025_09_14_142345_create_shift_volunteers_table.php`

No `unique(['shift_id', 'user_id'])` constraint exists. The `ShiftService` checks for duplicates in application code, but without a database constraint, race conditions can create duplicate signups.

---

### 4.4 Calendar innerHTML Assignment from Fetch

**File:** `resources/views/calendar/index.blade.php:94`
**File:** `resources/views/school-calendar/index.blade.php:94`

```javascript
oldContent.innerHTML = html;
```

Full HTML replacement via `innerHTML` after AJAX navigation forces the browser to reparse the entire calendar DOM. This could be optimized with targeted DOM updates.

---

### 4.5 AuditLogStats Widget — No Caching

**File:** `app/Filament/Widgets/AuditLogStats.php:13-21`

Three separate database queries run on every admin dashboard page load with no caching:
```php
$lastReset = AuditLog::where('action_type', 'year_reset')->orderBy(...)->first();
$criticalCount = AuditLog::where('severity', 'critical')->where(...)->count();
$todayCount = AuditLog::whereDate('created_at', today())->count();
```

---

### 4.6 Post::getAuthorNameAttribute — N+1 Risk

**File:** `app/Models/Post.php:44-47`

```php
public function getAuthorNameAttribute()
{
    return $this->user ? $this->user->name : 'Anonym';
}
```

Accessing `$this->user` triggers a lazy load if the relationship isn't eager loaded. The `Shift` model correctly uses `relationLoaded()` to guard against this, but `Post` does not.

---

## 5. Potential Improvements

### 5.1 Environment-Aware CSP

The Content Security Policy should differ between development and production. Production should not include `http://localhost:*`, `ws://localhost:*`, or `'unsafe-eval'`. Consider:

```php
if (app()->environment('production')) {
    // Strict CSP without localhost
} else {
    // Development CSP with Vite/HMR support
}
```

---

### 5.2 Standardize Date Handling

Shift dates are stored as free-text strings (e.g., `"17.05.2026, 15:00 - 18:00 Uhr"`), requiring complex regex parsing. A dedicated `shift_date` column with a proper `DATE` type would eliminate:
- MySQL-specific `STR_TO_DATE()` calls
- Fragile regex parsing in `CalendarService`
- Silent failures for unparseable formats

---

### 5.3 Replace Token-in-Query-String with Signed URLs

Edit tokens in query strings (`?token=abc123`) appear in browser history, server logs, and referrer headers. Laravel's signed URL feature (`URL::signedRoute()`) would provide:
- Token expiration
- Tamper protection via HMAC signature
- No token storage in the database needed

---

### 5.4 Implement Laravel Policies

Replace scattered authorization checks in controllers with proper Laravel Policies. This centralizes access control logic and enables:
- Automatic `authorize()` calls in Form Requests
- Policy-based Filament resource authorization
- Testable authorization logic

---

### 5.5 Accessibility Gaps

- Missing `aria-label` on icon-only buttons throughout calendar and forum views
- Calendar month navigation relies on touch swipe only — no keyboard arrow key support
- `javascript:history.back()` used for back navigation (`profile/show.blade.php:9`) — not accessible
- No skip-to-content link in layout
- Color contrast not verified for Steiner custom colors

---

### 5.6 Fragile DOM Selectors for Calendar Navigation

**File:** `resources/views/calendar/index.blade.php:51-56`
**File:** `resources/views/school-calendar/index.blade.php:51-56`

```javascript
const prevButton = document.querySelector('#calendar-content button:first-of-type');
const nextButton = document.querySelector('#calendar-content button:last-of-type');
```

Uses CSS pseudo-selectors that break if DOM structure changes. Should use `data-action="prev"` / `data-action="next"` attributes.

---

## 6. Testing

### 6.1 Current Test Coverage

Only 3 test files exist with limited coverage:

| Test File | What it Tests | Lines |
|-----------|--------------|-------|
| `AuthenticationTest.php` | Login, register, logout, demo accounts, password reset form render | ~215 |
| `BulletinTest.php` | Bulletin index, show, create, edit with auth | ~120 |
| `ShiftManagementTest.php` | Shift signup, withdraw, capacity enforcement | ~100 |

### 6.2 Missing Critical Test Coverage

**Authentication:**
- Password reset flow completion (only form render is tested)
- Email verification
- Session fixation protection
- Rate limiting enforcement on login
- Demo admin account creation and privilege escalation

**Authorization:**
- School calendar CRUD access control (super_admin only)
- Moderation endpoint access via edit tokens
- Profile visibility rules
- Filament admin panel access control per resource

**API Endpoints:**
- `GET /api/shifts/{shift}/volunteers` — privacy of volunteer data
- `POST /api/bulletin-posts/{slug}/forum` — anonymous post creation
- `POST /api/forum-posts/{post}/comments` — anonymous comment creation
- All legacy API routes (lines 94-98)

**Business Logic:**
- `CalendarService` date parsing for all shift date formats
- `CalendarService` recurring pattern parsing
- `UpdateExpiredItems` command with various date configurations
- `YearResetCommand` data archival/deletion
- Shift signup race conditions (concurrent requests)

**Data Integrity:**
- Duplicate volunteer signup prevention
- Orphaned data cleanup
- Cascade deletion behavior (bulletin post → shifts → volunteers)

### 6.3 Missing Factories

Only `UserFactory` and `BulletinPostFactory` exist. Missing factories for:
- `Shift`
- `ShiftVolunteer`
- `Post`
- `Comment`
- `Activity`
- `ActivityPost`
- `ActivityComment`
- `SchoolEvent`
- `Announcement`

This makes it difficult to write comprehensive tests.

---

## 7. DevOps & Configuration

### 7.1 Environment Defaults Unsafe for Production

**File:** `.env.example`

| Variable | Default | Production Should Be |
|----------|---------|---------------------|
| `APP_DEBUG` | `true` | `false` |
| `LOG_LEVEL` | `debug` | `warning` or `error` |
| `SESSION_ENCRYPT` | `false` | `true` |

Missing from `.env.example` entirely:
- `SESSION_SECURE_COOKIE` (should be `true` in production)
- `SESSION_SAME_SITE` (defaults to `lax`, could be `strict`)
- `BCRYPT_ROUNDS` (defaults to 12, production should be 14+)

---

### 7.2 CI/CD Pipeline Gaps

**File:** `.github/workflows/tests.yml`

The CI pipeline runs PHPUnit tests but does not:
- Run `npm run build` (frontend build errors won't be caught)
- Run `composer audit` or `npm audit` (vulnerable dependencies won't be flagged)
- Run code style checks (`vendor/bin/pint --test`)
- Run static analysis

---

### 7.3 Laravel Tinker in Production Dependencies

**File:** `composer.json`

`laravel/tinker` is in `require` (production) instead of `require-dev`. Tinker provides a PHP REPL that can execute arbitrary code. While it requires console access to use, it should not be installed in production as a defense-in-depth measure.

---

### 7.4 No Database Migration Rollback Strategy

No down migrations are defined for critical tables. If a migration needs to be rolled back in production, it will fail. Consider adding `down()` methods to migration files or documenting the rollback strategy.

---

### 7.5 Queue Worker Not Monitored

The application uses database queue (`QUEUE_CONNECTION=database`) but there's no configuration for:
- Supervisor/process manager to keep queue workers running
- Failed job monitoring or alerting
- Queue health checks

---

## 8. Documentation

### 8.1 Production Deployment Guide Missing

No documentation exists for:
- How to deploy to DigitalOcean (mentioned in CLAUDE.md)
- Required environment variables for production
- Database migration strategy for production MySQL
- SSL/HTTPS setup
- Queue worker setup and monitoring
- Backup strategy

### 8.2 API Documentation Missing

No documentation for the Alpine.js API endpoints:
- Request/response formats
- Authentication requirements
- Rate limiting rules
- Error response formats

The inline route comments are the only documentation. There are no OpenAPI/Swagger specs or Postman collections.

### 8.3 Existing Documentation Structure

The project has good internal documentation:
- `CLAUDE.md` — comprehensive development guide
- `docs/SANITY_CHECK_REPORT.md` — previous code review
- `docs/TODO_CHECKLIST.md` — actionable task list
- `docs/PROGRESS_LOG.md` — development history
- `KONZEPT.md` — project concept
- `CHANGELOG.md` — version history

---

## 9. Pre-Release TODO List

### 🔴 Critical (Must Fix Before Release)

| # | Issue | File | Line(s) | Description |
|---|-------|------|---------|-------------|
| C1 | Syntax error | `app/Models/ShiftVolunteer.php` | 24 | Remove extra closing brace `}}` → `}` |
| C2 | Auth crash | `app/Models/AuditLog.php` | 38 | Add null check: `auth()->user()?->name ?? 'System'` |
| C3 | SQLite incompatibility | `app/Repositories/ShiftRepository.php` | 30,68,89,93 | Replace `STR_TO_DATE()` with PHP-based date parsing or add proper `date` column to shifts table |
| C4 | Missing auth on forum routes | `routes/web.php` | 54,55 | Add `->middleware('auth')` to POST routes for posts and comments |
| C5 | Missing auth on API routes | `routes/web.php` | 80,84,88,89,95-98 | Add auth middleware or rate limiting to API POST endpoints |
| C6 | Moderation authorization | `app/Http/Controllers/ModerationController.php` | 11-39 | Add checks: verify user is admin OR owns the content; verify content belongs to the edit-token's bulletin post |
| C7 | Token timing attack | `app/Http/Middleware/VerifyEditToken.php` | 28 | Use `hash_equals($helpRequest->edit_token, $token)` instead of `!==` |
| C8 | NPM vulnerabilities | `package.json` | — | Run `npm audit fix` to update axios, tar, and vite |
| C9 | APP_DEBUG in production | `.env` (production) | — | Ensure `APP_DEBUG=false` in production environment |
| C10 | Demo admin in production | `app/Http/Controllers/AuthController.php` | 70-85 | Disable or remove demo admin login route for production, or restrict to non-production environments |

### 🟠 High (Should Fix Before Release)

| # | Issue | File | Line(s) | Description |
|---|-------|------|---------|-------------|
| H1 | Rate limit registration | `routes/web.php` | 21 | Add `throttle:5,1` middleware to registration route |
| H2 | Rate limit password reset | `routes/web.php` | 28,30 | Add `throttle:5,1` middleware to password reset routes |
| H3 | Rate limit demo login | `routes/web.php` | 22,23 | Add `throttle:3,1` to demo login routes |
| H4 | Rate limit API endpoints | `routes/web.php` | 72-99 | Add `throttle:60,1` to API route group |
| H5 | CSP production hardening | `app/Http/Middleware/SecurityHeaders.php` | 36-42 | Remove `localhost`, `unsafe-eval` from CSP in production environment |
| H6 | Session encryption | `.env` (production) | — | Set `SESSION_ENCRYPT=true` and `SESSION_SECURE_COOKIE=true` |
| H7 | Validate anonymous names | `app/Http/Controllers/Api/BulletinPostForumController.php` | 42 | Add validation: `'name' => 'nullable|string|max:100'` |
| H8 | Default import password | `app/Filament/Imports/UserImporter.php` | 70-72 | Generate random password with `Str::random(12)` instead of `'12345678'` |
| H9 | Missing unique constraint | `database/migrations/` | — | Add migration: `$table->unique(['shift_id', 'user_id'])` on `shift_volunteers` |
| H10 | Filament authorization gaps | `app/Filament/Resources/` | — | Add `canViewAny()` to BulletinPostResource, PostResource, CommentResource |
| H11 | Expired items logic | `app/Console/Commands/UpdateExpiredItems.php` | 42-44 | Fix expiry logic to check shift dates, not just start_at minus 1 day |
| H12 | Remove legacy routes | `routes/web.php` | 94-98 | Remove duplicate legacy API routes after verifying no frontend code uses them |

### 🟡 Medium (Fix Soon After Release)

| # | Issue | File | Line(s) | Description |
|---|-------|------|---------|-------------|
| M1 | Add database indexes | `database/migrations/` | — | Create migration adding indexes listed in section 4.2 |
| M2 | Standardize date handling | `app/Services/CalendarService.php` | 346+ | Add proper `shift_date` DATE column to shifts table |
| M3 | Complete recurring patterns | `app/Services/CalendarService.php` | 358-376 | Implement all German weekday recurring patterns |
| M4 | Fix Announcement scope | `app/Models/Announcement.php` | 57-71 | Return query builder instead of Collection from scope |
| M5 | Remove console.error | Multiple blade files | See 3.3 | Remove all `console.error()` statements from production templates |
| M6 | Profile access control | `routes/web.php` | 63 | Add auth middleware or verify profile view doesn't expose sensitive data |
| M7 | Add missing factories | `database/factories/` | — | Create factories for Shift, Post, Comment, Activity, SchoolEvent, Announcement |
| M8 | CI/CD improvements | `.github/workflows/tests.yml` | — | Add npm build, composer audit, npm audit, pint to CI pipeline |
| M9 | Move tinker to dev | `composer.json` | — | Move `laravel/tinker` from `require` to `require-dev` |
| M10 | Consistent auth pattern | `routes/web.php` + controllers | — | Standardize to route-level middleware instead of controller-level checks |
| M11 | School calendar auth | `routes/web.php` | 45-51 | Add admin/super_admin middleware instead of relying on controller checks |
| M12 | N+1 in ApiController | `app/Http/Controllers/ApiController.php` | 22,52 | Cache volunteer count in variable instead of querying twice |
| M13 | Post N+1 accessor | `app/Models/Post.php` | 44-47 | Add `relationLoaded()` check like Shift model does |

### 🟢 Low (Nice to Have)

| # | Issue | File | Line(s) | Description |
|---|-------|------|---------|-------------|
| L1 | Accessibility — ARIA labels | Multiple blade files | — | Add `aria-label` to all icon-only buttons |
| L2 | Accessibility — keyboard nav | Calendar views | — | Add keyboard arrow key support for month navigation |
| L3 | Fragile DOM selectors | Calendar views | 51-56 | Use `data-action` attributes instead of CSS pseudo-selectors |
| L4 | Cache AuditLogStats | `app/Filament/Widgets/AuditLogStats.php` | 13-21 | Add `Cache::remember()` wrapper |
| L5 | Inline import cleanup | `app/Http/Controllers/ProfileController.php` | 46 | Move `ShiftVolunteer` to `use` import |
| L6 | Production deployment docs | `docs/` | — | Create production deployment guide |
| L7 | API documentation | `docs/` | — | Document API endpoints, auth requirements, response formats |
| L8 | Signed URLs for edit tokens | `app/Http/Middleware/VerifyEditToken.php` | — | Replace custom token system with Laravel signed URLs |
| L9 | Laravel Policies | `app/Policies/` | — | Implement policies for BulletinPost, Post, Comment, SchoolEvent |
| L10 | Seeder data quality | `database/seeders/` | — | Fix truncated descriptions and inconsistent date formats in seeders |

---

## Summary

| Severity | Count |
|----------|-------|
| 🔴 Critical | 10 |
| 🟠 High | 12 |
| 🟡 Medium | 13 |
| 🟢 Low | 10 |
| **Total** | **45** |

The application is well-structured with good separation of concerns (services, repositories, form requests) and thoughtful features (honeypot spam protection, security headers, GDPR compliance). The most pressing issues are the syntax error in `ShiftVolunteer.php`, missing authentication on forum/API routes, the MySQL/SQLite incompatibility in `ShiftRepository`, and the overly permissive moderation routes. Fixing the 10 critical items and the 12 high-priority items would bring the application to a solid production-ready state.
