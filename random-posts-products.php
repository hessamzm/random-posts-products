<?php
/**
 * Plugin Name: Random Posts and Products
 * Description: نمایش 3 پست رندوم و 3 محصول رندوم داخل مقالات سایت.
 * Version: 0.6
 * Author: hessamzm
 * Author URI: https://github.com/hessamzm/Random-Posts-Products
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: random-posts-products
 * Domain Path: /languages
 * 
 */

// افزودن کد برای نمایش پست‌های رندوم
require_once plugin_dir_path(__FILE__) . 'includes/display-posts.php';
require_once plugin_dir_path(__FILE__) . 'includes/display-products.php';

function load_random_content_styles() {
    wp_enqueue_style('random-content-styles', plugin_dir_url(__FILE__) . 'css/style.css');
}
add_action('wp_enqueue_scripts', 'load_random_content_styles');


// نمایش پست‌ها و محصولات رندوم در مقالات
function insert_random_content_in_posts($content) {
  if (!is_single()) {
        return $content;
    }

    // قطعه‌بندی محتوا با حفظ خود پاراگراف‌ها
    $segments = preg_split('/(<p[^>]*>.*?<\/p>)/si', $content, -1, PREG_SPLIT_DELIM_CAPTURE);

    // اگر هیچ پاراگرافی پیدا نشد، دست نزن
    if (!$segments || count($segments) === 1) {
        return $content;
    }

    $new_content       = '';
    $p_count           = 0;
    $product_inserted  = false;
    $post_inserted     = false;

    foreach ($segments as $seg) {
        // اگر پاراگراف است
        if (preg_match('/^<p[^>]*>.*<\/p>$/si', $seg)) {
            $p_count++;
            $new_content .= $seg;

            if (!$product_inserted && $p_count === 2) {
                ob_start();
                show_random_products();
                $products_html = ob_get_clean();
                if (!empty($products_html)) {
                    $new_content .= '<div class="random-content">'.$products_html.'</div>';
                    $product_inserted = true;
                }
            }

            if (!$post_inserted && $p_count === 4) {
                ob_start();
                show_random_posts();
                $posts_html = ob_get_clean();
                if (!empty($posts_html)) {
                    $new_content .= '<div class="random-content">'.$posts_html.'</div>';
                    $post_inserted = true;
                }
            }
        } else {
            // بخش‌های غیر پاراگراف (هدر، تصاویر، بلوک‌ها...) را حفظ کن
            $new_content .= $seg;
        }
    }

    // fallback: اگر پاراگراف‌ها کم بودند، بعد از آخرین پاراگراف یا انتهای محتوا درج کن
    if (!$product_inserted) {
        ob_start();
        show_random_products();
        $products_html = ob_get_clean();
        if (!empty($products_html)) {
            $new_content .= '<div class="random-content">'.$products_html.'</div>';
        }
    }
    if (!$post_inserted) {
        ob_start();
        show_random_posts();
        $posts_html = ob_get_clean();
        if (!empty($posts_html)) {
            $new_content .= '<div class="random-content">'.$posts_html.'</div>';
        }
    }

    return $new_content;

}
add_filter('the_content', 'insert_random_content_in_posts');