# Carousel3 - Deployment Guide

## Overview

Carousel3 v1.0.0 is now ready for production deployment. This guide covers everything you need to successfully deploy the plugin to your WordPress installation.

## Quick Start

### 1. Download & Upload

```bash
# Navigate to your WordPress plugins directory
cd /var/www/html/wp-content/plugins/

# If using git
git clone https://your-repo/carousel3.git
# OR
# Upload the carousel3 folder via FTP/SFTP
```

### 2. Activate Plugin

1. Login to WordPress Dashboard
2. Go to **Plugins** menu
3. Find **Carousel3** in the list
4. Click **Activate**

### 3. Create Your First Carousel

1. From WordPress Dashboard, click **Carousels3**
2. Click **Add New**
3. Enter a title (e.g., "Homepage Banner")
4. Configure carousel settings on the right:
   - Animation type: Choose slide or fade
   - Enable navigation arrows if desired
   - Enable pagination dots if desired
   - Set height (optional)
5. Save carousel
6. Click **Add Slide** to add carousel images
7. Copy the shortcode and paste it on the desired page

## Installation Options

### Option 1: Manual FTP Upload

1. Download the plugin files
2. Extract the ZIP file
3. Connect via FTP to your server
4. Navigate to `/wp-content/plugins/`
5. Upload the `carousel3` folder
6. Activate via WordPress Admin

### Option 2: Git Clone (Development Servers)

```bash
cd /var/www/html/wp-content/plugins/
git clone https://your-git-repo/carousel3.git
```

### Option 3: Programmatic Installation

```php
// In your WordPress theme or custom plugin
require_once ABSPATH . 'wp-content/plugins/carousel3/Carousel3.php';
```

## Configuration

### File Permissions

Set proper permissions for security:

```bash
# Files: 644 (readable by all, writable by owner)
find /path/to/carousel3 -type f -exec chmod 644 {} \;

# Directories: 755 (readable by all, executable by owner)
find /path/to/carousel3 -type d -exec chmod 755 {} \;
```

### Server Configuration

Ensure these are enabled:
- PHP 7.2+
- jQuery
- WordPress 5.0+

### wp-config.php Settings

No special settings required. The plugin uses standard WordPress APIs.

## Using Carousel3

### Basic Shortcode

```
[carousel3 id="1"]
```

### Carousel ID

To find your carousel ID:
1. Go to **Carousels3** in admin
2. Hover over carousel name in list
3. Look at URL: `...&post=123` (123 is the ID)

### Carousel Settings

#### Animation Type
- **Slide**: Slides move in from the side
- **Fade**: Slides fade in/out

#### Navigation
- **Arrows**: Large arrow buttons on sides
- **Dots**: Small bullet points at bottom

#### Height
- Leave empty for auto-height
- Use `300px` for fixed height
- Use `50%` for viewport percentage

### Adding Slides

1. Open a carousel
2. Click **Add Slide** button
3. Add slide title
4. Add slide content/description
5. Upload featured image (carousel thumbnail)
6. Save slide
7. Drag to reorder if needed

## Testing Checklist

Before going live:

- [ ] Create test carousel
- [ ] Add 3+ test slides
- [ ] Test on mobile (360px width)
- [ ] Test on tablet (768px width)
- [ ] Test on desktop (1920px width)
- [ ] Test navigation (arrows, dots)
- [ ] Test slide reordering
- [ ] Check browser console for errors
- [ ] Verify responsive behavior
- [ ] Test on different browsers

## Troubleshooting

### Carousel Not Displaying

**Symptom**: Shortcode shows but carousel is blank

**Solutions**:
1. Verify carousel has slides added
2. Check carousel ID in shortcode is correct
3. Verify featured images are set on slides
4. Check browser console for JavaScript errors
5. Ensure jQuery is loaded

```javascript
// Test in browser console
typeof jQuery !== 'undefined' ? console.log('jQuery loaded') : console.log('jQuery NOT loaded');
typeof Swiper !== 'undefined' ? console.log('Swiper loaded') : console.log('Swiper NOT loaded');
```

### Slides Not Reordering

**Symptom**: Drag-and-drop not working

**Solutions**:
1. Refresh page and try again
2. Clear browser cache
3. Check user has `edit_posts` capability
4. Check browser console for errors
5. Verify AJAX is working

```javascript
// Test AJAX in console
jQuery.post(carousel3TableSort.ajaxUrl, {action: 'carousel3_update_order', nonce: carousel3TableSort.nonce, order: []}, function(data) { console.log(data); });
```

### JavaScript Errors

**Check these**:
1. Browser console (F12 → Console tab)
2. WordPress debug log: `/wp-content/debug.log`
3. Server error log: `/var/log/apache2/error.log` (Apache)

### Styling Issues

**Common causes**:
- Theme CSS conflicting with carousel CSS
- CSS not loading properly
- Browser cache needs clearing

**Solutions**:
1. Add `!important` to carousel CSS rules if needed
2. Clear browser cache (Ctrl+Shift+Delete)
3. Disable other plugins to test
4. Switch to default WordPress theme temporarily

### Admin Pages Slow

**Optimization**:
1. Only load admin.js on carousel pages (already optimized)
2. Check database queries in Debug Bar
3. Review server resources (CPU, RAM)
4. Consider caching plugins

## Performance Optimization

### Enable Caching

Add to `wp-config.php`:

```php
define('WP_CACHE', true);
```

### Use a Caching Plugin

Recommended caching plugins:
- WP Super Cache
- W3 Total Cache
- LiteSpeed Cache

### Lazy Load Images

Use WordPress 5.5+ native lazy loading or a plugin:
- Lazy Load by WP Rocket
- Ewww Image Optimizer

## Security

### File Permissions

```bash
# Only WordPress should modify
chmod 755 /path/to/carousel3
chmod 644 /path/to/carousel3/*.php
```

### Protection Headers

Add to `.htaccess`:

```apache
# Prevent direct file access
<FilesMatch "\.php$">
    Order Deny,Allow
    Deny from all
</FilesMatch>
```

### Regular Updates

- Update WordPress regularly
- Update plugins and themes
- Review WordPress debug log weekly

## Backup & Recovery

### Before Deployment

```bash
# Backup WordPress
mysqldump -u wordpress_user -p wordpress_db > backup_$(date +%Y%m%d).sql

# Backup files
tar -czf wordpress_backup_$(date +%Y%m%d).tar.gz /var/www/html/wp-content/
```

### Restore from Backup

```bash
# Restore database
mysql -u wordpress_user -p wordpress_db < backup_20260321.sql

# Restore files
tar -xzf wordpress_backup_20260321.tar.gz -C /
```

## Monitoring

### Enable Debug Log

Add to `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### Monitor After Deployment

Check these daily for first week:
- `/wp-content/debug.log`
- WordPress admin "Help" for notifications
- Server CPU and RAM usage
- Error logs

## Support

### Getting Help

1. Check README.md for common questions
2. Review PRODUCTION-CHECKLIST.md
3. Check browser console for errors
4. Review WordPress debug log
5. Contact: musite.xyz

### Report Issues

Include when reporting issues:
- WordPress version
- PHP version
- Plugin version
- Exact error message
- Steps to reproduce
- Browser/OS information

## Updates

### Check for Updates

1. WordPress Dashboard > Plugins
2. Look for "Carousel3" update notice
3. Click "Update" or manage auto-updates

### Update Safely

```bash
# Backup before updating
mysqldump -u wordpress_user -p wordpress_db > backup_pre_update.sql

# Update via WordPress or copy new files
cp -r carousel3_new/* carousel3/

# Test after update
# Verify all carousels still display
# Check admin pages work
```

## Next Steps

1. ✅ Deploy to staging server first
2. ✅ Run full testing suite
3. ✅ Get approval from stakeholders
4. ✅ Deploy to production
5. ✅ Monitor for issues
6. ✅ Collect user feedback

---

**Deployment Status**: Ready for Production  
**Last Updated**: 2026-03-21  
**Version**: 1.0.0

For additional support, visit musite.xyz or check the README.md
