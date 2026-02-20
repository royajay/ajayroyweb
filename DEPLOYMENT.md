# Deployment Guide - cPanel Hosting

This guide explains how to deploy the Ajay Roy Portfolio website to a live cPanel hosting server.

## Prerequisites

You need:

1. **A web hosting account** with cPanel access
   - Most affordable options support PHP 7.4+ and MySQL
   - Popular providers: Bluehost, HostGator, SiteGround, A2Hosting, DreamHost

2. **FTP/SFTP access credentials** (provided by your hosting company)
   - Hostname/Server address
   - Username
   - Password

3. **Database access** (usually auto-provided)

4. **Your local website backup** (the `AjayRoy-Website-PHP` folder)

## Step 1: Prepare Your Files

1. Make sure you have a complete `AjayRoy-Website-PHP` folder locally with:
   - ✅ All PHP files
   - ✅ `includes/` folder with config.php and functions.php
   - ✅ `admin/` folder with all management pages
   - ✅ `api/` folder with API endpoints
   - ✅ `database.sql` file
   - ✅ All documentation files

2. **Optional: Security Cleanup**
   - Remove `INSTALLATION.md` and `DEPLOYMENT.md` from production (not necessary, but keeps things tidy)
   - Remove `database.sql` file from production (safer, backup separately)

## Step 2: Create cPanel Database

1. **Log in to cPanel**
   - Visit your hosting provider's cPanel URL
   - Enter your username and password

2. **Create Database**
   - Look for "MySQL Databases" in cPanel
   - Database name: `yourname_rajayprofile` (each host limits length, usually 15 chars)
   - Create user with strong password
   - Add user to database with "All Privileges"

3. **Note down:**
   - Database name (e.g., `yourname_rajayprofile`)
   - Database username (e.g., `yourname_admin`)
   - Database password (securely saved)

4. **Import Your Database**
   - Go to "phpMyAdmin" in cPanel
   - Select your new database
   - Click "Import" tab
   - Choose and upload your `database.sql` file
   - Click "Import"

## Step 3: Upload Files to Server

### Option A: Using FTP (Easiest)

1. **Download FTP Client** (choose one):
   - [FileZilla](https://filezilla-project.org/) - Free, all platforms
   - [WinSCP](https://winscp.net/) - Windows only
   - [Cyberduck](https://cyberduck.io/) - Mac

2. **Connect to Server**:
   - Open FTP client
   - Create new connection:
     - Host: your hosting FTP address
     - Username: your FTP username
     - Password: your FTP password
     - Port: usually 21 (or 22 for SFTP)

3. **Upload Files**:
   - On left: browse to your local `AjayRoy-Website-PHP` folder
   - On right: navigate to `public_html/` folder
   - If your domain root is `/public_html`, upload everything there
   - If you want `rayajay.com.np/portfolio/`, create folder and upload
   - Drag and drop files to server

4. **Verify Upload**:
   - Check all files are on server
   - `includes/config.php` should be there
   - `admin/` folder should be there
   - `api/` folder should be there

### Option B: Using cPanel File Manager

1. Log in to cPanel
2. Go to "File Manager"
3. Navigate to `public_html/`
4. Click "Upload" button
5. Upload all files (may be slow for large uploads)
6. Extract any .zip files if needed

### Option C: Using SSH/Command Line (Advanced)

```bash
# SSH into your server
ssh username@yourserver.com

# Navigate to web root
cd public_html

# Upload using rsync or scp command
rsync -avz local_folder/ username@server:/home/username/public_html/

# Or use WinSCP via command line
winscp /script=script.txt
```

## Step 4: Update Database Configuration

1. **Access your server** via FTP or cPanel File Manager
2. **Edit `includes/config.php`**:
   - Find these lines:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'ajay_roy_portfolio');
     ```
   - Update to match your cPanel database info:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'yourname_admin');
     define('DB_PASS', 'your_strong_password');
     define('DB_NAME', 'yourname_rajayprofile');
     ```
3. **Save and re-upload** the file

## Step 5: Verify Installation

1. **Visit your website**:
   - Open browser: `https://yourdomain.com` (or `https://yourdomain.com/portfolio/`)
   - You should see the homepage with all sections

2. **Test Admin Panel**:
   - Visit: `https://yourdomain.com/admin/login.php`
   - Login with admin credentials
   - Navigate to Dashboard

3. **Test Contact Form**:
   - Fill and submit contact form on homepage
   - Check Admin → View Contacts for submission

4. **Check Error Logs**:
   - In cPanel, go to "Error Log"
   - Should be minimal or no errors

## Step 6: Security Setup

### Change Admin Password (CRITICAL!)

1. Go to Admin panel
2. For now, you must manually update in database
3. Export current admin_users table
4. Hash your new password using:
   ```php
   $password = password_hash('your_new_password', PASSWORD_BCRYPT);
   echo $password;
   ```
5. Update the database

**Or:** Import admin_users table update:
```sql
UPDATE admin_users SET password = '$2y$10$YOUR_BCRYPT_HASH_HERE' WHERE username = 'admin';
```

### Enable HTTPS/SSL

This is crucial for security and SEO:

1. **In cPanel**:
   - Find "AutoSSL" or "SSL/TLS" section
   - Look for "Install and Manage SSL for your site"
   - Click to install free SSL certificate (usually Let's Encrypt)
   - May take 10-30 minutes to activate

2. **Force HTTPS**:
   - Go to "File Manager" → `public_html`
   - Create/edit `.htaccess` file:
   ```apache
   <IfModule mod_rewrite.c>
   RewriteEngine On
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   </IfModule>
   ```

### Set File Permissions

1. Via **cPanel File Manager**:
   - Right-click `admin/` folder → Permissions → Set to `755`
   - Right-click `api/` folder → Permissions → Set to `755`
   - Right-click `includes/` folder → Permissions → Set to `755`

2. Via **SSH**:
   ```bash
   chmod 755 admin/
   chmod 755 api/
   chmod 755 includes/
   chmod 644 *php
   ```

## Step 7: Regular Maintenance

### Automatic Backups

Set up in cPanel:
1. Go to "Backup Wizard"
2. Configure daily/weekly automatic backups
3. Store off-site if possible

### Monitor Error Logs

Weekly, check:
- cPanel → Error Log
- Look for PHP warnings or database errors
- Fix any issues found

### Keep PHP Updated

1. Check your current PHP version: Visit `http://yourdomain.com/phpinfo.php` then delete
2. In cPanel under PHP, keep version updated (7.4+ minimum)

### Database Backups

Monthly:
1. Go to phpMyAdmin
2. Export your database (Structure + Data)
3. Download and store safely

## Troubleshooting Live Server

### "Database connection failed"

**Solution**:
- Verify database credentials in `includes/config.php`
- Check database name matches exactly
- Ensure database user has all privileges
- Test connection in phpMyAdmin first

### "Page shows blank or errors"

**Solution**:
- Check cPanel Error Log
- Enable PHP error display (temporarily):
  ```php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  ```
- Look at error messages

### "Admin login not working"

**Solution**:
- Verify `admin_users` table exists
- Check password hash is correct
- Clear browser cache and cookies
- Try incognito window

### "Contact form doesn't submit"

**Solution**:
- Check `process_contact.php` is uploaded
- Verify form posts to correct URL
- Check server error logs
- Ensure database `contacts` table exists

### "404 errors on certain pages"

**Solution**:
- Verify all files uploaded correctly
- Check for typos in API file names
- Ensure all folders (`admin/`, `api/`) are present

## Performance Optimization

### Enable Gzip Compression

In `.htaccess`:
```apache
<IfModule mod_deflate.c>
AddOutputFilterByType DEFLATE text/html
AddOutputFilterByType DEFLATE text/plain
AddOutputFilterByType DEFLATE text/xml
AddOutputFilterByType DEFLATE text/css
AddOutputFilterByType DEFLATE text/javascript
AddOutputFilterByType DEFLATE application/javascript
AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
```

### Cache Headers

In `.htaccess`:
```apache
<IfModule mod_expires.c>
ExpiresActive On
ExpiresByType text/css "access 1 month"
ExpiresByType application/javascript "access 1 month"
ExpiresByType image/jpeg "access 1 month"
ExpiresByType image/png "access 1 month"
</IfModule>
```

## Domain Configuration

### Point Domain to Hosting

1. Get **nameservers** from your hosting provider
2. Go to your domain registrar (GoDaddy, Namecheap, etc.)
3. Update nameservers to match hosting company
4. Wait 24-48 hours for DNS to propagate

### Set Up Addon Domain (Multiple Domains)

In cPanel:
1. Go to "Addon Domains"
2. Add your domain
3. Create corresponding public_html folder
4. Upload files there

## Testing Checklist

Before telling others about your site:

- [ ] Homepage loads fully (all sections visible)
- [ ] Navigation menu works
- [ ] Contact form submits successfully
- [ ] Admin panel login works
- [ ] Can add/edit content in admin panel
- [ ] HTTPS is active (lock icon in address bar)
- [ ] No console errors (F12 → Console)
- [ ] Mobile version is responsive
- [ ] All links work correctly

## Going Live Checklist

- [ ] Changed admin password
- [ ] SSL/HTTPS enabled
- [ ] Automatic backups configured
- [ ] Error logging set up
- [ ] Contact form tested
- [ ] All content updated
- [ ] Database backed up
- [ ] Tested from phone/tablet
- [ ] Performance optimized
- [ ] Email forwarding configured (optional)

## Support & Issues

If you encounter issues:

1. **Check error logs first** - usually tells you exactly what's wrong
2. **Verify file uploads** - sometimes files don't upload completely
3. **Test database connection** - try MySQL connection test in phpMyAdmin
4. **Check PHP version** - ensure hosting has PHP 7.4+
5. **Review file permissions** - should be 755 for folders, 644 for files

---

## Post-Deployment

**Congratulations!** Your site is live. Now:

1. Share the link with people
2. Test features regularly
3. Keep content updated
4. Monitor visitor contact submissions
5. Watch for error logs
6. Keep backups current

**Happy hosting!** 🚀
