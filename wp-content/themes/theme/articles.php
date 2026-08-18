<?php

/**
 * Template Name: Страница Все статьи
 *
 * @package LOCAL THEME
 * @since 1.0.0
 */
get_header();
?>


<?php include __DIR__ . '/includes/section-hero.php'; ?>
<?php include __DIR__ . '/includes/categories.php'; ?>
<?php include __DIR__ . '/includes/articles-popular.php'; ?>
<?php include __DIR__ . '/includes/articles-all.php'; ?>
<?php include __DIR__ . '/includes/articles-about.php'; ?>

<?php include __DIR__ . '/includes/cta.php'; ?>


<?php
get_footer();
wp_footer();
?>