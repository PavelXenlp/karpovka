<?php

/**
 * Template Name: Страница Мероприятия
 *
 * @package LOCAL THEME
 * @since 1.0.0
 */
get_header();
?>


<?php include __DIR__ . '/includes/section-hero.php'; ?>
<?php include __DIR__ . '/includes/events-types.php'; ?>
<?php include __DIR__ . '/includes/events-corporate.php'; ?>
<?php include __DIR__ . '/includes/events-weekend.php'; ?>
<?php include __DIR__ . '/includes/events-includes.php'; ?>



<?php include __DIR__ . '/includes/section-gallery.php'; ?>
<?php include __DIR__ . '/includes/reviews.php'; ?>
<?php include __DIR__ . '/includes/section-articles.php'; ?>
<?php include __DIR__ . '/includes/faq.php'; ?>
<?php include __DIR__ . '/includes/cta.php'; ?>


<?php
get_footer();
wp_footer();
?>