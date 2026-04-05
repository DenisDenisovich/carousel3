<?php

namespace Carousel3;

/**
 * @package Carousel3
 */

if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="carousel3-settings">
    <div class="carousel3-shortcode-wrap" style="margin-bottom:12px;">
        <p style="margin:0 0 6px;"><strong><?php esc_html_e('Шорткод карусели', 'carousel3'); ?>:</strong></p>
        <?php if ($shortcode) : ?>
            <input type="text" class="regular-text code carousel3-shortcode-field" readonly value="<?php echo esc_attr($shortcode); ?>" onclick="this.select();" />
        <?php else : ?>
            <p class="description"><?php esc_html_e('Сохраните карусель, чтобы получить шорткод.', 'carousel3'); ?></p>
        <?php endif; ?>
    </div>
    <p>
        <label for="<?php echo esc_attr( DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY ); ?>_effect">
            <?php esc_html_e('Тип анимации', 'carousel3'); ?>
        </label>
        <select id="<?php echo esc_attr( DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY ); ?>_effect" name="<?php echo esc_attr( DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY ); ?>_effect">
            <option value="slide" <?php selected($effect, 'slide'); ?>><?php esc_html_e('Скользящий', 'carousel3'); ?></option>
            <option value="fade" <?php selected($effect, 'fade'); ?>><?php esc_html_e('Исчезновение', 'carousel3'); ?></option>
        </select>
    </p>
    <p>
        <label>
            <input type="checkbox" name="<?php echo esc_attr( DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY ); ?>_show_arrows" value="1" <?php checked($show_arrows, '1'); ?>>
            <?php esc_html_e('Показывать стрелки навигации', 'carousel3'); ?>
        </label>
    </p>
    <p>
        <label>
            <input type="checkbox" name="<?php echo esc_attr( DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY ); ?>_show_dots" value="1" <?php checked($show_dots, '1'); ?>>
            <?php esc_html_e('Показывать точки навигации', 'carousel3'); ?>
        </label>
    </p>
    <p>
        <label for="<?php echo esc_attr( DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY ); ?>_height">
            <?php esc_html_e('Высота карусели (например, 300px или 50%)', 'carousel3'); ?>
        </label>
        <input type="text" id="<?php echo esc_attr( DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY ); ?>_height" name="<?php echo esc_attr( DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY ); ?>_height" 
               value="<?php echo esc_attr($height); ?>" 
               placeholder="none" class="regular-text">
    </p>
</div>