# Claude Development Notes

## Project: Steinerschule Elternaktivitäten

### Important Instructions
- **NO SIGNING**: Do not sign commits with Claude's name or attribution
- Simple commit messages without emoji or attribution
- **CRITICAL THINKING**: Criticize ideas and brainstorm alternatives. Don't agree for the sake of being agreeable

### Project Structure
- Laravel 12 application for school parent activities
- SQLite database
- Tailwind CSS 4 with @source directives
- Alpine.js for interactivity
- Filament admin panel

### Key Features
1. Activity listings with organizer info
2. Forum/discussion system under activities
3. Shift planning with volunteer signup
4. Magic link editing for organizers
5. Authentication system with demo login
6. Mobile-responsive design

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