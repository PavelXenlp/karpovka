<?php
/**
 * Метабокс: FAQ — категории и вопросы
 * Подключить: require __DIR__ . '/inc/faq_section.php';
 *
 * Заполняет секцию <section class="faq f-faq"> (файл /includes/faq.php).
 * Мета-ключ: kp_faq_data (массив с keys: active, cats, groups)
 */

add_action('add_meta_boxes', function () {
    add_meta_box('kp_faq_box', 'FAQ — категории и вопросы', 'kp_faq_box_cb', 'page', 'normal', 'high');
});

function kp_faq_box_cb($post) {
    wp_nonce_field('kp_faq_nonce', 'kp_faq_nonce_field');

    $data = get_post_meta($post->ID, 'kp_faq_data', true);
    if (!is_array($data)) $data = array();

    $active = !empty($data['active']) ? $data['active'] : 'booking';
    $cats   = !empty($data['cats']) && is_array($data['cats']) ? $data['cats'] : array();
    $groups = !empty($data['groups']) && is_array($data['groups']) ? $data['groups'] : array();
    ?>

    <style>
        .kp-f { margin-bottom: 12px; }
        .kp-f label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; }
        .kp-f input[type="text"], .kp-f select, .kp-f textarea { width: 100%; padding: 7px 10px; box-sizing: border-box; }
        .kp-hint { color: #888; font-size: 12px; margin: 4px 0 8px; }
        .kp-grp { font-size: 15px; font-weight: 700; margin: 20px 0 10px; padding-bottom: 6px; border-bottom: 1px solid #ddd; }
        .kp-grp:first-child { margin-top: 0; }
        .kp-cat-row { display: flex; gap: 8px; align-items: center; margin-bottom: 8px; }
        .kp-cat-row input { flex: 1; padding: 8px 10px; }
        .kp-rm { color: #a00; cursor: pointer; font-size: 18px; font-weight: 700; flex-shrink: 0; }
        .kp-rm:hover { color: #f00; }
        .kp-faq-group { border: 1px solid #ddd; padding: 14px; margin-bottom: 14px; background: #fafafa; border-radius: 4px; }
        .kp-group-hd { display: grid; grid-template-columns: 1fr 2fr auto; gap: 8px; align-items: center; margin-bottom: 10px; }
        .kp-group-hd input { padding: 7px 10px; }
        .kp-faq-items { margin-bottom: 10px; }
        .kp-faq-item { border: 1px solid #e5e5e5; padding: 10px; margin-bottom: 8px; background: #fff; border-radius: 3px; position: relative; }
        .kp-faq-item .kp-item-del { position: absolute; top: 8px; right: 8px; }
        .kp-item-main-wrap label { font-weight: normal; font-size: 13px; margin: 0; }
    </style>

    <div class="kp-grp">Стартовая категория</div>
    <div class="kp-f">
        <label>Активная категория при загрузке страницы</label>
        <select name="kp_faq_active" id="kp-faq-active">
            <option value="all" <?php selected($active, 'all'); ?>>Все вопросы</option>
            <option value="main" <?php selected($active, 'main'); ?>>Основные</option>
            <?php foreach ($cats as $c) :
                $slug = !empty($c['slug']) ? $c['slug'] : '';
                if (!$slug) continue;
                $label = !empty($c['label']) ? $c['label'] : $slug;
            ?>
                <option value="<?php echo esc_attr($slug); ?>" <?php selected($active, $slug); ?>><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
        <p class="kp-hint">Какая категория будет открыта при заходе на страницу.</p>
    </div>

    <div class="kp-grp">Категории (кнопки-фильтры)</div>
    <p class="kp-hint">«Все вопросы» и «Основные» добавляются в разметку автоматически — здесь только остальные.</p>
    <div id="kp-faq-cats-wrap">
        <?php foreach ($cats as $c) : ?>
        <div class="kp-cat-row">
            <input type="text" class="kp-cat-slug" value="<?php echo esc_attr(!empty($c['slug']) ? $c['slug'] : ''); ?>" placeholder="slug (booking, stay...)">
            <input type="text" class="kp-cat-label" value="<?php echo esc_attr(!empty($c['label']) ? $c['label'] : ''); ?>" placeholder="Название (Бронирование, Проживание...)">
            <span class="kp-rm kp-cat-del" title="Удалить">&times;</span>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button kp-add-cat">+ Добавить категорию</button>

    <div class="kp-grp">Группы вопросов</div>
    <p class="kp-hint">Slug группы должен совпадать со slug-ом категории. У вопроса — текст, ответ (HTML разрешён) и чекбокс «Основной» (data-main).</p>
    <div id="kp-faq-groups-wrap">
        <?php foreach ($groups as $group) :
            $gslug = !empty($group['slug']) ? $group['slug'] : '';
            $gtitle = !empty($group['title']) ? $group['title'] : '';
            $gitems = !empty($group['items']) && is_array($group['items']) ? $group['items'] : array();
        ?>
        <div class="kp-faq-group">
            <div class="kp-group-hd">
                <input type="text" class="kp-group-slug" value="<?php echo esc_attr($gslug); ?>" placeholder="slug группы (booking)">
                <input type="text" class="kp-group-title" value="<?php echo esc_attr($gtitle); ?>" placeholder="Заголовок группы (Бронирование)">
                <span class="kp-rm kp-group-del" title="Удалить группу">&times;</span>
            </div>
            <div class="kp-faq-items">
                <?php foreach ($gitems as $item) :
                    $q = !empty($item['q']) ? $item['q'] : '';
                    $a = !empty($item['a']) ? $item['a'] : '';
                    $ismain = !empty($item['is_main']);
                ?>
                <div class="kp-faq-item">
                    <span class="kp-rm kp-item-del" title="Удалить вопрос">&times;</span>
                    <div class="kp-f">
                        <label>Вопрос</label>
                        <input type="text" class="kp-item-q" value="<?php echo esc_attr($q); ?>" placeholder="Как забронировать проживание?">
                    </div>
                    <div class="kp-f">
                        <label>Ответ (HTML разрешён)</label>
                        <textarea class="kp-item-a" rows="3" placeholder="Текст ответа..."><?php echo esc_textarea($a); ?></textarea>
                    </div>
                    <div class="kp-item-main-wrap">
                        <label><input type="checkbox" class="kp-item-main" <?php checked($ismain); ?>> Показывать в категории «Основные» (data-main)</label>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button kp-add-item">+ Добавить вопрос</button>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button kp-add-group">+ Добавить группу</button>

    <template id="kp-cat-tpl">
        <div class="kp-cat-row">
            <input type="text" class="kp-cat-slug" value="" placeholder="slug (booking, stay...)">
            <input type="text" class="kp-cat-label" value="" placeholder="Название (Бронирование, Проживание...)">
            <span class="kp-rm kp-cat-del" title="Удалить">&times;</span>
        </div>
    </template>
    <template id="kp-group-tpl">
        <div class="kp-faq-group">
            <div class="kp-group-hd">
                <input type="text" class="kp-group-slug" value="" placeholder="slug группы (booking)">
                <input type="text" class="kp-group-title" value="" placeholder="Заголовок группы (Бронирование)">
                <span class="kp-rm kp-group-del" title="Удалить группу">&times;</span>
            </div>
            <div class="kp-faq-items"></div>
            <button type="button" class="button kp-add-item">+ Добавить вопрос</button>
        </div>
    </template>
    <template id="kp-item-tpl">
        <div class="kp-faq-item">
            <span class="kp-rm kp-item-del" title="Удалить вопрос">&times;</span>
            <div class="kp-f">
                <label>Вопрос</label>
                <input type="text" class="kp-item-q" value="" placeholder="Как забронировать проживание?">
            </div>
            <div class="kp-f">
                <label>Ответ (HTML разрешён)</label>
                <textarea class="kp-item-a" rows="3" placeholder="Текст ответа..."></textarea>
            </div>
            <div class="kp-item-main-wrap">
                <label><input type="checkbox" class="kp-item-main"> Показывать в категории «Основные» (data-main)</label>
            </div>
        </div>
    </template>

    <script>
    (function($) {
        $('.kp-add-cat').on('click', function() {
            $('#kp-faq-cats-wrap').append($('#kp-cat-tpl').html());
        });
        $(document).on('click', '.kp-cat-del', function() {
            $(this).closest('.kp-cat-row').remove();
        });

        $('.kp-add-group').on('click', function() {
            $('#kp-faq-groups-wrap').append($('#kp-group-tpl').html());
        });
        $(document).on('click', '.kp-group-del', function() {
            if (confirm('Удалить всю группу со всеми вопросами?')) {
                $(this).closest('.kp-faq-group').remove();
            }
        });

        $(document).on('click', '.kp-add-item', function() {
            $(this).closest('.kp-faq-group').find('.kp-faq-items').append($('#kp-item-tpl').html());
        });
        $(document).on('click', '.kp-item-del', function() {
            $(this).closest('.kp-faq-item').remove();
        });

        $(document).on('submit', '#post', function() {
            var cats = [];
            $('#kp-faq-cats-wrap .kp-cat-row').each(function() {
                var slug  = $(this).find('.kp-cat-slug').val();
                var label = $(this).find('.kp-cat-label').val();
                if (slug || label) cats.push({ slug: slug, label: label });
            });

            var groups = [];
            $('#kp-faq-groups-wrap .kp-faq-group').each(function() {
                var items = [];
                $(this).find('.kp-faq-items .kp-faq-item').each(function() {
                    items.push({
                        q:       $(this).find('.kp-item-q').val(),
                        a:       $(this).find('.kp-item-a').val(),
                        is_main: $(this).find('.kp-item-main').is(':checked')
                    });
                });
                groups.push({
                    slug:  $(this).find('.kp-group-slug').val(),
                    title: $(this).find('.kp-group-title').val(),
                    items: items
                });
            });

            var payload = {
                active: $('#kp-faq-active').val(),
                cats:   cats,
                groups: groups
            };

            $('<input>').attr({
                type: 'hidden',
                name: 'kp_faq_data_json',
                value: JSON.stringify(payload)
            }).appendTo('#post');
        });
    })(jQuery);
    </script>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['kp_faq_nonce_field'])) return;
    if (!wp_verify_nonce($_POST['kp_faq_nonce_field'], 'kp_faq_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (!isset($_POST['kp_faq_data_json'])) return;

    $raw = json_decode(stripslashes($_POST['kp_faq_data_json']), true);
    if (!is_array($raw)) {
        update_post_meta($post_id, 'kp_faq_data', array());
        return;
    }

    $active = isset($raw['active']) ? sanitize_text_field($raw['active']) : 'all';

    $cats = array();
    if (!empty($raw['cats']) && is_array($raw['cats'])) {
        foreach ($raw['cats'] as $c) {
            $slug  = isset($c['slug'])  ? sanitize_text_field($c['slug'])  : '';
            $label = isset($c['label']) ? sanitize_text_field($c['label']) : '';
            if ($slug || $label) $cats[] = array('slug' => $slug, 'label' => $label);
        }
    }

    $groups = array();
    if (!empty($raw['groups']) && is_array($raw['groups'])) {
        foreach ($raw['groups'] as $g) {
            $gslug  = isset($g['slug'])  ? sanitize_text_field($g['slug'])   : '';
            $gtitle = isset($g['title']) ? sanitize_text_field($g['title']) : '';
            $gitems = array();
            if (!empty($g['items']) && is_array($g['items'])) {
                foreach ($g['items'] as $item) {
                    $q = isset($item['q']) ? sanitize_text_field($item['q']) : '';
                    $a = isset($item['a']) ? wp_kses_post($item['a']) : '';
                    $ismain = !empty($item['is_main']);
                    if ($q || $a) $gitems[] = array('q' => $q, 'a' => $a, 'is_main' => $ismain);
                }
            }
            if ($gslug || $gtitle || $gitems) {
                $groups[] = array('slug' => $gslug, 'title' => $gtitle, 'items' => $gitems);
            }
        }
    }

    update_post_meta($post_id, 'kp_faq_data', array(
        'active' => $active,
        'cats'   => $cats,
        'groups' => $groups,
    ));
});