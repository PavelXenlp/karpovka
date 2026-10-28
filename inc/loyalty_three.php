<?php
/**
 * Метабокс: Секция 3 — правила программы (4 карточки)
 * Подключить: require __DIR__ . '/inc/loyalty_three.php';
 *
 * Заполняет секцию <section class="loyalty-three"> (файл /includes/loyalty-three.php).
 * Мета-ключ: kp_loyalty_three
 */

add_action('add_meta_boxes', function () {
    add_meta_box('kp_loyalty_three_box', 'Лояльность — Секция 3 (правила и условия)', 'kp_loyalty_three_box_cb', 'page', 'normal', 'high');
});

function kp_loyalty_three_box_cb($post) {
    wp_nonce_field('kp_loyalty_three_nonce', 'kp_loyalty_three_nonce_field');

    $defaults = array(
        'cards' => array(
            array(
                'title' => '«Рекомендуйте нас»',
                'text'  => '<p>Дарите нашу бонусную карту вашим друзьям и родным, нажав кнопку «Рекомендуйте нас» на нашей странице в UDS и получайте 3% баллам при каждой оплате друга! Больше рекомендаций – больше выгоды!</p>',
            ),
            array(
                'title' => 'День рождения',
                'text'  => '<p>В День рождения мы подарим вам электронный сертификат на 5000 бонусов на оплату жилого фонда, банкетного зала или беседки. Для этого проверьте, указана ли в вашем профиле дата рождения</p>',
            ),
            array(
                'title' => 'Бонусы начисляются, но НЕ списываются на:',
                'text'  => '<p>- акционные предложения</p><p>- на покупку подарочных сертификатов</p><p>- на покупку абонементов (на массаж, в бассейн, соляную комнату, аппаратные процедуры)</p>',
            ),
            array(
                'title' => 'Бонусы НЕ начисляются и НЕ списываются:',
                'text'  => '<p>- на покупки в минимаркете в зоне ресепшн</p><p>- на оплату в кафе</p><p>- на сумму предоплаты за проживание.</p>',
            ),
        ),
    );

    $data = get_post_meta($post->ID, 'kp_loyalty_three', true);
    if (!is_array($data)) $data = array();
    $data = array_merge($defaults, $data);
    if (!is_array($data['cards'] ?? array())) $data['cards'] = $defaults['cards'];
    ?>

    <style>
        .kp-l3 { margin-bottom: 12px; }
        .kp-l3 label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; }
        .kp-l3 input[type="text"], .kp-l3 textarea { width: 100%; padding: 7px 10px; box-sizing: border-box; }
        .kp-l3 textarea { min-height: 100px; resize: vertical; }
        .kp-hint { font-size: 12px; color: #646970; margin-top: 4px; }
        .kp-l3-grp { font-size: 14px; font-weight: 700; margin: 18px 0 8px; padding-bottom: 6px; border-bottom: 1px solid #ddd; }
        .kp-l3-grp:first-child { margin-top: 0; }
        .kp-l3-card { border: 1px solid #ddd; padding: 12px; margin-bottom: 10px; background: #fafafa; border-radius: 4px; position: relative; }
        .kp-l3-card .kp-card-del { position: absolute; top: 8px; right: 8px; color: #a00; cursor: pointer; font-size: 18px; font-weight: 700; }
        .kp-l3-card .kp-card-del:hover { color: #f00; }
        .kp-l3-card .kp-l3 { margin-bottom: 8px; }
    </style>

    <div class="kp-l3-grp">Карточки правил</div>
    <div id="kp-loyalty-three-cards-wrap">
        <?php foreach ($data['cards'] as $c) : ?>
        <div class="kp-l3-card">
            <span class="kp-card-del" title="Удалить">&times;</span>
            <div class="kp-l3">
                <label>Заголовок</label>
                <input type="text" class="kp-card-title" value="<?php echo esc_attr(!empty($c['title']) ? $c['title'] : ''); ?>" placeholder="«Рекомендуйте нас»">
            </div>
            <div class="kp-l3">
                <label>Текст (HTML: &lt;p&gt;...&lt;/p&gt; для каждого абзаца)</label>
                <textarea class="kp-card-text" rows="4" placeholder="<p>Текст правила...</p>"><?php echo esc_textarea(!empty($c['text']) ? $c['text'] : ''); ?></textarea>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button kp-add-card">+ Добавить карточку</button>

    <template id="kp-l3-card-tpl">
        <div class="kp-l3-card">
            <span class="kp-card-del" title="Удалить">&times;</span>
            <div class="kp-l3">
                <label>Заголовок</label>
                <input type="text" class="kp-card-title" value="" placeholder="«Рекомендуйте нас»">
            </div>
            <div class="kp-l3">
                <label>Текст (HTML)</label>
                <textarea class="kp-card-text" rows="4" placeholder="<p>Текст правила...</p>"></textarea>
            </div>
        </div>
    </template>

    <script>
    (function($) {
        $('.kp-add-card').on('click', function() {
            $('#kp-loyalty-three-cards-wrap').append($('#kp-l3-card-tpl').html());
        });
        $(document).on('click', '.kp-card-del', function() {
            $(this).closest('.kp-l3-card').remove();
        });

        $(document).on('submit', '#post', function() {
            var cards = [];
            $('#kp-loyalty-three-cards-wrap .kp-l3-card').each(function() {
                cards.push({
                    title: $(this).find('.kp-card-title').val(),
                    text:  $(this).find('.kp-card-text').val()
                });
            });
            $('<input>').attr({
                type: 'hidden',
                name: 'kp_loyalty_three_cards_json',
                value: JSON.stringify(cards)
            }).appendTo('#post');
        });
    })(jQuery);
    </script>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['kp_loyalty_three_nonce_field'])) return;
    if (!wp_verify_nonce($_POST['kp_loyalty_three_nonce_field'], 'kp_loyalty_three_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $cards = array();
    if (isset($_POST['kp_loyalty_three_cards_json'])) {
        $c_raw = json_decode(stripslashes($_POST['kp_loyalty_three_cards_json']), true);
        if (is_array($c_raw)) {
            foreach ($c_raw as $c) {
                $title = isset($c['title']) ? sanitize_text_field($c['title']) : '';
                $text  = isset($c['text'])  ? wp_kses_post($c['text']) : '';
                if ($title || $text) {
                    $cards[] = array('title' => $title, 'text' => $text);
                }
            }
        }
    }

    update_post_meta($post_id, 'kp_loyalty_three', array('cards' => $cards));
});