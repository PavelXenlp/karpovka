<?php
/**
 * Метабокс «Все статьи» (карточки)
 * Подключить: require_once get_template_directory() . '/inc/articles_all.php';
 */

add_action('add_meta_boxes', function () {
    add_meta_box('aa_box', 'Все статьи — карточки', 'aa_box_cb', 'page', 'normal', 'high');
});

function aa_box_cb($post) {
    wp_nonce_field('aa_nonce', 'aa_nonce_field');

    $cards = get_post_meta($post->ID, 'aa_cards', true);
    if (!is_array($cards)) $cards = array();
    ?>

    <style>
        .aa-field { margin-bottom: 14px; }
        .aa-field label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; }
        .aa-field input[type="text"],
        .aa-field textarea { width: 100%; padding: 7px 10px; box-sizing: border-box; }
        .aa-field textarea { min-height: 70px; }

        .aa-card {
            border: 1px solid #ddd;
            padding: 16px;
            margin-bottom: 16px;
            background: #fafafa;
        }
        .aa-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .aa-card-header span { font-weight: 700; font-size: 14px; }
        .aa-card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 16px;
        }
        .aa-card-grid .aa-field.full { grid-column: 1 / -1; }
        .aa-img-row { display: flex; gap: 10px; align-items: flex-start; }
        .aa-img-preview {
            width: 100px; height: 66px;
            background: #f0f0f0; border: 1px solid #ddd;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; overflow: hidden;
        }
        .aa-img-preview img { max-width: 100%; max-height: 100%; object-fit: cover; }
        .aa-remove { color: #a00; cursor: pointer; font-size: 18px; font-weight: 700; flex-shrink: 0; }
        .aa-remove:hover { color: #f00; }
        .aa-hint { color: #888; font-size: 12px; margin: 4px 0 0; }
    </style>

    <p class="aa-hint">Slug категории должен совпадать с категориями из метабокса «Статьи — категории и популярные». Название категории подставится автоматически.</p>

    <div id="aa-cards-wrap">
        <?php foreach ($cards as $i => $card) : ?>
        <div class="aa-card">
            <div class="aa-card-header">
                <span class="aa-card-num">Карточка #<?php echo $i + 1; ?></span>
                <span class="aa-remove aa-card-del" title="Удалить">&times;</span>
            </div>
            <div class="aa-card-grid">
                <div class="aa-field full">
                    <label>Изображение</label>
                    <div class="aa-img-row">
                        <div class="aa-img-preview">
                            <?php if (!empty($card['image'])) : ?>
                                <img src="<?php echo esc_url($card['image']); ?>" alt="">
                            <?php endif; ?>
                        </div>
                        <div style="flex:1">
                            <input type="text" class="aa-card-image" value="<?php echo esc_attr(!empty($card['image']) ? $card['image'] : ''); ?>" style="width:100%;margin-bottom:6px;padding:7px">
                            <button type="button" class="button aa-upload-btn">Загрузить</button>
                            <button type="button" class="button aa-clear-btn" style="margin-left:6px">Очистить</button>
                        </div>
                    </div>
                </div>
                <div class="aa-field">
                    <label>Slug категории (для фильтра)</label>
                    <input type="text" class="aa-card-cat-slug" value="<?php echo esc_attr(!empty($card['cat_slug']) ? $card['cat_slug'] : ''); ?>" placeholder="stay, spa, family...">
                </div>
                <div class="aa-field">
                    <label>Время чтения</label>
                    <input type="text" class="aa-card-read" value="<?php echo esc_attr(!empty($card['read']) ? $card['read'] : ''); ?>" placeholder="5 мин">
                </div>
                <div class="aa-field">
                    <label>Заголовок</label>
                    <input type="text" class="aa-card-title" value="<?php echo esc_attr(!empty($card['title']) ? $card['title'] : ''); ?>">
                </div>
                <div class="aa-field">
                    <label>Ссылка «Подробнее»</label>
                    <input type="text" class="aa-card-link" value="<?php echo esc_attr(!empty($card['link']) ? $card['link'] : ''); ?>" placeholder="/nazvanie-stranicy">
                </div>
                <div class="aa-field full">
                    <label>Описание</label>
                    <textarea class="aa-card-desc"><?php echo esc_textarea(!empty($card['desc']) ? $card['desc'] : ''); ?></textarea>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button aa-add-card">+ Добавить карточку</button>

    <template id="aa-card-tpl">
        <div class="aa-card">
            <div class="aa-card-header">
                <span class="aa-card-num">Карточка</span>
                <span class="aa-remove aa-card-del" title="Удалить">&times;</span>
            </div>
            <div class="aa-card-grid">
                <div class="aa-field full">
                    <label>Изображение</label>
                    <div class="aa-img-row">
                        <div class="aa-img-preview"></div>
                        <div style="flex:1">
                            <input type="text" class="aa-card-image" value="" style="width:100%;margin-bottom:6px;padding:7px">
                            <button type="button" class="button aa-upload-btn">Загрузить</button>
                            <button type="button" class="button aa-clear-btn" style="margin-left:6px">Очистить</button>
                        </div>
                    </div>
                </div>
                <div class="aa-field">
                    <label>Slug категории (для фильтра)</label>
                    <input type="text" class="aa-card-cat-slug" value="" placeholder="stay, spa, family...">
                </div>
                <div class="aa-field">
                    <label>Время чтения</label>
                    <input type="text" class="aa-card-read" value="" placeholder="5 мин">
                </div>
                <div class="aa-field">
                    <label>Заголовок</label>
                    <input type="text" class="aa-card-title" value="">
                </div>
                <div class="aa-field">
                    <label>Ссылка «Подробнее»</label>
                    <input type="text" class="aa-card-link" value="" placeholder="/nazvanie-stranicy">
                </div>
                <div class="aa-field full">
                    <label>Описание</label>
                    <textarea class="aa-card-desc"></textarea>
                </div>
            </div>
        </div>
    </template>

    <script>
    (function($) {
        function renumber() {
            $('#aa-cards-wrap .aa-card').each(function(i) {
                $(this).find('.aa-card-num').text('Карточка #' + (i + 1));
            });
        }

        $('.aa-add-card').on('click', function() {
            var tpl = $('#aa-card-tpl').html();
            $('#aa-cards-wrap').append(tpl);
            renumber();
        });

        $(document).on('click', '.aa-card-del', function() {
            $(this).closest('.aa-card').remove();
            renumber();
        });

        var frame;
        $(document).on('click', '.aa-upload-btn', function(e) {
            e.preventDefault();
            var $card = $(this).closest('.aa-card');
            var $input = $card.find('.aa-card-image');
            var $preview = $card.find('.aa-img-preview');

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

        $(document).on('click', '.aa-clear-btn', function() {
            var $card = $(this).closest('.aa-card');
            $card.find('.aa-card-image').val('');
            $card.find('.aa-img-preview').html('');
        });

        $(document).on('submit', '#post', function() {
            var cards = [];
            $('#aa-cards-wrap .aa-card').each(function() {
                cards.push({
                    image:    $(this).find('.aa-card-image').val(),
                    cat_slug: $(this).find('.aa-card-cat-slug').val(),
                    title:    $(this).find('.aa-card-title').val(),
                    desc:     $(this).find('.aa-card-desc').val(),
                    read:     $(this).find('.aa-card-read').val(),
                    link:     $(this).find('.aa-card-link').val()
                });
            });
            $('<input>').attr({ type: 'hidden', name: 'aa_cards_data', value: JSON.stringify(cards) }).appendTo('#post');
        });
    })(jQuery);
    </script>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['aa_nonce_field'])) return;
    if (!wp_verify_nonce($_POST['aa_nonce_field'], 'aa_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['aa_cards_data'])) {
        $raw = json_decode(stripslashes($_POST['aa_cards_data']), true);
        $cards = array();
        if (is_array($raw)) {
            foreach ($raw as $c) {
                $image    = isset($c['image'])    ? esc_url_raw($c['image'])          : '';
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
        update_post_meta($post_id, 'aa_cards', $cards);
    } else {
        delete_post_meta($post_id, 'aa_cards');
    }
});