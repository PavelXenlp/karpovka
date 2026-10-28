<?php
/**
 * Метабокс: Секция 2 — Бонусная карта UDS
 * Подключить: require __DIR__ . '/inc/gift_two.php';
 *
 * Заполняет секцию <section class="uds-section"> (файл /includes/gift-two.php).
 * Мета-ключ: kp_gift_two
 */

add_action('add_meta_boxes', function () {
    add_meta_box('kp_gift_two_box', 'Сертификаты — Секция 2 (бонусная карта UDS)', 'kp_gift_two_box_cb', 'page', 'normal', 'high');
});

function kp_gift_two_box_cb($post) {
    wp_nonce_field('kp_gift_two_nonce', 'kp_gift_two_nonce_field');

    $defaults = array(
        'eyebrow' => '',
        'title'   => 'Бонусная карта в приложении <em>UDS</em>',
        'text'    => 'Отсканируйте QR-код камерой телефона, установите приложение UDS и активируйте бонусную карту «Карповки». Копите баллы за каждое посещение и оплачивайте ими до части счёта.',
        'steps'   => array(
            array('text' => 'Наведите камеру телефона на QR-код'),
            array('text' => 'Установите приложение UDS'),
            array('text' => 'Активируйте бонусную карту и копите баллы'),
        ),
        'logo'    => '',
        'qr'      => '',
    );

    $data = get_post_meta($post->ID, 'kp_gift_two', true);
    if (!is_array($data)) $data = array();
    $data = array_merge($defaults, $data);
    if (!is_array($data['steps'] ?? array())) $data['steps'] = $defaults['steps'];
    ?>

    <style>
        .kp-g2 { margin-bottom: 12px; }
        .kp-g2 label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; }
        .kp-g2 input[type="text"], .kp-g2 textarea { width: 100%; padding: 7px 10px; box-sizing: border-box; }
        .kp-g2 textarea { min-height: 60px; resize: vertical; }
        .kp-hint { font-size: 12px; color: #646970; margin-top: 4px; }
        .kp-hint code { background: #f0f0f1; padding: 1px 4px; border-radius: 2px; font-size: 11px; }
        .kp-g2-grp { font-size: 14px; font-weight: 700; margin: 18px 0 8px; padding-bottom: 6px; border-bottom: 1px solid #ddd; }
        .kp-g2-grp:first-child { margin-top: 0; }
        .kp-g2-step { display: flex; gap: 8px; align-items: center; margin-bottom: 8px; }
        .kp-g2-step input { flex: 1; padding: 8px 10px; }
        .kp-rm { color: #a00; cursor: pointer; font-size: 18px; font-weight: 700; flex-shrink: 0; }
        .kp-rm:hover { color: #f00; }
    </style>

    <div class="kp-g2-grp">Заголовок и текст</div>

    <div class="kp-g2">
        <label>Надзаголовок (eyebrow) — необязательно</label>
        <input type="text" name="kp_gift_two[eyebrow]" value="<?php echo esc_attr($data['eyebrow']); ?>" placeholder="Бонусная программа">
    </div>

    <div class="kp-g2">
        <label>Заголовок (HTML: <code>&lt;em&gt;слово&lt;/em&gt;</code> = золотой курсив)</label>
        <textarea name="kp_gift_two[title]" rows="2" placeholder="Бонусная карта в приложении &lt;em&gt;UDS&lt;/em&gt;"><?php echo esc_textarea($data['title']); ?></textarea>
    </div>

    <div class="kp-g2">
        <label>Текст (HTML разрешён)</label>
        <textarea name="kp_gift_two[text]" rows="4"><?php echo esc_textarea($data['text']); ?></textarea>
    </div>

    <div class="kp-g2-grp">Шаги (список с галочками)</div>
    <div id="kp-gift-two-steps-wrap">
        <?php foreach ($data['steps'] as $s) : ?>
        <div class="kp-g2-step">
            <input type="text" class="kp-step-text" value="<?php echo esc_attr(!empty($s['text']) ? $s['text'] : ''); ?>" placeholder="Наведите камеру телефона на QR-код">
            <span class="kp-rm kp-step-del" title="Удалить">&times;</span>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button kp-add-step">+ Добавить шаг</button>

    <template id="kp-g2-step-tpl">
        <div class="kp-g2-step">
            <input type="text" class="kp-step-text" value="" placeholder="Наведите камеру телефона на QR-код">
            <span class="kp-rm kp-step-del" title="Удалить">&times;</span>
        </div>
    </template>

    <div class="kp-g2-grp">Изображения</div>
    <div class="kp-g2">
        <label>Логотип UDS</label>
        <input type="hidden" name="kp_gift_two[logo]" value="<?php echo esc_attr($data['logo']); ?>" data-kp-media>
    </div>
    <div class="kp-g2">
        <label>QR-код</label>
        <input type="hidden" name="kp_gift_two[qr]" value="<?php echo esc_attr($data['qr']); ?>" data-kp-media>
    </div>

    <script>
    (function($) {
        $('.kp-add-step').on('click', function() {
            $('#kp-gift-two-steps-wrap').append($('#kp-g2-step-tpl').html());
        });
        $(document).on('click', '.kp-step-del', function() {
            $(this).closest('.kp-g2-step').remove();
        });

        $(document).on('submit', '#post', function() {
            var steps = [];
            $('#kp-gift-two-steps-wrap .kp-g2-step').each(function() {
                var t = $(this).find('.kp-step-text').val();
                if (t) steps.push({ text: t });
            });
            $('<input>').attr({
                type: 'hidden',
                name: 'kp_gift_two_steps_json',
                value: JSON.stringify(steps)
            }).appendTo('#post');
        });
    })(jQuery);
    </script>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['kp_gift_two_nonce_field'])) return;
    if (!wp_verify_nonce($_POST['kp_gift_two_nonce_field'], 'kp_gift_two_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $raw = $_POST['kp_gift_two'] ?? array();

    $steps = array();
    if (isset($_POST['kp_gift_two_steps_json'])) {
        $s_raw = json_decode(stripslashes($_POST['kp_gift_two_steps_json']), true);
        if (is_array($s_raw)) {
            foreach ($s_raw as $s) {
                $t = isset($s['text']) ? sanitize_text_field($s['text']) : '';
                if ($t) $steps[] = array('text' => $t);
            }
        }
    }

    $data = array(
        'eyebrow' => sanitize_text_field($raw['eyebrow'] ?? ''),
        'title'   => wp_kses_post($raw['title'] ?? ''),
        'text'    => wp_kses_post($raw['text'] ?? ''),
        'steps'   => $steps,
        'logo'    => esc_url_raw($raw['logo'] ?? ''),
        'qr'      => esc_url_raw($raw['qr'] ?? ''),
    );

    update_post_meta($post_id, 'kp_gift_two', $data);
});