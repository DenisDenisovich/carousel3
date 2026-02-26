<?php

namespace Carousel3;

/**
 * @package Carousel3
 */

if (!defined('ABSPATH')) {
    exit;
}

class Menu {
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
        add_action('init', array($this, 'create_menu'));
        $this->create_menu();
    }
    // Мамка всех менюшек и подменюшек
    public function create_menu() {
        $this->menu_carousel();
        $this->menu_slides();
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

    public function menu_slides() {
        register_post_type('my_slide', [
            'labels' => [
                'name' => 'Слайды',
                'singular_name' => 'Слайд',
                'add_new' => 'Добавить новый',
                'add_new_item' => 'Создание нового слайда',
                'edit_item' => 'Редактирование слайда',
            ],
            'public' => false,
            'show_ui' => true,
            'menu_icon' => 'dashicons-images-alt2',
            'supports' => ['title', 'editor', 'thumbnail'],
            'capability_type' => 'post',
        ]);
    }
}