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
- Rate limiting on login (5 attempts/minute)
- Security headers (but allow localhost for dev)
- Simplified passwords (just 8 chars - parents get scared of complex rules)
- Privacy: volunteer names hidden from non-authenticated users
- GDPR: two-tier deletion (soft delete vs permanent anonymization)
- Legal pages: /datenschutz, /sicherheit, /impressum, /kontakt

### What NOT to do:
- Don't add complex features - parents need SIMPLE
- Don't use technical language - they get scared
- Don't make passwords complex - they'll complain
- Don't say "we" or "our team" - it's ONE volunteer (Buğra)
- Don't overcomplicate - this needs to solve email chaos, nothing more