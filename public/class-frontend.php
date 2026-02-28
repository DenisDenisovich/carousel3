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
        add_shortcode('carausel3', array($this, 'carousel_shortcode'));
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
        ), $atts, PLUGIN_NAME);
        error_log('PLUGIN_NAME: ' . PLUGIN_NAME);
        
        $carousel_id = absint($atts['id']);

        $query = new WP_Query([
            'post_type'      => 'carousel3_slides',
            'post_parent'    => carousel_id,
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'no_found_rows'  => true,      // оптимизация
            'cache_results'  => true,
        ]);

        // 1. Проверка на ошибку запроса
        if ( is_wp_error( $query ) ) {
            echo '<pre>WP_Error: ';
            print_r( $query->get_error_messages() );
            echo '</pre>';
            return;
        }

        $slides = $query->posts;
        
        if (!$carousel_id) {
            return '<p>' . __('Укажите ID карусели', TEXT_DOMAIN) . '</p>';
        }
        
        $carousel = get_post($carousel_id);
        
        if (!$carousel || $carousel->post_type !== PLUGIN_KEY) {
            return '<p>' . __('Карусель не найдена', TEXT_DOMAIN) . '</p>';
        }
        
        $images = get_post_meta($carousel_id, PLUGIN_KEY . '_images', true);
        
        if (empty($images) || !is_array($images)) {
            return '<p>' . __('В карусели нет изображений', TEXT_DOMAIN) . '</p>';
        }
        
        // Получение настроек
        // $autoplay = get_post_meta($carousel_id, PLUGIN_KEY . '_autoplay', true);
        // ...
        
         // Значения по умолчанию

        // Подключаем библиотеку только там, где реально выводится шорткод
        // Стили карусели плагина
        wp_enqueue_style(
            'swcarousel-swiper-css',
            SWCAROUSEL_PLUGIN_URL . 'public/assets/styles/swiper-bundle.min.css',
            array(),
            SWCAROUSEL_VERSION
        );
        wp_enqueue_style(
            'swcarousel-swiper-custom-css',
            SWCAROUSEL_PLUGIN_URL . 'public/assets/styles/swiper-custom.css',
            array('swcarousel-swiper-css'),
            SWCAROUSEL_VERSION
        );
        wp_enqueue_script(
            'swcarousel-swiper-js',
            SWCAROUSEL_PLUGIN_URL . 'public/assets/js/swiper-bundle.min.js',
            array(),
            '9.4.1',
            true
        );
        wp_enqueue_script(
            'swcarousel-slider-config',
            SWCAROUSEL_PLUGIN_URL . 'public/assets/js/swiper-config.js',
            array('swcarousel-swiper-js'),
            SWCAROUSEL_VERSION,
            true
        );
        
        ob_start();
        include SWCAROUSEL_PLUGIN_DIR . 'public/views/render_carousel.php';
        return ob_get_clean();
    }
}