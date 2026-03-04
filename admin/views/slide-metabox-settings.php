<?php

namespace Carousel3;

/**
 * @package Carousel3
 */

if (!defined('ABSPATH')) {
    exit;
}
$autoplay_speed = get_post_meta($post->ID, '_swcarousel_autoplay_speed', true); // УДАЛИТЬ ПОСЛЕ ОТЛАДКИ
$autoplay_speed = $autoplay_speed ? $autoplay_speed : '3000'; // УДАЛИТЬ ПОСЛЕ ОТЛАДКИ
?>

<p>
    <label for="<?php echo CAROUSEL3_PLUGIN_KEY; ?>_animation_type">
        <?php _e('Анимация', TEXT_DOMAIN); ?>
    </label>
    <select id="<?php echo CAROUSEL3_PLUGIN_KEY; ?>_animation_type" name="<?php echo CAROUSEL3_PLUGIN_KEY; ?>_animation_type">
        <?php
        $animations = [
            ''                              => __('No animation', TEXT_DOMAIN),
            'animate__fadeIn'               => __('Fade In', TEXT_DOMAIN),
            'animate__fadeInUp'             => __('Fade In Up', TEXT_DOMAIN),
            'animate__fadeInDown'           => __('Fade In Down', TEXT_DOMAIN),
            'animate__bounceIn'             => __('Bounce In', TEXT_DOMAIN),
            'animate__zoomIn'               => __('Zoom In', TEXT_DOMAIN),
            'animate__slideInLeft'          => __('Slide In Left', TEXT_DOMAIN),
            'animate__slideInRight'         => __('Slide In Right', TEXT_DOMAIN),
            'animate__backInDown'           => __('Back In Down', TEXT_DOMAIN),
            'animate__flipInX'              => __('Flip In X', TEXT_DOMAIN),
            'animate__lightSpeedInRight'    => __('Light Speed In', TEXT_DOMAIN),
            'animate__rollIn'               => __('Roll In', TEXT_DOMAIN),
        ];

        foreach ( $animations as $value => $label ) : ?>
            <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $animation_type, $value ); ?>>
                <?php echo esc_html( $label ); ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>