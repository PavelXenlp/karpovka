<?php

/**
 * Template Name: Страница Статья
 *
 * @package LOCAL THEME
 * @since 1.0.0
 */
get_header();
?>


<?php include __DIR__ . '/includes/section-hero.php'; ?>
<?php include __DIR__ . '/includes/article-meta.php'; ?>
<?php include __DIR__ . '/includes/article-body.php'; ?>
<?php include __DIR__ . '/includes/article-related.php'; ?>

<?php include __DIR__ . '/includes/cta.php'; ?>




<?php
get_footer();
wp_footer();
?>