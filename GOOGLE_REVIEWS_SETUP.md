# Google My Business Reviews Integration Guide

## Overview

Your website now supports **dynamic reviews from Google My Business**! Here's everything you need to know to set it up.

## Features

✅ **Automatic Review Loading** - Reviews load from Google My Business profile
✅ **Verified Badge** - Reviews show "Verified Google Review" badge
✅ **Auto-Caching** - Reviews synced to local database as backup
✅ **Fallback Support** - Falls back to local reviews if Google API fails
✅ **Real-time Updates** - Reviews update every time page loads
✅ **Admin Management** - Still manage local reviews as backup

## Quick Setup (5 minutes)

### Step 1: Get Your Google Place ID

1. Visit **[Google Maps](https://www.google.com/maps)**
2. Search for your business name
3. Click on your business to open the details panel
4. Look at the URL in your browser - copy the Part ID after `place_id=`

Example URL:
```
https://www.google.com/maps/place/ChIJ7czf4V_B1IkRv382O-ZP3nE@15z/data=...
                                   ↑
                            Your Place ID
```

### Step 2: Create Google Cloud Project & API Key

1. **Go to** [Google Cloud Console](https://console.cloud.google.com/)

2. **Create a new project:**
   - Click the dropdown at the top
   - Click "NEW PROJECT"
   - Enter project name
   - Click "CREATE"

3. **Enable APIs:**
   - Search for "Places API" in the search bar
   - Click "Enable"
   - Also search for "Maps JavaScript API"
   - Click "Enable"

4. **Create API Key:**
   - Go to **Credentials** (left sidebar)
   - Click **"+ CREATE CREDENTIALS"**
   - Choose **"API Key"**
   - Copy the API Key that appears

5. **Restrict API Key (IMPORTANT for security):**
   - Click on the API Key you just created
   - Under "API restrictions" select "Places API"
   - Save

### Step 3: Configure Website

1. **Visit Admin Panel:** http://localhost/ajayroyweb/admin/
2. **Login** with your admin credentials
3. **Click "🌐 Google Reviews API"** button
4. **Enter:**
   - **Google Places API Key:** Paste your API key from Step 2
   - **Google Place ID:** Paste your Place ID from Step 1
5. **Click "Save Settings"**
6. **Test Connection** by clicking the test button

### Step 4: Verify It's Working

1. Visit your website homepage: http://localhost/ajayroyweb/
2. Scroll to the **Google Reviews** section
3. You should see your actual Google My Business reviews!

## How It Works

```
Your Google My Business Profile
         ↓
   Google Places API
         ↓
   Your Website API Endpoint (/api/get_google_reviews.php)
         ↓
   Syncs to Local Database (for caching)
         ↓
   Frontend Displays with Badge
```

### System Flow:

1. **Website loads** → Calls `/api/get_google_reviews.php`
2. **API endpoint** → Fetches from Google Places API
3. **If successful** → Displays reviews with "Verified Google Review" badge
4. **If Google fails** → Falls back to local database reviews
5. **Auto-sync** → Google reviews are saved to local database as backup

## Features Available

### On Frontend (index.php)

- ⭐ Star rating display (filled and empty stars)
- 💬 Review text with quotation marks
- 👤 Reviewer name
- 📅 Review date
- ✓ Green "Verified Google Review" badge for Google reviews

### In Admin Panel

**Two ways to manage reviews:**

1. **Google Reviews Settings** (`/admin/google_settings.php`)
   - View/configure Google API credentials
   - Test Google connection
   - See live review count from Google

2. **Manage Reviews** (`/admin/reviews.php`)
   - Add/edit local reviews
   - Mark reviews as active/inactive
   - Delete reviews
   - Local backup storage

## API Endpoints

### Get Google Reviews (Dynamic)
```
GET /api/get_google_reviews.php
```
Returns: JSON array of reviews (from Google or local fallback)

### Get All Local Reviews
```
GET /api/get_reviews.php
```
Returns: JSON array of reviews from local database

## Troubleshooting

### ❌ "No reviews showing"

**Solution:**
1. Check API key is correct - copy-paste carefully
2. Check Place ID is correct - verify on Google Maps
3. Test connection using the test button
4. Wait 2-3 minutes for Google Cloud changes to take effect
5. Check browser console for error messages

### ❌ "Connection failed (HTTP 400)"

**Solution:**
- Invalid Place ID or API key
- Make sure API key is restricted to Places API
- Make sure Place ID is from Google Maps (not a URL)

### ❌ "Connection failed (HTTP 403)"

**Solution:**
- API key not enabled for Places API
- Check Google Cloud Console that Places API is enabled
- Check API key restrictions are set to Places API

### ❌ "Reviews appear from local database (not Google)"

**Solution:**
- This is normal! Google API is temporarily down
- Reviews will show from cached local database
- Google reviews will resume when API is back online

### ✓ Reviews show but no badge

**Solution:**
- Reviews are from local database (not Google)
- Make sure Google settings are configured
- Check API key and Place ID are valid
- Test connection to verify

## Using Local Reviews as Backup

Even with Google integration, you can add local reviews in `/admin/reviews.php`:

1. Click **"+ Add New Review"**
2. Enter reviewer name, text, and rating
3. Click **"Save Review"**
4. Local reviews will show if Google API is unavailable

## Updating Google Reviews (Manual Sync)

Reviews are **auto-synced** to local database. To force update:

1. Visit `/admin/google_settings.php`
2. Click **"Test Google Connection"** button
3. New Google reviews will be added to local database

## API Rate Limits

- Google Places API: 1,000-25,000 requests per day (free tier)
- Your website: Reviews load once per page view
- **Expected:** ~10-100 requests per day (very safe)

## Security Best Practices

✅ **DO:**
- Keep API key private (don't commit to GitHub)
- Use API key restrictions (Places API only)
- Use Place ID (not full URL)
- Test connection regularly

❌ **DON'T:**
- Share your API key
- Enable API key for all services
- Expose API key in client-side code
- Use same API key across many projects

## Database Schema

When Google reviews sync, they're stored as:

```sql
CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reviewer_name VARCHAR(100) NOT NULL,
  review_text TEXT NOT NULL,
  rating INT(1) NOT NULL,
  review_date DATETIME NOT NULL,
  active BOOLEAN DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Configuration File

Settings stored in database table: `site_settings`

```
google_api_key: Your Google Places API Key
google_place_id: Your Google Place ID
```

Both are automatically retrieved by the API endpoint.

## Next Steps

1. ✅ Get Place ID (Google Maps)
2. ✅ Create Google Cloud Project
3. ✅ Generate API Key
4. ✅ Configure in admin panel
5. ✅ Test connection
6. ✅ Verify on website

## Support Resources

- **Google Places API Docs:** https://developers.google.com/maps/documentation/places/web-service
- **Google Cloud Console:** https://console.cloud.google.com/
- **Get Place ID Tool:** https://developers.google.com/maps
- **Common Issues:** See Troubleshooting section above

## FAQ

**Q: Will my reviews be copied to local database?**
A: Yes! Each unique Google review is synced to local database for backup caching.

**Q: What if Google API fails?**
A: Website automatically falls back to cached local reviews. Users won't notice any downtime.

**Q: How often do reviews update?**
A: Every time a page loads. For more frequent updates, consider a cache system.

**Q: Can I use local reviews alongside Google reviews?**
A: Yes! Add reviews in admin panel to supplement Google reviews.

**Q: How much does this cost?**
A: Google Places API is free up to 1,000 requests/day. Your site will use ~10-100 daily.

---

**Last Updated:** February 21, 2026
**Version:** 1.0
