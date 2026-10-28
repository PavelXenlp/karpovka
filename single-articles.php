<?php
/**
 * Single template for the articles post type.
 *
 * @package LOCAL_THEME
 */

get_header();

while (have_posts()) {
    the_post();
    $includes = trailingslashit(get_template_directory()) . 'includes/';
    include $includes . 'section-hero.php';
    include $includes . 'article-meta.php';
    include $includes . 'article-body.php';
    include $includes . 'article-related.php';
    include $includes . 'article-cta.php';
}

get_footer();
