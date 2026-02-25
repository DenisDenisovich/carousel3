<?php

namespace Carousel3;

/**
 * @package Carousel3
 */

if (!defined('ABSPATH')) {
    exit;
}

class Menu {
    public function __construct() {
        add_action('init', array($this, 'create_menu'));
    }
    // Мамка всех менюшек и подменюшек
    public static function create_menu() {
        self::menu_carousel();
        self::menu_slides();
    }

    public static function menu_carousel() {
        register_post_type('my_carousel', [
            'labels' => [
                'name' => 'Карусели',
                'singular_name' => 'Карусель',
                'add_new' => 'Добавить новую',
                'add_new_item' => 'Создание новой карусели',
                'edit_item' => 'Редактирование карусели',
            ],
            'public' => false,
            'show_ui' => true,
            'menu_icon' => 'dashicons-images-alt2',
            'supports' => ['title'],
            'capability_type' => 'post',
        ]);
    }

    public static function menu_slides() {
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