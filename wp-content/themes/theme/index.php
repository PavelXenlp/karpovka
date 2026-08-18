<?php

/**
 * Template Name: Главная страница
 *
 * @package LOCAL THEME
 * @since 1.0.0
 */
get_header();
?>

<?php include __DIR__ . '/includes/hero.php'; ?>
<?php include __DIR__ . '/includes/stats.php'; ?>
<?php include __DIR__ . '/includes/accommodation.php'; ?>
<?php include __DIR__ . '/includes/spa.php'; ?>
<?php include __DIR__ . '/includes/services.php'; ?>
<?php include __DIR__ . '/includes/map.php'; ?>
<?php include __DIR__ . '/includes/advantages.php'; ?>
<?php include __DIR__ . '/includes/promo.php'; ?>
<?php include __DIR__ . '/includes/reviews.php'; ?>
<?php include __DIR__ . '/includes/faq.php'; ?>
<?php include __DIR__ . '/includes/contacts.php'; ?>
<?php include __DIR__ . '/includes/awards.php'; ?>


<?php
get_footer();
wp_footer();
?>