# Claude Development Notes

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
- MySQL database (NOT SQLite - on DigitalOcean)
- Tailwind CSS with Steiner school colors
- Alpine.js for interactivity
- Filament admin panel

### Key Features (What Actually Matters)
1. **Live volunteer counters** - "3 of 5 helpers found"
2. **One-click signup** - No complex registration required
3. **Automatic contact lists** - Organizers get all helper contacts
4. **Privacy protected** - Volunteer names hidden from non-logged users
5. **Mobile-first** - Parents check on their phones
6. **GDPR compliant** - Full deletion/anonymization support

### Common Commands
```bash
# Run migrations
php artisan migrate:fresh --seed

# Build assets
npm run build

# Start dev server
npm run dev
```

### Migration Order Issues
- Always ensure foreign key tables are created AFTER referenced tables
- Use sequential timestamps to control migration order
- Example: shifts table (142344) must run before shift_volunteers (142345)

### Testing Accounts
- Demo user: demo@example.com / demo123456
- Admin: bugraergin@gmail.com / 123456789

### Edit Links Format
Activities have magic edit links: `/aktivitaeten/{slug}/edit?token={edit_token}`

### Recent Security Implementations (Sept 2025)
- Rate limiting on login (5 attempts/minute) ✅ **Implemented 2025-01-18**
- Security headers (but allow localhost for dev)
- Simplified passwords (just 8 chars - parents get scared of complex rules)
- Privacy: volunteer names hidden from non-authenticated users
- GDPR: two-tier deletion (soft delete vs permanent anonymization)
- Legal pages: /datenschutz, /sicherheit, /impressum, /kontakt

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

### Database Note
- **Local development**: SQLite (for simplicity)
- **Production**: MySQL on DigitalOcean
- This is intentional - both configs are correct

### What NOT to do:
- Don't add complex features - parents need SIMPLE
- Don't use technical language - they get scared
- Don't make passwords complex - they'll complain
- Don't say "we" or "our team" - it's ONE volunteer (Buğra)
- Don't overcomplicate - this needs to solve email chaos, nothing more