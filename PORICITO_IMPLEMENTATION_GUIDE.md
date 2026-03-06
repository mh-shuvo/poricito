# Poricito - Memorial Archive Application

## Project Overview

**Poricito** is a Laravel-based memorial archive system for honoring and preserving memories of known persons who have passed away. The application allows contributors to submit memorial profiles with location details and multiple photos, while admins review and approve them for public display.

---

## Key Features Implemented

### 1. **Role-Based Access Control**
- **Admin**: Full access to all memorials, approve/reject submissions, manage contributors
- **Contributor**: Create and manage their own memorials, view approval status, receive feedback

### 2. **Multi-Level Location Hierarchy**
- District → Thana → Union → Ward
- Cascading dropdowns for easy location selection
- Optimized for Bangladesh administrative divisions

### 3. **Memorial Management**
- **Contributors** can:
  - Create new memorials (name, birth/death dates, bio, location)
  - Upload multiple photos per memorial
  - Edit pending/rejected submissions
  - See approval status and admin feedback on their dashboard
  
- **Admins** can:
  - Review pending memorials
  - Approve or reject with **required feedback notes**
  - Edit any memorial
  - Delete memorials (soft delete)

### 4. **Public Memorial Listing**
- Search memorials by person's name
- Filter by location (District, Thana, Union, Ward)
- Beautiful memorial cards with first image preview
- Detailed memorial pages with photo gallery and biography

### 5. **Authentication & Dashboards**
- Unified login form with role-based redirect
- **Admin Dashboard**: Overview of pending/approved memorials and total contributors
- **Contributor Dashboard**: Personal statistics and quick access to memorials
- **Public Website**: Browse memorials without login

---

## Database Structure

### Tables Created:
1. **users** - Authentication (extended with `role` column: admin/contributor)
2. **districts** - Top-level administrative divisions
3. **thanas** - Sub-divisions under districts
4. **unions** - Sub-divisions under thanas
5. **wards** - Lowest-level divisions under unions
6. **memorials** - Main memorial records with approval workflow
7. **memorial_photos** - Photos associated with each memorial

### Key Relationships:
- User → Many Memorials (as contributor)
- Memorial → Ward (single location)
- Memorial → Multiple MemorialPhotos
- District → Thanas → Unions → Wards (hierarchical)

---

## API Endpoints

### Public Routes:
- `GET /` - Landing page
- `GET /memorials` - Browse approved memorials with filters
- `GET /memorials/{id}` - View specific memorial

### Authentication:
- `GET /login` - Login form
- `POST /login` - Process login
- `POST /logout` - Logout

### Admin Routes (requires admin role):
- `GET /admin/dashboard` - Admin overview
- `GET /admin/memorials` - List all memorials
- `GET /admin/memorials/{id}` - View memorial for approval
- `POST /admin/memorials/{id}/approve` - Approve memorial
- `POST /admin/memorials/{id}/reject` - Reject with notes
- `GET|POST /admin/contributors` - Manage contributors

### Contributor Routes (requires contributor role):
- `GET /contributor/dashboard` - Personal statistics
- `GET|POST /contributor/memorials` - Create/list own memorials
- `GET /contributor/memorials/{id}` - View own memorial
- `PUT /contributor/memorials/{id}` - Edit pending/rejected

### API Route:
- `GET /api/locations` - Get all location data for cascading dropdowns

---

## Setup Instructions

### Prerequisites:
- PHP 8.2+
- Composer
- Laravel 12
- SQLite or MySQL
- Node.js & npm (for assets)

### Installation Steps:

1. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

2. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

3. **Run migrations:**
   ```bash
   php artisan migrate
   ```

4. **Seed test data:**
   ```bash
   php artisan db:seed
   ```

5. **Build assets:**
   ```bash
   npm run build
   ```

6. **Create storage symlink (for image uploads):**
   ```bash
   php artisan storage:link
   ```

7. **Start development server:**
   ```bash
   php artisan serve
   ```

   Or use the provided development command:
   ```bash
   composer dev
   ```

---

## Test Credentials

After running seeders, use these accounts to test:

### Admin Account:
- **Email:** admin@poricito.local
- **Password:** password
- **URL:** http://localhost:8000/login → /admin/dashboard

### Contributor Account:
- **Email:** contributor@poricito.local
- **Password:** password
- **URL:** http://localhost:8000/login → /contributor/dashboard

### Public Website:
- **URL:** http://localhost:8000/ (Browse memorials without login)

---

## Workflow Example

### 1. **Contributor Creates Memorial:**
   - Navigate to `/contributor/memorials/create`
   - Fill in: Name, Birth/Death dates, Location (District→Thana→Union→Ward), Biography
   - Submit → Status: **Pending**

### 2. **Admin Reviews:**
   - Navigate to `/admin/memorials`
   - Click on pending memorial
   - Two options:
     - **Approve** → Memorial appears on public website immediately
     - **Reject** → Add required notes explaining what needs revision

### 3. **Contributor Revises (if rejected):**
   - Views rejection notes on their memorial's dashboard
   - Clicks **Edit** button
   - Updates information and resubmits
   - Status returns to **Pending** for re-review

### 4. **Public Views:**
   - Navigate to `/memorials`
   - Only **approved** memorials visible
   - Search by name or filter by location
   - Click memorial card to view full details and photo gallery

---

## File Structure

```
poricito/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/AuthController.php
│   │   │   ├── Admin/MemorialController.php
│   │   │   ├── Admin/ContributorController.php
│   │   │   ├── Contributor/MemorialController.php
│   │   │   ├── MemorialController.php (public)
│   │   │   └── Api/LocationController.php
│   │   └── Middleware/
│   │       ├── EnsureAdminRole.php
│   │       └── EnsureContributorRole.php
│   ├── Models/
│   │   ├── User.php (extended)
│   │   ├── Memorial.php
│   │   ├── MemorialPhoto.php
│   │   ├── District.php
│   │   ├── Thana.php
│   │   ├── Union.php
│   │   └── Ward.php
│   └── Policies/
│       └── MemorialPolicy.php
├── database/
│   ├── migrations/ (7 new migrations)
│   └── seeders/
│       ├── LocationSeeder.php
│       └── DatabaseSeeder.php (updated)
├── resources/
│   └── views/
│       ├── auth/login.blade.php
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   ├── contributors/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── show.blade.php
│       │   └── memorials/
│       │       ├── index.blade.php
│       │       ├── show.blade.php
│       │       └── edit.blade.php
│       ├── contributor/
│       │   ├── dashboard.blade.php
│       │   └── memorials/
│       │       ├── index.blade.php
│       │       ├── create.blade.php
│       │       ├── show.blade.php
│       │       └── edit.blade.php
│       └── memorials/ (public)
│           ├── index.blade.php (with filters)
│           └── show.blade.php (full detail)
├── routes/
│   ├── web.php (updated with all routes)
│   └── api.php (location endpoint)
└── bootstrap/
    └── app.php (middleware registered)
```

---

## Next Steps & Future Enhancements

### Phase 2 - Email Notifications:
- Send credentials to newly created contributors
- Notify contributors when memorial is approved/rejected
- Email templates and Mailable classes

### Phase 3 - Photo Management:
- Image upload validation and resizing
- Photo reordering/captioning UI
- Image optimization

### Phase 4 - Advanced Features:
- Guest tribute comments/guestbook
- Family invite links
- Memorial search/filtering improvements
- Admin analytics dashboard
- Bulk memorial import

### Phase 5 - Performance:
- Image caching and CDN integration
- API pagination optimization
- Full-text search indexing
- Admin report generation

---

## Key Design Decisions

1. **Single Location per Memorial**: Simplified data model - each memorial tied to one ward (lowest division)
2. **Approval with Required Notes**: Forces admin feedback on rejection, helping contributors understand requirements
3. **Soft Deletes**: Memorials can be archived, not permanently deleted
4. **Role-Based Middleware**: Clean access control using custom middleware
5. **Cascading Location Selects**: Better UX than long flat dropdowns
6. **Public/Private Separation**: Only approved memorials visible publicly; contributors see drafts in dashboard

---

## Terminology

- **Contributor** (used instead of Moderator): Person who adds memorials to the system
- **Admin**: Approves/rejects submissions and manages the system
- **Memorial**: Profile/page for a deceased person with photos and biography

---

## Support & Troubleshooting

### Common Issues:

**Q: Storage images not showing?**
A: Run `php artisan storage:link` to create the symbolic link.

**Q: Cascading selects not working?**
A: Ensure JavaScript is enabled and the API endpoint `/api/locations` is accessible.

**Q: Can't login?**
A: Check if migrations were run: `php artisan migrate`
Verify test data seeded: `php artisan db:seed`

---

## License

Poricito is open-source and free to use for memorial archiving purposes.

---

**Built with Laravel 12 & Tailwind CSS | Designed for honoring memories**
