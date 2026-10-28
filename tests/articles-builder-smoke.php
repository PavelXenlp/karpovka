<?php

define('ABSPATH', __DIR__);
$registered_actions = array();

function add_action($hook, $callback, $priority = 10)
{
    global $registered_actions;
    $registered_actions[$hook] = $callback;
}

require __DIR__ . '/../inc/admin_page_builder.php';

$post = (object) array(
    'ID'        => 101,
    'post_type' => 'articles',
);

$registry = karpovka_get_builder_registry($post);
if ('' !== karpovka_get_builder_template($post) || null !== $registry) {
    throw new RuntimeException('Articles still depend on the page builder.');
}
if (isset($registered_actions['add_meta_boxes_articles'], $registered_actions['save_post_articles'])) {
    throw new RuntimeException('Page-builder hooks are still registered for articles.');
}
$template = file_get_contents(__DIR__ . '/../single-articles.php');
foreach (array('section-hero.php', 'article-meta.php', 'article-body.php', 'article-related.php', 'article-cta.php') as $include) {
    if (false === strpos($template, $include)) throw new RuntimeException('Single article template is incomplete.');
}

echo "Articles standard template: OK\n";
