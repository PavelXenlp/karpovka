<?php

function karpovka_resolve_legacy_article_reference($item)
{
    $article_id = absint($item['article_id'] ?? 0);
    if ($article_id && 'articles' === get_post_type($article_id)) return $article_id;

    $link = trim((string) ($item['link'] ?? ''));
    if ($link) {
        $path = (string) parse_url($link, PHP_URL_PATH);
        $slug = sanitize_title(basename(trim($path ?: $link, '/')));
        if ($slug) {
            $article = get_page_by_path($slug, OBJECT, 'articles');
            if ($article) return (int) $article->ID;
        }
    }

    $title = trim((string) ($item['title'] ?? ''));
    if ($title) {
        $matches = get_posts(array('post_type' => 'articles', 'post_status' => array('publish', 'draft'), 'posts_per_page' => 1, 'title' => $title));
        if ($matches) return (int) $matches[0]->ID;
    }
    return 0;
}

function karpovka_legacy_section_article_items($post_id)
{
    $items = array(array(
        'article_id' => get_post_meta($post_id, 'spa_articles_feat_article_id', true),
        'title'      => get_post_meta($post_id, 'spa_articles_feat_title', true),
        'link'       => get_post_meta($post_id, 'spa_articles_feat_link', true),
    ));
    $rows = get_post_meta($post_id, 'spa_articles_rows', true);
    if (is_array($rows)) $items = array_merge($items, $rows);
    return array_values(array_filter($items, static function ($item) {
        return ! empty($item['article_id']) || ! empty($item['title']) || ! empty($item['link']);
    }));
}

function karpovka_resolve_legacy_article_items($items, &$unresolved = array())
{
    $ids = array();
    foreach ((array) $items as $item) {
        $id = karpovka_resolve_legacy_article_reference($item);
        if ($id) $ids[] = $id;
        else $unresolved[] = (string) ($item['title'] ?? $item['link'] ?? 'Без названия');
    }
    return array_values(array_unique($ids));
}

function karpovka_resolve_legacy_section_article_ids($post_id)
{
    $unresolved = array();
    return karpovka_resolve_legacy_article_items(karpovka_legacy_section_article_items($post_id), $unresolved);
}

function karpovka_resolve_legacy_popular_article_ids($post_id)
{
    $unresolved = array();
    $cards = get_post_meta($post_id, 'ap_cards', true);
    return karpovka_resolve_legacy_article_items(is_array($cards) ? $cards : array(), $unresolved);
}

function karpovka_migrate_page_article_references($post_id)
{
    $marker = '_karpovka_article_references_migrated_20260918';
    $pending_key = '_karpovka_article_references_unresolved_20260918';
    if ('page' !== get_post_type($post_id) || (get_post_meta($post_id, $marker, true) && ! get_post_meta($post_id, $pending_key, true))) return false;

    $unresolved = array();
    $manual = get_post_meta($post_id, '_karpovka_article_references_manual_20260918', true);
    $manual = is_array($manual) ? $manual : array();
    $mappings = array(
        'spa_articles_article_ids' => karpovka_legacy_section_article_items($post_id),
        'ap_article_ids'           => is_array(get_post_meta($post_id, 'ap_cards', true)) ? get_post_meta($post_id, 'ap_cards', true) : array(),
    );

    foreach ($mappings as $meta_key => $legacy_items) {
        if (in_array($meta_key, $manual, true)) continue;
        if (! $legacy_items && ! metadata_exists('post', $post_id, $meta_key)) continue;
        $field_unresolved = array();
        $resolved = karpovka_resolve_legacy_article_items($legacy_items, $field_unresolved);
        $current = get_post_meta($post_id, $meta_key, true);
        $current = is_array($current) ? array_map('absint', $current) : array();
        update_post_meta($post_id, $meta_key, array_values(array_unique(array_merge($current, $resolved))));
        if ($field_unresolved) $unresolved[$meta_key] = $field_unresolved;
    }

    if ($unresolved) {
        update_post_meta($post_id, $pending_key, $unresolved);
        delete_post_meta($post_id, $marker);
    } else {
        delete_post_meta($post_id, $pending_key);
        update_post_meta($post_id, $marker, current_time('mysql'));
    }
    return true;
}

function karpovka_mark_article_references_manual($post_id, $meta_key)
{
    $manual_key = '_karpovka_article_references_manual_20260918';
    $pending_key = '_karpovka_article_references_unresolved_20260918';
    $marker = '_karpovka_article_references_migrated_20260918';
    $manual = get_post_meta($post_id, $manual_key, true);
    $manual = is_array($manual) ? $manual : array();
    $manual[] = $meta_key;
    update_post_meta($post_id, $manual_key, array_values(array_unique($manual)));

    $pending = get_post_meta($post_id, $pending_key, true);
    $pending = is_array($pending) ? $pending : array();
    unset($pending[$meta_key]);
    if ($pending) update_post_meta($post_id, $pending_key, $pending);
    else {
        delete_post_meta($post_id, $pending_key);
        update_post_meta($post_id, $marker, current_time('mysql'));
    }
}

add_action('admin_init', function () {
    $page_ids = get_posts(array('post_type' => 'page', 'post_status' => array('publish', 'draft', 'private'), 'posts_per_page' => -1, 'fields' => 'ids'));
    foreach ($page_ids as $page_id) karpovka_migrate_page_article_references($page_id);
});
