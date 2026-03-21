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
        <?php _e('Анимация', 'carousel3'); ?>
    </label>
    <select id="<?php echo CAROUSEL3_PLUGIN_KEY; ?>_animation_type" name="<?php echo CAROUSEL3_PLUGIN_KEY; ?>_animation_type">
        <?php
        $animations = [
            ''                              => __('No animation', 'carousel3'),
            'animate__fadeIn'               => __('Fade In', 'carousel3'),
            'animate__fadeInUp'             => __('Fade In Up', 'carousel3'),
            'animate__fadeInDown'           => __('Fade In Down', 'carousel3'),
            'animate__bounceIn'             => __('Bounce In', 'carousel3'),
            'animate__zoomIn'               => __('Zoom In', 'carousel3'),
            'animate__slideInLeft'          => __('Slide In Left', 'carousel3'),
            'animate__slideInRight'         => __('Slide In Right', 'carousel3'),
            'animate__backInDown'           => __('Back In Down', 'carousel3'),
            'animate__flipInX'              => __('Flip In X', 'carousel3'),
            'animate__lightSpeedInRight'    => __('Light Speed In', 'carousel3'),
            'animate__rollIn'               => __('Roll In', 'carousel3'),
        ];

        foreach ( $animations as $value => $label ) : ?>
            <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $animation_type, $value ); ?>>
                <?php echo esc_html( $label ); ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>