<?php

/**
 * Template Name: Страница Отзывы
 *
 * @package LOCAL THEME
 * @since 1.0.0
 */
get_header();
?>


<?php include __DIR__ . '/includes/section-hero.php'; ?>
<?php include __DIR__ . '/includes/rating.php'; ?>
<?php include __DIR__ . '/includes/reviews-list.php'; ?>
<?php include __DIR__ . '/includes/platforms.php'; ?>
<?php include __DIR__ . '/includes/cta-review.php'; ?>


<?php
get_footer();
wp_footer();
?>