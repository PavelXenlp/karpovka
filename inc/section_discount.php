<?php
/**
 * Метабокс «Акция / Промо-страница»
 * Подключить в functions.php: require_once get_template_directory() . '/inc/promo_page.php';
 */

add_action('add_meta_boxes', function () {
    add_meta_box('promo_page_box', 'Акция — контент страницы', 'promo_page_box_cb', 'page', 'normal', 'high');
});

function promo_page_box_cb($post) {
    wp_nonce_field('promo_page_nonce', 'promo_page_nonce_field');

    $over    = get_post_meta($post->ID, 'promo_over', true);
    $title   = get_post_meta($post->ID, 'promo_title', true);
    $gold    = get_post_meta($post->ID, 'promo_gold', true);
    $sub     = get_post_meta($post->ID, 'promo_sub', true);
    $intro   = get_post_meta($post->ID, 'promo_intro', true);
    $image   = get_post_meta($post->ID, 'promo_image', true);
    $badge   = get_post_meta($post->ID, 'promo_badge', true);
    $desc    = get_post_meta($post->ID, 'promo_desc', true);
    $advs    = get_post_meta($post->ID, 'promo_advantages', true);
    if (!is_array($advs)) $advs = array();
    $feats   = get_post_meta($post->ID, 'promo_features', true);
    if (!is_array($feats)) $feats = array();
    ?>

    <style>
        .promo-field { margin-bottom: 18px; }
        .promo-field label { display: block; font-weight: 600; margin-bottom: 5px; font-size: 13px; }
        .promo-field input[type="text"],
        .promo-field textarea { width: 100%; padding: 8px 10px; box-sizing: border-box; }
        .promo-field textarea { min-height: 100px; }
        .promo-group-title { font-size: 15px; font-weight: 700; margin: 24px 0 10px; padding-bottom: 6px; border-bottom: 1px solid #ddd; }
        .promo-repeatable { margin-bottom: 8px; display: flex; gap: 8px; align-items: center; }
        .promo-repeatable input { flex: 1; padding: 8px 10px; }
        .promo-repeatable textarea { flex: 1; padding: 8px 10px; min-height: 60px; }
        .promo-remove { color: #a00; cursor: pointer; font-size: 18px; font-weight: 700; flex-shrink: 0; }
        .promo-remove:hover { color: #f00; }
        .promo-add { margin-top: 6px; cursor: pointer; }
    </style>

    <div class="promo-group-title">Заголовки</div>

    <div class="promo-field">
        <label>Надзаголовок</label>
        <input type="text" name="promo_over" value="<?php echo esc_attr($over); ?>">
    </div>
    <div class="promo-field">
        <label>Заголовок</label>
        <input type="text" name="promo_title" value="<?php echo esc_attr($title); ?>">
    </div>
    <div class="promo-field">
        <label>Золотой текст заголовка (выделяется курсивом)</label>
        <input type="text" name="promo_gold" value="<?php echo esc_attr($gold); ?>">
    </div>
    <div class="promo-field">
        <label>Подзаголовок</label>
        <input type="text" name="promo_sub" value="<?php echo esc_attr($sub); ?>">
    </div>
    <div class="promo-field">
        <label>Краткий вводный текст</label>
        <?php wp_editor($intro, 'promo_intro', array(
            'textarea_name' => 'promo_intro',
            'textarea_rows' => 3,
            'media_buttons' => false,
            'teeny'         => true,
            'quicktags'     => false,
        )); ?>
    </div>

    <div class="promo-group-title">Основное изображение</div>

    <div class="promo-field">
        <label>Бейдж (например: СКИДКА 20%)</label>
        <input type="text" name="promo_badge" value="<?php echo esc_attr($badge); ?>" placeholder="Оставьте пустым, если не нужен">
    </div>
    <div class="promo-field">
        <label>Изображение (загрузить файлом)</label>
        <input type="hidden" name="promo_image" value="<?php echo esc_attr($image); ?>" data-kp-media>
    </div>

    <div class="promo-group-title">Описание акции</div>

        <div class="promo-field">
        <label>Основное описание</label>
        <textarea name="promo_desc" rows="8"><?php echo esc_textarea($desc); ?></textarea>
        <p style="margin:4px 0 0;color:#888;font-size:12px;">Двойной Enter = новый абзац. Жирный: *текст*. Ссылки: [текст](url)</p>
    </div>

    <div class="promo-group-title">Преимущества (галочки, как на других страницах)</div>
    <div id="promo-advs-wrap">
        <?php foreach ($advs as $i => $adv) : ?>
        <div class="promo-repeatable">
            <input type="text" name="promo_advantages[]" value="<?php echo esc_attr($adv); ?>">
            <span class="promo-remove" title="Удалить">&times;</span>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button promo-add" data-target="promo-advs-wrap">+ Добавить преимущество</button>

    <div class="promo-group-title">Особенности (блоки с золотым фоном)</div>
    <div id="promo-feats-wrap">
        <?php foreach ($feats as $i => $feat) : ?>
        <div class="promo-repeatable">
            <textarea name="promo_features[]"><?php echo esc_textarea($feat); ?></textarea>
            <span class="promo-remove" title="Удалить">&times;</span>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button promo-add" data-target="promo-feats-wrap">+ Добавить особенность</button>

        <script>
    (function($) {
        $('.promo-add').on('click', function() {
            var target = $(this).data('target');
            var html;
            if (target === 'promo-feats-wrap') {
                html = '<div class="promo-repeatable"><textarea name="promo_features[]"></textarea><span class="promo-remove" title="Удалить">&times;</span></div>';
            } else {
                html = '<div class="promo-repeatable"><input type="text" name="promo_advantages[]"><span class="promo-remove" title="Удалить">&times;</span></div>';
            }
            $('#' + target).append(html);
        });
        $(document).on('click', '.promo-remove', function() {
            $(this).closest('.promo-repeatable').remove();
        });
    })(jQuery);
    </script>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['promo_page_nonce_field'])) return;
    if (!wp_verify_nonce($_POST['promo_page_nonce_field'], 'promo_page_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    /* Простые текстовые поля */
    $text_fields = array('promo_over', 'promo_title', 'promo_gold', 'promo_sub', 'promo_badge');
    foreach ($text_fields as $f) {
        if (isset($_POST[$f])) {
            update_post_meta($post_id, $f, sanitize_text_field($_POST[$f]));
        }
    }

    /* Вводный текст — HTML (wp_editor) */
    if (isset($_POST['promo_intro'])) {
        update_post_meta($post_id, 'promo_intro', wp_kses_post($_POST['promo_intro']));
    }

    /* Описание — простой текст */
    if (isset($_POST['promo_desc'])) {
        update_post_meta($post_id, 'promo_desc', sanitize_textarea_field($_POST['promo_desc']));
    }

    /* Изображение */
    if (isset($_POST['promo_image'])) {
        update_post_meta($post_id, 'promo_image', esc_url_raw($_POST['promo_image']));
    }

    /* Преимущества — массив простого текста */
    if (isset($_POST['promo_advantages']) && is_array($_POST['promo_advantages'])) {
        $clean = array();
        foreach ($_POST['promo_advantages'] as $v) {
            $v = sanitize_text_field($v);
            if ($v !== '') $clean[] = $v;
        }
        update_post_meta($post_id, 'promo_advantages', $clean);
    } else {
        delete_post_meta($post_id, 'promo_advantages');
    }

    /* Особенности — массив простого текста (НЕ wp_kses_post) */
    if (isset($_POST['promo_features']) && is_array($_POST['promo_features'])) {
        $clean = array();
        foreach ($_POST['promo_features'] as $v) {
            $v = sanitize_textarea_field($v);
            if ($v !== '') $clean[] = $v;
        }
        update_post_meta($post_id, 'promo_features', $clean);
    } else {
        delete_post_meta($post_id, 'promo_features');
    }
});
