<?php
/**
 * Метабокс: Секция 1 — Подарочные сертификаты (заголовок + слайдер + CTA)
 * Подключить: require __DIR__ . '/inc/gift_one.php';
 *
 * Заполняет секцию <section class="certificates-section"> (файл /includes/gift-one.php).
 * Мета-ключ: kp_gift_one
 */

add_action('add_meta_boxes', function () {
    add_meta_box('kp_gift_one_box', 'Сертификаты — Секция 1 (заголовок + слайдер + CTA)', 'kp_gift_one_box_cb', 'page', 'normal', 'high');
});

function kp_gift_one_box_cb($post) {
    wp_nonce_field('kp_gift_one_nonce', 'kp_gift_one_nonce_field');

    $defaults = array(
        'eyebrow'   => 'Подарок с заботой',
        'title'     => 'Подарочные <em>сертификаты</em>',
        'lead'      => 'Лучший подарок для близких — день на природе, СПА-восстановление или тёплый банный вечер. Выберите номинал, а получатель сам решит, как провести время в «Карповке».',
        'cards'     => array(
            array('brand' => 'Карповка', 'brand_sub' => 'Парк-отель &amp; СПА', 'value' => '5 000 ₽',  'theme' => 'СПА-день',           'validity' => 'Действует 12 месяцев', 'code' => '№ KRP-0001', 'variant' => 'pine'),
            array('brand' => 'Карповка', 'brand_sub' => 'Парк-отель &amp; СПА', 'value' => '10 000 ₽', 'theme' => 'Отдых для двоих',    'validity' => 'Действует 12 месяцев', 'code' => '№ KRP-0002', 'variant' => 'light'),
            array('brand' => 'Карповка', 'brand_sub' => 'Парк-отель &amp; СПА', 'value' => '15 000 ₽', 'theme' => 'Выходные на природе','validity' => 'Действует 12 месяцев', 'code' => '№ KRP-0003', 'variant' => 'pine'),
            array('brand' => 'Карповка', 'brand_sub' => 'Парк-отель &amp; СПА', 'value' => '25 000 ₽', 'theme' => 'Большая программа',   'validity' => 'Действует 12 месяцев', 'code' => '№ KRP-0004', 'variant' => 'light'),
        ),
        'cta_text'  => 'Оформить сертификат',
        'cta_link'  => '#contacts',
        'note'      => 'Номиналы указаны для примера — набор и стоимость сертификатов уточняются.',
    );

    $data = get_post_meta($post->ID, 'kp_gift_one', true);
    if (!is_array($data)) $data = array();
    $data = array_merge($defaults, $data);
    if (!is_array($data['cards'] ?? array())) $data['cards'] = $defaults['cards'];
    ?>

    <style>
        .kp-g1 { margin-bottom: 12px; }
        .kp-g1 label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; }
        .kp-g1 input[type="text"], .kp-g1 textarea, .kp-g1 select { width: 100%; padding: 7px 10px; box-sizing: border-box; }
        .kp-g1 textarea { min-height: 60px; resize: vertical; }
        .kp-hint { font-size: 12px; color: #646970; margin-top: 4px; }
        .kp-hint code { background: #f0f0f1; padding: 1px 4px; border-radius: 2px; font-size: 11px; }
        .kp-g1-grp { font-size: 14px; font-weight: 700; margin: 18px 0 8px; padding-bottom: 6px; border-bottom: 1px solid #ddd; }
        .kp-g1-grp:first-child { margin-top: 0; }
        .kp-g1-card { border: 1px solid #ddd; padding: 12px; margin-bottom: 10px; background: #fafafa; border-radius: 4px; position: relative; }
        .kp-g1-card .kp-card-del { position: absolute; top: 8px; right: 8px; color: #a00; cursor: pointer; font-size: 18px; font-weight: 700; }
        .kp-g1-card .kp-card-del:hover { color: #f00; }
        .kp-g1-card .kp-g1 { margin-bottom: 8px; }
        .kp-g1-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .kp-g1-row3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; }
    </style>

    <div class="kp-g1-grp">Заголовок секции</div>

    <div class="kp-g1">
        <label>Надзаголовок (eyebrow) — необязательно</label>
        <input type="text" name="kp_gift_one[eyebrow]" value="<?php echo esc_attr($data['eyebrow']); ?>" placeholder="Подарок с заботой">
    </div>

    <div class="kp-g1">
        <label>Заголовок (HTML: <code>&lt;em&gt;слово&lt;/em&gt;</code> = золотой курсив)</label>
        <textarea name="kp_gift_one[title]" rows="2" placeholder="Подарочные &lt;em&gt;сертификаты&lt;/em&gt;"><?php echo esc_textarea($data['title']); ?></textarea>
    </div>

    <div class="kp-g1">
        <label>Подзаголовок (HTML разрешён)</label>
        <textarea name="kp_gift_one[lead]" rows="3"><?php echo esc_textarea($data['lead']); ?></textarea>
    </div>

    <div class="kp-g1-grp">Карточки сертификатов</div>
    <div id="kp-gift-one-cards-wrap">
        <?php foreach ($data['cards'] as $c) : ?>
        <div class="kp-g1-card">
            <span class="kp-card-del" title="Удалить">&times;</span>
            <div class="kp-g1-row2">
                <div class="kp-g1">
                    <label>Бренд</label>
                    <input type="text" class="kp-card-brand" value="<?php echo esc_attr(!empty($c['brand']) ? $c['brand'] : ''); ?>" placeholder="Карповка">
                </div>
                <div class="kp-g1">
                    <label>Подзаголовок бренда</label>
                    <input type="text" class="kp-card-brand-sub" value="<?php echo esc_attr(!empty($c['brand_sub']) ? $c['brand_sub'] : ''); ?>" placeholder="Парк-отель &amp; СПА">
                </div>
            </div>
            <div class="kp-g1-row2">
                <div class="kp-g1">
                    <label>Номинал</label>
                    <input type="text" class="kp-card-value" value="<?php echo esc_attr(!empty($c['value']) ? $c['value'] : ''); ?>" placeholder="5 000 ₽">
                </div>
                <div class="kp-g1">
                    <label>Тема</label>
                    <input type="text" class="kp-card-theme" value="<?php echo esc_attr(!empty($c['theme']) ? $c['theme'] : ''); ?>" placeholder="СПА-день">
                </div>
            </div>
            <div class="kp-g1-row2">
                <div class="kp-g1">
                    <label>Срок действия</label>
                    <input type="text" class="kp-card-validity" value="<?php echo esc_attr(!empty($c['validity']) ? $c['validity'] : ''); ?>" placeholder="Действует 12 месяцев">
                </div>
                <div class="kp-g1">
                    <label>Код</label>
                    <input type="text" class="kp-card-code" value="<?php echo esc_attr(!empty($c['code']) ? $c['code'] : ''); ?>" placeholder="№ KRP-0001">
                </div>
            </div>
            <div class="kp-g1">
                <label>Вариант оформления</label>
                <select class="kp-card-variant">
                    <option value="pine"  <?php selected(!empty($c['variant']) ? $c['variant'] : '', 'pine');  ?>>Тёмный (pine)</option>
                    <option value="light" <?php selected(!empty($c['variant']) ? $c['variant'] : '', 'light'); ?>>Светлый (light)</option>
                </select>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button kp-add-card">+ Добавить сертификат</button>

    <template id="kp-g1-card-tpl">
        <div class="kp-g1-card">
            <span class="kp-card-del" title="Удалить">&times;</span>
            <div class="kp-g1-row2">
                <div class="kp-g1">
                    <label>Бренд</label>
                    <input type="text" class="kp-card-brand" value="" placeholder="Карповка">
                </div>
                <div class="kp-g1">
                    <label>Подзаголовок бренда</label>
                    <input type="text" class="kp-card-brand-sub" value="" placeholder="Парк-отель &amp; СПА">
                </div>
            </div>
            <div class="kp-g1-row2">
                <div class="kp-g1">
                    <label>Номинал</label>
                    <input type="text" class="kp-card-value" value="" placeholder="5 000 ₽">
                </div>
                <div class="kp-g1">
                    <label>Тема</label>
                    <input type="text" class="kp-card-theme" value="" placeholder="СПА-день">
                </div>
            </div>
            <div class="kp-g1-row2">
                <div class="kp-g1">
                    <label>Срок действия</label>
                    <input type="text" class="kp-card-validity" value="" placeholder="Действует 12 месяцев">
                </div>
                <div class="kp-g1">
                    <label>Код</label>
                    <input type="text" class="kp-card-code" value="" placeholder="№ KRP-0001">
                </div>
            </div>
            <div class="kp-g1">
                <label>Вариант оформления</label>
                <select class="kp-card-variant">
                    <option value="pine">Тёмный (pine)</option>
                    <option value="light">Светлый (light)</option>
                </select>
            </div>
        </div>
    </template>

    <div class="kp-g1-grp">Кнопка и примечание</div>
    <div class="kp-g1-row2">
        <div class="kp-g1">
            <label>Текст кнопки</label>
            <input type="text" name="kp_gift_one[cta_text]" value="<?php echo esc_attr($data['cta_text']); ?>" placeholder="Оформить сертификат">
        </div>
        <div class="kp-g1">
            <label>Ссылка кнопки</label>
            <input type="text" name="kp_gift_one[cta_link]" value="<?php echo esc_attr($data['cta_link']); ?>" placeholder="#contacts">
        </div>
    </div>
    <div class="kp-g1">
        <label>Примечание под кнопкой (HTML разрешён)</label>
        <textarea name="kp_gift_one[note]" rows="2"><?php echo esc_textarea($data['note']); ?></textarea>
    </div>

    <script>
    (function($) {
        $('.kp-add-card').on('click', function() {
            $('#kp-gift-one-cards-wrap').append($('#kp-g1-card-tpl').html());
        });
        $(document).on('click', '.kp-card-del', function() {
            $(this).closest('.kp-g1-card').remove();
        });

        $(document).on('submit', '#post', function() {
            var cards = [];
            $('#kp-gift-one-cards-wrap .kp-g1-card').each(function() {
                cards.push({
                    brand:      $(this).find('.kp-card-brand').val(),
                    brand_sub:   $(this).find('.kp-card-brand-sub').val(),
                    value:       $(this).find('.kp-card-value').val(),
                    theme:       $(this).find('.kp-card-theme').val(),
                    validity:    $(this).find('.kp-card-validity').val(),
                    code:        $(this).find('.kp-card-code').val(),
                    variant:     $(this).find('.kp-card-variant').val()
                });
            });
            $('<input>').attr({
                type: 'hidden',
                name: 'kp_gift_one_cards_json',
                value: JSON.stringify(cards)
            }).appendTo('#post');
        });
    })(jQuery);
    </script>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['kp_gift_one_nonce_field'])) return;
    if (!wp_verify_nonce($_POST['kp_gift_one_nonce_field'], 'kp_gift_one_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $raw = $_POST['kp_gift_one'] ?? array();

    $cards = array();
    if (isset($_POST['kp_gift_one_cards_json'])) {
        $c_raw = json_decode(stripslashes($_POST['kp_gift_one_cards_json']), true);
        if (is_array($c_raw)) {
            foreach ($c_raw as $c) {
                $brand    = isset($c['brand'])     ? sanitize_text_field($c['brand'])      : '';
                $brandSub = isset($c['brand_sub'])  ? wp_kses_post($c['brand_sub'])         : '';
                $value    = isset($c['value'])      ? sanitize_text_field($c['value'])      : '';
                $theme    = isset($c['theme'])      ? sanitize_text_field($c['theme'])      : '';
                $validity = isset($c['validity'])    ? sanitize_text_field($c['validity'])   : '';
                $code     = isset($c['code'])        ? sanitize_text_field($c['code'])       : '';
                $variant  = isset($c['variant'])      ? sanitize_text_field($c['variant'])    : 'pine';
                if ($brand || $value || $theme) {
                    $cards[] = array(
                        'brand'      => $brand,
                        'brand_sub'  => $brandSub,
                        'value'      => $value,
                        'theme'      => $theme,
                        'validity'   => $validity,
                        'code'       => $code,
                        'variant'    => $variant,
                    );
                }
            }
        }
    }

    $data = array(
        'eyebrow'  => sanitize_text_field($raw['eyebrow'] ?? ''),
        'title'    => wp_kses_post($raw['title'] ?? ''),
        'lead'     => wp_kses_post($raw['lead'] ?? ''),
        'cards'    => $cards,
        'cta_text' => sanitize_text_field($raw['cta_text'] ?? ''),
        'cta_link' => esc_url_raw($raw['cta_link'] ?? ''),
        'note'     => wp_kses_post($raw['note'] ?? ''),
    );

    update_post_meta($post_id, 'kp_gift_one', $data);
});