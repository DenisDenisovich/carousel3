<?php

namespace Carousel3;

/**
 * @package Carousel3
 */

if (!defined('ABSPATH')) {
    exit;
}

class Sliders {
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
        add_action('init', array($this, 'create_menu_slides'));
        add_action('add_meta_boxes', array($this, 'add_carousel_slides_meta_box'));
        // Установка родительской связи между слайдами и каруселью
        add_action('add_meta_boxes', array($this, 'set_parent_for_new_slide'));
        $this->create_menu_slides();
    }

    public function create_menu_slides() {
        register_post_type('my_carousel_slide', [
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

    //Добавляем страницу управления слайдами внутри Карусели
    public function add_carousel_slides_meta_box() {
        add_meta_box(
            'carousel_slides_meta_box',
            'Слайды карусели',
            array($this, 'render_carousel_slides'),
            'my_carousel',
            'normal',
            'high'
        );
    }

    // Таблица слайдов внутри карусели
    public function render_carousel_slides($post) {
        
        $slides = get_children([
            'post_type'   => 'my_carousel_slide',
            'post_parent' => $post->ID,
            'orderby'     => 'menu_order',
            'order'       => 'ASC'
        ]);

        echo '<table class="widefat striped">';
        echo '<thead><tr><th>Заголовок</th><th>Порядок</th><th>Действия</th></tr></thead>';
        echo '<tbody>';

        if ($slides) {
            foreach ($slides as $slide) {
                echo '<tr>';
                echo '<td>' . esc_html($slide->post_title) . '</td>';
                echo '<td>' . intval($slide->menu_order) . '</td>';
                echo '<td>';
                echo '<a href="' . get_edit_post_link($slide->ID) . '">Редактировать</a>';
                echo '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="3">Слайдов нет</td></tr>';
        }

        echo '</tbody>';
        echo '</table>';

        // Кнопка добавить
        $add_link = admin_url('post-new.php?post_type=my_carousel_slide&parent=' . $post->ID);
        echo '<p><a class="button button-primary" href="' . esc_url($add_link) . '">Добавить слайд</a></p>';
    }

    // Установка родительской связи между слайдами и каруселью при создании нового слайда
    public function set_parent_for_new_slide() {
        if (
            isset($_GET['post_type']) &&
            $_GET['post_type'] === 'my_carousel_slide' &&
            isset($_GET['parent'])
        ) {
            add_filter('wp_insert_post_data', function ($data) {
                $data['post_parent'] = intval($_GET['parent']);
                return $data;
            }, 10, 1);
        }
    }
}