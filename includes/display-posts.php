<?php
// نمایش 3 پست رندوم
function show_random_posts() {
    $args = array(
        'posts_per_page' => 3,
        'orderby' => 'rand', // نمایش تصادفی
        'post_status' => 'publish'
    );

    $random_posts = new WP_Query($args);

    if ($random_posts->have_posts()) {
        echo '<div class="random-posts-container">';
        while ($random_posts->have_posts()) {
            $random_posts->the_post();
            echo '<div class="random-post">';
            echo '<div class="post-thumbnail">' . get_the_post_thumbnail(get_the_ID(), 'thumbnail') . '</div>';
			echo '<h4><a href="' . get_the_permalink() . '">' . wp_trim_words(get_the_title(), 6, '...') . '</a></h4>';
            echo '</div>';
        }
        echo '</div>';
    }
    wp_reset_postdata();
}
?>
