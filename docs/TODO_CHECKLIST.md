# TODO Checklist - Sanity Check Fixes

**IMPORTANT**: Mark items as completed by changing `[ ]` to `[x]` when done. Update PROGRESS_LOG.md before committing any changes.

## 🚨 Critical (Security) - Do First!

### Rate Limiting
- [x] Add throttle middleware to login route
  - **File**: `routes/web.php:22`
  - **Fix**: Add `->middleware('throttle:5,1')` to login POST route
  - **Time**: 2 minutes
  - **Test**: Try 6 rapid login attempts, should get 429 error on 6th

## ⚠️ High Priority - Do This Week

### Security Enhancement
- [x] Add invisible captcha to all forms
  - **Options**: Google reCAPTCHA v3, hCaptcha, or Laravel Honeypot
  - **Priority**: Medium-High (prevents bot spam, but rate limiting covers brute force)
  - **Forms to protect**: login, register, shift signup, forum posts
  - **Time**: 2-3 hours
  - **Note**: Consider privacy-friendly option (parents are privacy-conscious)

### Testing Infrastructure
- [x] Create authentication test suite
  - **File**: `tests/Feature/AuthenticationTest.php`
  - **Tests needed**: login, register, logout, password reset
  - **Time**: 2 hours

- [x] Create shift management tests
  - **File**: `tests/Feature/ShiftManagementTest.php`
  - **Tests needed**: signup, withdraw, capacity limits
  - **Time**: 1 hour

- [x] Create bulletin CRUD tests
  - **File**: `tests/Feature/BulletinTest.php`
  - **Tests needed**: create, edit with token, archive
  - **Time**: 1.5 hours

## 📝 Medium Priority - Improve Code Quality

### Form Request Classes
- [x] Create UpdateBulletinRequest
  - **File**: `app/Http/Requests/UpdateBulletinRequest.php`
  - **Move validation from**: `BulletinController::update()`
  - **Time**: 15 minutes

- [x] Create RegisterUserRequest
  - **File**: `app/Http/Requests/RegisterUserRequest.php`
  - **Move validation from**: `AuthController::register()`
  - **Time**: 15 minutes

- [x] Create StorePostRequest (replaced StoreShiftRequest)
  - **File**: `app/Http/Requests/StorePostRequest.php`
  - **Move validation from**: `PostController::store()`
  - **Time**: 15 minutes
  - **Note**: ShiftController doesn't need Form Request (no validation)

### Service Layer
- [x] Extract CalendarController logic to CalendarService
  - **Create**: `app/Services/CalendarService.php`
  - **Move**: Date calculations and calendar item processing
  - **Time**: 2 hours

- [x] Create ShiftService for shift management logic
  - **Create**: `app/Services/ShiftService.php`
  - **Move**: Signup/withdrawal business logic
  - **Time**: 1 hour

### Remove Inline Styles
- [x] Fix calendar/index.blade.php inline styles
  - **Replace**: `style="min-height: 60px"` → `class="min-h-[60px]"`
  - **Time**: 10 minutes

- [x] Fix calendar/partials/content.blade.php inline styles
  - **Time**: 15 minutes

- [x] Fix school-calendar views inline styles
  - **Time**: 15 minutes

- [x] Remove debug.blade.php or fix its styles
  - **Time**: 5 minutes

## 🐛 Bug Fixes - User Reported

### Authentication Flow
- [x] Fix auth page redirects for authenticated users
  - **File**: `routes/web.php`
  - **Fix**: Add `middleware('guest')` to auth routes
  - **Time**: 10 minutes
  - **Issue**: Authenticated users could access login/register pages

## 🔧 Low Priority - Nice to Have

### Documentation
- [x] Fix README.md line 160 typo
  - **Change**: "composer run dev" → "npm run dev"
  - **Time**: 1 minute

- [x] Document edit token API endpoints
  - **File**: `docs/API.md`
  - **Time**: 30 minutes

- [x] Add PHPDoc comments to complex methods
  - **Focus on**: CalendarController, shift calculations
  - **Time**: 1 hour

### Code Organization
- [x] Create repository classes for complex queries
  - **Start with**: `app/Repositories/BulletinRepository.php`
  - **Time**: 2 hours

- [x] Standardize controller methods to RESTful patterns
  - **Review**: All controllers for consistency
  - **Time**: 2 hours

## Needs Clarification (Jan 2026 Code Quality Review)

These items were identified during a thorough code review but need owner input before fixing.

### #3 - API Routes: Anonymous Posting Policy
- **Status**: Owner needs to clarify
- **Issue**: The new API controllers (`BulletinPostForumController`, `ForumCommentController`) allow unauthenticated users to create forum posts and comments (with `user_id => null` and name "Anonym"). The legacy `ApiController` endpoints require auth. It's unclear whether anonymous forum posting is intentional.
- **Files**: `routes/web.php:80,84`, `Api/BulletinPostForumController.php:41`, `Api/ForumCommentController.php:35`
- **Action needed**: Decide if anonymous forum posts are intended behavior. If yes, document it. If no, add `auth` middleware to those routes.

### #9 - Duplicate API Controllers
- **Status**: Owner needs to clarify which set the frontend uses
- **Issue**: Two parallel sets of API endpoints exist for the same operations:
  - **Legacy**: `ApiController` handles shift signup, post creation, comment creation (uses `body` field, inline logic)
  - **New**: `ShiftVolunteerController`, `BulletinPostForumController`, `ForumCommentController` (uses `content`/`name` fields, `ShiftService`)
  - Both route sets are active simultaneously in `web.php`
- **Files**: `ApiController.php` vs `Api/ShiftVolunteerController.php`, `Api/BulletinPostForumController.php`, `Api/ForumCommentController.php`
- **Action needed**: Identify which endpoints the frontend actually uses. Remove the unused set and delete dead code.

### #11 - Post Model Field Mismatch (Likely Bug)
- **Status**: Owner needs to verify - this looks like a bug
- **Issue**: The `Post` model `$fillable` has `['bulletin_post_id', 'user_id', 'body', 'ip_hash', 'deletion_reason']`. The `posts` migration defines `body` (text column). However, `BulletinPostForumController::store()` creates posts with `name` and `content` fields - **neither exists in the database or in `$fillable`**. Laravel's mass assignment protection silently drops them. The legacy `ApiController::storePost()` correctly uses `body`.
  - This means forum posts created through the new API controller have **NULL content** - `body` is never set and `content` doesn't exist in the table.
- **Files**: `app/Models/Post.php:11-17`, `database/migrations/2025_09_14_093012_create_posts_table.php:15`, `Api/BulletinPostForumController.php:39-44`
- **Action needed**: If `content`/`name` columns were intended, add a migration. If `body` is correct, fix `BulletinPostForumController` to use `body` instead of `content`, and derive author from user relationship instead of `name`.
- **Note**: Same issue likely exists in `ForumCommentController::store()` - uses `name`/`content` which may not match the `comments` table schema either.

## Progress Tracking

### Completed Items Summary
- **Critical**: 1/1
- **High**: 4/4
- **Medium**: 8/8
- **Bug Fixes**: 1/1
- **Low**: 5/5
- **Total**: 19/19 ALL COMPLETE!

### Time Estimates
- **Total estimated time**: ~15 hours
- **Quick wins (< 30 min)**: 7 items
- **Half-day tasks**: 5 items
- **Full-day tasks**: 5 items

## Notes for Implementation

1. **Always test after changes** - Run `php artisan test` after implementing tests
2. **Check rate limiting** - Use browser dev tools to verify 429 responses
3. **Preserve German UI** - Keep all user-facing text in German
4. **Update progress log** - Document what you did in PROGRESS_LOG.md before commits
5. **One task at a time** - Complete and test each item before moving to next

## Quick Command Reference

```bash
# Run tests
php artisan test

# Create Form Request
php artisan make:request UpdateBulletinRequest

# Create Service
php artisan make:service CalendarService  # Note: might need manual creation

# Create Test
php artisan make:test AuthenticationTest

# Check routes
php artisan route:list | grep login
```