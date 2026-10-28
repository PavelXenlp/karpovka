<?php

$posts = array();
$meta = array(99 => array());
$terms = array();
$insert_count = 0;

function add_action() {}
function wp_slash($value) { return $value; }
function is_wp_error($value) { return false; }
function has_post_thumbnail($post_id) { return true; }
function get_post_thumbnail_id($post_id) { return 700 + (int) $post_id; }
function wp_get_attachment_url($attachment_id) { return '/uploads/' . $attachment_id . '.jpg'; }
function update_option() {}
function wp_insert_post($data, $wp_error = false) {
    global $posts, $insert_count;
    $id = 101 + $insert_count++;
    $posts[$id] = $data;
    return $id;
}
function get_posts($args) {
    global $posts, $meta;
    if ('page' === $args['post_type']) return array(99);
    if ('articles' !== $args['post_type']) return array();
    foreach ($posts as $id => $post) {
        if (($meta[$id][$args['meta_key']] ?? '') === $args['meta_value']) return array($id);
    }
    return array();
}
function update_post_meta($post_id, $key, $value) {
    global $meta;
    $meta[$post_id][$key] = $value;
}
function get_post_meta($post_id, $key, $single = true) {
    global $meta;
    return $meta[$post_id][$key] ?? '';
}
function wp_set_object_terms($post_id, $values, $taxonomy, $append = false) {
    global $terms;
    $terms[$post_id][$taxonomy] = $values;
}

require __DIR__ . '/../inc/articles_seed_migration.php';

if (! karpovka_run_articles_seed_migration()) throw new RuntimeException('Seed migration failed.');
if ($insert_count !== 2) throw new RuntimeException('Seed migration did not create two articles.');
if ($meta[101]['article_related_ids'] !== array(102) || $meta[102]['article_related_ids'] !== array(101)) {
    throw new RuntimeException('Related article references were not created.');
}
if ($meta[99]['ap_article_ids'] !== array(101, 102)) throw new RuntimeException('Archive popular articles were not populated.');
if (! karpovka_run_articles_seed_migration() || $insert_count !== 2) throw new RuntimeException('Seed migration is not idempotent.');

echo "Articles seed migration: OK\n";
