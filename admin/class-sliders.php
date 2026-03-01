<?php

namespace Carousel3;

/**
 * @package Carousel3
 */

if (!defined('ABSPATH')) {
    exit;
}

class Sliders {
    private const POST_TYPE_SLIDE = PLUGIN_NAME . '_slides';

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init_hooks();
    }

    private function init_hooks() {
        $this->create_menu_slides();
        
        add_action('edit_form_after_title', array($this, 'render_parent_hidden_field'));
        add_filter('wp_insert_post_data', array($this, 'set_parent_for_slide_on_save'), 10, 2);
        // Добавление мета-боксов
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
    }

    public function create_menu_slides() {
        register_post_type(self::POST_TYPE_SLIDE, [
            'labels' => [
                'name' => 'Слайды',
                'singular_name' => 'Слайд',
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false, // Скрываем из основного меню
            'hierarchical' => true, // КЛЮЧЕВОЕ
            'supports' => [
                'title',
                'editor',
                'thumbnail',
                'page-attributes'
            ],
        ]);
    }

    public function add_meta_boxes() {
        add_meta_box(
            PLUGIN_NAME . '_slide_settings',
            __('Настройки слайда', TEXT_DOMAIN),
            array($this, 'render_slide_settings_meta_box'),
            self::POST_TYPE_SLIDE,
            'side',
            'default'
        );
    }



    public function render_slide_settings_meta_box($post) {
        // Выводим настройки слайда
        include CAROUSEL3_PLUGIN_DIR . 'admin/views/slide-metabox-settings.php';
    }

    public function render_parent_hidden_field($post) {
        if (!isset($_GET['post_type']) || $_GET['post_type'] !== self::POST_TYPE_SLIDE) {
            return;
        }

        if (!isset($_GET['parent'])) {
            return;
        }

        $parent_id = absint($_GET['parent']);
        if ($parent_id <= 0) {
            return;
        }

        echo '<input type="hidden" name="carousel_parent_id" value="' . esc_attr($parent_id) . '">';
    }

    public function set_parent_for_slide_on_save($data, $postarr) {
        if (!isset($data['post_type']) || $data['post_type'] !== self::POST_TYPE_SLIDE) {
            return $data;
        }

        $parent_id = 0;

        if (isset($_POST['carousel_parent_id'])) {
            $parent_id = absint($_POST['carousel_parent_id']);
        } elseif (isset($postarr['post_parent'])) {
            $parent_id = absint($postarr['post_parent']);
        }

        if ($parent_id > 0) {
            $data['post_parent'] = $parent_id;
        }

        return $data;
    }
}