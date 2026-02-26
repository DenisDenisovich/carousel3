<?php
namespace LCE;

use Carousel3\Init;

/**
 * Класс для управления административной панелью
 *
 * @package LCE
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Класс для активации плагина
 */
class Activator {
    
    /**
     * Действия при активации плагина
     */
    public static function activate() {
        // Регистрация кастомного типа записи
        //self::register_post_type();
        
        // Обновление правил перезаписи URL
        //flush_rewrite_rules();

        // Установка версии плагина
        //update_option('lce_version', LCE_VERSION);
        
        // Установка даты активации
        //if (!get_option('lce_activation_date')) {
        //    update_option('lce_activation_date', current_time('mysql'));
        //}
    }
    
    /**
     * Регистрация кастомного типа записи
     */
    private static function register_post_type() {
        
    }
}