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

    ob_start();
    ?>
    <p>
        <label for="<?php echo esc_attr( CAROUSEL3_PLUGIN_KEY ); ?>_animation_type">
            <?php esc_html_e('Анимация', 'carousel3'); ?>
        </label>
        <select id="<?php echo esc_attr( CAROUSEL3_PLUGIN_KEY ); ?>_animation_type" name="<?php echo esc_attr( CAROUSEL3_PLUGIN_KEY ); ?>_animation_type">
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