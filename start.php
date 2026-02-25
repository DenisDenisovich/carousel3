<?php
/**
 * Plugin Name: My Carousel
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MY_CAROUSEL_PATH', plugin_dir_path(__FILE__));
define('MY_CAROUSEL_URL', plugin_dir_url(__FILE__));

require_once MY_CAROUSEL_PATH . 'includes/post-types.php';
require_once MY_CAROUSEL_PATH . 'includes/meta-boxes.php';
require_once MY_CAROUSEL_PATH . 'includes/slides-admin.php';
require_once MY_CAROUSEL_PATH . 'includes/ajax.php';