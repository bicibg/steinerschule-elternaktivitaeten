# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project: Steinerschule Elternaktivitäten

### 🚨 CRITICAL PROJECT CONTEXT - READ THIS FIRST!

**The Real Problem We're Solving:**
- Email chains with 50+ replies for simple volunteer coordination
- WhatsApp: "Does anyone know if setup still needs help?" asked 5 times
- Same parents always volunteer while others want to help but don't know how
- Organizers scramble last-minute when they realize they're short-staffed

**Who & Why:**
- **Buğra Ergin** - parent at the school, volunteering his time (NOT a business!)
- Built this for free to help the school community
- Meeting with school admin **Marianne on Friday Oct 19** for approval
- Already presented at school conference - positive reception

**Key Numbers:**
- Our solution: CHF 240/year (just domain cost)
- SignupNow alternative: CHF 1,020/year (and doesn't fit our needs)
- Development value: ~CHF 20,000 (donated)

### Important Instructions
- **NO SIGNING**: Do not sign commits with Claude's name or attribution
- Simple commit messages without emoji or attribution
- **CRITICAL THINKING**: Criticize ideas and brainstorm alternatives. Don't agree for the sake of being agreeable
- **LANGUAGE**: Always use "I" not "we" - it's ONE person volunteering!
- **TONE**: Personal, not corporate. These are parents, not tech people

### User Psychology & Concerns (IMPORTANT!)
**These parents are:**
- Tech-scared - avoid ALL technical jargon
- Privacy-concerned - emphasize no tracking, no Google
- Busy - need SIMPLE solutions
- German-speaking - everything must be in German

**Security/Privacy approach:**
- Passwords: Just 8 characters minimum (they panic with complex rules)
- Data: "Your data stays here, I don't sell it, I'm a parent too"
- No technical explanations about XSS, SQL injection etc. - they don't care and it scares them

### Project Structure
- Laravel 12 application for school parent activities
- SQLite for local development, MySQL for production (DigitalOcean)
- Tailwind CSS 4 with Steiner school colors
- Alpine.js for interactivity
- Filament 3 admin panel (German localized)
- Vite for asset building

### Key Features (What Actually Matters)
1. **Live volunteer counters** - "3 of 5 helpers found"
2. **One-click signup** - No complex registration required
3. **Automatic contact lists** - Organizers get all helper contacts
4. **Privacy protected** - Volunteer names hidden from non-logged users
5. **Mobile-first** - Parents check on their phones
6. **GDPR compliant** - Full deletion/anonymization support

## Architecture & Code Organization

### Core Domain Models

The application has four main content types, each with its own model hierarchy:

1. **BulletinPost** (Pinnwand - Urgent Help Requests)
   - Has many `Shift` (volunteer shifts with capacity tracking)
   - Has many `Post` (forum discussions)
   - Uses edit tokens for organizer access without login
   - Fields: title, description, priority, category, date, edit_token

2. **Activity** (Elternaktivitäten - Parent Groups)
   - Has many `ActivityPost` (forum discussions)
   - Category-based organization (anlass, haus_umgebung_taskforces, produktion, organisation, verkauf)
   - Fields: title, description, category, contact info, meeting details
   - Slug generation: `{title}-{random6}`

3. **Shift** (Volunteer Shift Management)
   - Belongs to `BulletinPost`
   - Has many `ShiftVolunteer`
   - Capacity tracking: `needed` vs `filled` (offline_filled + online volunteers)
   - **IMPORTANT**: Always use `$shift->filled` attribute (NOT manual counting)
   - **IMPORTANT**: Eager load with `->with(['volunteers'])` to avoid N+1 queries

4. **SchoolEvent** (Schulkalender - School Events)
   - Standalone events (festivals, conferences, holidays)
   - Event type categorization
   - Slug-based URLs

### User Roles & Permissions

Three-tier role system (stored in `users` table):
- **User**: Can signup for shifts, post in forums, manage own profile
- **Admin**: Full content management via Filament panel, moderate discussions
- **Super Admin**: All admin powers + announcements + year reset + audit logs

### Authentication & Token-Based Editing

Two authentication mechanisms:
1. **Standard Auth**: Laravel Breeze with email/password (min 8 chars)
2. **Magic Edit Links**: Token-based editing for organizers without accounts
   - Format: `/pinnwand/{slug}/edit?token={edit_token}`
   - Verified by `VerifyEditToken` middleware
   - Allows non-authenticated organizers to manage their bulletin posts

### API Architecture (Alpine.js Integration)

RESTful API routes under `/api` prefix for AJAX interactions:
- **Shift volunteers**: `/api/shifts/{shift}/volunteers` (GET, POST, DELETE)
- **Forum posts**: `/api/bulletin-posts/{slug}/forum` (GET, POST)
- **Forum comments**: `/api/forum-posts/{post}/comments` (GET, POST, DELETE)
- All API routes return JSON for Alpine.js components

### N+1 Query Prevention Strategy

**CRITICAL**: This codebase prioritizes eager loading to prevent N+1 queries:
- Calendar views: `->with(['shifts.volunteers'])`
- Shift model uses `relationLoaded()` to check for eager-loaded data
- When adding queries, always eager load relationships

### Filament Admin Panel

Location: `app/Filament/`
- Resources: CRUD interfaces for all models
- Custom pages: `YearReset.php` for new school year prep
- Exporters: CSV/XLSX export functionality
- Importers: Bulk data import
- German localization throughout

### Security Implementation

**Rate Limiting**: Applied to sensitive routes
- Login: `throttle:5,1` (5 attempts per minute)

**Middleware Stack**:
- `SecurityHeaders`: XSS, CSRF, clickjacking protection (allows localhost for dev)
- `VerifyEditToken`: Token validation for magic edit links
- `FilamentAdminMiddleware`: Role-based admin access

**GDPR Compliance**:
- Two-tier deletion: soft delete (reversible) vs GDPR anonymization (permanent)
- `UserDeletionLog` model tracks all deletions
- `AuditLog` model tracks critical system actions

### Database Design Notes

**Migration Ordering**:
- Use sequential timestamps to control execution order
- Foreign key tables must run AFTER referenced tables
- Example: `2025_09_14_142344_create_shifts_table.php` before `2025_09_14_142345_create_shift_volunteers_table.php`

**Key Relationships**:
```
BulletinPost
  ├── shifts (1:many)
  │   └── volunteers (1:many)
  └── posts (1:many)
      └── comments (1:many)

Activity
  └── posts (1:many)
      └── comments (1:many)
```

### Frontend Architecture

**Blade Components**: Reusable components in `resources/views/components/`
- Card layouts, buttons, forms, alerts
- Consistent styling via Tailwind classes

**Alpine.js Patterns**:
- Shift signup forms with live capacity updates
- Calendar navigation (month switching without page reload)
- Forum comment threads
- Announcement dismissal

**Tailwind Theme**:
- Custom Steiner colors: `steiner-blue`, `steiner-dark`, `steiner-light`, `steiner-lighter`
- Safelist for dynamic calendar colors
- Mobile-first responsive design

### Common Commands

**Development Environment**:
```bash
# Start all services concurrently (server, queue, logs, vite)
composer dev

# Or start individually:
npm run dev              # Vite dev server with HMR
php artisan serve        # Laravel server
php artisan queue:listen # Background jobs
php artisan pail         # Real-time logs
```

**Database**:
```bash
# Fresh migration with seed data
php artisan migrate:fresh --seed

# Run migrations only
php artisan migrate

# Create new migration
php artisan make:migration create_table_name
```

**Assets**:
```bash
npm run build           # Production build
npm run dev            # Development with hot reload
```

**Testing**:
```bash
composer test          # Run test suite (clears config first)
php artisan test       # Direct test execution
```

**Code Quality**:
```bash
php artisan optimize    # Cache routes/views/config for production
php artisan optimize:clear  # Clear all caches
```

### Testing Accounts
- Demo user: `demo@example.com` / `demo123456`
- Demo admin: Separate login button (uses demo admin account)
- Admin: `bugraergin@gmail.com` / `123456789`

### Security Features Summary
- ✅ Rate limiting on login (5 attempts/minute)
- ✅ Security headers (XSS, CSRF, clickjacking protection)
- ✅ Password policy: 8 character minimum (simplified for parent audience)
- ✅ Privacy: Volunteer names hidden from non-authenticated users
- ✅ GDPR: Two-tier deletion (soft delete vs permanent anonymization)
- ✅ Legal pages: `/datenschutz`, `/impressum`, `/kontakt`
- ✅ Search engine blocking (robots.txt + meta noindex/nofollow)
- ✅ Honeypot spam protection on registration/login

## 📋 Development Workflow (IMPORTANT - Follow This!)

### Before Starting Any Work
1. **Check the TODO list**: Open `docs/TODO_CHECKLIST.md`
2. **Start with CRITICAL items first** - these are security issues
3. **Mark items with [x] when completed** - keep the checklist updated
4. **One task at a time** - complete and test before moving to next

### Before Making ANY Commits
1. **Update Progress Log**: Add entry to `docs/PROGRESS_LOG.md`
   - What you completed
   - Any issues encountered
   - What's next
2. **Run tests if they exist**: `php artisan test`
3. **Verify your changes work**: Test manually in browser
4. **Then commit** with clear message (no emojis, no attribution)

### Documentation Structure
```
docs/
├── SANITY_CHECK_REPORT.md  # Full code review findings
├── TODO_CHECKLIST.md        # ✅ CHECK THIS FIRST! Actionable tasks
└── PROGRESS_LOG.md          # Update before EVERY commit
```

### Priority Order
1. 🚨 **CRITICAL** (Security) - Do immediately
2. ⚠️ **HIGH** (No tests) - Do this week
3. 📝 **MEDIUM** (Code quality) - Improve over time
4. 🔧 **LOW** (Nice to have) - When time permits

### Current Status
- **Sanity check completed**: 2025-01-18
- **17 total issues found**: 1 critical, 3 high, 8 medium, 5 low
- **Most urgent**: Add rate limiting to login route (2 minute fix)
- See `docs/TODO_CHECKLIST.md` for complete list

### Environment Configuration

**Database**:
- Local development: SQLite (`database/database.sqlite`)
- Production: MySQL on DigitalOcean
- Both configurations are intentional and correct

**Key .env Variables**:
- `APP_ENV`: local vs production
- `DB_CONNECTION`: sqlite vs mysql
- `APP_URL`: Set correctly for magic edit links to work

### Development Rules & Learnings

#### Commit Strategy
- **COMMIT AFTER EACH TODO ITEM**: When completing a checklist item, commit immediately
- Update PROGRESS_LOG.md before committing
- Clear, simple commit messages (no emojis, no attribution)

#### Project Status & Decisions
- **This is a NEW PROJECT** - not yet live, no need for backward compatibility
- **Eager Loading**: Always eager load relationships to avoid N+1 queries
  - Example: `->with(['shifts.volunteers'])` for calendar views
- **Shift Calculations**: Use `filled` attribute (combines offline_filled + online volunteers)
- **AJAX Issues**: Calendar navigation needs proper eager loading for shift counts

#### Known Issues Fixed
- Calendar AJAX navigation wasn't loading volunteer counts (fixed by eager loading)
- Shift model now uses `relationLoaded()` to avoid N+1 queries
- Views standardized to use `$shift->filled` instead of mixed approaches

### What NOT to do:
- Don't add complex features - parents need SIMPLE
- Don't use technical language - they get scared
- Don't make passwords complex - they'll complain
- Don't say "we" or "our team" - it's ONE volunteer (Buğra)
- Don't overcomplicate - this needs to solve email chaos, nothing more

## Application Sections & Routes

The application has four main public sections:

### 1. Pinnwand (Bulletin Board) - `/pinnwand`
**Purpose**: Urgent help requests from activity organizers
- Route: `BulletinController`
- Lists bulletin posts with priority labels (Dringend, Wichtig, Last Minute)
- Each post can have multiple shifts (volunteer opportunities)
- Organizers get magic edit links to manage without login
- Public forum for questions/discussion

### 2. Elternaktivitäten (Parent Activities) - `/elternaktivitaeten`
**Purpose**: Directory of all parent groups and committees
- Route: `ActivityController`
- Category-based organization (5 categories)
- Contact information for group leaders
- Meeting schedules and locations
- Optional forum for each activity (toggle via `has_forum`)

### 3. Kalender (Shift Calendar) - `/kalender`
**Purpose**: Shows ALL upcoming shifts from bulletin posts
- Route: `CalendarController`
- Month view with color-coded activities
- AJAX navigation for smooth month transitions
- Shows volunteer capacity for each shift

### 4. Schulkalender (School Calendar) - `/schulkalender`
**Purpose**: Official school events, holidays, conferences
- Route: `SchoolCalendarController`
- Event type categorization (Festival, Meeting, Performance, Holiday, etc.)
- Mobile-friendly with swipe navigation
- Admin/authenticated users can create/edit events

### Additional Routes
- `/profile` - User profile management (password, contact info, shift history)
- `/profile/{user}` - Public profile view
- `/my-shifts` - User's upcoming shift commitments
- `/admin` - Filament admin panel (requires admin role)
- Daily Rules for Claude Code

⚠️ CRITICAL: NEVER SIGN COMMITS WITH CLAUDE SIGNATURE
Do NOT add "🤖 Generated with Claude Code" or "Co-Authored-By: Claude" to commits!

🚫 Do NOT

Do NOT say "You are absolutely right."

Do NOT agree just to agree.

Do NOT sign or attribute commit messages to Claude.

✅ Always

Be critical and blunt: if an idea is bad, say so clearly.

Prioritize:

Database safety

Module structure & namespaces