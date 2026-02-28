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

        // Добавление мета-боксов
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
    }
    
    public function create_menu_carousel() {
        $this->menu_carousel();
    }

    public function menu_carousel() {
        register_post_type(PLUGIN_NAME, [
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

    /**
     * Добавление мета-боксов
     */
    public function add_meta_boxes() {        
        add_meta_box(
            PLUGIN_KEY . '_settings',
            __('Настройки карусели', TEXT_DOMAIN),
            array($this, 'render_settings_metabox'),
            PLUGIN_NAME,
            'side',
            'default'
        );
    }

    public function render_settings_metabox($post) {
        ?>
        
        <div class="carousel3-settings">
        <?php
        $shortcode = '';
        if (isset($post) && isset($post->ID) && $post->ID) {
            $shortcode = sprintf('[%s id="%d"]', PLUGIN_NAME, (int) $post->ID);
        }
        ?>

            <div class="carousel3-shortcode-wrap" style="margin-bottom:12px;">
                <p style="margin:0 0 6px;"><strong><?php _e('Шорткод карусели', TEXT_DOMAIN); ?>:</strong></p>
                <?php if ($shortcode) : ?>
                    <input type="text" class="regular-text code carousel3-shortcode-field" readonly value="<?php echo esc_attr($shortcode); ?>" onclick="this.select();" />
                    <p class="description" style="margin-top:6px;"><?php _e('Скопируйте и вставьте этот шорткод в запись или страницу.', TEXT_DOMAIN); ?></p>
                    <p class="description" style="margin-top:4px;">PHP: <code>&lt;?php echo do_shortcode('<?php echo esc_html($shortcode); ?>'); ?&gt;</code></p>
                <?php else : ?>
                    <p class="description"><?php _e('Сохраните карусель, чтобы получить шорткод.', TEXT_DOMAIN); ?></p>
                <?php endif; ?>
            </div>

        </div>
        
        <?php
    }
}