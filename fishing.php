<?php

/**
 * Template Name: Страница Рыбалка
 *
 * @package LOCAL THEME
 * @since 1.0.0
 */
get_header();
?>


<?php include __DIR__ . '/includes/section-hero.php'; ?>

<?php include __DIR__ . '/includes/spa-detail.php'; ?>
<?php include __DIR__ . '/includes/events-includes.php'; ?>

<?php include __DIR__ . '/includes/offers-current.php'; ?>


<?php include __DIR__ . '/includes/section-gallery.php'; ?>
<?php include __DIR__ . '/includes/reviews.php'; ?>
<?php include __DIR__ . '/includes/section-articles.php'; ?>
<?php include __DIR__ . '/includes/faq.php'; ?>
<?php include __DIR__ . '/includes/cta.php'; ?>




<?php
get_footer();
wp_footer();
?>