<?php
/**
 * Метабокс: Секция 2 — система статусов (заголовок + 6 карточек)
 * Подключить: require __DIR__ . '/inc/loyalty_two.php';
 *
 * Заполняет секцию <section class="l-statuses"> (файл /includes/loyalty-two.php).
 * Мета-ключ: kp_loyalty_two
 *
 * ИЗМЕНЕНИЯ:
 *   1) Добавлено поле eyebrow (надзаголовок) над section_title
 *   2) Поле section_title теперь textarea с поддержкой HTML (<em> для золота)
 */

add_action('add_meta_boxes', function () {
    add_meta_box('kp_loyalty_two_box', 'Лояльность — Секция 2 (система статусов)', 'kp_loyalty_two_box_cb', 'page', 'normal', 'high');
});

function kp_loyalty_two_box_cb($post) {
    wp_nonce_field('kp_loyalty_two_nonce', 'kp_loyalty_two_nonce_field');

    $defaults = array(
        'eyebrow'       => '',
        'section_title' => 'Система <em>статусов</em>',
        'cards' => array(
            array('image' => '', 'title' => 'ДОБРО ПОЖАЛОВАТЬ',  'descr' => 'сумма оплат до 50 000 руб, кешбэк 3%, списание до 5%'),
            array('image' => '', 'title' => 'ДОРОГОЙ ГОСТЬ',    'descr' => 'сумма оплат от 50 000 до 200 000 руб, кешбэк 5%, списание до 7%'),
            array('image' => '', 'title' => 'ЛУЧШИЙ ДРУГ',       'descr' => 'сумма оплат от 200 000 до 400 000 руб, кешбэк 7%, списание до 10%'),
            array('image' => '', 'title' => 'ЛИДЕР ПАРК-ОТЕЛЯ «КАРПОВКА»', 'descr' => 'сумма оплат от 400 000 до 700 000 руб, кешбэк 8%, списание до 12%'),
            array('image' => '', 'title' => 'АМБАССАДОР ПАРК-ОТЕЛЯ «КАРПОВКА»', 'descr' => 'сумма свыше 700 000 руб, кешбэк 10%, списание до 20%'),
            array('image' => '', 'title' => 'ПОДАРОК',           'descr' => 'Приветственные 1 000 бонусов при регистрации'),
        ),
    );

    $data = get_post_meta($post->ID, 'kp_loyalty_two', true);
    if (!is_array($data)) $data = array();
    $data = array_merge($defaults, $data);
    if (!is_array($data['cards'] ?? array())) $data['cards'] = $defaults['cards'];
    ?>

    <style>
        .kp-l2 { margin-bottom: 12px; }
        .kp-l2 label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; }
        .kp-l2 input[type="text"], .kp-l2 textarea { width: 100%; padding: 7px 10px; box-sizing: border-box; }
        .kp-l2 textarea { min-height: 60px; resize: vertical; }
        .kp-hint { font-size: 12px; color: #646970; margin-top: 4px; }
        .kp-hint code { background: #f0f0f1; padding: 1px 4px; border-radius: 2px; font-size: 11px; }
        .kp-l2-grp { font-size: 14px; font-weight: 700; margin: 18px 0 8px; padding-bottom: 6px; border-bottom: 1px solid #ddd; }
        .kp-l2-grp:first-child { margin-top: 0; }
        .kp-l2-card { border: 1px solid #ddd; padding: 12px; margin-bottom: 10px; background: #fafafa; border-radius: 4px; position: relative; }
        .kp-l2-card .kp-card-del { position: absolute; top: 8px; right: 8px; color: #a00; cursor: pointer; font-size: 18px; font-weight: 700; }
        .kp-l2-card .kp-card-del:hover { color: #f00; }
        .kp-l2-card .kp-l2 { margin-bottom: 8px; }
    </style>

    <div class="kp-l2-grp">Заголовок секции</div>

    <!-- НОВОЕ: Надзаголовок (eyebrow) — маленькая золотая надпись над основным заголовком -->
    <div class="kp-l2">
        <label>Надзаголовок (eyebrow) — необязательно</label>
        <input type="text" name="kp_loyalty_two[eyebrow]" value="<?php echo esc_attr($data['eyebrow']); ?>" placeholder="Например: ПРОГРАММА ЛОЯЛЬНОСТИ">
        <p class="kp-hint">Маленькая uppercase-надпись золотом над основным заголовком. Можно оставить пустым.</p>
    </div>

    <!-- ИЗМЕНЕНО: section_title теперь textarea с поддержкой HTML -->
    <div class="kp-l2">
        <label>Заголовок (HTML разрешён — обёртывайте слова в <code>&lt;em&gt;...&lt;/em&gt;</code> для золотого курсива)</label>
        <textarea name="kp_loyalty_two[section_title]" rows="2" placeholder="Например: Система &lt;em&gt;статусов&lt;/em&gt;"><?php echo esc_textarea($data['section_title']); ?></textarea>
        <p class="kp-hint">Любое слово можно выделить: <code>&lt;em&gt;статусов&lt;/em&gt;</code> — станет золотым курсивом.</p>
    </div>

    <div class="kp-l2-grp">Карточки статусов</div>
    <div id="kp-loyalty-two-cards-wrap">
        <?php foreach ($data['cards'] as $c) : ?>
        <div class="kp-l2-card">
            <span class="kp-card-del" title="Удалить">&times;</span>
            <div class="kp-l2">
                <label>Изображение (иконка)</label>
                <input type="hidden" class="kp-card-image" value="<?php echo esc_attr(!empty($c['image']) ? $c['image'] : ''); ?>" data-kp-media>
            </div>
            <div class="kp-l2">
                <label>Название статуса</label>
                <input type="text" class="kp-card-title" value="<?php echo esc_attr(!empty($c['title']) ? $c['title'] : ''); ?>" placeholder="ДОБРО ПОЖАЛОВАТЬ">
            </div>
            <div class="kp-l2">
                <label>Описание</label>
                <input type="text" class="kp-card-descr" value="<?php echo esc_attr(!empty($c['descr']) ? $c['descr'] : ''); ?>" placeholder="сумма оплат до 50 000 руб, кешбэк 3%, списание до 5%">
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button kp-add-card">+ Добавить карточку</button>

    <template id="kp-card-tpl">
        <div class="kp-l2-card">
            <span class="kp-card-del" title="Удалить">&times;</span>
            <div class="kp-l2">
                <label>Изображение (иконка)</label>
                <input type="hidden" class="kp-card-image" value="" data-kp-media>
            </div>
            <div class="kp-l2">
                <label>Название статуса</label>
                <input type="text" class="kp-card-title" value="" placeholder="ДОБРО ПОЖАЛОВАТЬ">
            </div>
            <div class="kp-l2">
                <label>Описание</label>
                <input type="text" class="kp-card-descr" value="" placeholder="сумма оплат до 50 000 руб, кешбэк 3%, списание до 5%">
            </div>
        </div>
    </template>

    <script>
    (function($) {
        $('.kp-add-card').on('click', function() {
            $('#kp-loyalty-two-cards-wrap').append($('#kp-card-tpl').html());
        });
        $(document).on('click', '.kp-card-del', function() {
            $(this).closest('.kp-l2-card').remove();
        });

        $(document).on('submit', '#post', function() {
            var cards = [];
            $('#kp-loyalty-two-cards-wrap .kp-l2-card').each(function() {
                cards.push({
                    image: $(this).find('.kp-card-image').val(),
                    title: $(this).find('.kp-card-title').val(),
                    descr: $(this).find('.kp-card-descr').val()
                });
            });
            $('<input>').attr({
                type: 'hidden',
                name: 'kp_loyalty_two_cards_json',
                value: JSON.stringify(cards)
            }).appendTo('#post');
        });
    })(jQuery);
    </script>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['kp_loyalty_two_nonce_field'])) return;
    if (!wp_verify_nonce($_POST['kp_loyalty_two_nonce_field'], 'kp_loyalty_two_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $raw = $_POST['kp_loyalty_two'] ?? array();

    $cards = array();
    if (isset($_POST['kp_loyalty_two_cards_json'])) {
        $c_raw = json_decode(stripslashes($_POST['kp_loyalty_two_cards_json']), true);
        if (is_array($c_raw)) {
            foreach ($c_raw as $c) {
                $img   = isset($c['image']) ? esc_url_raw($c['image']) : '';
                $title = isset($c['title']) ? sanitize_text_field($c['title']) : '';
                $descr = isset($c['descr']) ? sanitize_text_field($c['descr']) : '';
                if ($img || $title || $descr) {
                    $cards[] = array('image' => $img, 'title' => $title, 'descr' => $descr);
                }
            }
        }
    }

    $data = array(
        /* НОВОЕ: сохраняем eyebrow */
        'eyebrow'       => sanitize_text_field($raw['eyebrow'] ?? ''),
        /* ИЗМЕНЕНО: section_title теперь сохраняется как HTML (разрешены <em>, <strong>, <br>) */
        'section_title' => wp_kses_post($raw['section_title'] ?? ''),
        'cards'         => $cards,
    );

    update_post_meta($post_id, 'kp_loyalty_two', $data);
});