# Steinerschule Elternaktivitäten

A web application for managing parent volunteer activities and community engagement at Steinerschule Langnau.

## Overview

This platform enables parents and organizers to coordinate volunteer activities, manage shift schedules, and facilitate community discussions for the school. Built with modern web technologies, it provides an intuitive interface for both organizers and volunteers.

## Features

### Four Main Sections

#### Pinnwand (Bulletin Board)
- Urgent help requests from parent activity organizers
- Priority labeling system (Dringend, Wichtig, Last Minute)
- Category-based filtering with distinct colors
- Date-based display with automatic status indicators
- Secure organizer editing via magic links (no login required)

#### Elternaktivitäten (Parent Activities)
- Complete directory of all parent groups and committees
- Seven distinct categories with visual color coding
- Contact information for group leaders
- Meeting schedules and locations
- Integrated discussion forums for each activity

#### Schichtkalender (Shift Calendar)
- Shows ALL shifts and activity dates from parent groups
- Month view with color-coded activities
- Mobile swipe navigation for easy browsing
- AJAX-powered smooth month transitions
- Detailed list view below calendar grid

#### Schulkalender (School Calendar)
- Official school events, holidays, and conferences
- Event type categorization (Festival, Meeting, Performance, etc.)
- Mobile-friendly with swipe gestures
- Seamless navigation without page reloads

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

### User Profiles
- Personal profile pages with contact information
- Phone number and remarks fields for additional details
- Password management and security settings
- Personal shift history and upcoming commitments
- Public profile view for other users

### Notification System
- System-wide announcements for all users
- Priority notifications for urgent information
- Dismissible notifications with tracking
- Type-based styling (urgent, reminder, announcement, info)
- Automatic expiry management with scheduled jobs
- Character limits with real-time counter

### Administration
- Comprehensive admin panel for content management
- Multi-tier user roles (User, Admin, Super Admin)
- Content moderation capabilities
- Activity and volunteer reporting
- German localization for admin interface
- Announcement management for super admins only
- Export functionality for volunteer data (CSV/XLSX)
- Import functionality for bulk data management
- Audit logging for critical system actions
- New school year preparation tools

### Security & Data Protection
- **Rate limiting**: Maximum 5 login attempts per minute
- **Security headers**: XSS, CSRF, clickjacking protection
- **Password policy**: Minimum 8 characters (simplified for ease of use)
- **GDPR compliance**: Full data deletion and anonymization support
- **Privacy-first**: No tracking, analytics, or third-party cookies
- **Search engine blocking**: robots.txt and meta tags prevent indexing
- **Protected user data**: Volunteer names hidden from non-authenticated users
- **Session security**: Automatic regeneration on login
- **HTTPS ready**: Strict-Transport-Security for production

## Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: SQLite
- **Frontend**: Blade templates with Alpine.js
- **Styling**: Tailwind CSS 4 with custom Steiner School theme
- **Admin Panel**: Filament 3 (German localized)
- **Build Tool**: Vite
- **Authentication**: Laravel Breeze
- **Scheduler**: Laravel Task Scheduling for automated jobs

### Design System
- **Theme Colors**: Steiner School blue palette (rgb(57, 123, 161))
- **Components**: Reusable Blade components (cards, buttons, forms, alerts)
- **Typography**: Consistent sizing with text-sm as base
- **Spacing**: Standardized using Tailwind's 4px grid
- **Category Colors**: Seven distinct colors for visual differentiation
- **Swiss German**: Using 'ss' instead of 'ss' throughout

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

## Security & Data Protection

### Security Features
- CSRF protection on all forms
- Token-based editing for non-authenticated organizers
- IP tracking for moderation purposes
- Regular security updates via Composer
- Search engine blocking (noindex, nofollow)

### GDPR Compliance & Data Protection
- **Right to be Forgotten**: Two-tier user deletion system
  - Soft deletion (deactivation): Reversible account suspension
  - GDPR anonymization: Permanent anonymization of personal data
- **Audit Trail**: Complete logging of all deletion actions
- **Data Minimization**: Only essential user data collected
- **Privacy by Design**: No tracking cookies or analytics
- **Transparent Data Use**: Clear privacy policy and data handling
- **User Control**: Users can view and edit their personal data

## Support

For technical issues or questions about the platform, please contact the school's IT coordinator or submit an issue through the appropriate channels.

## License

This project is proprietary software developed for Steinerschule Langnau. All rights reserved.

## Acknowledgments

Developed for the parent community of Steinerschule Langnau to facilitate volunteer coordination and strengthen school community engagement.
