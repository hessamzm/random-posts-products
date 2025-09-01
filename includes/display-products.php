<?php
// نمایش 3 محصول رندوم
function show_random_products() {
    $args = array(
        'post_type' => 'product', // محصول از نوع WooCommerce
        'posts_per_page' => 3,
        'orderby' => 'rand', // نمایش تصادفی
        'post_status' => 'publish'
    );

    $random_products = new WP_Query($args);

    if ($random_products->have_posts()) {
        echo '<div class="random-products-container">';
        while ($random_products->have_posts()) {
            $random_products->the_post();
            echo '<div class="random-product">';
            echo '<div class="product-thumbnail">' . get_the_post_thumbnail(get_the_ID(), 'thumbnail') . '</div>';
			echo '<h4><a href="' . get_the_permalink() . '">' . wp_trim_words(get_the_title(), 6, '...') . '</a></h4>';
            echo '</div>';
        }
        echo '</div>';
    }
    wp_reset_postdata();
}
?>
