<?php
/**
 * Метабокс «Тело статьи» — конструктор блоков
 * Подключить: require_once get_template_directory() . '/inc/article_body.php';
 */

add_action('add_meta_boxes', function () {
    add_meta_box('ab_box', 'Тело статьи — блоки', 'ab_box_cb', 'page', 'normal', 'high');
});

function ab_box_cb($post) {
    wp_nonce_field('ab_nonce', 'ab_nonce_field');

    $lead     = get_post_meta($post->ID, 'ab_lead', true);
    $blocks   = get_post_meta($post->ID, 'ab_blocks', true);
    $tags     = get_post_meta($post->ID, 'ab_tags', true);
    $cta_t    = get_post_meta($post->ID, 'ab_cta_title', true);
    $cta_txt  = get_post_meta($post->ID, 'ab_cta_text', true);
    $cta_btn  = get_post_meta($post->ID, 'ab_cta_btn', true);
    $cta_url  = get_post_meta($post->ID, 'ab_cta_url', true);
    $cta_tel  = get_post_meta($post->ID, 'ab_cta_tel', true);

    if (!is_array($blocks)) $blocks = array();
    if (!is_array($tags))   $tags   = array();
    ?>

    <style>
        .ab-grp { font-size:15px; font-weight:700; margin:24px 0 10px; padding-bottom:6px; border-bottom:1px solid #ddd; }
        .ab-grp:first-child { margin-top:0; }
        .ab-f { margin-bottom:14px; }
        .ab-f label { display:block; font-weight:600; margin-bottom:4px; font-size:13px; }
        .ab-f input[type="text"],.ab-f textarea { width:100%; padding:7px 10px; box-sizing:border-box; }
        .ab-f textarea { min-height:80px; }
        .ab-hint { color:#888; font-size:12px; margin:3px 0 0; }

        /* Блоки */
        .ab-block { border:1px solid #ddd; padding:16px; margin-bottom:16px; background:#fafafa; }
        .ab-block-hd { display:flex; gap:12px; align-items:center; margin-bottom:12px; }
        .ab-block-hd select { flex:1; }
        .ab-rm { color:#a00; cursor:pointer; font-size:18px; font-weight:700; }
        .ab-rm:hover { color:#f00; }

        /* Показ/скрытие полей по типу */
        .ab-bf { display:none; }
        .ab-bf.ab-bf-active { display:block; }

        .ab-img-row { display:flex; gap:10px; align-items:flex-start; }
        .ab-img-prev { width:100px; height:66px; background:#f0f0f0; border:1px solid #ddd; display:flex; align-items:center; justify-content:center; flex-shrink:0; overflow:hidden; }
        .ab-img-prev img { max-width:100%; max-height:100%; object-fit:cover; }

        /* Теги */
        .ab-tag-row { display:flex; gap:8px; align-items:center; margin-bottom:8px; }
        .ab-tag-row input { flex:1; padding:8px 10px; }
    </style>

    <!-- Вводный абзац -->
    <div class="ab-grp">Вводный абзац (Lead)</div>
    <div class="ab-f">
        <textarea name="ab_lead" rows="3"><?php echo esc_textarea($lead); ?></textarea>
        <p class="ab-hint">*текст* = золотой выделение</p>
    </div>

    <!-- Контент-блоки -->
    <div class="ab-grp">Контент-блоки</div>
    <div id="ab-blocks-wrap">
        <?php foreach ($blocks as $i => $b) :
            $type = !empty($b['type']) ? $b['type'] : 'text';
        ?>
        <div class="ab-block" data-type="<?php echo esc_attr($type); ?>">
            <div class="ab-block-hd">
                <select class="ab-block-type">
                    <option value="heading" <?php selected($type,'heading'); ?>>Заголовок</option>
                    <option value="text" <?php selected($type,'text'); ?>>Текст / Абзац</option>
                    <option value="image" <?php selected($type,'image'); ?>>Изображение</option>
                    <option value="pullquote" <?php selected($type,'pullquote'); ?>>Цитата</option>
                    <option value="table" <?php selected($type,'table'); ?>>Таблица</option>
                </select>
                <span class="ab-rm ab-block-del" title="Удалить">&times;</span>
            </div>

            <!-- Заголовок -->
            <div class="ab-bf ab-bf-heading <?php echo $type==='heading'?'ab-bf-active':''; ?>">
                <div class="ab-f">
                    <label>Текст заголовка</label>
                    <input type="text" class="ab-h-text" value="<?php echo esc_attr(!empty($b['heading_text'])?$b['heading_text']:''); ?>">
                </div>
                <div class="ab-f">
                    <label>Якорь id (для навигации)</label>
                    <input type="text" class="ab-h-id" value="<?php echo esc_attr(!empty($b['heading_id'])?$b['heading_id']:''); ?>" placeholder="eco">
                </div>
            </div>

            <!-- Текст -->
            <div class="ab-bf ab-bf-text <?php echo $type==='text'?'ab-bf-active':''; ?>">
                <div class="ab-f">
                    <textarea class="ab-b-text" rows="4"><?php echo esc_textarea(!empty($b['text'])?$b['text']:''); ?></textarea>
                    <p class="ab-hint">*текст* = золотой. Двойной Enter = новый абзац.</p>
                </div>
            </div>

            <!-- Изображение -->
            <div class="ab-bf ab-bf-image <?php echo $type==='image'?'ab-bf-active':''; ?>">
                <div class="ab-img-row">
                    <div class="ab-img-prev">
                        <?php if (!empty($b['image'])) : ?><img src="<?php echo esc_url($b['image']); ?>" alt=""><?php endif; ?>
                    </div>
                    <div style="flex:1">
                        <input type="text" class="ab-b-image" value="<?php echo esc_attr(!empty($b['image'])?$b['image']:''); ?>" style="width:100%;margin-bottom:6px">
                        <button type="button" class="button ab-upload-btn">Загрузить</button>
                        <button type="button" class="button ab-clear-btn" style="margin-left:6px">Очистить</button>
                    </div>
                </div>
                <div class="ab-f" style="margin-top:10px">
                    <label>Alt текст</label>
                    <input type="text" class="ab-b-alt" value="<?php echo esc_attr(!empty($b['image_alt'])?$b['image_alt']:''); ?>">
                </div>
                <div class="ab-f">
                    <label><input type="checkbox" class="ab-b-wide" <?php checked(!empty($b['image_wide'])); ?>> Широкое (во всю ширину)</label>
                </div>
            </div>

            <!-- Цитата -->
            <div class="ab-bf ab-bf-pullquote <?php echo $type==='pullquote'?'ab-bf-active':''; ?>">
                <div class="ab-f">
                    <textarea class="ab-b-quote" rows="3"><?php echo esc_textarea(!empty($b['quote'])?$b['quote']:''); ?></textarea>
                    <p class="ab-hint">*текст* = золотой выделение</p>
                </div>
            </div>

            <!-- Таблица -->
            <div class="ab-bf ab-bf-table <?php echo $type==='table'?'ab-bf-active':''; ?>">
                <div class="ab-f">
                    <label>Заголовки колонок (через | )</label>
                    <input type="text" class="ab-t-hdrs" value="<?php echo esc_attr(!empty($b['table_headers'])?$b['table_headers']:''); ?>" placeholder="Параметр | Эко-дом | Номер | Гостевой дом">
                </div>
                <div class="ab-f">
                    <label>Строки (каждая строка = строка таблицы, значения через | )</label>
                    <textarea class="ab-t-rows" rows="5"><?php echo esc_textarea(!empty($b['table_rows'])?$b['table_rows']:''); ?></textarea>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div style="display:flex;gap:8px;margin-bottom:24px">
        <button type="button" class="button ab-add" data-type="heading">+ Заголовок</button>
        <button type="button" class="button ab-add" data-type="text">+ Текст</button>
        <button type="button" class="button ab-add" data-type="image">+ Картинка</button>
        <button type="button" class="button ab-add" data-type="pullquote">+ Цитата</button>
        <button type="button" class="button ab-add" data-type="table">+ Таблица</button>
    </div>

    <!-- Теги -->
    <div class="ab-grp">Теги</div>
    <div id="ab-tags-wrap">
        <?php foreach ($tags as $tag) : ?>
        <div class="ab-tag-row">
            <input type="text" name="ab_tags_text[]" value="<?php echo esc_attr($tag); ?>">
            <span class="ab-rm ab-tag-del" title="Удалить">&times;</span>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button" id="ab-add-tag">+ Добавить тег</button>

    <!-- Боковая панель — CTA -->
    <div class="ab-grp">Боковая панель — «Забронировать»</div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="ab-f">
            <label>Заголовок блока</label>
            <input type="text" name="ab_cta_title" value="<?php echo esc_attr($cta_t); ?>" placeholder="Забронировать">
        </div>
        <div class="ab-f">
            <label>Телефон</label>
            <input type="text" name="ab_cta_tel" value="<?php echo esc_attr($cta_tel); ?>" placeholder="+79850200203">
        </div>
        <div class="ab-f" style="grid-column:1/-1">
            <label>Описание</label>
            <input type="text" name="ab_cta_text" value="<?php echo esc_attr($cta_txt); ?>">
        </div>
        <div class="ab-f">
            <label>Текст кнопки</label>
            <input type="text" name="ab_cta_btn" value="<?php echo esc_attr($cta_btn); ?>" placeholder="Оставить заявку">
        </div>
        <div class="ab-f">
            <label>Ссылка кнопки</label>
            <input type="text" name="ab_cta_url" value="<?php echo esc_attr($cta_url); ?>" placeholder="/contacts.html">
        </div>
    </div>

    <!-- Шаблон блока для JS -->
    <template id="ab-block-tpl">
        <div class="ab-block" data-type="TEXT_TYPE">
            <div class="ab-block-hd">
                <select class="ab-block-type">
                    <option value="heading">Заголовок</option>
                    <option value="text" selected>Текст / Абзац</option>
                    <option value="image">Изображение</option>
                    <option value="pullquote">Цитата</option>
                    <option value="table">Таблица</option>
                </select>
                <span class="ab-rm ab-block-del" title="Удалить">&times;</span>
            </div>
            <div class="ab-bf ab-bf-heading">
                <div class="ab-f"><label>Текст заголовка</label><input type="text" class="ab-h-text" value=""></div>
                <div class="ab-f"><label>Якорь id</label><input type="text" class="ab-h-id" value="" placeholder="eco"></div>
            </div>
            <div class="ab-bf ab-bf-text">
                <div class="ab-f"><textarea class="ab-b-text" rows="4"></textarea><p class="ab-hint">*текст* = золотой. Двойной Enter = новый абзац.</p></div>
            </div>
            <div class="ab-bf ab-bf-image">
                <div class="ab-img-row">
                    <div class="ab-img-prev"></div>
                    <div style="flex:1">
                        <input type="text" class="ab-b-image" value="" style="width:100%;margin-bottom:6px">
                        <button type="button" class="button ab-upload-btn">Загрузить</button>
                        <button type="button" class="button ab-clear-btn" style="margin-left:6px">Очистить</button>
                    </div>
                </div>
                <div class="ab-f" style="margin-top:10px"><label>Alt текст</label><input type="text" class="ab-b-alt" value=""></div>
                <div class="ab-f"><label><input type="checkbox" class="ab-b-wide"> Широкое</label></div>
            </div>
            <div class="ab-bf ab-bf-pullquote">
                <div class="ab-f"><textarea class="ab-b-quote" rows="3"></textarea><p class="ab-hint">*текст* = золотой</p></div>
            </div>
            <div class="ab-bf ab-bf-table">
                <div class="ab-f"><label>Заголовки (через | )</label><input type="text" class="ab-t-hdrs" value="" placeholder="Параметр | Эко-дом | Номер"></div>
                <div class="ab-f"><label>Строки</label><textarea class="ab-t-rows" rows="5"></textarea></div>
            </div>
        </div>
    </template>

    <script>
    (function($) {

        /* Переключение типа блока */
        $(document).on('change', '.ab-block-type', function() {
            var type = $(this).val();
            var $block = $(this).closest('.ab-block');
            $block.attr('data-type', type);
            $block.find('.ab-bf').removeClass('ab-bf-active');
            $block.find('.ab-bf-' + type).addClass('ab-bf-active');
        });

        /* Добавить блок */
        $('.ab-add').on('click', function() {
            var type = $(this).data('type');
            var tpl = $('#ab-block-tpl').html();
            tpl = tpl.replace(/TEXT_TYPE/g, type);
            var $el = $(tpl);
            /* Установить правильный тип в селекте */
            $el.find('.ab-block-type').val(type);
            $el.find('.ab-bf').removeClass('ab-bf-active');
            $el.find('.ab-bf-' + type).addClass('ab-bf-active');
            $('#ab-blocks-wrap').append($el);
        });

        /* Удалить блок */
        $(document).on('click', '.ab-block-del', function() {
            $(this).closest('.ab-block').remove();
        });

        /* Медиа-загрузчик */
        var frame;
        $(document).on('click', '.ab-upload-btn', function(e) {
            e.preventDefault();
            var $block = $(this).closest('.ab-block');
            var $input = $block.find('.ab-b-image');
            var $prev = $block.find('.ab-img-prev');
            if (frame) { frame.open(); return; }
            frame = wp.media({ title: 'Выбрать изображение', button: { text: 'Использовать' }, multiple: false });
            frame.on('select', function() {
                var att = frame.state().get('selection').first().toJSON();
                $input.val(att.url);
                $prev.html('<img src="' + att.url + '" alt="">');
                frame = null;
            });
            frame.open();
        });
        $(document).on('click', '.ab-clear-btn', function() {
            var $block = $(this).closest('.ab-block');
            $block.find('.ab-b-image').val('');
            $block.find('.ab-img-prev').html('');
        });

        /* Теги */
        $('#ab-add-tag').on('click', function() {
            var html = '<div class="ab-tag-row"><input type="text" name="ab_tags_text[]" value=""><span class="ab-rm ab-tag-del" title="Удалить">&times;</span></div>';
            $('#ab-tags-wrap').append(html);
        });
        $(document).on('click', '.ab-tag-del', function() { $(this).closest('.ab-tag-row').remove(); });

        /* Собрать данные при сохранении */
        $(document).on('submit', '#post', function() {
            var blocks = [];
            $('#ab-blocks-wrap .ab-block').each(function() {
                var type = $(this).find('.ab-block-type').val();
                var b = { type: type };
                if (type === 'heading') {
                    b.heading_text = $(this).find('.ab-h-text').val();
                    b.heading_id = $(this).find('.ab-h-id').val();
                } else if (type === 'text') {
                    b.text = $(this).find('.ab-b-text').val();
                } else if (type === 'image') {
                    b.image = $(this).find('.ab-b-image').val();
                    b.image_alt = $(this).find('.ab-b-alt').val();
                    b.image_wide = $(this).find('.ab-b-wide').is(':checked');
                } else if (type === 'pullquote') {
                    b.quote = $(this).find('.ab-b-quote').val();
                } else if (type === 'table') {
                    b.table_headers = $(this).find('.ab-t-hdrs').val();
                    b.table_rows = $(this).find('.ab-t-rows').val();
                }
                blocks.push(b);
            });
            $('<input>').attr({ type:'hidden', name:'ab_blocks_data', value:JSON.stringify(blocks) }).appendTo('#post');
        });
    })(jQuery);
    </script>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['ab_nonce_field'])) return;
    if (!wp_verify_nonce($_POST['ab_nonce_field'], 'ab_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    /* Lead */
    if (isset($_POST['ab_lead'])) {
        update_post_meta($post_id, 'ab_lead', sanitize_textarea_field($_POST['ab_lead']));
    }

    /* Блоки */
    if (isset($_POST['ab_blocks_data'])) {
        $raw = json_decode(stripslashes($_POST['ab_blocks_data']), true);
        $blocks = array();
        if (is_array($raw)) {
            foreach ($raw as $b) {
                $type = isset($b['type']) ? sanitize_text_field($b['type']) : 'text';
                $block = array('type' => $type);
                if ($type === 'heading') {
                    $block['heading_text'] = isset($b['heading_text']) ? sanitize_text_field($b['heading_text']) : '';
                    $block['heading_id']   = isset($b['heading_id'])   ? sanitize_text_field($b['heading_id'])   : '';
                } elseif ($type === 'text') {
                    $block['text'] = isset($b['text']) ? sanitize_textarea_field($b['text']) : '';
                } elseif ($type === 'image') {
                    $block['image']      = isset($b['image'])      ? esc_url_raw($b['image'])      : '';
                    $block['image_alt']  = isset($b['image_alt'])  ? sanitize_text_field($b['image_alt'])  : '';
                    $block['image_wide'] = !empty($b['image_wide']);
                } elseif ($type === 'pullquote') {
                    $block['quote'] = isset($b['quote']) ? sanitize_textarea_field($b['quote']) : '';
                } elseif ($type === 'table') {
                    $block['table_headers'] = isset($b['table_headers']) ? sanitize_text_field($b['table_headers']) : '';
                    $block['table_rows']   = isset($b['table_rows'])   ? sanitize_textarea_field($b['table_rows'])   : '';
                }
                /* Проверяем что блок не пустой */
                $has = false;
                foreach ($block as $k => $v) {
                    if ($k !== 'type' && $k !== 'image_wide' && $v !== '') $has = true;
                }
                if ($type === 'image' && !empty($block['image'])) $has = true;
                if ($has) $blocks[] = $block;
            }
        }
        update_post_meta($post_id, 'ab_blocks', $blocks);
    } else {
        update_post_meta($post_id, 'ab_blocks', array());
    }

    /* Теги */
    if (isset($_POST['ab_tags_text']) && is_array($_POST['ab_tags_text'])) {
        $clean = array();
        foreach ($_POST['ab_tags_text'] as $t) {
            $t = sanitize_text_field($t);
            if ($t !== '') $clean[] = $t;
        }
        update_post_meta($post_id, 'ab_tags', $clean);
    } else {
        delete_post_meta($post_id, 'ab_tags');
    }

    /* CTA */
    $cta_fields = array('ab_cta_title','ab_cta_text','ab_cta_btn','ab_cta_url','ab_cta_tel');
    foreach ($cta_fields as $f) {
        if (isset($_POST[$f])) {
            update_post_meta($post_id, $f, sanitize_text_field($_POST[$f]));
        }
    }
});