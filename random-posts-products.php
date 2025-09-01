<?php
/**
 * Plugin Name: Random Posts and Products
 * Description: نمایش 3 پست رندوم و 3 محصول رندوم داخل مقالات سایت.
 * Version: 0.5
 * Author: hessamzm
 * Author URI: https://githuben.com/hessamzm/Random-Posts-Products
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: random-posts-products
 * Domain Path: /languages
 * 
 */

// افزودن کد برای نمایش پست‌های رندوم
function show_random_posts() {
    $args = array(
        'posts_per_page' => 3,
        'orderby' => 'rand', // نمایش تصادفی
        'post_status' => 'publish'
    );

    $random_posts = new WP_Query($args);

    if ($random_posts->have_posts()) {
        echo '<div class="random-posts">';
        echo '<h3>پست‌های رندوم</h3>';
        while ($random_posts->have_posts()) {
            $random_posts->the_post();
            echo '<div class="random-post">';
            echo '<h4><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h4>';
            echo '</div>';
        }
        echo '</div>';
    }
    wp_reset_postdata();
}

// افزودن کد برای نمایش محصولات رندوم
function show_random_products() {
    $args = array(
        'post_type' => 'product', // محصول از نوع WooCommerce
        'posts_per_page' => 3,
        'orderby' => 'rand', // نمایش تصادفی
        'post_status' => 'publish'
    );

    $random_products = new WP_Query($args);

    if ($random_products->have_posts()) {
        echo '<div class="random-products">';
        echo '<h3>محصولات رندوم</h3>';
        while ($random_products->have_posts()) {
            $random_products->the_post();
            echo '<div class="random-product">';
            echo '<h4><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h4>';
            echo '</div>';
        }
        echo '</div>';
    }
    wp_reset_postdata();
}

// نمایش پست‌ها و محصولات رندوم در مقالات
function insert_random_content_in_posts($content) {
    if (is_single()) {
        ob_start();
        show_random_posts();
        show_random_products();
        $random_content = ob_get_clean();
        $content .= $random_content;
    }
    return $content;
}
add_filter('the_content', 'insert_random_content_in_posts');
