<?php

/**
 * Template Name: Страница СПА
 *
 * @package LOCAL THEME
 * @since 1.0.0
 */
get_header();
?>


<?php include __DIR__ . '/includes/section-hero.php'; ?>
<?php include __DIR__ . '/includes/stats.php'; ?>
<?php include __DIR__ . '/includes/spa-detail.php'; ?>
<?php include __DIR__ . '/includes/spa-programs.php'; ?>
<?php include __DIR__ . '/includes/spa-massage.php'; ?>
<?php include __DIR__ . '/includes/spa-detail-solt.php'; ?>
<?php include __DIR__ . '/includes/section-price.php'; ?>
<?php include __DIR__ . '/includes/section-gallery.php'; ?>
<?php include __DIR__ . '/includes/reviews.php'; ?>
<?php include __DIR__ . '/includes/section-articles.php'; ?>
<?php include __DIR__ . '/includes/faq.php'; ?>
<?php include __DIR__ . '/includes/cta.php'; ?>


<?php
get_footer();
wp_footer();
?>