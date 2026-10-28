<?php

add_action('add_meta_boxes', function () {
    add_meta_box('ap_box', 'Популярные статьи', 'ap_box_cb', 'page', 'normal', 'high');
});

function ap_box_cb($post)
{
    wp_nonce_field('ap_nonce', 'ap_nonce_field');
    $ids = get_post_meta($post->ID, 'ap_article_ids', true);
    $ids = is_array($ids) ? $ids : array();
    $pending = get_post_meta($post->ID, '_karpovka_article_references_unresolved_20260918', true);
    ?>
    <p>Выберите статьи для блока «Популярные статьи». Все данные карточек берутся из выбранных записей.</p>
    <?php kp_articles_multiselect($ids, 'ap_article_ids'); ?>
    <?php if (! empty($pending['ap_article_ids'])) : ?>
        <div class="notice notice-warning inline"><p>Не удалось автоматически найти: <?php echo esc_html(implode(', ', $pending['ap_article_ids'])); ?>. Выберите подходящие записи вручную.</p></div>
    <?php endif; ?>
    <?php
}

add_action('save_post_page', function ($post_id) {
    if (! isset($_POST['ap_nonce_field'])) return;
    if (! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ap_nonce_field'])), 'ap_nonce')) return;
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ! current_user_can('edit_page', $post_id)) return;

    $ids = isset($_POST['ap_article_ids']) && is_array($_POST['ap_article_ids'])
        ? array_values(array_unique(array_filter(array_map('absint', wp_unslash($_POST['ap_article_ids'])))))
        : array();
    $ids = array_values(array_filter($ids, static function ($id) { return 'articles' === get_post_type($id); }));
    update_post_meta($post_id, 'ap_article_ids', $ids);
    if (function_exists('karpovka_mark_article_references_manual')) {
        karpovka_mark_article_references_manual($post_id, 'ap_article_ids');
    }
});
