<?php
add_action('add_meta_boxes', function() {
    add_meta_box(
        'wedding_gallery_box',
        'Секция: Галерея с описанием',
        'wedding_gallery_render_box',
        'page',
        'normal',
        'high'
    );
});

function wedding_gallery_render_box($post) {

    wp_nonce_field('wedding_gallery_nonce', 'wedding_gallery_nonce_field');

    $over      = get_post_meta($post->ID, 'wg_over', true);
    $title     = get_post_meta($post->ID, 'wg_title', true);
    $gold      = get_post_meta($post->ID, 'wg_gold', true);
    $sub       = get_post_meta($post->ID, 'wg_sub', true);
    $images    = get_post_meta($post->ID, 'wg_images', true);
    $bg_image  = get_post_meta($post->ID, 'wg_bg_image', true);
    $intro     = get_post_meta($post->ID, 'wg_intro', true);
    $desc      = get_post_meta($post->ID, 'wg_desc', true);
    $advs      = get_post_meta($post->ID, 'wg_advantages', true);
    $feats     = get_post_meta($post->ID, 'wg_features', true);

    if (!is_array($images)) $images = array();
    if (!is_array($advs))   $advs   = array();
    if (!is_array($feats))  $feats  = array();

    ?>
    <style>
        .wg-field { margin-bottom: 16px; }
        .wg-field label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; color: #1d2327; }
        .wg-field input[type="text"],
        .wg-field textarea { width: 100%; padding: 8px 10px; border: 1px solid #8c8f94; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
        .wg-field textarea { min-height: 100px; resize: vertical; }
        .wg-field .description { font-size: 12px; color: #646970; margin-top: 4px; }
        .wg-group-title { font-weight: 700; font-size: 14px; margin: 28px 0 12px; padding-bottom: 8px; border-bottom: 1px solid #dcdcde; color: #1d2327; }
        .wg-repeatable { background: #f6f7f7; border: 1px solid #dcdcde; border-radius: 4px; padding: 12px; margin-bottom: 10px; position: relative; }
        .wg-repeatable .wg-field { margin-bottom: 10px; }
        .wg-repeatable .wg-field:last-child { margin-bottom: 0; }
        .wg-remove { position: absolute; top: 8px; right: 8px; background: #d63638; color: #fff; border: none; border-radius: 3px; padding: 2px 8px; font-size: 12px; cursor: pointer; }
        .wg-add { background: #2271b1; color: #fff; border: none; border-radius: 4px; padding: 6px 14px; font-size: 13px; cursor: pointer; margin-top: 6px; }
        .wg-add:hover { background: #135e96; }
        .wg-img-preview { max-width: 200px; max-height: 120px; border-radius: 4px; margin-top: 6px; display: block; border: 1px solid #dcdcde; }
        .wg-img-row { display: flex; align-items: center; gap: 8px; }
        .wg-img-row input { flex: 1; }
    </style>

    <!-- Заголовки -->
    <div class="wg-field">
        <label>Надзаголовок</label>
        <input type="text" name="wg_over" value="<?php echo esc_attr($over); ?>" placeholder="НАПРИМЕР: ГАЛЕРЕЯ">
        <p class="description">Отобразится заглавными буквами, золотым цветом</p>
    </div>
    <div class="wg-field">
        <label>Заголовок</label>
        <input type="text" name="wg_title" value="<?php echo esc_attr($title); ?>" placeholder="Основной заголовок секции">
    </div>
    <div class="wg-field">
        <label>Золотой текст в заголовке</label>
        <input type="text" name="wg_gold" value="<?php echo esc_attr($gold); ?>" placeholder="Часть заголовка золотым цветом">
    </div>
    <div class="wg-field">
        <label>Подзаголовок</label>
        <input type="text" name="wg_sub" value="<?php echo esc_attr($sub); ?>" placeholder="Подзаголовок под заголовком">
    </div>

    <!-- Фон -->
    <div class="wg-group-title">Фон секции</div>
    <div class="wg-field">
        <label>Фоновое изображение</label>
        <div class="wg-img-row">
            <input type="text" name="wg_bg_image" value="<?php echo esc_attr($bg_image); ?>" class="wg-bg-input" placeholder="Выберите файл ниже">
            <button type="button" class="button wg-bg-btn">Выбрать</button>
            <?php if ($bg_image) : ?>
                <button type="button" class="button wg-bg-clear" style="color:#b00;">Очистить</button>
            <?php endif; ?>
        </div>
        <?php if ($bg_image) : ?>
            <img src="<?php echo esc_url($bg_image); ?>" class="wg-img-preview" alt="">
        <?php endif; ?>
        <p class="description">Будет отображаться как фон секции с тёмным затемняющим слоем</p>
    </div>

    <!-- Галерея -->
    <div class="wg-group-title">Галерея (Swiper-карусель)</div>
    <div id="wg-images-list">
        <?php foreach ($images as $i => $img) : ?>
        <div class="wg-repeatable">
            <button type="button" class="wg-remove" onclick="this.closest('.wg-repeatable').remove();">Удалить</button>
            <div class="wg-field">
                <label>Изображение</label>
                <div class="wg-img-row">
                    <input type="text" name="wg_images[<?php echo $i; ?>]" value="<?php echo esc_attr($img); ?>" class="wg-img-input" placeholder="Выберите файл ниже">
                    <button type="button" class="button wg-img-btn">Выбрать</button>
                </div>
                <?php if ($img) : ?>
                    <img src="<?php echo esc_url($img); ?>" class="wg-img-preview" alt="">
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="wg-add" id="wg-add-img">+ Добавить изображение</button>

    <!-- Текст слева -->
    <div class="wg-group-title">Текст (левая колонка)</div>
    <div class="wg-field">
        <label>Выделенный блок (основная информация)</label>
        <textarea name="wg_intro" rows="3" placeholder="2-3 предложения с главной информацией..."><?php echo esc_textarea($intro); ?></textarea>
        <p class="description">Короткий текст, который будет выделен визуально (с золотой полосой слева)</p>
    </div>
    <div class="wg-field">
        <label>Подробное описание</label>
        <textarea name="wg_desc" rows="6" placeholder="Развернутое описание..."><?php echo esc_textarea($desc); ?></textarea>
        <p class="description">Допускается HTML: &lt;br&gt;, &lt;strong&gt;, &lt;em&gt;</p>
    </div>

    <!-- Преимущества -->
    <div class="wg-group-title">Преимущества (правая колонка)</div>
    <div id="wg-advs-list">
        <?php foreach ($advs as $i => $adv) : ?>
        <div class="wg-repeatable">
            <button type="button" class="wg-remove" onclick="this.closest('.wg-repeatable').remove();">Удалить</button>
            <div class="wg-field">
                <label>Преимущество</label>
                <input type="text" name="wg_advantages[<?php echo $i; ?>]" value="<?php echo esc_attr($adv); ?>" placeholder="Описание преимущества">
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="wg-add" id="wg-add-adv">+ Добавить преимущество</button>

    <!-- Особенности -->
    <div class="wg-group-title">Особенности (правая колонка, абзацы с золотым фоном)</div>
    <div id="wg-feats-list">
        <?php foreach ($feats as $i => $feat) : ?>
        <div class="wg-repeatable">
            <button type="button" class="wg-remove" onclick="this.closest('.wg-repeatable').remove();">Удалить</button>
            <div class="wg-field">
                <label>Особенность</label>
                <textarea name="wg_features[<?php echo $i; ?>]" rows="3" placeholder="Текст особенности..."><?php echo esc_textarea($feat); ?></textarea>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="wg-add" id="wg-add-feat">+ Добавить особенность</button>

    <script>
    (function($) {

        /* ── Фоновое изображение ── */
        $('.wg-bg-btn').on('click', function() {
            var input = $(this).prev('input.wg-bg-input');
            var row   = input.closest('.wg-field');
            var frame = wp.media({ title: 'Выберите фоновое изображение', multiple: false, library: { type: 'image' } });
            frame.on('select', function() {
                var url = frame.state().get('selection').first().toJSON().url;
                input.val(url);
                row.find('.wg-img-preview').remove();
                $('<img>').attr({ src: url, class: 'wg-img-preview', alt: '' }).insertAfter(input.parent());
                /* показать кнопку «Очистить» */
                if (!row.find('.wg-bg-clear').length) {
                    $('<button type="button" class="button wg-bg-clear" style="color:#b00;">Очистить</button>').insertAfter($('.wg-bg-btn'));
                }
            });
            frame.open();
        });
        $(document).on('click', '.wg-bg-clear', function() {
            var row = $(this).closest('.wg-field');
            row.find('.wg-bg-input').val('');
            row.find('.wg-img-preview').remove();
            $(this).remove();
        });

        /* ── Медиазагрузчик для изображений галереи ── */
        $('#wg-images-list').on('click', '.wg-img-btn', function() {
            var btn   = $(this);
            var input = btn.prev('input.wg-img-input');
            var row   = btn.closest('.wg-repeatable');
            var frame = wp.media({ title: 'Выберите изображение для галереи', multiple: false, library: { type: 'image' } });
            frame.on('select', function() {
                var url = frame.state().get('selection').first().toJSON().url;
                input.val(url);
                row.find('.wg-img-preview').remove();
                $('<img>').attr({ src: url, class: 'wg-img-preview', alt: '' }).insertAfter(btn.parent());
            });
            frame.open();
        });

        /* ── Добавить изображение ── */
        var imgI = <?php echo count($images); ?>;
        $('#wg-add-img').on('click', function() {
            var i = imgI; imgI++;
            var h = '<div class="wg-repeatable"><button type="button" class="wg-remove" onclick="this.closest(\'.wg-repeatable\').remove();">Удалить</button>' +
                '<div class="wg-field"><label>Изображение</label>' +
                '<div class="wg-img-row">' +
                '<input type="text" name="wg_images[' + i + ']" value="" class="wg-img-input" placeholder="Выберите файл ниже">' +
                '<button type="button" class="button wg-img-btn">Выбрать</button>' +
                '</div></div></div>';
            $('#wg-images-list').append(h);
        });

        /* ── Добавить преимущество ── */
        var advI = <?php echo count($advs); ?>;
        $('#wg-add-adv').on('click', function() {
            var i = advI; advI++;
            var h = '<div class="wg-repeatable"><button type="button" class="wg-remove" onclick="this.closest(\'.wg-repeatable\').remove();">Удалить</button>' +
                '<div class="wg-field"><label>Преимущество</label>' +
                '<input type="text" name="wg_advantages[' + i + ']" value="" placeholder="Описание преимущества"></div></div>';
            $('#wg-advs-list').append(h);
        });

        /* ── Добавить особенность ── */
        var featI = <?php echo count($feats); ?>;
        $('#wg-add-feat').on('click', function() {
            var i = featI; featI++;
            var h = '<div class="wg-repeatable"><button type="button" class="wg-remove" onclick="this.closest(\'.wg-repeatable\').remove();">Удалить</button>' +
                '<div class="wg-field"><label>Особенность</label>' +
                '<textarea name="wg_features[' + i + ']" rows="3" placeholder="Текст особенности..."></textarea></div></div>';
            $('#wg-feats-list').append(h);
        });

    })(jQuery);
    </script>

    <?php
}

/* ── Save ────────────────────────────────────────── */
add_action('save_post', function($post_id) {
    if (!isset($_POST['wedding_gallery_nonce_field'])) return;
    if (!wp_verify_nonce($_POST['wedding_gallery_nonce_field'], 'wedding_gallery_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_page', $post_id)) return;

    /* Простые текстовые поля */
    $simple = array('wg_over', 'wg_title', 'wg_gold', 'wg_sub');
    foreach ($simple as $f) {
        if (isset($_POST[$f])) {
            update_post_meta($post_id, $f, sanitize_text_field($_POST[$f]));
        }
    }

    /* Фон */
    if (isset($_POST['wg_bg_image'])) {
        update_post_meta($post_id, 'wg_bg_image', esc_url_raw($_POST['wg_bg_image']));
    }

    /* Текстовые поля с HTML */
    if (isset($_POST['wg_intro'])) {
        update_post_meta($post_id, 'wg_intro', wp_kses_post($_POST['wg_intro']));
    }
    if (isset($_POST['wg_desc'])) {
        update_post_meta($post_id, 'wg_desc', wp_kses_post($_POST['wg_desc']));
    }

    /* Массив изображений */
    if (isset($_POST['wg_images']) && is_array($_POST['wg_images'])) {
        $imgs = array();
        foreach ($_POST['wg_images'] as $url) {
            $url = is_array($url) ? ($url['text'] ?? '') : $url;
            $url = esc_url_raw($url);
            if ($url) $imgs[] = $url;
        }
        update_post_meta($post_id, 'wg_images', $imgs);
    } else {
        delete_post_meta($post_id, 'wg_images');
    }

    /* Массив преимуществ */
    if (isset($_POST['wg_advantages']) && is_array($_POST['wg_advantages'])) {
        $advs = array();
        foreach ($_POST['wg_advantages'] as $a) {
            $a = is_array($a) ? ($a['text'] ?? '') : $a;
            $a = sanitize_text_field($a);
            if ($a) $advs[] = $a;
        }
        update_post_meta($post_id, 'wg_advantages', $advs);
    } else {
        delete_post_meta($post_id, 'wg_advantages');
    }

    /* Массив особенностей */
    if (isset($_POST['wg_features']) && is_array($_POST['wg_features'])) {
        $feats = array();
        foreach ($_POST['wg_features'] as $f) {
            $f = is_array($f) ? ($f['text'] ?? '') : $f;
            $f = wp_kses_post($f);
            if ($f) $feats[] = $f;
        }
        update_post_meta($post_id, 'wg_features', $feats);
    } else {
        delete_post_meta($post_id, 'wg_features');
    }
});