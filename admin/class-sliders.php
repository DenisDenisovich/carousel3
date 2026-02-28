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
        add_action('add_meta_boxes', array($this, 'add_carousel_slides_meta_box'));
        add_action('edit_form_after_title', array($this, 'render_parent_hidden_field'));
        add_filter('wp_insert_post_data', array($this, 'set_parent_for_slide_on_save'), 10, 2);
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

    //Добавляем страницу управления слайдами внутри Карусели
    public function add_carousel_slides_meta_box() {
        add_meta_box(
            'carousel_slides_meta_box',
            'Слайды карусели',
            array($this, 'render_carousel_slides'),
            PLUGIN_NAME,
            'normal',
            'high'
        );
    }

    // Таблица слайдов внутри карусели
    public function render_carousel_slides($post) {
        
        $slides = get_children([
            'post_type'   => self::POST_TYPE_SLIDE,
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
        $add_link = admin_url('post-new.php?post_type=' . self::POST_TYPE_SLIDE . '&parent=' . $post->ID);
        echo '<p><a class="button button-primary" href="' . esc_url($add_link) . '">Добавить слайд</a></p>';
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