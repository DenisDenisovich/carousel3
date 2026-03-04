<?php

namespace Carousel3;

/**
 * @package Carousel3
 */

if (!defined('ABSPATH')) {
    exit;
}

class Frontend {
    /**
     * Единственный экземпляр класса
     *
     * @var Frontend
     */
    private static $instance = null;
    
    /**
     * Получить экземпляр класса
     *
     * @return Frontend
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Конструктор
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Инициализация хуков
     */
    private function init_hooks() {
        // Регистрация шорткода
        add_shortcode('carousel3', array($this, 'carousel_shortcode'));
    }

    /**
     * Шорткод для вывода карусели
     *
     * @param array $atts Атрибуты шорткода
     * @return string HTML код карусели
     */
    public function carousel_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
        ), $atts, CAROUSEL3_PLUGIN_NAME);
        
        
        $carousel_id = absint($atts['id']);

        if (!$carousel_id) {
            return '<p>' . __('Укажите ID карусели', TEXT_DOMAIN) . '</p>';
        }

        $carousel = get_post($carousel_id);

        if (!$carousel || $carousel->post_type !== CAROUSEL3_PLUGIN_NAME) {
            return '<p>' . __('Карусель не найдена', TEXT_DOMAIN) . '</p>';
        }

        $query = new \WP_Query([
            'post_type'      => CAROUSEL3_PLUGIN_NAME . '_slides',
            'post_parent'    => $carousel_id,
            'posts_per_page' => -1,
            'orderby'     => 'menu_order',
            'order'   => 'ASC',
            'post_status'    => 'any',
            'no_found_rows'  => true,      // оптимизация
            'cache_results'          => true,
        ]);

        // 1. Проверка на ошибку запроса
        if ( is_wp_error( $query ) ) {
            echo '<pre>WP_Error: ';
            print_r( $query->get_error_messages() );
            echo '</pre>';
            return;
        }

        // 2. Проверка есть ли записи
        if ( ! $query->have_posts() ) {
            echo '<pre>Слайды не найдены для parent ID: ' . esc_html($post_parent) . '</pre>';
            return;
        }

        $slides = $query->posts;
        
        // Получение настроек карусели
        $show_arrows = get_post_meta($carousel->ID, CAROUSEL3_PLUGIN_KEY . '_show_arrows', true);
        $show_dots = get_post_meta($carousel->ID, CAROUSEL3_PLUGIN_KEY . '_show_dots', true);
        $height = get_post_meta($carousel->ID, CAROUSEL3_PLUGIN_KEY . '_height', true);
        $effect = get_post_meta($carousel->ID, CAROUSEL3_PLUGIN_KEY . '_effect', true);

        // Значения по умолчанию
        $show_arrows = $show_arrows !== '' ? $show_arrows : '1';
        $show_dots = $show_dots !== '' ? $show_dots : '1';
        $height = $height ? $height : 'none';
        $effect = $effect ? $effect : 'slide';

        // Подключаем библиотеку только там, где реально выводится шорткод
        // Стили карусели плагина
        wp_enqueue_style(
            'carousel3-swiper-css',
            CAROUSEL3_PLUGIN_URL . 'public/assets/styles/swiper-bundle.min.css',
            array(),
            CAROUSEL3_VERSION
        );
        wp_enqueue_style(
            'carousel3-swiper-custom-css',
            CAROUSEL3_PLUGIN_URL . 'public/assets/styles/swiper-custom.css',
            array('carousel3-swiper-css'),
            CAROUSEL3_VERSION
        );
        wp_enqueue_style(
            'carousel3-animate-css',
            CAROUSEL3_PLUGIN_URL . 'public/assets/styles/animate.css',
            array('carousel3-swiper-custom-css'),
            CAROUSEL3_VERSION
        );
        wp_enqueue_script(
            'swcarousel-swiper-js',
            CAROUSEL3_PLUGIN_URL . 'public/assets/js/swiper-bundle.min.js',
            array(),
            '9.4.1',
            true
        );
        wp_enqueue_script(
            'swcarousel-slider-config',
            CAROUSEL3_PLUGIN_URL . 'public/assets/js/swiper-config.js',
            array('swcarousel-swiper-js'),
            CAROUSEL3_VERSION,
            true
        );
        
        ob_start();
        include CAROUSEL3_PLUGIN_DIR . 'public/views/render_carousel.php';
        return ob_get_clean();
    }
}