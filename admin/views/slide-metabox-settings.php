<?php

namespace Carousel3;

/**
 * @package Carousel3
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render slide metabox settings HTML
 *
 * @param string $animation_type
 * @return string
 */
function render_slide_metabox_settings($animation_type) {
    $animations = [
        ''                              => __('No animation', 'denissv-animated-text-slider'),
        'animate__fadeIn'               => __('Fade In', 'denissv-animated-text-slider'),
        'animate__fadeInUp'             => __('Fade In Up', 'denissv-animated-text-slider'),
        'animate__fadeInDown'           => __('Fade In Down', 'denissv-animated-text-slider'),
        'animate__bounceIn'             => __('Bounce In', 'denissv-animated-text-slider'),
        'animate__zoomIn'               => __('Zoom In', 'denissv-animated-text-slider'),
        'animate__slideInLeft'          => __('Slide In Left', 'denissv-animated-text-slider'),
        'animate__slideInRight'         => __('Slide In Right', 'denissv-animated-text-slider'),
        'animate__backInDown'           => __('Back In Down', 'denissv-animated-text-slider'),
        'animate__flipInX'              => __('Flip In X', 'denissv-animated-text-slider'),
        'animate__lightSpeedInRight'    => __('Light Speed In', 'denissv-animated-text-slider'),
        'animate__rollIn'               => __('Roll In', 'denissv-animated-text-slider'),
    ];

    ob_start();
    ?>
    <p>
        <label for="<?php echo esc_attr( DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY ); ?>_animation_type">
            <?php esc_html_e('Анимация', 'denissv-animated-text-slider'); ?>
        </label>
        <select id="<?php echo esc_attr( DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY ); ?>_animation_type" name="<?php echo esc_attr( DENISSV_ANIMATED_TEXT_SLIDER_PLUGIN_KEY ); ?>_animation_type">
            <?php foreach ( $animations as $value => $label ) : ?>
                <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $animation_type, $value ); ?>>
                    <?php echo esc_html( $label ); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <?php
    return ob_get_clean();
}