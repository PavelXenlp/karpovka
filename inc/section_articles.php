<?php

add_action('add_meta_boxes', function () {
    add_meta_box('spa_articles_box', 'Секция: Полезные статьи', 'spa_articles_render_box', 'page', 'normal', 'high');
});

function spa_articles_render_box($post)
{
    wp_nonce_field('spa_articles_nonce', 'spa_articles_nonce_field');
    $layout  = get_post_meta($post->ID, 'spa_articles_layout', true) ?: 'feature_rows';
    $eyebrow = get_post_meta($post->ID, 'spa_articles_eyebrow', true);
    $title   = get_post_meta($post->ID, 'spa_articles_title', true);
    $gold    = get_post_meta($post->ID, 'spa_articles_gold', true);
    $sec_id  = get_post_meta($post->ID, 'spa_articles_id', true);
    $ids     = get_post_meta($post->ID, 'spa_articles_article_ids', true);
    $ids     = is_array($ids) ? $ids : array();
    $pending = get_post_meta($post->ID, '_karpovka_article_references_unresolved_20260918', true);
    ?>
    <div class="sa-field">
        <label>Вариант отображения</label>
        <select name="spa_articles_layout">
            <option value="feature_rows" <?php selected($layout, 'feature_rows'); ?>>Крупная статья и список</option>
            <option value="spa_cards" <?php selected($layout, 'spa_cards'); ?>>Карточки</option>
        </select>
    </div>
    <div class="sa-field"><label>Надзаголовок</label><input type="text" name="spa_articles_eyebrow" value="<?php echo esc_attr($eyebrow); ?>"></div>
    <div class="sa-field"><label>Заголовок</label><input type="text" name="spa_articles_title" value="<?php echo esc_attr($title); ?>"></div>
    <div class="sa-field"><label>Выделенная часть заголовка</label><input type="text" name="spa_articles_gold" value="<?php echo esc_attr($gold); ?>"></div>
    <div class="sa-field"><label>ID секции</label><input type="text" name="spa_articles_id" value="<?php echo esc_attr($sec_id); ?>"></div>
    <div class="sa-field">
        <label>Статьи</label>
        <?php kp_articles_multiselect($ids, 'spa_articles_article_ids'); ?>
    </div>
    <?php if (! empty($pending['spa_articles_article_ids'])) : ?>
        <div class="notice notice-warning inline"><p>Не удалось автоматически найти: <?php echo esc_html(implode(', ', $pending['spa_articles_article_ids'])); ?>. Выберите подходящие записи вручную.</p></div>
    <?php endif; ?>
    <style>
        .sa-field{margin-bottom:16px}.sa-field>label{display:block;font-weight:600;margin-bottom:4px}.sa-field input[type="text"],.sa-field select{width:100%;max-width:760px}
    </style>
    <?php
}

add_action('save_post_page', function ($post_id) {
    if (! isset($_POST['spa_articles_nonce_field'])) return;
    if (! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['spa_articles_nonce_field'])), 'spa_articles_nonce')) return;
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ! current_user_can('edit_page', $post_id)) return;

    foreach (array('spa_articles_layout', 'spa_articles_eyebrow', 'spa_articles_title', 'spa_articles_gold', 'spa_articles_id') as $field) {
        if (isset($_POST[$field])) update_post_meta($post_id, $field, sanitize_text_field(wp_unslash($_POST[$field])));
    }
    $ids = isset($_POST['spa_articles_article_ids']) && is_array($_POST['spa_articles_article_ids'])
        ? array_values(array_unique(array_filter(array_map('absint', wp_unslash($_POST['spa_articles_article_ids'])))))
        : array();
    $ids = array_values(array_filter($ids, static function ($id) { return 'articles' === get_post_type($id); }));
    update_post_meta($post_id, 'spa_articles_article_ids', $ids);
    if (function_exists('karpovka_mark_article_references_manual')) {
        karpovka_mark_article_references_manual($post_id, 'spa_articles_article_ids');
    }
});
