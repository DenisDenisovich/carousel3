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

    public static $carousel3_pages = array(
        'edit-carousel3',
        'carousel3',
        'carousel3_slides',
    );

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
        if ($hook !== 'post.php') {
            return;
        }

        $screen = get_current_screen();

        error_log('Current screen ID: ' . $screen->id); // Отладочный вывод

        wp_enqueue_style(PLUGIN_NAME . '-admin', CAROUSEL3_PLUGIN_URL . 'admin/css/admin.css', array(), CAROUSEL3_VERSION);
        wp_enqueue_script(PLUGIN_NAME . '-admin', CAROUSEL3_PLUGIN_URL . 'admin/js/admin.js', array('jquery'), CAROUSEL3_VERSION, true);
    }
}