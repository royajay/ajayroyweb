# Installation Guide - Local Setup

This guide will help you set up the Ajay Roy Portfolio website on your local computer for development and testing.

## What You Need

Before starting, ensure you have installed:

1. **Local Web Server** (choose one):
   - [XAMPP](https://www.apachefriends.org/) (Windows, macOS, Linux)
   - [WAMP](https://www.wampserver.com/en/) (Windows)
   - [MAMP](https://www.mamp.info/) (Mac)
   - Or any Apache + PHP + MySQL server

2. **Web Browser** - Chrome, Firefox, Safari, or Edge

3. **Text Editor** (optional, for making changes):
   - VS Code
   - Sublime Text
   - Notepad++

## Installation Steps

### Step 1: Download/Extract the Project

1. Download the `AjayRoy-Website-PHP` folder
2. Extract it to your web server's root directory:
   - **XAMPP**: `C:\xampp\htdocs\` (Windows) or `/Applications/XAMPP/htdocs/` (Mac)
   - **WAMP**: `C:\wamp64\www\` (Windows)
   - **MAMP**: `/Applications/MAMP/htdocs/` (Mac)

Your path should look like: `C:\xampp\htdocs\AjayRoy-Website-PHP\`

### Step 2: Start Your Local Server

#### XAMPP:
1. Open XAMPP Control Panel
2. Click "Start" next to Apache
3. Click "Start" next to MySQL

#### WAMP:
1. Click system tray icon
2. Verify it shows green (all running)

#### MAMP:
1. Open MAMP
2. Click "Start Servers"

**Wait 2-3 seconds for both services to start.**

### Step 3: Create the Database

#### Option A: Using phpMyAdmin (Easiest)

1. Open your browser and go to: `http://localhost/phpmyadmin/`
2. Look for the "Databases" tab at the top
3. Under "Create new database":
   - Type: `ajay_roy_portfolio`
   - Collation: `utf8mb4_general_ci`
   - Click "Create"
4. Click on the new database name to open it
5. Click the "Import" tab
6. Click "Choose File" and select `database.sql` from the project folder
7. Click "Import" (wait for success message)

#### Option B: Using Command Line

Open Command Prompt/Terminal and run:

```bash
# Navigate to your MySQL installation
cd C:\xampp\mysql\bin

# Login to MySQL
mysql -u root -p

# If prompted for password, just press Enter (default is no password)
```

Then paste these commands:

```sql
CREATE DATABASE ajay_roy_portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE ajay_roy_portfolio;
SOURCE C:\path\to\database.sql;
```

(Replace `C:\path\to\database.sql` with actual path to your database.sql file)

### Step 4: Verify Database Configuration

Open `includes/config.php` and verify these settings match your setup:

```php
define('DB_HOST', 'localhost');      // Usually localhost
define('DB_USER', 'root');           // Default for local servers
define('DB_PASS', '');               // Empty for XAMPP/WAMP default
define('DB_NAME', 'ajay_roy_portfolio'); // Database name created above
```

**Save the file if you made changes.**

### Step 5: Test Installation

Open your browser and visit:

**Public Website:**
```
http://localhost/AjayRoy-Website-PHP/
```

You should see:
- ✅ Purple gradient header with "I am Ajay Roy"
- ✅ Navigation menu (Home, About, Services, Portfolio, Contact, Admin)
- ✅ Services, Portfolio, and Skills sections populated from database
- ✅ Contact form at the bottom

**Admin Panel:**
```
http://localhost/AjayRoy-Website-PHP/admin/login.php
```

You should see:
- ✅ Login form with fields for Username and Password
- ✅ Demo credentials shown (username: `admin`, password: `admin123`)

### Step 6: Login to Admin Panel

1. Enter the credentials:
   - Username: `admin`
   - Password: `admin123`
2. Click "Login"
3. You should see the Admin Dashboard with:
   - Statistics cards (Total Contacts, Unread Messages, etc.)
   - Management menu with options to manage services, portfolio, skills, etc.
   - Recent contact submissions table

## Database Connection Test

To verify your database is working:

1. Open `includes/config.php`
2. Add this temporary code at the end:

```php
// TEMPORARY TEST - DELETE AFTER VERIFYING
echo "✅ Database connection successful!";
echo "<br>Database: " . DB_NAME;
$result = $mysqli->query("SELECT COUNT(*) as count FROM services");
$row = $result->fetch_assoc();
echo "<br>Services in database: " . $row['count'];
```

3. Save and visit: `http://localhost/AjayRoy-Website-PHP/includes/config.php`
4. You should see all three success messages
5. **Delete the test code afterward**

## Common Issues & Solutions

### Issue: "Connection refused" when accessing website

**Solution:**
- Make sure Apache and MySQL are both running in your server control panel
- Check you're visiting `http://localhost/` (not `https://`)
- Restart your local server

### Issue: "Access denied for user 'root'@'localhost'"

**Solution:**
- Your MySQL password doesn't match `config.php`
- Check your server's MySQL password (often blank for local installs)
- Update `DB_PASS` in `config.php` to match

### Issue: "No such table 'services'"

**Solution:**
- Database `ajay_roy_portfolio` wasn't created
- `database.sql` wasn't imported properly
- Check phpMyAdmin to see if database exists and has tables

### Issue: Admin login doesn't work

**Solution:**
- Check database has `admin_users` table
- Verify password is exactly: `admin123`
- Clear browser cookies: Press `Ctrl+Shift+Delete` (or right-click → Inspect → Storage → Clear All)
- Try incognito/private browsing window

### Issue: "File not found" or 404 errors

**Solution:**
- Ensure project folder is in correct location
- Check folder name is exactly `AjayRoy-Website-PHP`
- Verify you're accessing `http://localhost/AjayRoy-Website-PHP/` (with folder name)
- Restart Apache

### Issue: Contact form shows blank error

**Solution:**
- Check PHP error logs in your server control panel
- Make sure `process_contact.php` exists
- Verify form is posting to correct URL

## File Permissions (If Needed)

On Mac/Linux, if you get permission denied errors:

```bash
# Navigate to project folder
cd /path/to/AjayRoy-Website-PHP

# Allow writing to folders
chmod 777 admin/
chmod 777 api/
chmod 777 includes/
```

## Making Your First Changes

Now that everything is set up, try:

1. **Change site name**:
   - Go to Admin → Settings
   - Change "Website Name" to something else
   - Press "Save Settings"
   - Refresh homepage to see it updated

2. **Add a new service**:
   - Go to Admin → Manage Services
   - Fill in a service name, description, and icon
   - Press "Add Service"
   - Go to homepage and scroll to Services section

3. **Update your profile**:
   - Go to Admin → Site Settings
   - Update contact information
   - Check homepage contact section for changes

4. **Test contact form**:
   - Go to homepage
   - Scroll to Contact section at bottom
   - Fill form and submit
   - Go to Admin → View Contacts to see submission

## Backing Up Your Database

Important: Save your work! Before making big changes:

### In phpMyAdmin:
1. Open phpMyAdmin
2. Click your database name
3. Click "Export" at top
4. Click "Go" to download SQL file
5. Save in safe location

## Next Steps

Once local installation is working:

1. Read [README.md](README.md) to understand the structure
2. Customize your content in the Admin panel
3. Test the contact form submission
4. When ready, follow [DEPLOYMENT.md](DEPLOYMENT.md) to launch on the internet

## Getting Help

If you're stuck:

1. Check the **Common Issues** section above
2. Read error messages carefully (they usually tell you what's wrong)
3. Verify all files are in correct locations
4. Check browser console for JavaScript errors (F12 → Console tab)
5. Check server error logs (look in your XAMPP/WAMP/MAMP installation)

## Important Security Notes

⚠️ **This setup is for LOCAL DEVELOPMENT ONLY**

Before putting online:
- Change admin password immediately
- Set PHP error reporting to off (hide errors from public)
- Use HTTPS/SSL certificate
- Keep database backups
- Regularly update PHP and MySQL

---

**Stuck?** Try restarting your local server. It solves 90% of issues!

When you're ready to go live, see [DEPLOYMENT.md](DEPLOYMENT.md)
