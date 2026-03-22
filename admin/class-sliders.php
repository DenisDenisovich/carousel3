<?php

namespace Carousel3;

/**
 * @package Carousel3
 */

if (!defined('ABSPATH')) {
    exit;
}

class Sliders {
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
        
        add_action('edit_form_after_title', array($this, 'render_parent_hidden_field'));
        add_filter('wp_insert_post_data', array($this, 'set_parent_for_slide_on_save'), 10, 2);
        // Добавление мета-боксов
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));

        // Сохранение данных
        add_action('save_post_' . self::POST_TYPE_SLIDE, array($this,'save_slide_data'), 10, 2);

        // Отчаяние
        add_action('edit_form_top', [$this, 'render_back_button']);
    }

    public function render_back_button($post) {
        // Проверяем тип поста
        if ($post->post_type !== self::POST_TYPE_SLIDE) {
            return;
        }

        // Получаем ID родителя: из URL (безопасно) или из данных поста
        $parent_id = filter_input(INPUT_GET, 'parent', FILTER_VALIDATE_INT);
        if (!$parent_id && !empty($post->post_parent)) {
            $parent_id = $post->post_parent;
        }

        if ($parent_id) {
            $parent_url = admin_url("post.php?post={$parent_id}&action=edit");
            echo sprintf(
                '<a href="%s" class="page-title-action" style="margin: 10px 0 20px 0; display: inline-block;">⬅ %s</a>',
                esc_url($parent_url),
                esc_html__('Back to carousel', 'carousel3')
            );
        }
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
            __('Настройки слайда', 'carousel3'),
            array($this, 'render_slide_settings_meta_box'),
            self::POST_TYPE_SLIDE,
            'side',
            'default'
        );
    }

 
    public function render_slide_settings_meta_box($post) {
        wp_nonce_field('carousel3_save_data', 'carousel3_slide_nonce');
        if (!isset($post->ID)) {
            echo '<p>' . esc_html__('Сохраните слайд, чтобы увидеть настройки.', 'carousel3') . '</p>';
            return;
        }

        $animation_type = get_post_meta(
            $post->ID,
            CAROUSEL3_PLUGIN_KEY . '_animation_type',
            true
        );

        $animation_type = $animation_type ? $animation_type : 'none';

        // Выводим настройки слайда
        require_once CAROUSEL3_PLUGIN_DIR . 'admin/views/slide-metabox-settings.php';
        echo wp_kses(render_slide_metabox_settings($animation_type), [
            'label' => [
                'for' => [],
            ],
            'select' => [
                'id'    => [],
                'name'  => [],
                'class' => [],
            ],
            'option' => [
                'value'    => [],
                'selected' => [],
            ],
            // Добавьте 'label' или 'span', если функция их выводит
        ]);
    }

    public function save_slide_data($post_id, $post) {
        // Проверка nonce
        if (
            !isset($_POST['carousel3_slide_nonce']) || 
            !wp_verify_nonce(
                sanitize_text_field(
                    wp_unslash($_POST['carousel3_slide_nonce'])
                ),
                'carousel3_save_data'
            )
        ) {
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
            $animation_type = sanitize_text_field( wp_unslash( $_POST[CAROUSEL3_PLUGIN_KEY . '_animation_type'] ) );
            update_post_meta($post_id, CAROUSEL3_PLUGIN_KEY . '_animation_type', $animation_type);
        }
    }

    public function render_parent_hidden_field($post) {
        if ($post->post_type !== self::POST_TYPE_SLIDE) {
            return;
        }

        $parent_id = 0;

        if (isset($_GET['parent'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $parent_id = absint(wp_unslash($_GET['parent'])); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
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

        if (
            !isset($_POST['carousel3_slide_nonce']) || 
            !wp_verify_nonce(
                sanitize_text_field(
                    wp_unslash($_POST['carousel3_slide_nonce'])
                ),
                'carousel3_save_data'
            )
        ) {
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