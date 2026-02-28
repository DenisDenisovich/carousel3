<?php

if (!defined('ABSPATH')) {
    exit;
}

// 2. Проверка есть ли записи
if ( ! $query->have_posts() ) {
    echo '<pre>Слайды не найдены для parent ID: ' . esc_html($post_parent) . '</pre>';
    return;
}

// 3. Отладочный вывод
echo '<pre>';
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

echo '</pre>'