<?php

/**
 * local functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package local
 * @since local
 */

add_theme_support('post-thumbnails');
add_theme_support('title-tag');

function mytheme_enqueue_styles()
{
    // Стили
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_style('swiper-style', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], time());
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/assets/css/style.css', [], time());
    wp_enqueue_style('amedia-custom-style', get_template_directory_uri() . '/assets/css/amedia.css', [], time());
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_styles');

require __DIR__ . '/inc/transliterate_slug.php';
require __DIR__ . '/inc/spa_detail.php';
require __DIR__ . '/inc/spa_massage.php';
require __DIR__ . '/inc/spa_detail_solt.php';
require __DIR__ . '/inc/section_hero.php';
require __DIR__ . '/inc/section_price.php';
require __DIR__ . '/inc/section-gallery.php';
require __DIR__ . '/inc/reviews_section.php';
require __DIR__ . '/inc/section_articles.php';
require __DIR__ . '/inc/faq_section.php';
require __DIR__ . '/inc/cta_sction.php';
require __DIR__ . '/inc/bath_types.php';
require __DIR__ . '/inc/bath_detail.php';
require __DIR__ . '/inc/dining_menu.php';
require __DIR__ . '/inc/dining_booking.php';
require __DIR__ . '/inc/events_weekend.php';
require __DIR__ . '/inc/events_includes.php';
require __DIR__ . '/inc/contacts_section.php';
require __DIR__ . '/inc/section_directions.php';
require __DIR__ . '/inc/section_requisites.php';
require __DIR__ . '/inc/section_feedback.php';
require __DIR__ . '/inc/reviews_list.php';
require __DIR__ . '/inc/section_platforms.php';
require __DIR__ . '/inc/cta_review.php';
require __DIR__ . '/inc/articles_seo.php';
require __DIR__ . '/inc/offers_loyalty.php';

require __DIR__ . '/inc/hero_main.php';
require __DIR__ . '/inc/section_accommodation.php';
require __DIR__ . '/inc/section_spa.php';
require __DIR__ . '/inc/section_advantages.php';
require __DIR__ . '/inc/section_promo.php';
require __DIR__ . '/inc/section_awards.php';

require __DIR__ . '/inc/acc_objects.php';
require __DIR__ . '/inc/acc_compare.php';
require __DIR__ . '/inc/acc_booking.php';

require __DIR__ . '/inc/section_stats.php';
require __DIR__ . '/inc/spa_programs.php';

require __DIR__ . '/inc/recreation_seasons.php';
require __DIR__ . '/inc/recreation_spa.php';

require __DIR__ . '/inc/offers_current.php';
require __DIR__ . '/inc/offers_acc.php';

// Дополнительные страницы
require __DIR__ . '/inc/inf_house.php';
require __DIR__ . '/inc/wedding_page.php';
require __DIR__ . '/inc/section_discount.php';