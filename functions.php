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

    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css', [], '6.5.2');
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_styles');

add_action( 'wp_enqueue_scripts', function () {
    $is_house      = is_singular( 'karpovka_house' );
    $is_spa        = is_page_template( 'spa-page.php' );
    $is_bath       = is_page_template( 'bath-page.php' );
    $is_dining     = is_page_template( 'dining.php' );
    $is_fishing    = is_page_template( 'fishing.php' );

    // ДОБАВЛЯЕМ ЭТИ ДВЕ СТРОКИ:
    $is_recreation = is_page_template( 'recreation.php' );
    $is_events     = is_page_template( 'events.php' );

    // ИСПРАВЛЯЕМ УСЛОВИЕ ВНИЗУ (добавляем новые переменные):
    if ( ! $is_house && ! $is_spa && ! $is_bath && ! $is_dining && ! $is_fishing && ! $is_recreation && ! $is_events ) return;

    wp_enqueue_style(
        'fancybox',
        'https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1.14/dist/fancybox/fancybox.css',
        [],
        '6.1.14'
    );
    if ( $is_house ) {
        wp_enqueue_style(
            'karpovka-house-single',
            get_template_directory_uri() . '/assets/css/house-single.css',
            [ 'custom-style', 'fancybox' ],
            '1.0.0'
        );
    }
    wp_enqueue_script(
        'fancybox',
        'https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1.14/dist/fancybox/fancybox.umd.js',
        [],
        '6.1.14',
        false
    );
} );

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

require __DIR__ . '/inc/houses_post_type.php';
require __DIR__ . '/inc/acc_objects.php';
require __DIR__ . '/inc/acc_compare.php';
require __DIR__ . '/inc/acc_booking.php';

require __DIR__ . '/inc/section_stats.php';
require __DIR__ . '/inc/spa_programs.php';
require __DIR__ . '/inc/spa_page_migration.php';
require __DIR__ . '/inc/bath_page_migration.php';
require __DIR__ . '/inc/dining_page_migration.php';
require __DIR__ . '/inc/recreation_page_migration.php';
require __DIR__ . '/inc/events_page_migration.php';
require __DIR__ . '/inc/contacts_page_migration.php';
require __DIR__ . '/inc/reviews_page_migration.php';
require __DIR__ . '/inc/stock_page_migration.php';
require __DIR__ . '/inc/home_hero_booking_migration.php';

require __DIR__ . '/inc/recreation_seasons.php';
require __DIR__ . '/inc/recreation_spa.php';

require __DIR__ . '/inc/offers_current.php';
require __DIR__ . '/inc/offers_acc.php';

// Дополнительные страницы
require __DIR__ . '/inc/inf_house.php';
require __DIR__ . '/inc/wedding_page.php';
require __DIR__ . '/inc/section_discount.php';




require __DIR__ . '/inc/articles_popular.php';
require __DIR__ . '/inc/article_related.php';
require __DIR__ . '/inc/article_standard_content_migration.php';
require __DIR__ . '/inc/article_references_migration.php';

require __DIR__ . '/inc/home_static_sections.php';
require __DIR__ . '/inc/admin_page_builder.php';

require __DIR__ . '/inc/certificates_section.php';
require __DIR__ . '/inc/layalty_section.php';

require __DIR__ . '/inc/section-faq.php';
require __DIR__ . '/inc/faq_help_section.php';
require __DIR__ . '/inc/faq_form_section.php';

require __DIR__ . '/inc/loyalty_one.php';
require __DIR__ . '/inc/loyalty_two.php';
require __DIR__ . '/inc/loyalty_three.php';

require __DIR__ . '/inc/gift_one.php';
require __DIR__ . '/inc/gift_two.php';

require __DIR__ . '/inc/dining_formats.php';

require __DIR__ . '/inc/articles_post_type.php';
require __DIR__ . '/inc/articles_seed_migration.php';
