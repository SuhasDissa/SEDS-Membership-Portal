# SEDS Mora Membership Portal

A comprehensive membership portal for SEDS Mora (Students for Exploration and Development of Space, University of Moratuwa).

## Tech Stack

- **Laravel 12** - PHP Framework
- **Livewire Volt** - Full-stack framework (Class-based components)
- **Tailwind CSS 4** - Utility-first CSS framework
- **DaisyUI** - Tailwind CSS component library (no prefix)
- **MaryUI** - Laravel Livewire component library
- **AOS** - Animate On Scroll library
- **Laravel Socialite** - OAuth authentication

## Features

### Authentication
- Email/Password registration and login
- Google OAuth Sign-In
- Profile completion flow for new users

### Student Dashboard
- Community feed showing posts from members
- Contribution tracker with monthly statistics
- Log activities/contributions with approval workflow
- Personal contribution history

### Admin Dashboard
- Member management (approve, ban, promote to admin)
- View all members with filtering by faculty and search
- Individual member detail pages
- Statistics overview
- Pending contributions review

### Profile Management
- University ID, Faculty, Department, Phone
- Profile photo (URL or Google avatar)
- Edit profile information

## Installation

### 1. Clone the repository
```bash
cd /home/suhasdissa/Documents/SuhasDissa/SEDS_Portal
```

### 2. Install dependencies
```bash
composer install
npm install
```

### 3. Environment setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Google OAuth
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable Google+ API
4. Create OAuth 2.0 credentials
5. Add authorized redirect URI: `http://localhost:8000/auth/google/callback`
6. Update `.env` file:
```env
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

### 5. Database setup
```bash
# Create SQLite database (default)
touch database/database.sqlite

# Run migrations
php artisan migrate
```

### 6. Run the application
```bash
# Development mode (runs server, queue, logs, and vite)
composer dev

# Or run separately:
php artisan serve
npm run dev
```

## Usage

### Creating the First Admin
After registering your first user, promote them to admin using:
```bash
php artisan user:promote your@email.com
```

### Default Routes
- `/` - Landing page
- `/login` - Login page
- `/register` - Registration page
- `/onboarding` - Profile completion (auto-redirected if incomplete)
- `/dashboard` - Student dashboard
- `/contributions` - View all contributions
- `/profile` - User profile
- `/settings` - Edit profile
- `/admin` - Admin dashboard (admin only)
- `/admin/members` - Member management (admin only)

## Design Constraints

### Color Usage
- **Strictly use DaisyUI semantic classes only**
- Primary, Secondary, Accent, Base-100, Success, Warning, Error
- **No arbitrary hex codes or custom colors**

### Animations
- AOS (Animate On Scroll) library for entry animations
- Applied to landing page elements and dashboard cards

### UI Components
- Use MaryUI components wherever possible
- `x-mary-header`, `x-mary-list`, `x-mary-stat`, `x-mary-table`, etc.

## Database Schema

### Users Table
- `id`, `name`, `email`, `password`
- `university_id` (unique, format: 6 digits + letter, e.g., 230152X)
- `faculty` (enum: Engineering, IT, Architecture, Business, Science, Other)
- `department`, `phone`, `avatar_url`
- `is_admin` (boolean, default: false)
- `is_approved` (boolean, default: false)
- `google_id` (for OAuth)

### Contributions Table
- `id`, `user_id`, `title`, `description`, `date`
- `status` (enum: pending, approved)

### Posts Table
- `id`, `user_id`, `content`, `image_url`

## Middleware

- `profile.completed` - Ensures user has completed profile before accessing protected routes
- `admin` - Ensures user is an admin before accessing admin routes

## Artisan Commands

### Promote User to Admin
```bash
php artisan user:promote {email}
```

## Development Notes

- All Livewire Volt components use functional API
- AOS initialization in `components/layouts/app.blade.php`
- Guest layout for landing and auth pages
- App layout with sidebar for authenticated pages

## License

MIT License
