<?php

if (!defined('ABSPATH')) {
    exit;
}

$slides_per_view = 1; //get_post_meta($carousel_id, '_swcarousel_slides_per_view', true); // Колличество видимых слайдов
$slides = max(1, (int)$slides_per_view);
$vw = round(100 / $slides, 4);
$sizes = "(max-width: 768px) 100vw, {$vw}vw";

?>
<div class="carouselwp-carousel slider-container">
    <div class="swiper carousel3" style="height:50vh;">
        <div class="swiper-wrapper">
            <?php foreach ($query->posts as $slide) : ?>
                <?php
                $slide_id = (int) $slide->ID; // ID записи типа "слайд"
                $thumb_id = get_post_thumbnail_id($slide_id); // ID самой картинки (attachment)

                if ($thumb_id) : // Проверяем, есть ли у слайда миниатюра
                    $attachment_image = wp_get_attachment_image(
                        $thumb_id,
                        'large',
                        false,
                        [
                            'sizes' => $sizes, // Убедитесь, что переменная $sizes определена выше
                            'class' => 'swiper-image'
                        ]
                    );
                ?>
                    <div class="swiper-slide">
                        <?php echo $attachment_image; ?>
                        <div class="description"><?php echo $slide->post_content; ?></div>
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
<?php

// 3. Отладочный вывод
/*echo '<pre>';
echo 'Найдено: ' . (int) $query->found_posts . PHP_EOL;
echo '------------------------' . PHP_EOL;

foreach ( $query->posts as $slide ) {

    // Проверка объекта
    if ( ! $slide instanceof WP_Post ) {
        continue;
    }

    echo 'ID: ' . (int) $slide->ID . PHP_EOL;
    echo 'Title: ' . esc_html( $slide->post_title ) . PHP_EOL;
    echo 'Status: ' . esc_html( $slide->post_status ) . PHP_EOL;
    echo 'Menu order: ' . (int) $slide->menu_order . PHP_EOL;
    echo 'Date: ' . esc_html( $slide->post_date ) . PHP_EOL;
    echo '------------------------' . PHP_EOL;
}

echo '</pre>';*/