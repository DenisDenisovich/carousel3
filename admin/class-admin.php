<?php

namespace Carousel3;

/**
 * @package Carousel3
 */

if (!defined('ABSPATH')) {
    exit;
}

class Admin {
    private static $instance = null;

    public static $carousel3_pages = [
        'edit-carousel3',
        'carousel3',
        'carousel3_slides',
    ];

    public static $allowed_hooks = [
        'post.php', 
        'edit.php'
    ];

    private function __construct() {
        $this->init_hooks();
    }

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function init_hooks() {
        Carousels::get_instance();
        Sliders::get_instance();

        // Подключение Js и CSS для админки только в плагине
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    public function enqueue_admin_assets($hook) {
        $screen = get_current_screen();
        if (empty($hook) || !in_array($hook, self::$allowed_hooks) || empty($screen) || !in_array($screen->id, self::$carousel3_pages)) {
            return;
        }

        wp_enqueue_style(CAROUSEL3_PLUGIN_NAME . '-css-admin', CAROUSEL3_PLUGIN_URL . 'admin/css/admin.css', array(), CAROUSEL3_VERSION);

        wp_enqueue_script(CAROUSEL3_PLUGIN_NAME . '-js-admin', CAROUSEL3_PLUGIN_URL . 'admin/js/admin.js', array('jquery'), CAROUSEL3_VERSION, true);
        wp_localize_script(CAROUSEL3_PLUGIN_NAME . '-js-admin', 'carousel3TableSort', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('carousel3_sort_slides_nonce'),
        ]);
    }
}