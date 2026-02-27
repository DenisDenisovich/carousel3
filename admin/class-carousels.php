<?php

namespace Carousel3;

/**
 * @package Carousel3
 */

if (!defined('ABSPATH')) {
    exit;
}

class Carousels {
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
        add_action('init', array($this, 'create_menu_carousel'));
        $this->create_menu_carousel();
    }
    
    public function create_menu_carousel() {
        $this->menu_carousel();
    }

    public function menu_carousel() {
        register_post_type('my_carousel', [
            'labels' => [
                'name' => 'Карусели3',
                'singular_name' => 'Карусель3',
                'add_new' => 'Добавить новую',
                'add_new_item' => 'Создание новой карусели3',
                'edit_item' => 'Редактирование карусели3',
            ],
            'public' => false,
            'show_ui' => true,
            'menu_icon' => 'dashicons-images-alt2',
            'supports' => ['title'],
            'capability_type' => 'post',
        ]);
    }
}