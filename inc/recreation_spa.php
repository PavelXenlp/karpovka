<?php
/* ===== Секция: Активности — Группы по сезонам ===== */
/* Каждый формат = карточка + модалка act-modal с описанием, списком, ценой, CTA */
/* Мета-ключ: rec_groups */
/*
 * ПРЕДЗАПОЛНЕНИЕ: при открытии страницы в админке пустые поля модалок
 * автоматически заполняются значениями из карточки (фото, название, тег и т.д.).
 * При первом сохранении страницы значения фиксируются в БД.
 */

add_action('add_meta_boxes', function () {
    add_meta_box('recreation_groups', 'Активности — Сезонные группы', 'recreation_groups_mb', 'page', 'normal', 'high');
});

function recreation_groups_mb($post) {
    wp_nonce_field('rec_groups_nonce', 'rec_groups_nonce_f');
    $groups = get_post_meta($post->ID, 'rec_groups', true);
    if (!is_array($groups)) $groups = [];

    /* ═══ ПРЕДЗАПОЛНЕНИЕ ПУСТЫХ ПОЛЕЙ МОДАЛОК ═══
       Берём данные из карточки и подставляем в пустые поля модалки.
       Срабатывает при отображении метабокса в админке.
       При сохранении страницы значения фиксируются в БД. */
    foreach ($groups as $gi => $g) {
        if (empty($g['cards'])) continue;
        foreach ($g['cards'] as $ci => $c) {
            /* modal_image — из фото карточки */
            if (empty($c['modal_image']) && !empty($c['image'])) {
                $groups[$gi]['cards'][$ci]['modal_image'] = $c['image'];
            }
            /* modal_badge — из текста тега */
            if (empty($c['modal_badge']) && !empty($c['tag_text'])) {
                $groups[$gi]['cards'][$ci]['modal_badge'] = $c['tag_text'];
            }
            /* modal_title — из названия карточки */
            if (empty($c['modal_title']) && !empty($c['title'])) {
                $groups[$gi]['cards'][$ci]['modal_title'] = $c['title'];
            }
            /* modal_meta — из meta1 + meta2 */
            if (empty($c['modal_meta'])) {
                $meta = implode(' · ', array_filter([$c['meta1_text'] ?? '', $c['meta2_text'] ?? '']));
                if ($meta) $groups[$gi]['cards'][$ci]['modal_meta'] = $meta;
            }
            /* modal_desc — из описания карточки, обёрнутого в <p> */
            if (empty($c['modal_desc']) && !empty($c['desc'])) {
                if (strip_tags($c['desc']) === $c['desc']) {
                    /* Нет HTML-тегов — обернём в <p> */
                    $groups[$gi]['cards'][$ci]['modal_desc'] = '<p>' . nl2br(esc_html($c['desc'])) . '</p>';
                } else {
                    $groups[$gi]['cards'][$ci]['modal_desc'] = $c['desc'];
                }
            }
            /* modal_items — пример списка (пользователь отредактирует под каждую активность) */
            if (empty($c['modal_items']) || !is_array($c['modal_items'])) {
                $groups[$gi]['cards'][$ci]['modal_items'] = [
                    ['text' => 'Обучение — инструкция и подготовка перед стартом'],
                    ['text' => 'Снаряжение — всё необходимое оборудование включено'],
                    ['text' => 'Сопровождение — опытный инструктор на связи'],
                    ['text' => 'Безопасность — страховка и инструктаж включены'],
                ];
            }
            /* price — дефолтный placeholder */
            if (empty($c['price'])) {
                $groups[$gi]['cards'][$ci]['price'] = 'от 500 ₽';
            }
            /* price_small — дефолт */
            if (empty($c['price_small'])) {
                $groups[$gi]['cards'][$ci]['price_small'] = '/ час';
            }
            /* cta_text — дефолт */
            if (empty($c['cta_text'])) {
                $groups[$gi]['cards'][$ci]['cta_text'] = 'Забронировать';
            }
            /* cta_link — дефолт */
            if (empty($c['cta_link'])) {
                $groups[$gi]['cards'][$ci]['cta_link'] = '#cta';
            }
        }
    }
    /* ═══ КОНЕЦ ПРЕДЗАПОЛНЕНИЯ ═══ */

    ?>
    <style>
        .rg-group{margin-bottom:16px;padding:16px;border:2px solid #c3c4c7;background:#fff;border-radius:4px;}
        .rg-group h4{margin:0 0 10px;padding:6px 10px;background:#f0f0f1;display:flex;justify-content:space-between;align-items:center;}
        .rg-card{margin-bottom:10px;padding:10px;border:1px solid #dcdcde;background:#fafafa;border-radius:4px;}
        .rg-card h5{margin:0 0 6px;color:#2271b1;}
        .rg-card label{display:block;font-weight:600;margin-bottom:3px;font-size:12px;}
        .rg-card input[type="text"],
        .rg-card textarea{width:100%;}
        .rg-card details{margin:10px 0;padding:12px;border:1px solid #dcdcde;background:#fff;border-radius:4px;}
        .rg-card details summary{cursor:pointer;font-weight:600;margin-bottom:10px;}
        .rg-card .rg-row{display:flex;gap:10px;}
        .rg-card .rg-row > p{flex:1;margin:0;}
        .rg-card p{margin:0 0 8px;}
        .rg-hint{font-size:11px;color:#666;margin-top:3px;}
        .rg-card input.widefat,
        .rg-card textarea.widefat{width:100%;}
        .rg-prefilled{background:#e7f3e7;border-left:3px solid #46b450;padding:4px 8px;margin:6px 0;font-size:11px;color:#1c6c1c;}
    </style>

    <p><strong>Сезонные группы:</strong> классы секций и фона определяются автоматически по сезону. Все классы/ID сохраняются — фильтры продолжат работать.</p>
    <p class="rg-prefilled">ℹ️ Поля модалок предзаполнены значениями по умолчанию из карточек. Сохраните страницу — значения зафиксируются. После этого отредактируйте конкретные поля (особенно цену и список «Что входит») под каждую активность.</p>
    <div id="rg-groups-wrap">
    <?php foreach ($groups as $gi => $g) : ?>
        <div class="rg-group">
            <h4>Группа: <span class="rg-g-label"><?php echo esc_html($g['section_id'] ?? 'group_'.$gi); ?></span>
                <button type="button" class="button rg-group-del">Удалить группу</button>
            </h4>

            <p><label>Ключ сезона (data-season-group):</label><br>
            <select name="rec_groups[<?php echo $gi; ?>][group_key]">
                <?php foreach (['summer','winter','allyear'] as $v) : ?>
                    <option value="<?php echo $v; ?>" <?php selected($g['group_key'] ?? '', $v); ?>><?php echo $v; ?></option>
                <?php endforeach; ?>
            </select></p>

            <p><label>ID секции:</label><br>
            <input type="text" name="rec_groups[<?php echo $gi; ?>][section_id]" value="<?php echo esc_attr($g['section_id'] ?? ''); ?>" class="widefat" placeholder="summer"></p>

            <p><label>Фон — изображение:</label><br>
            <input type="hidden" name="rec_groups[<?php echo $gi; ?>][bg_image]" value="<?php echo esc_url($g['bg_image'] ?? ''); ?>" class="rg-bg-img" data-kp-media></p>

            <p><label>Надзаголовок (eyebrow):</label><br>
            <input type="text" name="rec_groups[<?php echo $gi; ?>][eyebrow]" value="<?php echo esc_attr($g['eyebrow'] ?? ''); ?>" class="widefat"></p>

            <p><label>Заголовок (до золотого):</label><br>
            <input type="text" name="rec_groups[<?php echo $gi; ?>][title_before]" value="<?php echo esc_attr($g['title_before'] ?? ''); ?>" class="widefat"></p>

            <p><label>Золотой текст (курсивом):</label><br>
            <input type="text" name="rec_groups[<?php echo $gi; ?>][title_em]" value="<?php echo esc_attr($g['title_em'] ?? ''); ?>" class="widefat"></p>

            <p><label>Подзаголовок:</label><br>
            <textarea name="rec_groups[<?php echo $gi; ?>][sub]" rows="2" class="widefat"><?php echo esc_textarea($g['sub'] ?? ''); ?></textarea></p>

            <hr>
            <p><strong>Карточки:</strong></p>
            <div class="rg-cards-wrap">
            <?php if (!empty($g['cards']) && is_array($g['cards'])) : foreach ($g['cards'] as $ci => $c) : ?>
                <div class="rg-card">
                    <h5>Карточка <?php echo $ci + 1; ?></h5>

                    <p><label>Изображение:</label><br>
                    <input type="hidden" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][image]" value="<?php echo esc_url($c['image'] ?? ''); ?>" class="rg-card-img" data-kp-media></p>

                    <p><label>Иконка тега (FA class):</label><br>
                    <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][tag_icon]" value="<?php echo esc_attr($c['tag_icon'] ?? ''); ?>" class="widefat" placeholder="fa-solid fa-sun"></p>

                    <p><label>Текст тега:</label><br>
                    <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][tag_text]" value="<?php echo esc_attr($c['tag_text'] ?? ''); ?>" class="widefat" placeholder="Май — Октябрь"></p>

                    <p><label>Название:</label><br>
                    <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][title]" value="<?php echo esc_attr($c['title'] ?? ''); ?>" class="widefat"></p>

                    <p><label>Описание (для карточки):</label><br>
                    <textarea name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][desc]" rows="3" class="widefat"><?php echo esc_textarea($c['desc'] ?? ''); ?></textarea></p>

                    <div class="rg-row">
                        <p><label>Мета 1 — иконка:</label>
                        <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][meta1_icon]" value="<?php echo esc_attr($c['meta1_icon'] ?? ''); ?>" placeholder="fa-regular fa-clock"></p>
                        <p><label>Мета 1 — текст:</label>
                        <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][meta1_text]" value="<?php echo esc_attr($c['meta1_text'] ?? ''); ?>" placeholder="от 2 часов"></p>
                    </div>

                    <div class="rg-row">
                        <p><label>Мета 2 — иконка:</label>
                        <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][meta2_icon]" value="<?php echo esc_attr($c['meta2_icon'] ?? ''); ?>" placeholder="fa-solid fa-child"></p>
                        <p><label>Мета 2 — текст:</label>
                        <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][meta2_text]" value="<?php echo esc_attr($c['meta2_text'] ?? ''); ?>" placeholder="от 6 лет"></p>
                    </div>

                    <p><label>Ссылка «Подробнее» (ID модалки без #):</label><br>
                    <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][link]" value="<?php echo esc_attr($c['link'] ?? ''); ?>" class="widefat" placeholder="modal-act-summer-0"></p>

                    <details>
                        <summary>Подробное модальное окно (act-modal)</summary>

                        <p><label>Фото в модалке (можно другое, не как в карточке)</label><br>
                        <input type="hidden" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][modal_image]" value="<?php echo esc_url($c['modal_image'] ?? ''); ?>" class="rg-modal-img" data-kp-media></p>

                        <p><label>Бейдж в модалке (если пусто — возьмётся из «Текст тега»)</label><br>
                        <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][modal_badge]" value="<?php echo esc_attr($c['modal_badge'] ?? ''); ?>" placeholder="Лето / Осень"></p>

                        <p><label>Заголовок в модалке (если пусто — из «Название»)</label><br>
                        <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][modal_title]" value="<?php echo esc_attr($c['modal_title'] ?? ''); ?>"></p>

                        <p><label>Метаданные в модалке (если пусто — из Мета 1 + Мета 2)</label><br>
                        <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][modal_meta]" value="<?php echo esc_attr($c['modal_meta'] ?? ''); ?>" placeholder="Продолжительность: 2 часа · Для всех возрастов"></p>

                        <p><label>Описание в модалке (HTML разрешён, &lt;p&gt; для абзацев)</label><br>
                        <textarea name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][modal_desc]" rows="5"><?php echo esc_textarea($c['modal_desc'] ?? ''); ?></textarea></p>

                        <p><label>Что входит — один пункт на строку</label><br>
                        <textarea name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][modal_items_text]" rows="7"><?php echo esc_textarea(implode("\n", array_map(static function($item) { return is_array($item) ? ($item['text'] ?? '') : $item; }, $c['modal_items'] ?? []))); ?></textarea>
                        <p class="rg-hint">Формат: «<strong>Заголовок</strong> — описание» (с тире). Часть до тире станет жирной. Без тире — просто текст.</p></p>

                        <div class="rg-row">
                            <p><label>Цена (например: от 1 200 ₽)</label><br>
                            <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][price]" value="<?php echo esc_attr($c['price'] ?? ''); ?>" placeholder="от 1 200 ₽"></p>
                            <p><label>Дополнение к цене (small, напр.: / час)</label><br>
                            <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][price_small]" value="<?php echo esc_attr($c['price_small'] ?? ''); ?>" placeholder="/ час"></p>
                        </div>

                        <div class="rg-row">
                            <p><label>Текст CTA-кнопки</label><br>
                            <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][cta_text]" value="<?php echo esc_attr($c['cta_text'] ?? 'Забронировать'); ?>"></p>
                            <p><label>Ссылка CTA (по умолчанию #cta)</label><br>
                            <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][cta_link]" value="<?php echo esc_attr($c['cta_link'] ?? ''); ?>" placeholder="#cta"></p>
                        </div>
                    </details>

                    <p><button type="button" class="button rg-card-del">Удалить карточку</button></p>
                </div>
            <?php endforeach; endif; ?>
            </div>
            <p><button type="button" class="button rg-card-add">+ Добавить карточку</button></p>
        </div>
    <?php endforeach; ?>
    </div>
    <p><button type="button" class="button button-primary" id="rg-group-add">+ Добавить сезонную группу</button></p>

    <script>
(function($){
    var gn = $('#rg-groups_wrap .rg-group').length;

    function mkCard(gi, ci){
        return '<div class="rg-card"><h5>Карточка '+(ci+1)+'</h5>'
            +'<p><label>Изображение:</label><br>'
            +'<input type="hidden" name="rec_groups['+gi+'][cards]['+ci+'][image]" class="rg-card-img" data-kp-media></p>'
            +'<p><label>Иконка тега (FA class):</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][tag_icon]" class="widefat" placeholder="fa-solid fa-sun"></p>'
            +'<p><label>Текст тега:</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][tag_text]" class="widefat" placeholder="Май — Октябрь"></p>'
            +'<p><label>Название:</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][title]" class="widefat"></p>'
            +'<p><label>Описание (для карточки):</label><br>'
            +'<textarea name="rec_groups['+gi+'][cards]['+ci+'][desc]" rows="3" class="widefat"></textarea></p>'
            +'<div class="rg-row"><p><label>Мета 1 — иконка:</label>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][meta1_icon]" placeholder="fa-regular fa-clock"></p>'
            +'<p><label>Мета 1 — текст:</label>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][meta1_text]" placeholder="от 2 часов"></p></div>'
            +'<div class="rg-row"><p><label>Мета 2 — иконка:</label>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][meta2_icon]" placeholder="fa-solid fa-child"></p>'
            +'<p><label>Мета 2 — текст:</label>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][meta2_text]" placeholder="от 6 лет"></p></div>'
            +'<p><label>Ссылка «Подробнее» (ID модалки без #):</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][link]" class="widefat" placeholder="modal-act-summer-0"></p>'
            +'<details><summary>Подробное модальное окно (act-modal)</summary>'
            +'<p><label>Фото в модалке</label><br>'
            +'<input type="hidden" name="rec_groups['+gi+'][cards]['+ci+'][modal_image]" class="rg-modal-img" data-kp-media></p>'
            +'<p><label>Бейдж в модалке</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][modal_badge]" placeholder="Лето / Осень"></p>'
            +'<p><label>Заголовок в модалке</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][modal_title]"></p>'
            +'<p><label>Метаданные в модалке</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][modal_meta]" placeholder="Продолжительность: 2 часа · Для всех возрастов"></p>'
            +'<p><label>Описание в модалке (HTML)</label><br>'
            +'<textarea name="rec_groups['+gi+'][cards]['+ci+'][modal_desc]" rows="5"></textarea></p>'
            +'<p><label>Что входит — один пункт на строку</label><br>'
            +'<textarea name="rec_groups['+gi+'][cards]['+ci+'][modal_items_text]" rows="7"></textarea>'
            +'<p class="rg-hint">Формат: «Заголовок — описание» (с тире). Часть до тире станет жирной.</p></p>'
            +'<div class="rg-row"><p><label>Цена</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][price]" placeholder="от 1 200 ₽"></p>'
            +'<p><label>Дополнение к цене (small)</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][price_small]" placeholder="/ час"></p></div>'
            +'<div class="rg-row"><p><label>Текст CTA</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][cta_text]" value="Забронировать"></p>'
            +'<p><label>Ссылка CTA</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][cta_link]" placeholder="#cta"></p></div>'
            +'</details>'
            +'<p><button type="button" class="button rg-card-del">Удалить карточку</button></p></div>';
    }

    function mkGroup(){
        var gi = gn;
        var h = '<div class="rg-group"><h4>Группа: <span class="rg-g-label">group_'+gi+'</span>'
            +' <button type="button" class="button rg-group-del">Удалить группу</button></h4>'
            +'<p><label>Ключ сезона:</label><br>'
            +'<select name="rec_groups['+gi+'][group_key]">'
            +'<option value="summer">summer</option><option value="winter">winter</option><option value="allyear">allyear</option>'
            +'</select></p>'
            +'<p><label>ID секции:</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][section_id]" class="widefat" placeholder="summer"></p>'
            +'<p><label>Фон — изображение:</label><br>'
            +'<input type="hidden" name="rec_groups['+gi+'][bg_image]" class="rg-bg-img" data-kp-media></p>'
            +'<p><label>Надзаголовок (eyebrow):</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][eyebrow]" class="widefat"></p>'
            +'<p><label>Заголовок (до золотого):</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][title_before]" class="widefat"></p>'
            +'<p><label>Золотой текст (курсивом):</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][title_em]" class="widefat"></p>'
            +'<p><label>Подзаголовок:</label><br>'
            +'<textarea name="rec_groups['+gi+'][sub]" rows="2" class="widefat"></textarea></p>'
            +'<hr><p><strong>Карточки:</strong></p>'
            +'<div class="rg-cards-wrap"></div>'
            +'<p><button type="button" class="button rg-card-add">+ Добавить карточку</button></p></div>';
        $('#rg-groups_wrap').append(h);
        gn++;
    }

    $('#rg-group-add').on('click', function(){ mkGroup(); });
    $(document).on('click', '.rg-group-del', function(){ $(this).closest('.rg-group').remove(); });

    $(document).on('click', '.rg-card-add', function(){
        var wrap = $(this).closest('p').prev('.rg-cards-wrap');
        var gi = wrap.closest('.rg-group').index('#rg-groups_wrap .rg-group');
        var ci = wrap.find('.rg-card').length;
        wrap.append(mkCard(gi, ci));
    });

    $(document).on('click', '.rg-card-del', function(){ $(this).closest('.rg-card').remove(); });

})(jQuery);
</script>
    <?php
}

add_action('save_post', function ($pid) {
    if (!isset($_POST['rec_groups_nonce_f'])) return;
    if (!wp_verify_nonce($_POST['rec_groups_nonce_f'], 'rec_groups_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $pid)) return;

    $groups = [];
    if (!empty($_POST['rec_groups']) && is_array($_POST['rec_groups'])) {
        foreach ($_POST['rec_groups'] as $g) {
            $cards = [];
            if (!empty($g['cards']) && is_array($g['cards'])) {
                foreach ($g['cards'] as $c) {
                    /* modal_items из текста — один пункт на строку */
                    $modal_items = array_values(array_filter(array_map(static function ($line) {
                        $line = sanitize_text_field($line);
                        return $line === '' ? null : ['text' => $line];
                    }, preg_split('/\R/u', $c['modal_items_text'] ?? '') ?: [])));

                    $cards[] = [
                        'image'         => esc_url_raw($c['image'] ?? ''),
                        'tag_icon'      => sanitize_text_field($c['tag_icon'] ?? ''),
                        'tag_text'      => sanitize_text_field($c['tag_text'] ?? ''),
                        'title'         => sanitize_text_field($c['title'] ?? ''),
                        'desc'          => sanitize_textarea_field($c['desc'] ?? ''),
                        'meta1_icon'    => sanitize_text_field($c['meta1_icon'] ?? ''),
                        'meta1_text'    => sanitize_text_field($c['meta1_text'] ?? ''),
                        'meta2_icon'    => sanitize_text_field($c['meta2_icon'] ?? ''),
                        'meta2_text'    => sanitize_text_field($c['meta2_text'] ?? ''),
                        'link'          => sanitize_text_field($c['link'] ?? ''),
                        /* Поля модалки */
                        'modal_image'   => esc_url_raw($c['modal_image'] ?? ''),
                        'modal_badge'   => sanitize_text_field($c['modal_badge'] ?? ''),
                        'modal_title'   => sanitize_text_field($c['modal_title'] ?? ''),
                        'modal_meta'    => sanitize_text_field($c['modal_meta'] ?? ''),
                        'modal_desc'    => wp_kses_post($c['modal_desc'] ?? ''),
                        'modal_items'   => $modal_items,
                        'cta_text'      => sanitize_text_field($c['cta_text'] ?? 'Забронировать'),
                        'cta_link'      => sanitize_text_field($c['cta_link'] ?? ''),
                        /* Поля цены */
                        'price'         => sanitize_text_field($c['price'] ?? ''),
                        'price_small'   => sanitize_text_field($c['price_small'] ?? ''),
                    ];
                }
            }
            $gk = in_array($g['group_key'] ?? '', ['summer','winter','allyear']) ? $g['group_key'] : 'summer';
            $groups[] = [
                'group_key'    => $gk,
                'section_id'   => sanitize_text_field($g['section_id'] ?? ''),
                'bg_image'    => esc_url_raw($g['bg_image'] ?? ''),
                'eyebrow'     => sanitize_text_field($g['eyebrow'] ?? ''),
                'title_before'=> sanitize_text_field($g['title_before'] ?? ''),
                'title_em'    => sanitize_text_field($g['title_em'] ?? ''),
                'sub'         => sanitize_textarea_field($g['sub'] ?? ''),
                'cards'       => $cards,
            ];
        }
    }
    update_post_meta($pid, 'rec_groups', $groups);
});