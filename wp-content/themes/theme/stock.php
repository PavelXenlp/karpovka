<?php

/**
 * Template Name: Страница Акции
 *
 * @package LOCAL THEME
 * @since 1.0.0
 */
get_header();
?>


<?php include __DIR__ . '/includes/section-hero.php'; ?>
<?php include __DIR__ . '/includes/offers-current.php'; ?>
<?php include __DIR__ . '/includes/offers-loyalty.php'; ?>
<?php include __DIR__ . '/includes/offers-spa.php'; ?>
<?php include __DIR__ . '/includes/offers-acc.php'; ?>


<?php include __DIR__ . '/includes/reviews.php'; ?>
<?php include __DIR__ . '/includes/section-articles.php'; ?>
<?php include __DIR__ . '/includes/faq.php'; ?>
<?php include __DIR__ . '/includes/cta.php'; ?>




<?php
get_footer();
wp_footer();
?>
