<?php

$post = (object) array('ID' => 7, 'post_type' => 'articles', 'post_content' => '', 'post_excerpt' => '');
$meta = array(
    'ab_lead' => 'Вводный текст',
    'ab_blocks' => array(
        array('type' => 'heading', 'heading_text' => 'Раздел', 'heading_id' => 'section'),
        array('type' => 'text', 'text' => "Первый абзац\n\nВторой абзац"),
    ),
    'am_category' => 'SPA',
    'ab_tags' => array('Отдых', 'SPA'),
    'ap_cards' => array(array('article_id' => 8)),
);
$terms = array();
$updates = 0;

function add_action() {}
function get_post_type($id) { return in_array((int) $id, array(7, 8), true) ? 'articles' : ''; }
function get_post($id) { global $post; return 7 === (int) $id ? $post : null; }
function get_post_meta($id, $key, $single) { global $meta; return $meta[$key] ?? ''; }
function update_post_meta($id, $key, $value) { global $meta; $meta[$key] = $value; }
function metadata_exists($type, $id, $key) { global $meta; return array_key_exists($key, $meta); }
function wp_update_post($data, $return_error) { global $post, $updates; foreach ($data as $key => $value) if ('ID' !== $key) $post->$key = $value; $updates++; return $post->ID; }
function wp_slash($value) { return $value; }
function is_wp_error($value) { return false; }
function wp_get_object_terms($id, $taxonomy, $args) { return array(); }
function wp_set_object_terms($id, $values, $taxonomy, $append) { global $terms; $terms[$taxonomy] = $values; }
function sanitize_title($value) { return strtolower($value); }
function sanitize_text_field($value) { return trim($value); }
function esc_attr($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
function esc_html($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
function esc_url($value) { return $value; }
function absint($value) { return abs((int) $value); }
function current_time($type) { return '2026-09-18 12:00:00'; }
function get_posts($args) { return array(); }

require __DIR__ . '/../inc/article_standard_content_migration.php';

if (! karpovka_migrate_article_to_standard_fields(7)) throw new RuntimeException('Migration did not run.');
if ('Вводный текст' !== $post->post_excerpt || false === strpos($post->post_content, '<h2 id="section">Раздел</h2>')) throw new RuntimeException('Content was not migrated.');
if ($terms['category'] !== array('SPA') || $terms['post_tag'] !== array('Отдых', 'SPA')) throw new RuntimeException('Terms were not migrated.');
if ($meta['article_related_ids'] !== array(8)) throw new RuntimeException('Related articles were not migrated.');
if (karpovka_migrate_article_to_standard_fields(7) || 1 !== $updates) throw new RuntimeException('Migration is not idempotent.');

echo "Article standard migration: OK\n";
