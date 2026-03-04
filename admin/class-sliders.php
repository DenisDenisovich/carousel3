<?php

namespace Carousel3;

/**
 * @package Carousel3
 */

if (!defined('ABSPATH')) {
    exit;
}

class Sliders {
    // TODO: Добавить настройки слайда, например: ссылка, атрибуты для анимации и т.д. И вынести их в отдельный мета-бокс
    private const POST_TYPE_SLIDE = CAROUSEL3_PLUGIN_NAME . '_slides';

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

        add_action('admin_title', [$this, 'add_back_button'], 10, 2); // TODO OPTIMIZE: Срабатывает на всех страницах админки, нужно только на странице редактирования слайда внутри карусели
        
        add_action('edit_form_after_title', array($this, 'render_parent_hidden_field'));
        add_filter('wp_insert_post_data', array($this, 'set_parent_for_slide_on_save'), 10, 2);
        // Добавление мета-боксов
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));

        // Сохранение данных
        add_action('save_post_' . self::POST_TYPE_SLIDE, array($this,'save_slide_data'), 10, 2);
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
            'hierarchical' => false, // КЛЮЧЕВОЕ
            'supports' => [
                'title',
                'editor',
                'thumbnail',
            ],
        ]);
    }

    public function add_meta_boxes() {
        add_meta_box(
            CAROUSEL3_PLUGIN_NAME . '_slide_settings',
            __('Настройки слайда', TEXT_DOMAIN),
            array($this, 'render_slide_settings_meta_box'),
            self::POST_TYPE_SLIDE,
            'side',
            'default'
        );
    }

    public function add_back_button($admin_title, $title) {

        global $post;
        $screen = get_current_screen();
        if ($screen->id !== self::POST_TYPE_SLIDE) { 
            return $admin_title;
        }

        if ($post->post_type !== self::POST_TYPE_SLIDE) {
            return;
        }

        $parent_id = 0;

        if (isset($_GET['parent'])) {
            $parent_id = absint($_GET['parent']);
        } elseif (!empty($post->post_parent)) {
            $parent_id = absint($post->post_parent);
        }

        if ($parent_id <= 0) {
            return;
        }

        $parent_url = add_query_arg(
            [
                'post'   => $parent_id,
                'action' => 'edit',
            ],
            admin_url('post.php')
        );

        add_action('admin_notices', function() use ($parent_url) {
            echo '<style>
                .wp-heading-inline {
                    display:flex;
                    align-items:center;
                    gap:15px;
                    a .page-title-action {
                        margin-left: 20px;
                    }
                }
            </style>';

        echo '<script>
            document.addEventListener("DOMContentLoaded", function(){
                let heading = document.querySelector("h1.wp-heading-inline");
                heading = heading || document.querySelector(".wrap h1"); // Альтернативный селектор для старых версий WP
                layout = heading.closest(".wrap");
                if (!layout) return;

                const btn = document.createElement("a");
                btn.style.display = "block";
                btn.style.width = "300px";
                btn.style.textAlign = "center";
                btn.style.backgroundColor = "#0073aa";
                btn.style.color = "#fff";
                btn.style.padding = "6px 12px";
                btn.href = ' . json_encode($parent_url) . ';
                btn.className = "page-title-action";
                btn.textContent = " ⬅ " + ' . json_encode(__('Back to carousel', TEXT_DOMAIN)) . ';

                layout.prepend(btn);
            });
            </script>';
        });

        return $admin_title;
    }

    public function render_slide_settings_meta_box($post) {
        wp_nonce_field('carousel3_save_data', 'carousel3_slide_nonce');
        if (!isset($post->ID)) {
            echo '<p>' . __('Сохраните слайд, чтобы увидеть настройки.', TEXT_DOMAIN) . '</p>';
            return;
        }

        
        if (isset($_POST[CAROUSEL3_PLUGIN_KEY . "_animation_type"])) {
            $animation_type = sanitize_text_field($_POST[CAROUSEL3_PLUGIN_KEY . "_animation_type"]);
        } else {
            $animation_type = get_post_meta($post->ID, CAROUSEL3_PLUGIN_KEY . '_animation_type', true);
            $animation_type = $animation_type ? $animation_type : 'none';
        }
        // Выводим настройки слайда
        include CAROUSEL3_PLUGIN_DIR . 'admin/views/slide-metabox-settings.php';
    }

    public function save_slide_data($post_id, $post) {
        // Проверка nonce
        if (!isset($_POST['carousel3_slide_nonce']) || !wp_verify_nonce($_POST['carousel3_slide_nonce'], 'carousel3_save_data')) {
            return;
        }

        // Проверка автосохранения
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Проверка прав
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST[CAROUSEL3_PLUGIN_KEY . '_animation_type'])) {
            $animation_type = sanitize_text_field($_POST[CAROUSEL3_PLUGIN_KEY . '_animation_type']);
            update_post_meta($post_id, CAROUSEL3_PLUGIN_KEY . '_animation_type', $animation_type);
        }
    }

    public function render_parent_hidden_field($post) {
        if ($post->post_type !== self::POST_TYPE_SLIDE) {
            return;
        }

        $parent_id = 0;

        if (isset($_GET['parent'])) {
            $parent_id = absint($_GET['parent']);
        } elseif (!empty($post->post_parent)) {
            $parent_id = absint($post->post_parent);
        }

        if ($parent_id <= 0) {
            return;
        }

        $parent_url = get_edit_post_link($parent_id);

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