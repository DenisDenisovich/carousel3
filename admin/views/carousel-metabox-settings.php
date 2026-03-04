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
        <p style="margin:0 0 6px;"><strong><?php _e('Шорткод карусели', TEXT_DOMAIN); ?>:</strong></p>
        <?php if ($shortcode) : ?>
            <input type="text" class="regular-text code carousel3-shortcode-field" readonly value="<?php echo esc_attr($shortcode); ?>" onclick="this.select();" />
        <?php else : ?>
            <p class="description"><?php _e('Сохраните карусель, чтобы получить шорткод.', TEXT_DOMAIN); ?></p>
        <?php endif; ?>
    </div>
    <p>
        <label for="<?php echo CAROUSEL3_PLUGIN_KEY; ?>_effect">
            <?php _e('Тип анимации', TEXT_DOMAIN); ?>
        </label>
        <select id="<?php echo CAROUSEL3_PLUGIN_KEY; ?>_effect" name="<?php echo CAROUSEL3_PLUGIN_KEY; ?>_effect">
            <option value="slide" <?php selected($effect, 'slide'); ?>><?php _e('Скользящий', TEXT_DOMAIN); ?></option>
            <option value="fade" <?php selected($effect, 'fade'); ?>><?php _e('Исчезновение', TEXT_DOMAIN); ?></option>
        </select>
    </p>
    <p>
        <label>
            <input type="checkbox" name="<?php echo CAROUSEL3_PLUGIN_KEY; ?>_show_arrows" value="1" <?php checked($show_arrows, '1'); ?>>
            <?php _e('Показывать стрелки навигации', TEXT_DOMAIN); ?>
        </label>
    </p>
    <p>
        <label>
            <input type="checkbox" name="<?php echo CAROUSEL3_PLUGIN_KEY; ?>_show_dots" value="1" <?php checked($show_dots, '1'); ?>>
            <?php _e('Показывать точки навигации', TEXT_DOMAIN); ?>
        </label>
    </p>
    <p>
        <label for="<?php echo CAROUSEL3_PLUGIN_KEY; ?>_height">
            <?php _e('Высота карусели (например, 300px или 50%)', TEXT_DOMAIN); ?>
        </label>
        <input type="text" id="<?php echo CAROUSEL3_PLUGIN_KEY; ?>_height" name="<?php echo CAROUSEL3_PLUGIN_KEY; ?>_height" 
               value="<?php echo esc_attr($height); ?>" 
               placeholder="none" class="regular-text">
    </p>
</div>