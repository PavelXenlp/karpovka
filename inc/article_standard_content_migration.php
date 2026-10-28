<?php

function karpovka_article_legacy_blocks_to_content($blocks)
{
    if (! is_array($blocks)) return '';
    $html = '';
    foreach ($blocks as $block) {
        $type = isset($block['type']) ? $block['type'] : '';
        if ('heading' === $type && ! empty($block['heading_text'])) {
            $id = ! empty($block['heading_id']) ? ' id="' . esc_attr(sanitize_title($block['heading_id'])) . '"' : '';
            $html .= '<h2' . $id . '>' . esc_html($block['heading_text']) . '</h2>';
        } elseif ('text' === $type && ! empty($block['text'])) {
            foreach (preg_split('/\n\s*\n/', $block['text']) as $paragraph) {
                if ('' !== trim($paragraph)) $html .= '<p>' . nl2br(esc_html(trim($paragraph))) . '</p>';
            }
        } elseif ('image' === $type && ! empty($block['image'])) {
            $html .= '<figure><img src="' . esc_url($block['image']) . '" alt="' . esc_attr($block['image_alt'] ?? '') . '"></figure>';
        } elseif ('pullquote' === $type && ! empty($block['quote'])) {
            $html .= '<blockquote><p>' . nl2br(esc_html($block['quote'])) . '</p></blockquote>';
        } elseif ('table' === $type && ! empty($block['table_headers'])) {
            $headers = array_map('trim', explode('|', $block['table_headers']));
            $html .= '<table><thead><tr>';
            foreach ($headers as $header) $html .= '<th>' . esc_html($header) . '</th>';
            $html .= '</tr></thead><tbody>';
            foreach (preg_split('/\r?\n/', $block['table_rows'] ?? '') as $row) {
                if ('' === trim($row)) continue;
                $html .= '<tr>';
                foreach (array_map('trim', explode('|', $row)) as $cell) $html .= '<td>' . esc_html($cell) . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
        }
    }
    return $html;
}

function karpovka_migrate_article_to_standard_fields($post_id)
{
    $marker = '_karpovka_article_standard_fields_20260918';
    if ('articles' !== get_post_type($post_id) || get_post_meta($post_id, $marker, true)) return false;

    $post = get_post($post_id);
    if (! $post) return false;

    $update = array('ID' => $post_id);
    if ('' === trim((string) $post->post_excerpt)) {
        $lead = (string) get_post_meta($post_id, 'ab_lead', true);
        if ('' !== trim($lead)) $update['post_excerpt'] = $lead;
    }
    if ('' === trim((string) $post->post_content)) {
        $content = karpovka_article_legacy_blocks_to_content(get_post_meta($post_id, 'ab_blocks', true));
        if ('' !== $content) $update['post_content'] = $content;
    }
    if (count($update) > 1) {
        $result = wp_update_post(wp_slash($update), true);
        if (is_wp_error($result)) return false;
    }

    if (! wp_get_object_terms($post_id, 'category', array('fields' => 'ids'))) {
        $category = trim((string) get_post_meta($post_id, 'am_category', true));
        if ($category) wp_set_object_terms($post_id, array($category), 'category', false);
    }
    if (! wp_get_object_terms($post_id, 'post_tag', array('fields' => 'ids'))) {
        $tags = get_post_meta($post_id, 'ab_tags', true);
        if (is_array($tags) && $tags) wp_set_object_terms($post_id, array_values(array_filter(array_map('sanitize_text_field', $tags))), 'post_tag', false);
    }
    if (! metadata_exists('post', $post_id, 'article_related_ids')) {
        $cards = get_post_meta($post_id, 'ap_cards', true);
        $ids = array();
        if (is_array($cards)) {
            foreach ($cards as $card) {
                $related_id = isset($card['article_id']) ? absint($card['article_id']) : 0;
                if ($related_id && $related_id !== (int) $post_id && 'articles' === get_post_type($related_id)) $ids[] = $related_id;
            }
        }
        update_post_meta($post_id, 'article_related_ids', array_values(array_unique($ids)));
    }

    update_post_meta($post_id, $marker, current_time('mysql'));
    return true;
}

add_action('admin_init', function () {
    $article_ids = get_posts(array(
        'post_type'      => 'articles',
        'post_status'    => array('publish', 'draft', 'pending', 'private'),
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ));
    foreach ($article_ids as $article_id) karpovka_migrate_article_to_standard_fields($article_id);
});
