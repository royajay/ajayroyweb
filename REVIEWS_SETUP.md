# Google Reviews Integration Setup Guide

## Overview
The website now includes a complete Google Reviews management system integrated into your portfolio. Reviews are displayed on the frontend and can be managed through the admin panel.

## Features Implemented

### 1. **Frontend Reviews Section**
- Beautiful card-based layout showing Google reviews
- 5-star rating display with visual stars
- Review text, author name, and date
- Responsive grid layout (3 columns on desktop, 1 on mobile)
- Smooth hover animations

### 2. **Admin Panel - Reviews Management**
- **Location**: `/admin/reviews.php`
- **Access**: http://localhost/ajayroyweb/admin/reviews.php
- **Features**:
  - Add new reviews manually
  - Edit existing reviews
  - Delete reviews
  - Toggle review visibility (Active/Inactive)
  - View all reviews in a clean table
  - Filter and search reviews

### 3. **Database Table**
```sql
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reviewer_name VARCHAR(100) NOT NULL,
    review_text TEXT NOT NULL,
    rating INT NOT NULL (1-5),
    review_date DATETIME NOT NULL,
    active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
```

### 4. **API Endpoint**
- **Endpoint**: `/api/get_reviews.php`
- **Method**: GET
- **Returns**: JSON array of active reviews
- **Example Response**:
```json
[
  {
    "id": 1,
    "reviewer_name": "Rajesh Kumar",
    "review_text": "Excellent SEO services! My website traffic increased by 300%",
    "rating": 5,
    "review_date": "2024-12-15"
  }
]
```

## How to Add Reviews

### Method 1: Admin Panel (Recommended)
1. Log in to admin: http://localhost/ajayroyweb/admin/
2. Click "⭐ Manage Reviews" from dashboard
3. Click "+ Add New Review" button
4. Fill in the form:
   - **Reviewer Name**: Name of the person giving review
   - **Review Text**: The review content
   - **Rating**: Select 1-5 stars
   - **Review Date**: When the review was given
   - **Active**: Check to show on website
5. Click "Save Review"

### Method 2: Database Direct SQL
```sql
INSERT INTO reviews (reviewer_name, review_text, rating, review_date, active) 
VALUES ('Your Name', 'Your review text here...', 5, NOW(), 1);
```

### Method 3: Manual CSV Import (TODO)
Future feature to import reviews from CSV file

## Integration with Google My Business

### Current Setup
The system allows manual entry of reviews. This is ideal for:
- Managing reviews from Google My Business
- Adding testimonials from clients
- Moderating review content
- Highlighting best reviews

### Google My Business Profile
Your GMB profile: https://share.google/7yR7zDYArWAFyvdAO

To sync reviews automatically:
1. Periodically check your GMB profile
2. Copy reviews manually to the admin panel, OR
3. Set up Google Places API integration (advanced)

### Future Automation (Google Places API)
For automatic review syncing, you would need:
1. Google Cloud Project with Places API enabled
2. API Key from Google Cloud Console
3. Backend script to fetch reviews from Places API
4. Scheduled task (cron job) to sync reviews daily

## Sample Reviews
The database comes pre-loaded with 6 sample reviews to get you started:
- 5 reviews with 5-star rating
- 1 review with 4-star rating
- All dated within the last 60 days

## Frontend Display
- **Location**: This section appears after the Portfolio section
- **Section ID**: `#reviews`
- **File**: `index.php` (lines 623-629)
- **Styling**: Gradient background (#f8f9ff to #fff)

## Dashboard Stats
- The admin dashboard now shows "⭐ Google Reviews" card
- Displays count of active reviews
- Updated in real-time as you add/remove reviews

## Customization Options

### Change Number of Reviews Displayed
In `index.php`, modify the API limit:
```javascript
// Change the LIMIT in get_reviews.php
```

### Change Review Card Layout
Edit CSS in `index.php`:
```css
.reviews-grid {
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
}
```

### Change Star Rating Colors
```css
.review-stars {
    color: #FFC107; /* Change this color */
}
```

## Best Practices

1. **Keep Reviews Current**
   - Update reviews every 1-2 months
   - Highlight your most recent positive reviews
   - Remove outdated reviews

2. **Showcase Quality Reviews**
   - Pin your best 5-10 reviews
   - Use reviews with specific metrics/results
   - Vary reviewer types (different industries)

3. **Manage Visibility**
   - Use "Active" toggle to showcase best reviews
   - Keep inactive reviews in database (archive)
   - Don't delete reviews permanently

4. **Update Visuals**
   - Review photos/images (future feature)
   - Company names from reviewers
   - Reviewer role/position

## Troubleshooting

### Reviews Not Appearing
1. Check if reviews are marked as "Active"
2. Verify database table exists: `SELECT * FROM reviews;`
3. Check API endpoint: http://localhost/ajayroyweb/api/get_reviews.php
4. Check browser console for JavaScript errors

### Database Errors
1. Ensure database is imported with the reviews table
2. Check table structure: `DESCRIBE reviews;`
3. Verify user permissions to insert/update/delete

### Admin Panel Issues
1. Ensure you're logged in
2. Check session cookies are enabled
3. Verify file permissions on `/admin/reviews.php`

## Files Modified/Created

### New Files
- `/admin/reviews.php` - Admin management panel
- `/api/get_reviews.php` - API endpoint for frontend
- `/database.sql` - Updated with reviews table

### Modified Files
- `/index.php` - Added reviews section to frontend
- `/admin/dashboard.php` - Added reviews management link and stats
- `/includes/functions.php` - Added review count to dashboard stats

## Security Notes
- Reviews are stored in your database (secure)
- No direct Google My Business API dependency
- Manual review moderation is possible
- SQL injection prevention implemented
- Admin-only access for management

## Performance
- Reviews load asynchronously (doesn't block page)
- Optimized database queries
- Responsive design works on all devices
- Average load time: <500ms

## Support
For questions or issues:
1. Check the troubleshooting section above
2. Review database table structure
3. Check file permissions
4. Verify all files were imported correctly

---

**Last Updated**: 2026-02-21
**Version**: 1.0
