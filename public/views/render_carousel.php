<?php

if (!defined('ABSPATH')) {
    exit;
}

$c3_conf = [
    'autoplay'         => '1',
    'autoplay_speed'   => '3000',
    'animation_speed'  => '600',
    'show_arrows'      => '1',
    'show_dots'        => '1',
    'show_scrollbar'   => '0',
    'spaces_between'   => '0',
    'slides_per_view'  => '1',
];

$c3_slides_count = max(1, (int)$c3_conf['slides_per_view']);
$c3_vw           = round(100 / $c3_slides_count, 4);
$c3_sizes        = "(max-width: 768px) 100vw, {$c3_vw}vw";

?>
<div class="carouselwp-carousel slider-container">
    <div class="swiper carousel3" 
        <?php echo ($height !== 'none') ? 'style="height:' . esc_attr($height) . ';"' : ''; ?>
        data-carousel-id="<?php echo esc_attr($carousel_id); ?>"
        data-autoplay="<?php echo esc_attr($c3_conf['autoplay']); ?>"
        data-autoplay-speed="<?php echo esc_attr($c3_conf['autoplay_speed']); ?>"
        data-animation-speed="<?php echo esc_attr($c3_conf['animation_speed']); ?>"
        data-show-arrows="<?php echo esc_attr($c3_conf['show_arrows']); ?>"
        data-show-dots="<?php echo esc_attr($c3_conf['show_dots']); ?>"
        data-show-scrollbar="<?php echo esc_attr($c3_conf['show_scrollbar']); ?>"
        data-height="<?php echo esc_attr($height); ?>"
        data-slides-per-view="<?php echo esc_attr($c3_conf['slides_per_view']); ?>"
        data-spaces-between="<?php echo esc_attr($c3_conf['spaces_between']); ?>"
        data-effect="<?php echo esc_attr($effect); ?>"
    >
        <div class="swiper-wrapper">
            <?php foreach ($query->posts as $slide) : 
                $animation_type = get_post_meta($slide->ID, CAROUSEL3_PLUGIN_KEY . '_animation_type', true);
                $animation_type = $animation_type ? $animation_type : 'animate__fadeInUp';

                $slide_id = (int) $slide->ID;
                $thumb_id = get_post_thumbnail_id($slide_id);

                if ($thumb_id) :
                    $attachment_image = wp_get_attachment_image(
                        $thumb_id,
                        'large',
                        false,
                        [
                            'sizes' => $c3_sizes,
                            'class' => 'swiper-image'
                        ]
                    );
            ?>
                <div class="swiper-slide">
                    <?php echo wp_kses_post( $attachment_image ); ?>

                    <div class="ani-item description" data-ani="<?php echo esc_attr( $animation_type ); ?>">
                        <h2><?php echo esc_html( $slide->post_title ); ?></h2>
                        <?php echo wp_kses_post( apply_filters( 'the_content', $slide->post_content ) ); ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <!-- Pagination -->
        <div class="swiper-pagination"></div>

        <!-- Navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>

    </div>
</div>