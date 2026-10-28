<?php

add_action('add_meta_boxes_articles', function () {
    add_meta_box('karpovka_article_related', 'Похожие статьи', 'karpovka_article_related_box', 'articles', 'normal', 'default');
});

function karpovka_article_related_box($post)
{
    wp_nonce_field('karpovka_article_related', 'karpovka_article_related_nonce');
    $selected = get_post_meta($post->ID, 'article_related_ids', true);
    $selected = is_array($selected) ? array_map('absint', $selected) : array();
    $articles = get_posts(array(
        'post_type'      => 'articles',
        'posts_per_page' => -1,
        'post_status'    => array('publish', 'draft'),
        'post__not_in'   => array($post->ID),
        'orderby'        => 'date',
        'order'          => 'DESC',
    ));
    $ordered = array();
    foreach ($selected as $selected_id) {
        foreach ($articles as $article) {
            if ((int) $article->ID === $selected_id) $ordered[$selected_id] = $article;
        }
    }
    foreach ($articles as $article) {
        if (! isset($ordered[$article->ID])) $ordered[$article->ID] = $article;
    }
    $selected_count = count(array_intersect($selected, array_map('intval', array_keys($ordered))));
    ?>
    <style>
        #karpovka_article_related .kp-related-picker{max-width:760px}
        #karpovka_article_related .kp-related-picker__toolbar{display:flex;align-items:center;gap:12px;margin:12px 0}
        #karpovka_article_related .kp-related-picker__search{width:100%;max-width:520px;min-height:38px}
        #karpovka_article_related .kp-related-picker__count{margin:0;white-space:nowrap;color:#50575e}
        #karpovka_article_related .kp-related-picker__list{max-height:360px;overflow:auto;border:1px solid #c3c4c7;background:#fff}
        #karpovka_article_related .kp-related-picker__item{display:flex;align-items:center;gap:10px;margin:0;padding:10px 12px;border-bottom:1px solid #f0f0f1;cursor:pointer}
        #karpovka_article_related .kp-related-picker__item:last-child{border-bottom:0}
        #karpovka_article_related .kp-related-picker__item:hover{background:#f6f7f7}
        #karpovka_article_related .kp-related-picker__item:has(input:checked){background:#f0f6fc}
        #karpovka_article_related .kp-related-picker__item input{margin:0;flex:0 0 auto}
        #karpovka_article_related .kp-related-picker__title{font-weight:500;flex:1}
        #karpovka_article_related .kp-related-picker__status{color:#646970;font-size:12px}
        #karpovka_article_related .kp-related-picker__empty{padding:18px;text-align:center;color:#646970}
        @media (max-width:782px){#karpovka_article_related .kp-related-picker__toolbar{align-items:stretch;flex-direction:column}.kp-related-picker__count{white-space:normal}}
    </style>
    <div class="kp-related-picker" data-kp-related-picker>
        <p>Отметьте статьи для блока «Похожие статьи». Рекомендуется не более трёх.</p>
        <div class="kp-related-picker__toolbar">
            <label class="screen-reader-text" for="kp-related-search-<?php echo esc_attr($post->ID); ?>">Поиск статей</label>
            <input class="kp-related-picker__search" id="kp-related-search-<?php echo esc_attr($post->ID); ?>" type="search" placeholder="Поиск по названию статьи" autocomplete="off" data-kp-related-search>
            <p class="kp-related-picker__count"><strong data-kp-related-count><?php echo esc_html($selected_count); ?></strong> выбрано</p>
        </div>
        <div class="kp-related-picker__list" role="group" aria-label="Похожие статьи" data-kp-related-list>
            <?php foreach ($ordered as $article) : ?>
                <label class="kp-related-picker__item" data-kp-related-item>
                    <input type="checkbox" name="article_related_ids[]" value="<?php echo esc_attr($article->ID); ?>" <?php checked(in_array((int) $article->ID, $selected, true)); ?>>
                    <span class="kp-related-picker__title"><?php echo esc_html($article->post_title); ?></span>
                    <?php if ('draft' === $article->post_status) : ?><span class="kp-related-picker__status">Черновик</span><?php endif; ?>
                </label>
            <?php endforeach; ?>
            <div class="kp-related-picker__empty" data-kp-related-empty<?php echo $ordered ? ' hidden' : ''; ?>>Статьи не найдены</div>
        </div>
    </div>
    <script>
    (function () {
        var picker = document.querySelector('[data-kp-related-picker]');
        if (!picker) return;
        var search = picker.querySelector('[data-kp-related-search]');
        var items = Array.prototype.slice.call(picker.querySelectorAll('[data-kp-related-item]'));
        var count = picker.querySelector('[data-kp-related-count]');
        var empty = picker.querySelector('[data-kp-related-empty]');

        function updateCount() {
            count.textContent = picker.querySelectorAll('input[name="article_related_ids[]"]:checked').length;
        }
        search.addEventListener('input', function () {
            var query = search.value.trim().toLocaleLowerCase();
            var visible = 0;
            items.forEach(function (item) {
                var match = !query || item.textContent.toLocaleLowerCase().indexOf(query) !== -1;
                item.hidden = !match;
                if (match) visible++;
            });
            empty.hidden = visible !== 0;
        });
        picker.addEventListener('change', updateCount);
    }());
    </script>
    <?php
}

add_action('save_post_articles', function ($post_id) {
    if (! isset($_POST['karpovka_article_related_nonce'])) {
        return;
    }
    if (! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['karpovka_article_related_nonce'])), 'karpovka_article_related')) {
        return;
    }
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ! current_user_can('edit_post', $post_id)) {
        return;
    }

    $ids = isset($_POST['article_related_ids']) && is_array($_POST['article_related_ids'])
        ? array_values(array_unique(array_filter(array_map('absint', wp_unslash($_POST['article_related_ids'])))))
        : array();
    $ids = array_values(array_filter($ids, function ($id) use ($post_id) {
        return $id !== (int) $post_id && 'articles' === get_post_type($id);
    }));
    update_post_meta($post_id, 'article_related_ids', $ids);
});
