# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project: Steinerschule Elternaktivitaeten

Laravel 12 app for coordinating parent volunteer activities at Steinerschule Langnau. Replaces chaotic email chains and WhatsApp groups with a simple platform for shift signups, activity directories, and school calendars.

Built and donated by one parent (Bugra Ergin) - NOT a business. All user-facing content is in German.

## Behavioral Rules

- **NO SIGNING**: Never sign commits with Claude's name, attribution, or "Co-Authored-By: Claude"
- Simple commit messages without emoji or attribution
- **Be critical**: Challenge bad ideas directly. Don't agree for the sake of agreeing
- **Say "I" not "we"**: One person volunteers, not a team
- **Keep it simple**: Parents are tech-scared, privacy-concerned, German-speaking, and busy
- No technical jargon in user-facing text. No complex password rules. No corporate tone
- Prioritize database safety and module structure

## Common Commands

```bash
# Development (starts server, queue, logs, vite concurrently)
composer dev

# Tests
composer test                              # Full suite (clears config first)
php artisan test                           # Direct execution
php artisan test --filter=AuthenticationTest  # Single test class
php artisan test --filter=test_method_name    # Single test method

# Code style (enforced in CI)
vendor/bin/pint          # Fix style
vendor/bin/pint --test   # Check only (CI runs this)

# Database
php artisan migrate:fresh --seed   # Reset with seed data
php artisan migrate                # Run pending migrations

# Assets
npm run build   # Production build
npm run dev     # Vite dev server with HMR

# Cache
php artisan optimize        # Cache routes/views/config
php artisan optimize:clear  # Clear all caches
```

## CI Pipeline

GitHub Actions (`.github/workflows/tests.yml`) runs on PRs and pushes to `master`/`*.x`:
- Tests on PHP 8.2, 8.3, 8.4
- `composer audit` and `npm audit`
- `vendor/bin/pint --test` (code style)
- `php artisan test`

Main branch for PRs: `12.x`

## Tech Stack

- **Backend**: Laravel 12, PHP 8.2+
- **Database**: SQLite (local), MySQL (production on DigitalOcean)
- **Frontend**: Blade + Alpine.js + Tailwind CSS 4
- **Admin**: Filament 3 (German localized) at `/admin`
- **Auth**: Laravel Breeze (8 char min password) + honeypot spam protection
- **Build**: Vite
- **Tests**: PHPUnit with in-memory SQLite

## Architecture

### Code Organization

```
app/
  Http/
    Controllers/          # Web controllers (Blade views)
    Controllers/Api/      # JSON API controllers (Alpine.js AJAX)
    Controllers/Auth/     # Password reset controllers
    Middleware/            # SecurityHeaders, VerifyEditToken, FilamentAdminMiddleware, EnsureSuperAdmin
    Requests/             # Form request validation classes
  Models/                 # Eloquent models
  Services/               # CalendarService, ShiftService, IcsImportService
  Repositories/           # BulletinPostRepository, ShiftRepository
  Filament/
    Resources/            # CRUD admin interfaces
    Pages/YearReset.php   # School year prep
    Exports/              # CSV/XLSX exporters
    Imports/              # Bulk data importers
```

### Core Models & Relationships

```
BulletinPost (Pinnwand - help requests)
  +-- Shift (volunteer slots with capacity: needed vs filled)
  |     +-- ShiftVolunteer
  +-- Post (forum threads)
        +-- Comment

Activity (Elternaktivitaeten - parent groups)
  +-- ActivityPost
        +-- ActivityComment

SchoolEvent (Schulkalender - school events, standalone)
User -> ShiftVolunteer (volunteers for shifts)
```

### Critical Patterns

**Shift capacity**: Always use `$shift->filled` accessor (combines `offline_filled` + online volunteer count). Never count volunteers manually.

**Eager loading**: Always eager load relationships to prevent N+1 queries. Use `->with(['shifts.volunteers'])` for calendar/bulletin views. The Shift model uses `relationLoaded()` to check.

**Two auth mechanisms**:
1. Standard login (email/password via Laravel Breeze)
2. Magic edit links: `/pinnwand/{slug}/edit?token={edit_token}` - lets organizers edit without accounts, verified by `VerifyEditToken` middleware

**Three user roles**: User, Admin, Super Admin (stored in `users.role`)

### API Routes (Alpine.js)

All under `/api` prefix with `throttle:60,1`:
- Shift volunteers: `POST/DELETE /api/shifts/{shift}/signup|withdraw`, `GET /api/shifts/{shift}/volunteers`
- Forum: `GET/POST /api/bulletin-posts/{slug}/forum`, `GET/POST /api/posts/{post}/comments`
- Activities: `POST /api/elternaktivitaeten/{slug}/posts`, `POST /api/activity-posts/{post}/comments`

### Main Public Routes

- `/` redirects to `/pinnwand`
- `/pinnwand` - Bulletin board (urgent help requests)
- `/elternaktivitaeten` - Parent activity directory
- `/kalender` - Shift calendar (month view, AJAX navigation)
- `/schulkalender` - School events calendar
- `/profile`, `/my-shifts` - User pages (auth required)

### Frontend

- **Tailwind theme colors**: `steiner-blue`, `steiner-dark`, `steiner-light`, `steiner-lighter`
- **Blade components**: `resources/views/components/` (cards, buttons, forms, alerts)
- **Alpine.js**: Shift signup forms, calendar navigation, forum threads, announcement dismissal
- **Mobile-first**: Parents primarily use phones

### Database

- Migrations use sequential timestamps for foreign key ordering
- Tests use in-memory SQLite (`:memory:` in `phpunit.xml`)
- GDPR: Two-tier deletion (soft delete vs permanent anonymization via `UserDeletionLog`)
- `AuditLog` tracks critical system actions

## Development Workflow

1. Check `docs/TODO_CHECKLIST.md` for open items
2. Update `docs/PROGRESS_LOG.md` before committing
3. Run `composer test` before committing
4. Commit after each completed item with a clear message

## Test Account (Seeded)

- Admin: `bugraergin@gmail.com` / `123456789`
