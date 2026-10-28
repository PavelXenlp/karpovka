<?php

define('OBJECT', 'OBJECT');
$meta = array(
    'spa_articles_feat_article_id' => 11,
    'spa_articles_feat_title' => 'Первая',
    'spa_articles_rows' => array(array('article_id' => 12, 'title' => 'Вторая')),
    'ap_cards' => array(array('link' => '/article/third/', 'title' => 'Третья')),
);

function add_action() {}
function absint($value) { return abs((int) $value); }
function sanitize_title($value) { return strtolower(trim($value)); }
function get_post_type($id) { return 55 === (int) $id ? 'page' : (in_array((int) $id, array(11, 12, 13), true) ? 'articles' : ''); }
function get_post_meta($id, $key, $single) { global $meta; return $meta[$key] ?? ''; }
function metadata_exists($type, $id, $key) { global $meta; return array_key_exists($key, $meta); }
function update_post_meta($id, $key, $value) { global $meta; $meta[$key] = $value; }
function delete_post_meta($id, $key) { global $meta; unset($meta[$key]); }
function current_time($type) { return '2026-09-18 14:00:00'; }
function get_page_by_path($slug, $output, $post_type) { return 'third' === $slug ? (object) array('ID' => 13) : null; }
function get_posts($args) { return array(); }

require __DIR__ . '/../inc/article_references_migration.php';

if (! karpovka_migrate_page_article_references(55)) throw new RuntimeException('Reference migration did not run.');
if ($meta['spa_articles_article_ids'] !== array(11, 12) || $meta['ap_article_ids'] !== array(13)) throw new RuntimeException('Legacy references were not migrated.');
if (! isset($meta['spa_articles_rows'], $meta['ap_cards'])) throw new RuntimeException('Legacy data was removed.');
if (karpovka_migrate_page_article_references(55)) throw new RuntimeException('Reference migration is not idempotent.');

$meta['_karpovka_article_references_unresolved_20260918'] = array(
    'spa_articles_article_ids' => array('Не найдена'),
    'ap_article_ids' => array('Не найдена'),
);
karpovka_mark_article_references_manual(55, 'spa_articles_article_ids');
if ($meta['_karpovka_article_references_unresolved_20260918'] !== array('ap_article_ids' => array('Не найдена'))) {
    throw new RuntimeException('Manual selection did not clear its unresolved migration warning.');
}
karpovka_mark_article_references_manual(55, 'ap_article_ids');
if (isset($meta['_karpovka_article_references_unresolved_20260918'])) throw new RuntimeException('Resolved migration warning was not removed.');
if ($meta['_karpovka_article_references_manual_20260918'] !== array('spa_articles_article_ids', 'ap_article_ids')) {
    throw new RuntimeException('Manual article references were not recorded.');
}

echo "Article references migration: OK\n";
