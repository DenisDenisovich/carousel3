# Carousel3 - Developer Documentation

## Architecture Overview

Carousel3 is built using WordPress best practices and Object-Oriented PHP with the Singleton pattern.

### Class Hierarchy

```
Carousel3
├── Init (Initialization)
├── Admin (Admin Dashboard)
│   ├── Carousels (Carousel CPT Management)
│   └── Sliders (Slides CPT Management)
└── Frontend (Public Display)
```

## Class Structure

### `Init` Class
**File**: `includes/class-init.php`

- Initializes the plugin on `plugins_loaded` hook
- Loads text domain for translations
- Instantiates Admin and Frontend classes

```php
Carousel3\Init::get_instance();
```

### `Admin` Class
**File**: `admin/class-admin.php`

- Manages admin-specific functionality
- Enqueues admin CSS/JS
- Instantiates admin sub-classes

**Key Methods**:
- `enqueue_admin_assets()` - Load admin styles/scripts

### `Carousels` Class
**File**: `admin/class-carousels.php`

- Registers `carousel3` custom post type
- Creates admin UI for carousel management
- Handles carousel settings meta boxes

**Key Methods**:
- `create_menu_carousel()` - Register CPT
- `render_carousel_slides()` - Display slides table
- `save_carousel_data()` - Save carousel settings
- `update_slide_order()` - AJAX handler for reordering

**Hooks**:
- `wp_ajax_carousel3_update_order` - AJAX action

### `Sliders` Class
**File**: `admin/class-sliders.php`

- Registers `carousel3_slides` custom post type
- Manages individual slide editing
- Handles slide-specific meta boxes

**Key Methods**:
- `create_menu_slides()` - Register CPT
- `add_back_button()` - Navigation helper
- `save_slide_data()` - Save slide data

### `Frontend` Class
**File**: `public/class-frontend.php`

- Registers `[carousel3]` shortcode
- Queries slides from database
- Renders carousel HTML
- Enqueues frontend assets

**Key Methods**:
- `carousel_shortcode($atts)` - Shortcode handler

## Database Structure

### Custom Post Types

#### `carousel3`
Main carousel post type

- Post title: Carousel name
- Supports: title only
- Visibility: Private

**Meta Keys**:
```php
'_carousel3_show_arrows'      // '0' or '1'
'_carousel3_show_dots'        // '0' or '1'
'_carousel3_height'           // CSS value (300px, auto, 50%, etc)
'_carousel3_effect'           // 'slide' or 'fade'
```

#### `carousel3_slides`
Individual slide type (hierarchical under carousel3)

- Post title: Slide name
- Supports: title, editor, thumbnail
- Parent: carousel3 post
- Menu order: Used for slide ordering

**Meta Keys**:
```php
'_carousel3_animation_type'   // CSS animation class
```

## Custom Hooks & Filters

### Actions

None currently exposed. Future versions may include:
```php
do_action('carousel3_before_carousel', $carousel_id);
do_action('carousel3_after_carousel', $carousel_id);
```

### Filters

None currently exposed. Future versions may include:
```php
apply_filters('carousel3_query_slides', $query);
apply_filters('carousel3_carousel_settings', $settings);
```

## File Locations

### Plugin Files
```
carousel3/
├── Carousel3.php                      # Main entry point
├── admin/
│   ├── class-admin.php               # Admin class
│   ├── class-carousels.php           # Carousel CPT
│   ├── class-sliders.php             # Slides CPT
│   ├── css/
│   │   └── admin.css                 # Admin styles
│   ├── js/
│   │   └── admin.js                  # Admin scripts
│   └── views/
│       ├── carousel-metabox-settings.php
│       └── slide-metabox-settings.php
├── includes/
│   ├── class-activator.php           # Activation handler
│   └── class-init.php                # Init class
└── public/
    ├── class-frontend.php            # Frontend class
    ├── assets/
    │   ├── js/
    │   │   ├── swiper-bundle.min.js  # Swiper library
    │   │   └── swiper-config.js      # Swiper config
    │   └── styles/
    │       ├── animate.css
    │       ├── swiper-bundle.min.css
    │       └── swiper-custom.css
    └── views/
        └── render_carousel.php       # Frontend template
```

## Constants

```php
CAROUSEL3_VERSION              // '1.0.0'
CAROUSEL3_PLUGIN_DIR           // /path/to/carousel3/
CAROUSEL3_PLUGIN_URL           // http://example.com/wp-content/plugins/carousel3/
CAROUSEL3_PLUGIN_BASENAME      // carousel3/Carousel3.php
CAROUSEL3_PLUGIN_NAME          // 'carousel3'
CAROUSEL3_PLUGIN_KEY           // '_carousel3'
TEXT_DOMAIN                    // 'carousel3'
```

## Extending Carousel3

### Adding Custom Meta Fields

1. Add to `admin/class-carousels.php`:

```php
public function add_meta_boxes() {
    // ... existing code ...
    
    add_meta_box(
        'carousel3_custom_settings',
        __('Custom Settings', TEXT_DOMAIN),
        array($this, 'render_custom_settings'),
        CAROUSEL3_PLUGIN_NAME,
        'side'
    );
}

public function render_custom_settings($post) {
    wp_nonce_field('carousel3_save_data', 'carousel3_nonce');
    $value = get_post_meta($post->ID, CAROUSEL3_PLUGIN_KEY . '_custom_field', true);
    ?>
    <input type="text" name="<?php echo CAROUSEL3_PLUGIN_KEY; ?>_custom_field" 
           value="<?php echo esc_attr($value); ?>" />
    <?php
}

public function save_carousel_data($post_id, $post) {
    // ... existing checks ...
    
    if (isset($_POST[CAROUSEL3_PLUGIN_KEY . '_custom_field'])) {
        $value = sanitize_text_field($_POST[CAROUSEL3_PLUGIN_KEY . '_custom_field']);
        update_post_meta($post_id, CAROUSEL3_PLUGIN_KEY . '_custom_field', $value);
    }
}
```

### Adding Custom Shortcode Attributes

1. Modify `public/class-frontend.php`:

```php
public function carousel_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id'       => 0,
        'autoplay' => true,        // New attribute
        'speed'    => 3000,        // New attribute
    ), $atts, CAROUSEL3_PLUGIN_NAME);
    
    // Pass to template
    $autoplay = $atts['autoplay'];
    $speed = $atts['speed'];
    
    ob_start();
    include CAROUSEL3_PLUGIN_DIR . 'public/views/render_carousel.php';
    return ob_get_clean();
}
```

### Modifying Swiper Configuration

1. Edit `public/assets/js/swiper-config.js`:

```javascript
var swiper = new Swiper(container, {
    // Add your custom configuration here
    direction: "horizontal",
    loop: true,
    effect: effect,
    // Custom options
    allowTouchMove: true,
    grabCursor: true,
    // ... other options
});
```

## Security Considerations

### Nonce Verification

All admin forms include nonce verification:

```php
wp_nonce_field('carousel3_save_data', 'carousel3_nonce');

// In handler:
if (!wp_verify_nonce($_POST['carousel3_nonce'], 'carousel3_save_data')) {
    return;
}
```

### Capability Checks

User permissions verified before operations:

```php
if (!current_user_can('edit_posts')) {
    return;
}
```

### Data Sanitization

All user input sanitized:

```php
sanitize_text_field()     // Text inputs
intval()                  // Integer values
absint()                  // Absolute integers
wp_unslash()             // Remove slashes
esc_attr()               // Escape HTML attributes
esc_html()               // Escape HTML content
esc_url()                // Escape URLs
```

### Output Escaping

All dynamic output escaped:

```php
echo esc_html($title);
echo esc_attr($value);
echo wp_kses_post($html);
```

## Debugging

### Enable WordPress Debug

Add to `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### Check Debug Log

```bash
tail -f wp-content/debug.log
```

### Browser Console

Press F12 to open developer tools and check:
- Console tab for JavaScript errors
- Network tab for failed asset loads
- Elements tab for HTML structure

### PHP Debugging

Add temporary debug output:

```php
error_log('Carousel ID: ' . print_r($carousel_id, true));
```

## Testing

### Unit Testing Setup

Create `tests/test-carousel.php`:

```php
<?php

class CarouselTest extends WP_UnitTestCase {
    
    public function test_shortcode_registered() {
        $this->assertTrue(shortcode_exists('carousel3'));
    }
    
    public function test_carousel_query() {
        // Create test carousel and slides
        $carousel_id = wp_insert_post([
            'post_type' => 'carousel3',
            'post_title' => 'Test Carousel'
        ]);
        
        $this->assertGreaterThan(0, $carousel_id);
    }
}
```

Run tests:

```bash
phpunit
```

## Performance Tips

### Optimization Checklist

- [x] WP_Query optimized with `no_found_rows`
- [x] Assets lazy loaded (only when shortcode used)
- [x] Autoloader for efficient class loading
- [x] Minified JavaScript library
- [x] No unnecessary database queries

### Future Optimization

- Implement query caching
- Add object caching support
- Implement lazy-loading images
- Code split Swiper configuration

## Code Style

### PHP Standards

- PSR-12 compatible
- Camelcase for variables and methods
- snake_case for WordPress hooks
- Class names in PascalCase

### JavaScript Standards

- ES6+ syntax
- Const/let over var
- Arrow functions where appropriate
- Clear variable names

## Version History

### 1.0.0 (2026-03-21)
- Initial production release
- Clean code for deployment
- Comprehensive documentation
- Production checklist

## Contributing

### Code Review Checklist

Before submitting code:

1. Follow code style guidelines
2. Add security checks (nonce, capability)
3. Sanitize/escape all data
4. Add comments for complex logic
5. Test thoroughly
6. Update documentation

### Git Workflow

```bash
# Create feature branch
git checkout -b feature/my-feature

# Make changes and test
git commit -m "Add feature description"

# Push and create pull request
git push origin feature/my-feature
```

## Resources

- [WordPress Plugin Development](https://developer.wordpress.org/plugins/)
- [Swiper.js Documentation](https://swiperjs.com/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- [WordPress Security](https://developer.wordpress.org/plugins/security/)

## Contact & Support

- GitHub: [Your Repository]
- Email: support@musite.xyz
- Website: https://musite.xyz

---

**Last Updated**: 2026-03-21  
**Version**: 1.0.0  
**Maintained By**: Development Team
