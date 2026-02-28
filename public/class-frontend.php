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
     * @var PublicContent
     */
    private static $instance = null;
    
    /**
     * Получить экземпляр класса
     *
     * @return PublicContent
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
        
        $carousel_id = absint($atts['id']);
        
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
        $autoplay = get_post_meta($carousel_id, PLUGIN_KEY . '_autoplay', true);
        $autoplay_speed = get_post_meta($carousel_id, PLUGIN_KEY . '_autoplay_speed', true);
        $show_arrows = get_post_meta($carousel_id, PLUGIN_KEY . '_show_arrows', true);
        $show_dots = get_post_meta($carousel_id, PLUGIN_KEY . '_show_dots', true);
        $show_scrollbar = get_post_meta($carousel_id, PLUGIN_KEY . '_show_scrollbar', true);
        $height = get_post_meta($carousel_id, PLUGIN_KEY . '_height', true);
        $animation_speed = get_post_meta($carousel_id, PLUGIN_KEY . '_animation_speed', true);
        $spaces_between = get_post_meta($carousel_id, PLUGIN_KEY . '_spaces_between', true);

        $slides_per_view = get_post_meta($carousel_id, PLUGIN_KEY . '_slides_per_view', true);
        $slides = max(1, (int)$slides_per_view);
        $slide_size = round(100 / $slides, 4);
        $vw = round(100 / $slides, 4);
        $sizes = "(max-width: 768px) 100vw, {$vw}vw";
        
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
        include SWCAROUSEL_PLUGIN_DIR . 'public/views/carousel.php';
        return ob_get_clean();
    }
}