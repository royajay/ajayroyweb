# Ajay Roy - Portfolio Website (PHP/MySQL)

A complete, production-ready portfolio website for Ajay Roy - SEO Specialist & Digital Marketer. Built with PHP, MySQL, and vanilla JavaScript with an admin panel for content management.

## Features

### Public Website
- **Responsive Design** - Works perfectly on desktop, tablet, and mobile devices
- **Gradient Theme** - Modern purple gradient design (#667eea, #764ba2)
- **Dynamic Content** - All content loaded from database via API endpoints
- **Contact Form** - Visitors can submit inquiries, saved to database
- **Mobile Menu** - Hamburger menu for mobile navigation
- **Smooth Scrolling** - Anchor navigation with smooth scroll behavior

### Admin Panel
- **Secure Authentication** - Username/password login system with bcrypt hashing
- **Dashboard** - Overview of key metrics and recent submissions
- **Content Management**:
  - Manage Services (add, edit, delete, toggle active status)
  - Manage Portfolio Projects (add, edit, delete)
  - Manage Skills with proficiency levels
  - View and manage contact form submissions
  - Edit site statistics and metrics
  - Configure site settings

### Technical Features
- **Database Security** - Prepared statements prevent SQL injection
- **Input Sanitization** - All user inputs sanitized against XSS
- **Session Management** - Secure admin authentication
- **Modular Architecture** - Include-based design for easy maintenance
- **API Endpoints** - RESTful JSON APIs for frontend data loading
- **UTF-8 Support** - Full international character support

## Directory Structure

```
AjayRoy-Website-PHP/
├── index.php                 # Main public homepage
├── process_contact.php       # Contact form handler
├── database.sql             # Database schema and initial data
├── includes/
│   ├── config.php           # Database configuration and utilities
│   └── functions.php        # Helper functions
├── admin/
│   ├── login.php            # Admin login page
│   ├── logout.php           # Logout handler
│   ├── dashboard.php        # Admin dashboard
│   ├── contacts.php         # View/manage contact submissions
│   ├── services.php         # Manage services
│   ├── portfolio.php        # Manage portfolio projects
│   ├── skills.php           # Manage skills
│   ├── statistics.php       # Manage statistics
│   └── settings.php         # Site settings configuration
├── api/
│   ├── get_services.php     # API endpoint for services
│   ├── get_portfolio.php    # API endpoint for portfolio
│   ├── get_skills.php       # API endpoint for skills
│   └── get_statistics.php   # API endpoint for statistics
└── README.md                # This file
```

## Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher (or MariaDB)
- Apache with mod_rewrite enabled (optional, for .htaccess)
- A local development environment (XAMPP, WAMP, MAMP, or similar)

## Quick Start Guide

### 1. Local Installation

See [INSTALLATION.md](INSTALLATION.md) for detailed setup instructions.

### 2. Default Admin Credentials

```
Username: admin
Password: admin123
```

⚠️ **IMPORTANT**: Change these credentials immediately after first login!

### 3. Database Setup

1. Create a new MySQL database named `ajay_roy_portfolio`
2. Import the `database.sql` file into your database
3. Update credentials in `includes/config.php` if needed

### 4. Running Locally

1. Place the project folder in your web server's root directory (`htdocs` for XAMPP, `www` for WAMP)
2. Navigate to `http://localhost/AjayRoy-Website-PHP/`
3. Admin panel: `http://localhost/AjayRoy-Website-PHP/admin/login.php`

## Configuration

### Database Configuration

Edit `includes/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ajay_roy_portfolio');
```

### Site Settings

All site configuration (name, description, contact info, social links) is managed via the admin panel under **Settings**, not hardcoded.

## API Endpoints

The website uses these JSON API endpoints for dynamic content:

- `GET /api/get_services.php` - Returns array of services
- `GET /api/get_portfolio.php` - Returns array of portfolio items
- `GET /api/get_skills.php` - Returns array of skills with proficiency
- `GET /api/get_statistics.php` - Returns array of statistics

- `POST /process_contact.php` - Submits contact form

## Database Schema

### Tables

- **contacts** - Contact form submissions with read status
- **services** - Service offerings with icons and descriptions
- **portfolio** - Project showcase items with categories
- **skills** - Professional skills with proficiency percentages
- **statistics** - Key metrics displayed on homepage
- **testimonials** - Client testimonials (prepared for future use)
- **site_settings** - Global configuration settings
- **admin_users** - Administrator accounts with hashed passwords

## Security Features

- ✅ Prepared statements for SQL injection protection
- ✅ Password hashing with bcrypt
- ✅ Session-based authentication
- ✅ HTML entity encoding for XSS protection
- ✅ Input sanitization and validation
- ✅ HTTPS-ready (deploy with SSL certificate)

## Deployment to cPanel

See [DEPLOYMENT.md](DEPLOYMENT.md) for step-by-step cPanel hosting instructions.

### Quick Checklist:
- [ ] Change admin password
- [ ] Update site settings (contact info, social links)
- [ ] Add your content (services, portfolio, skills)
- [ ] Test contact form
- [ ] Enable HTTPS/SSL certificate
- [ ] Set up automatic backups
- [ ] Monitor logs for errors

## Customization

### Change Color Theme

Edit the CSS in `index.php` and admin pages:

```css
/* Current theme */
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

/* Change to your colors */
background: linear-gradient(135deg, #YOUR_COLOR_1 0%, #YOUR_COLOR_2 100%);
```

### Add More Fields to Contact Form

1. Edit the form in `index.php`
2. Update `process_contact.php` to handle new fields
3. Update the `contacts` table schema if needed

### Customize Admin Dashboard

Edit `admin/dashboard.php` to add custom widgets, charts, or reports.

## Troubleshooting

### Database Connection Error

- Verify MySQL is running
- Check database credentials in `includes/config.php`
- Ensure database and tables exist (import `database.sql`)

### Admin Login Not Working

- Clear browser cookies and cache
- Check php sessions are enabled
- Verify database has admin_users table with data

### API Endpoints Returning Empty

- Check `api/` folder files exist
- Verify database has data in respective tables
- Check browser console for JavaScript errors

### Contact Form Not Submitting

- Check PHP error logs
- Verify form action URL is correct
- Check `process_contact.php` has proper permissions

## File Permissions

For security, set proper file permissions:

```bash
chmod 755 admin/
chmod 755 api/
chmod 755 includes/
chmod 644 index.php
chmod 644 process_contact.php
chmod 644 database.sql
```

## Support & Improvements

This is a modern, clean codebase. Some future enhancements could include:

- [ ] File upload for portfolio images
- [ ] Email notifications for contact form
- [ ] Contact form export to CSV
- [ ] Multi-language support
- [ ] Content versioning/history
- [ ] Advanced analytics dashboard
- [ ] API rate limiting
- [ ] Database backup system

## License

This website template is provided as-is for personal and professional use.

## Credits

Built with:
- PHP 7.4+
- MySQL 5.7+
- Vanilla JavaScript (no dependencies)
- Responsive Design (CSS Grid & Flexbox)

---

**Website:** https://rayajay.com.np/  
**Admin Contact:** admin@rayajay.com.np  
**Phone:** +977 9745232233
