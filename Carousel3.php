<?php
/**
 * Plugin Name: Denissv Animated Text Slider
 * Description: Плагин для создания карусели с анимированным текстом.
 * Version: 1.0.0
 * License: GPL2
 * Text Domain: denissv-animated-text-slider
 * Domain Path: /languages
 */

// Запрет прямого доступа к файлу
if (!defined('ABSPATH')) {
    exit;
}

// Определение констант плагина
define('DENISSV_ANIMATED_TEXT_SLIDER_VERSION', '1.0.0');
define('DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_NAME', dirname(DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_BASENAME));
define('DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY', '_' . DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_NAME);

// Автозагрузка классов плагина
spl_autoload_register('carousel3_autoload');

function carousel3_autoload($class_name) {
    // работаем только с нашими классами
    if (strpos($class_name, 'Carousel3\\') !== 0) {
        return;
    }

    // убираем namespace
    $class = str_replace('Carousel3\\', '', $class_name);

    // Варианты имени файла для совместимости с разными стилями именования
    $class_flat = str_replace('\\', '-', $class);
    $class_kebab = preg_replace('/(?<!^)[A-Z]/', '-$0', $class_flat);
    $candidates = array_unique(array(
        strtolower($class_flat),      // EditorAssets -> editorassets
        lcfirst($class_flat),         // EditorAssets -> editorAssets
        strtolower($class_kebab),     // EditorAssets -> editor-assets
    ));

    $dirs = [
        'includes/',
        'admin/',
        'public/',
    ];

    foreach ($dirs as $dir) {
        foreach ($candidates as $candidate) {
            $file = DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_DIR . $dir . 'class-' . $candidate . '.php';

            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
}

// Инициализация плагина
function carousel3_init() {

    // Инициализация основного класса
    Carousel3\Init::get_instance();
}
add_action('plugins_loaded', 'carousel3_init');

