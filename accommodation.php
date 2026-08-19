<?php

/**
 * Template Name: Страница Проживание
 *
 * @package LOCAL THEME
 * @since 1.0.0
 */
get_header();
?>


<?php include __DIR__ . '/includes/section-hero.php'; ?>
<?php include __DIR__ . '/includes/acc-objects.php'; ?>
<?php include __DIR__ . '/includes/acc-compare.php'; ?>

<?php include __DIR__ . '/includes/offers-loyalty.php'; ?>

<?php include __DIR__ . '/includes/acc-extras.php'; ?>

<?php include __DIR__ . '/includes/reviews.php'; ?>
<?php include __DIR__ . '/includes/section-articles.php'; ?>
<?php include __DIR__ . '/includes/faq.php'; ?>
<?php include __DIR__ . '/includes/acc-booking.php'; ?>


<?php
get_footer();
wp_footer();
?>