<?php

namespace Carousel3;

/**
 * @package Carousel3
 */

if (!defined('ABSPATH')) {
    exit;
}

class Init {
    private static $instance = null;

    private function __construct() {
        $this->init_hooks();
    }

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    private function init_hooks() {
        add_action('init', array($this, 'init_plugin'));
    }

    public function init_plugin() {
        // Инициализация классов плагина
        Carousels::get_instance();
        Sliders::get_instance();
        Frontend::get_instance();
        //new Public\Assets();
        //new Public\Shortcode();
    }
}