<?php
/**
 * Метабокс «Статьи: категории + популярные»
 * Подключить: require_once get_template_directory() . '/inc/articles_popular.php';
 */

add_action('add_meta_boxes', function () {
    add_meta_box('ap_box', 'Статьи — категории и популярные', 'ap_box_cb', 'page', 'normal', 'high');
});

function ap_box_cb($post) {
    wp_nonce_field('ap_nonce', 'ap_nonce_field');

    $cats  = get_post_meta($post->ID, 'ap_cats', true);
    if (!is_array($cats)) $cats = array();
    $cards = get_post_meta($post->ID, 'ap_cards', true);
    if (!is_array($cards)) $cards = array();
    ?>

    <style>
        .ap-field { margin-bottom: 14px; }
        .ap-field label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; }
        .ap-field input[type="text"],
        .ap-field textarea { width: 100%; padding: 7px 10px; box-sizing: border-box; }
        .ap-field textarea { min-height: 70px; }

        .ap-group-title {
            font-size: 15px;
            font-weight: 700;
            margin: 28px 0 10px;
            padding-bottom: 6px;
            border-bottom: 1px solid #ddd;
        }
        .ap-group-title:first-child { margin-top: 0; }

        /* Категории */
        .ap-cat-row {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 8px;
        }
        .ap-cat-row input { flex: 1; padding: 8px 10px; }
        .ap-remove { color: #a00; cursor: pointer; font-size: 18px; font-weight: 700; flex-shrink: 0; }
        .ap-remove:hover { color: #f00; }

        /* Карточки */
        .ap-card {
            border: 1px solid #ddd;
            padding: 16px;
            margin-bottom: 16px;
            background: #fafafa;
        }
        .ap-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .ap-card-header span { font-weight: 700; font-size: 14px; }
        .ap-card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 16px;
        }
        .ap-card-grid .ap-field.full { grid-column: 1 / -1; }
        .ap-img-row { display: flex; gap: 10px; align-items: flex-start; }
        .ap-img-preview {
            width: 100px; height: 66px;
            background: #f0f0f0; border: 1px solid #ddd;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; overflow: hidden;
        }
        .ap-img-preview img { max-width: 100%; max-height: 100%; object-fit: cover; }
        .ap-hint { color: #888; font-size: 12px; margin: 4px 0 0; }
    </style>

    <!-- ===== КАТЕГОРИИ ===== -->
    <div class="ap-group-title">Категории (кнопки-фильтры)</div>
    <p class="ap-hint">Slug — внутренний идентификатор (латиница, для связи с карточками). Например: stay, spa, family.</p>
    <div id="ap-cats-wrap">
        <?php foreach ($cats as $cat) : ?>
        <div class="ap-cat-row">
            <input type="text" class="ap-cat-slug" value="<?php echo esc_attr(!empty($cat['slug']) ? $cat['slug'] : ''); ?>" placeholder="slug (stay, spa, family...)">
            <input type="text" class="ap-cat-label" value="<?php echo esc_attr(!empty($cat['label']) ? $cat['label'] : ''); ?>" placeholder="Название кнопки (Проживание, SPA...)">
            <span class="ap-remove ap-cat-del" title="Удалить">&times;</span>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button ap-add-cat">+ Добавить категорию</button>

    <!-- ===== КАРТОЧКИ ===== -->
    <div class="ap-group-title">Популярные статьи (карточки)</div>
    <div id="ap-cards-wrap">
        <?php foreach ($cards as $i => $card) : ?>
        <div class="ap-card">
            <div class="ap-card-header">
                <span class="ap-card-num">Карточка #<?php echo $i + 1; ?></span>
                <span class="ap-remove ap-card-del" title="Удалить карточку">&times;</span>
            </div>
            <div class="ap-card-grid">
                <div class="ap-field full">
                    <label>Изображение</label>
                    <div class="ap-img-row">
                        <div class="ap-img-preview">
                            <?php if (!empty($card['image'])) : ?>
                                <img src="<?php echo esc_url($card['image']); ?>" alt="">
                            <?php endif; ?>
                        </div>
                        <div style="flex:1">
                            <input type="text" class="ap-card-image" value="<?php echo esc_attr(!empty($card['image']) ? $card['image'] : ''); ?>" style="width:100%;margin-bottom:6px;padding:7px">
                            <button type="button" class="button ap-upload-btn">Загрузить</button>
                            <button type="button" class="button ap-clear-btn" style="margin-left:6px">Очистить</button>
                        </div>
                    </div>
                </div>
                <div class="ap-field">
                    <label>Slug категории (для фильтра)</label>
                    <input type="text" class="ap-card-cat-slug" value="<?php echo esc_attr(!empty($card['cat_slug']) ? $card['cat_slug'] : ''); ?>" placeholder="stay, spa, family...">
                    <p class="ap-hint">Должен совпадать со slug одной из категорий выше</p>
                </div>
                <div class="ap-field">
                    <label>Время чтения</label>
                    <input type="text" class="ap-card-read" value="<?php echo esc_attr(!empty($card['read']) ? $card['read'] : ''); ?>" placeholder="5 мин чтения">
                </div>
                <div class="ap-field">
                    <label>Заголовок</label>
                    <input type="text" class="ap-card-title" value="<?php echo esc_attr(!empty($card['title']) ? $card['title'] : ''); ?>">
                </div>
                <div class="ap-field">
                    <label>Ссылка «Подробнее»</label>
                    <input type="text" class="ap-card-link" value="<?php echo esc_attr(!empty($card['link']) ? $card['link'] : ''); ?>" placeholder="/nazvanie-stranicy">
                </div>
                <div class="ap-field full">
                    <label>Описание</label>
                    <textarea class="ap-card-desc"><?php echo esc_textarea(!empty($card['desc']) ? $card['desc'] : ''); ?></textarea>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button ap-add-card">+ Добавить карточку</button>

    <!-- Скрытый шаблон карточки -->
    <template id="ap-card-tpl">
        <div class="ap-card">
            <div class="ap-card-header">
                <span class="ap-card-num">Карточка</span>
                <span class="ap-remove ap-card-del" title="Удалить карточку">&times;</span>
            </div>
            <div class="ap-card-grid">
                <div class="ap-field full">
                    <label>Изображение</label>
                    <div class="ap-img-row">
                        <div class="ap-img-preview"></div>
                        <div style="flex:1">
                            <input type="text" class="ap-card-image" value="" style="width:100%;margin-bottom:6px;padding:7px">
                            <button type="button" class="button ap-upload-btn">Загрузить</button>
                            <button type="button" class="button ap-clear-btn" style="margin-left:6px">Очистить</button>
                        </div>
                    </div>
                </div>
                <div class="ap-field">
                    <label>Slug категории (для фильтра)</label>
                    <input type="text" class="ap-card-cat-slug" value="" placeholder="stay, spa, family...">
                    <p class="ap-hint">Должен совпадать со slug одной из категорий выше</p>
                </div>
                <div class="ap-field">
                    <label>Время чтения</label>
                    <input type="text" class="ap-card-read" value="" placeholder="5 мин чтения">
                </div>
                <div class="ap-field">
                    <label>Заголовок</label>
                    <input type="text" class="ap-card-title" value="">
                </div>
                <div class="ap-field">
                    <label>Ссылка «Подробнее»</label>
                    <input type="text" class="ap-card-link" value="" placeholder="/nazvanie-stranicy">
                </div>
                <div class="ap-field full">
                    <label>Описание</label>
                    <textarea class="ap-card-desc"></textarea>
                </div>
            </div>
        </div>
    </template>

    <script>
    (function($) {

        /* --- Категории --- */
        $('.ap-add-cat').on('click', function() {
            var html = '<div class="ap-cat-row">' +
                '<input type="text" class="ap-cat-slug" value="" placeholder="slug (stay, spa, family...)">' +
                '<input type="text" class="ap-cat-label" value="" placeholder="Название кнопки (Проживание, SPA...)">' +
                '<span class="ap-remove ap-cat-del" title="Удалить">&times;</span>' +
                '</div>';
            $('#ap-cats-wrap').append(html);
        });

        $(document).on('click', '.ap-cat-del', function() {
            $(this).closest('.ap-cat-row').remove();
        });

        /* --- Карточки --- */
        function renumber() {
            $('#ap-cards-wrap .ap-card').each(function(i) {
                $(this).find('.ap-card-num').text('Карточка #' + (i + 1));
            });
        }

        $('.ap-add-card').on('click', function() {
            var tpl = $('#ap-card-tpl').html();
            $('#ap-cards-wrap').append(tpl);
            renumber();
        });

        $(document).on('click', '.ap-card-del', function() {
            $(this).closest('.ap-card').remove();
            renumber();
        });

        /* --- Медиа-загрузчик --- */
        var frame;
        $(document).on('click', '.ap-upload-btn', function(e) {
            e.preventDefault();
            var $card = $(this).closest('.ap-card');
            var $input = $card.find('.ap-card-image');
            var $preview = $card.find('.ap-img-preview');

            if (frame) { frame.open(); return; }
            frame = wp.media({
                title: 'Выбрать изображение',
                button: { text: 'Использовать' },
                multiple: false
            });
            frame.on('select', function() {
                var att = frame.state().get('selection').first().toJSON();
                $input.val(att.url);
                $preview.html('<img src="' + att.url + '" alt="">');
                frame = null;
            });
            frame.open();
        });

        $(document).on('click', '.ap-clear-btn', function() {
            var $card = $(this).closest('.ap-card');
            $card.find('.ap-card-image').val('');
            $card.find('.ap-img-preview').html('');
        });

        /* --- Собираем данные при сохранении --- */
        $(document).on('submit', '#post', function() {
            /* Категории */
            var cats = [];
            $('#ap-cats-wrap .ap-cat-row').each(function() {
                var slug  = $(this).find('.ap-cat-slug').val();
                var label = $(this).find('.ap-cat-label').val();
                if (slug || label) cats.push({ slug: slug, label: label });
            });

            /* Карточки */
            var cards = [];
            $('#ap-cards-wrap .ap-card').each(function() {
                cards.push({
                    image:    $(this).find('.ap-card-image').val(),
                    cat_slug: $(this).find('.ap-card-cat-slug').val(),
                    title:    $(this).find('.ap-card-title').val(),
                    desc:     $(this).find('.ap-card-desc').val(),
                    read:     $(this).find('.ap-card-read').val(),
                    link:     $(this).find('.ap-card-link').val()
                });
            });

            $('<input>').attr({ type: 'hidden', name: 'ap_cats_data', value: JSON.stringify(cats) }).appendTo('#post');
            $('<input>').attr({ type: 'hidden', name: 'ap_cards_data', value: JSON.stringify(cards) }).appendTo('#post');
        });
    })(jQuery);
    </script>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['ap_nonce_field'])) return;
    if (!wp_verify_nonce($_POST['ap_nonce_field'], 'ap_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    /* Категории */
    if (isset($_POST['ap_cats_data'])) {
        $raw = json_decode(stripslashes($_POST['ap_cats_data']), true);
        $cats = array();
        if (is_array($raw)) {
            foreach ($raw as $c) {
                $slug  = isset($c['slug'])  ? sanitize_text_field($c['slug'])  : '';
                $label = isset($c['label']) ? sanitize_text_field($c['label']) : '';
                if ($slug && $label) $cats[] = array('slug' => $slug, 'label' => $label);
            }
        }
        update_post_meta($post_id, 'ap_cats', $cats);
    } else {
        delete_post_meta($post_id, 'ap_cats');
    }

    /* Карточки */
    if (isset($_POST['ap_cards_data'])) {
        $raw = json_decode(stripslashes($_POST['ap_cards_data']), true);
        $cards = array();
        if (is_array($raw)) {
            foreach ($raw as $c) {
                $image    = isset($c['image'])    ? esc_url_raw($c['image'])    : '';
                $cat_slug = isset($c['cat_slug']) ? sanitize_text_field($c['cat_slug']) : '';
                $title    = isset($c['title'])    ? sanitize_text_field($c['title'])    : '';
                $desc     = isset($c['desc'])     ? sanitize_textarea_field($c['desc'])  : '';
                $read     = isset($c['read'])     ? sanitize_text_field($c['read'])      : '';
                $link     = isset($c['link'])     ? sanitize_text_field($c['link'])      : '';
                if ($title || $image) {
                    $cards[] = compact('image', 'cat_slug', 'title', 'desc', 'read', 'link');
                }
            }
        }
        update_post_meta($post_id, 'ap_cards', $cards);
    } else {
        delete_post_meta($post_id, 'ap_cards');
    }
});