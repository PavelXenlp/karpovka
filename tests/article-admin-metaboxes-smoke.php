<?php

$actions = array();
$meta_boxes = array();

function add_action($hook, $callback) {
    global $actions;
    $actions[$hook][] = $callback;
}
function add_meta_box($id, $title, $callback, $screen) {
    global $meta_boxes;
    $meta_boxes[$id] = $screen;
}

require __DIR__ . '/../inc/articles_seo.php';

foreach ($actions['add_meta_boxes'] as $callback) $callback();
if (($meta_boxes['articles_seo_section'] ?? null) !== 'page') {
    throw new RuntimeException('Archive SEO metabox is still registered for article records.');
}

$hero = file_get_contents(__DIR__ . '/../inc/section_hero.php');
$controls = file_get_contents(__DIR__ . '/../assets/admin/content-controls.js');
if (false === strpos($hero, 'data-kp-content-controls') || false === strpos($hero, "current_user_can('edit_post', \$post_id)")) {
    throw new RuntimeException('Article hero is not configured as a standalone editable media section.');
}
if (false === strpos($controls, '[data-kp-content-controls]')) {
    throw new RuntimeException('Standalone article media controls are not initialized.');
}

echo "Article admin metaboxes: OK\n";
