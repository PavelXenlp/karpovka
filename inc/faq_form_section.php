<?php
/**
 * Метабокс: Форма обратной связи (f-form-section)
 * Подключить: require __DIR__ . '/inc/faq_form_section.php';
 *
 * Заполняет секцию <section class="f-form-section"> (файл /includes/f-form-section.php).
 * Мета-ключ: kp_faq_form
 *
 * ИЗМЕНЕНИЕ: добавлено поле bg_img (фоновая картинка) с пикером медиабиблиотеки.
 */

add_action('add_meta_boxes', function () {
    add_meta_box('kp_faq_form_box', 'Форма обратной связи (f-form-section)', 'kp_faq_form_box_cb', 'page', 'normal', 'high');
});

function kp_faq_form_box_cb($post) {
    wp_nonce_field('kp_faq_form_nonce', 'kp_faq_form_nonce_field');

    $defaults = array(
        'bg_img'              => '',
        'eyebrow'             => 'Обратная связь',
        'title'               => 'Остались <em>вопросы</em>?',
        'subtitle'            => 'Оставьте контакты и коротко опишите вопрос — перезвоним, ответим и поможем с бронированием.',
        'name_label'          => 'Ваше имя',
        'name_placeholder'    => 'Как к вам обращаться',
        'phone_label'         => 'Телефон',
        'phone_placeholder'   => '+7 (___) ___-__-__',
        'topic_label'         => 'Тема вопроса',
        'message_label'       => 'Вопрос',
        'message_placeholder' => 'Расскажите, что хотите узнать...',
        'submit_text'         => 'Отправить вопрос',
        'note'                => 'Нажимая кнопку, вы соглашаетесь с <a href="#">политикой конфиденциальности</a>',
        'topics'              => array(
            array('value' => 'booking',  'label' => 'Бронирование и оплата'),
            array('value' => 'stay',    'label' => 'Проживание'),
            array('value' => 'spa',     'label' => 'СПА-центр'),
            array('value' => 'bath',    'label' => 'Баня'),
            array('value' => 'activity','label' => 'Активный отдых'),
            array('value' => 'events',  'label' => 'Мероприятия'),
            array('value' => 'kids',    'label' => 'Дети и питомцы'),
            array('value' => 'other',   'label' => 'Другое'),
        ),
    );

    $data = get_post_meta($post->ID, 'kp_faq_form', true);
    if (!is_array($data)) $data = array();
    $data = array_merge($defaults, $data);
    if (!is_array($data['topics'] ?? array())) $data['topics'] = $defaults['topics'];
    ?>

    <style>
        .kp-ff { margin-bottom: 12px; }
        .kp-ff label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; }
        .kp-ff input[type="text"], .kp-ff textarea { width: 100%; padding: 7px 10px; box-sizing: border-box; }
        .kp-ff textarea { min-height: 60px; resize: vertical; }
        .kp-ff-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .kp-ff-grp { font-size: 14px; font-weight: 700; margin: 18px 0 8px; padding-bottom: 6px; border-bottom: 1px solid #ddd; }
        .kp-ff-grp:first-child { margin-top: 0; }
        .kp-topic-row { display: flex; gap: 8px; align-items: center; margin-bottom: 8px; }
        .kp-topic-row input { flex: 1; padding: 8px 10px; }
        .kp-rm { color: #a00; cursor: pointer; font-size: 18px; font-weight: 700; flex-shrink: 0; }
        .kp-rm:hover { color: #f00; }
    </style>

    <div class="kp-ff-grp">Фон секции</div>
    <div class="kp-ff">
        <label>Фоновая картинка</label>
        <input type="hidden" name="kp_faq_form[bg_img]" value="<?php echo esc_attr($data['bg_img']); ?>" data-kp-media>
        <p style="font-size:12px; color:#646970; margin-top:4px;">Кликните на поле, чтобы выбрать изображение из медиабиблиотеки. Если не задано — используется картинка по умолчанию из CSS.</p>
    </div>

    <div class="kp-ff-grp">Заголовок секции</div>
    <div class="kp-ff-row2">
        <div class="kp-ff"><label>Надзаголовок (eyebrow)</label><input type="text" name="kp_faq_form[eyebrow]" value="<?php echo esc_attr($data['eyebrow']); ?>"></div>
        <div class="kp-ff"><label>Заголовок (HTML: &lt;em&gt;...&lt;/em&gt;)</label><input type="text" name="kp_faq_form[title]" value="<?php echo esc_attr($data['title']); ?>"></div>
    </div>
    <div class="kp-ff">
        <label>Подзаголовок</label>
        <textarea name="kp_faq_form[subtitle]" rows="2"><?php echo esc_textarea($data['subtitle']); ?></textarea>
    </div>

    <div class="kp-ff-grp">Поля «Имя» и «Телефон»</div>
    <div class="kp-ff-row2">
        <div class="kp-ff"><label>Лейбл «Имя»</label><input type="text" name="kp_faq_form[name_label]" value="<?php echo esc_attr($data['name_label']); ?>"></div>
        <div class="kp-ff"><label>Placeholder «Имя»</label><input type="text" name="kp_faq_form[name_placeholder]" value="<?php echo esc_attr($data['name_placeholder']); ?>"></div>
    </div>
    <div class="kp-ff-row2">
        <div class="kp-ff"><label>Лейбл «Телефон»</label><input type="text" name="kp_faq_form[phone_label]" value="<?php echo esc_attr($data['phone_label']); ?>"></div>
        <div class="kp-ff"><label>Placeholder «Телефон»</label><input type="text" name="kp_faq_form[phone_placeholder]" value="<?php echo esc_attr($data['phone_placeholder']); ?>"></div>
    </div>

    <div class="kp-ff-grp">Поля «Тема» и «Вопрос»</div>
    <div class="kp-ff-row2">
        <div class="kp-ff"><label>Лейбл «Тема»</label><input type="text" name="kp_faq_form[topic_label]" value="<?php echo esc_attr($data['topic_label']); ?>"></div>
        <div class="kp-ff"><label>Лейбл «Вопрос»</label><input type="text" name="kp_faq_form[message_label]" value="<?php echo esc_attr($data['message_label']); ?>"></div>
    </div>
    <div class="kp-ff">
        <label>Placeholder «Вопрос»</label>
        <input type="text" name="kp_faq_form[message_placeholder]" value="<?php echo esc_attr($data['message_placeholder']); ?>">
    </div>

    <div class="kp-ff-grp">Темы вопроса (выпадающий список)</div>
    <div id="kp-faq-topics-wrap">
        <?php foreach ($data['topics'] as $t) : ?>
        <div class="kp-topic-row">
            <input type="text" class="kp-topic-value" value="<?php echo esc_attr(!empty($t['value']) ? $t['value'] : ''); ?>" placeholder="value (booking)">
            <input type="text" class="kp-topic-label" value="<?php echo esc_attr(!empty($t['label']) ? $t['label'] : ''); ?>" placeholder="label (Бронирование и оплата)">
            <span class="kp-rm kp-topic-del" title="Удалить">&times;</span>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button kp-add-topic">+ Добавить тему</button>

    <template id="kp-topic-tpl">
        <div class="kp-topic-row">
            <input type="text" class="kp-topic-value" value="" placeholder="value (booking)">
            <input type="text" class="kp-topic-label" value="" placeholder="label (Бронирование и оплата)">
            <span class="kp-rm kp-topic-del" title="Удалить">&times;</span>
        </div>
    </template>

    <div class="kp-ff-grp">Кнопка и примечание</div>
    <div class="kp-ff">
        <label>Текст кнопки отправки</label>
        <input type="text" name="kp_faq_form[submit_text]" value="<?php echo esc_attr($data['submit_text']); ?>">
    </div>
    <div class="kp-ff">
        <label>Примечание (HTML разрешён: &lt;a href="#"&gt;...&lt;/a&gt;)</label>
        <textarea name="kp_faq_form[note]" rows="2"><?php echo esc_textarea($data['note']); ?></textarea>
    </div>

    <script>
    (function($) {
        $('.kp-add-topic').on('click', function() {
            $('#kp-faq-topics-wrap').append($('#kp-topic-tpl').html());
        });
        $(document).on('click', '.kp-topic-del', function() {
            $(this).closest('.kp-topic-row').remove();
        });

        $(document).on('submit', '#post', function() {
            var topics = [];
            $('#kp-faq-topics-wrap .kp-topic-row').each(function() {
                var v = $(this).find('.kp-topic-value').val();
                var l = $(this).find('.kp-topic-label').val();
                if (v || l) topics.push({ value: v, label: l });
            });
            $('<input>').attr({
                type: 'hidden',
                name: 'kp_faq_form_topics_json',
                value: JSON.stringify(topics)
            }).appendTo('#post');
        });
    })(jQuery);
    </script>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['kp_faq_form_nonce_field'])) return;
    if (!wp_verify_nonce($_POST['kp_faq_form_nonce_field'], 'kp_faq_form_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $raw = $_POST['kp_faq_form'] ?? array();

    $topics = array();
    if (isset($_POST['kp_faq_form_topics_json'])) {
        $t_raw = json_decode(stripslashes($_POST['kp_faq_form_topics_json']), true);
        if (is_array($t_raw)) {
            foreach ($t_raw as $t) {
                $v = isset($t['value']) ? sanitize_text_field($t['value']) : '';
                $l = isset($t['label']) ? sanitize_text_field($t['label']) : '';
                if ($v || $l) $topics[] = array('value' => $v, 'label' => $l);
            }
        }
    }

    $data = array(
        'bg_img'              => esc_url_raw($raw['bg_img'] ?? ''),
        'eyebrow'             => sanitize_text_field($raw['eyebrow'] ?? ''),
        'title'               => wp_kses_post($raw['title'] ?? ''),
        'subtitle'            => sanitize_textarea_field($raw['subtitle'] ?? ''),
        'name_label'          => sanitize_text_field($raw['name_label'] ?? ''),
        'name_placeholder'    => sanitize_text_field($raw['name_placeholder'] ?? ''),
        'phone_label'         => sanitize_text_field($raw['phone_label'] ?? ''),
        'phone_placeholder'   => sanitize_text_field($raw['phone_placeholder'] ?? ''),
        'topic_label'         => sanitize_text_field($raw['topic_label'] ?? ''),
        'message_label'       => sanitize_text_field($raw['message_label'] ?? ''),
        'message_placeholder' => sanitize_text_field($raw['message_placeholder'] ?? ''),
        'submit_text'         => sanitize_text_field($raw['submit_text'] ?? ''),
        'note'                => wp_kses_post($raw['note'] ?? ''),
        'topics'              => $topics,
    );

    update_post_meta($post_id, 'kp_faq_form', $data);
});