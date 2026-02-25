<?php
/**
 * Plugin Name: Carousel3
 * Plugin URI: https://your-site.com/
 * Description: Описание вашего плагина
 * Version: 1.0.0
 * Author: musite.xyz
 * Author URI: https://example.com
 * License: GPL2
 * Text Domain: carousel3
 * Domain Path: /languages
 */

// Запрет прямого доступа к файлу
if (!defined('ABSPATH')) {
    exit;
}

// Определение констант плагина
define('CAROUSEL3_VERSION', '1.0.0');
define('CAROUSEL3_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CAROUSEL3_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CAROUSEL3_PLUGIN_BASENAME', plugin_basename(__FILE__));

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
            $file = CAROUSEL3_PLUGIN_DIR . $dir . 'class-' . $candidate . '.php';

            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
}

// Инициализация плагина
function carousel3_init() {
    // Загрузка текстового домена для перевода
    load_plugin_textdomain('carousel3', false, dirname(CAROUSEL3_PLUGIN_BASENAME) . '/languages');

    // Инициализация основного класса
    Carousel3\Init::get_instance();
}
add_action('plugins_loaded', 'carousel3_init');

// Активация плагина
register_activation_hook(__FILE__, array('Carousel3\Activator', 'activate'));