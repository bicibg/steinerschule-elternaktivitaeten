# Steinerschule Elternaktivitäten

A web application for managing parent volunteer activities and community engagement at Steinerschule Langnau.

## Overview

This platform enables parents and organizers to coordinate volunteer activities, manage shift schedules, and facilitate community discussions for the school. Built with modern web technologies, it provides an intuitive interface for both organizers and volunteers.

## Features

### Three Main Sections

#### Pinnwand (Bulletin Board)
- Activities seeking volunteer help with shift management
- Priority labeling system for urgent or important activities
- Date-based filtering and archiving
- Secure organizer editing via magic links (no login required for organizers)

#### Elternaktivitäten (Parent Activities)
- Comprehensive directory of all parent groups and committees
- Contact information for group leaders
- Meeting schedules and locations
- Discussion forums for each activity group

#### Dual Calendar System
- **Activity Calendar**: Shows all volunteer opportunities and shifts
- **School Calendar**: Displays school events, holidays, and conferences
- Context-aware navigation between related events

### Volunteer Coordination
- Shift-based signup system with capacity management
- Volunteer registration without requiring user accounts
- Real-time availability tracking
- Contact information collection for coordinators

### Community Forum
- Discussion threads for each activity
- Anonymous posting capability with moderation controls
- Nested comments and replies
- Content moderation tools for administrators

### Enhanced Navigation
- Context-aware back buttons (returns to origin page)
- Clickable calendar events with detailed views
- Mobile-friendly interface for all features
- Category-based filtering across all sections

### Administration
- Comprehensive admin panel for content management
- Multi-tier user roles (User, Admin, Super Admin)
- Content moderation capabilities
- Activity and volunteer reporting

## Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: SQLite
- **Frontend**: Blade templates with Alpine.js
- **Styling**: Tailwind CSS 4
- **Admin Panel**: Filament 3
- **Build Tool**: Vite
- **Authentication**: Laravel Breeze

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite

## Installation

1. Clone the repository
```bash
git clone [repository-url]
cd steinerschule-elternaktivitaeten
```

2. Install PHP dependencies
```bash
composer install
```

3. Install Node dependencies
```bash
npm install
```

4. Set up environment configuration
```bash
cp .env.example .env
php artisan key:generate
```

5. Set up the database
```bash
php artisan migrate:fresh --seed
```

6. Build frontend assets
```bash
npm run build
```

## Development

Start the development environment with all necessary services:
```bash
composer run dev
```

This will concurrently run:
- Laravel development server
- Vite development server with hot module replacement
- Queue worker for background jobs
- Real-time log monitoring

Alternatively, run services individually:
```bash
npm run dev    # Vite dev server
php artisan serve    # Laravel server
```

## Usage

### For Organizers
1. Create activities through the admin panel or contact form
2. Receive a magic edit link via email
3. Manage shifts and volunteer signups
4. Monitor discussions and volunteer registrations

### For Volunteers
1. Browse three main sections:
   - **Pinnwand**: Activities needing help
   - **Elternaktivitäten**: All parent groups and committees
   - **Kalender**: Upcoming events and opportunities
2. Sign up for shifts without creating an account
3. Participate in activity discussions
4. View detailed information for any event or activity

### For Administrators
1. Access the admin panel at `/admin`
2. Manage all activities, users, and content
3. Moderate forum discussions
4. Generate reports on volunteer participation

## Testing

### Demo Access
- Demo user: `demo@example.com` / `demo123456`
- Admin access: Contact system administrator

### Running Tests
```bash
php artisan test
```

## Deployment

1. Ensure production environment settings in `.env`
2. Run production build:
```bash
npm run build
php artisan optimize
```

3. Set up appropriate file permissions
4. Configure web server (Apache/Nginx)
5. Set up SSL certificate for secure access

## Security

- CSRF protection on all forms
- Token-based editing for non-authenticated organizers
- IP tracking for moderation purposes
- Regular security updates via Composer

## Support

For technical issues or questions about the platform, please contact the school's IT coordinator or submit an issue through the appropriate channels.

## License

This project is proprietary software developed for Steinerschule Langnau. All rights reserved.

## Acknowledgments

Developed for the parent community of Steinerschule Langnau to facilitate volunteer coordination and strengthen school community engagement.