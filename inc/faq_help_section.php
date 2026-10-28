<?php
/**
 * Метабокс: Секция «Не нашли ответ?» (f-help)
 * Подключить: require __DIR__ . '/inc/faq_help_section.php';
 *
 * Заполняет секцию <section class="f-help"> (файл /includes/f-help.php).
 * Мета-ключ: kp_faq_help
 */

add_action('add_meta_boxes', function () {
    add_meta_box('kp_faq_help_box', 'Секция «Не нашли ответ?» (f-help)', 'kp_faq_help_box_cb', 'page', 'normal', 'high');
});

function kp_faq_help_box_cb($post) {
    wp_nonce_field('kp_faq_help_nonce', 'kp_faq_help_nonce_field');

    $defaults = array(
        'eyebrow'      => 'Не нашли ответ?',
        'title'        => 'Спросите нас <em>напрямую</em>',
        'lead'         => 'Если вопроса не оказалось в списке — напишите или позвоните. Поможем подобрать размещение, рассчитаем стоимость отдыха и ответим на всё, что важно узнать до поездки.',
        'phone_label'  => 'Позвонить',
        'phone_value'  => '+7 (985) 020-02-03',
        'phone_url'    => 'tel:+79850200203',
        'tg_label'     => 'Написать в Telegram',
        'tg_value'     => '@karpovka59',
        'tg_url'       => 'https://t.me/karpovka59',
        'email_label'  => 'Почта',
        'email_value'  => 'karpovka59@yandex.ru',
        'email_url'    => 'mailto:karpovka59@yandex.ru',
        'btn1_text'    => 'Задать вопрос в форме',
        'btn1_link'    => '#feedback',
        'btn2_text'    => 'Все контакты',
        'btn2_link'    => '/contacts.html',
    );

    $data = get_post_meta($post->ID, 'kp_faq_help', true);
    if (!is_array($data)) $data = array();
    $data = array_merge($defaults, $data);
    ?>

    <style>
        .kp-hf { margin-bottom: 12px; }
        .kp-hf label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; }
        .kp-hf input[type="text"], .kp-hf textarea { width: 100%; padding: 7px 10px; box-sizing: border-box; }
        .kp-hf textarea { min-height: 60px; resize: vertical; }
        .kp-hf-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .kp-hf-row3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; }
        .kp-hf-grp { font-size: 14px; font-weight: 700; margin: 18px 0 8px; padding-bottom: 6px; border-bottom: 1px solid #ddd; }
        .kp-hf-grp:first-child { margin-top: 0; }
    </style>

    <div class="kp-hf-grp">Заголовок секции</div>
    <div class="kp-hf-row2">
        <div class="kp-hf">
            <label>Надзаголовок (eyebrow)</label>
            <input type="text" name="kp_faq_help[eyebrow]" value="<?php echo esc_attr($data['eyebrow']); ?>">
        </div>
        <div class="kp-hf">
            <label>Заголовок (HTML: &lt;em&gt;...&lt;/em&gt;)</label>
            <input type="text" name="kp_faq_help[title]" value="<?php echo esc_attr($data['title']); ?>">
        </div>
    </div>
    <div class="kp-hf">
        <label>Подзаголовок (lead)</label>
        <textarea name="kp_faq_help[lead]" rows="2"><?php echo esc_textarea($data['lead']); ?></textarea>
    </div>

    <div class="kp-hf-grp">Способ связи 1 — телефон</div>
    <div class="kp-hf-row3">
        <div class="kp-hf"><label>Лейбл</label><input type="text" name="kp_faq_help[phone_label]" value="<?php echo esc_attr($data['phone_label']); ?>"></div>
        <div class="kp-hf"><label>Значение</label><input type="text" name="kp_faq_help[phone_value]" value="<?php echo esc_attr($data['phone_value']); ?>"></div>
        <div class="kp-hf"><label>Ссылка (tel:...)</label><input type="text" name="kp_faq_help[phone_url]" value="<?php echo esc_attr($data['phone_url']); ?>"></div>
    </div>

    <div class="kp-hf-grp">Способ связи 2 — Telegram</div>
    <div class="kp-hf-row3">
        <div class="kp-hf"><label>Лейбл</label><input type="text" name="kp_faq_help[tg_label]" value="<?php echo esc_attr($data['tg_label']); ?>"></div>
        <div class="kp-hf"><label>Значение</label><input type="text" name="kp_faq_help[tg_value]" value="<?php echo esc_attr($data['tg_value']); ?>"></div>
        <div class="kp-hf"><label>Ссылка (https://t.me/...)</label><input type="text" name="kp_faq_help[tg_url]" value="<?php echo esc_attr($data['tg_url']); ?>"></div>
    </div>

    <div class="kp-hf-grp">Способ связи 3 — почта</div>
    <div class="kp-hf-row3">
        <div class="kp-hf"><label>Лейбл</label><input type="text" name="kp_faq_help[email_label]" value="<?php echo esc_attr($data['email_label']); ?>"></div>
        <div class="kp-hf"><label>Значение</label><input type="text" name="kp_faq_help[email_value]" value="<?php echo esc_attr($data['email_value']); ?>"></div>
        <div class="kp-hf"><label>Ссылка (mailto:...)</label><input type="text" name="kp_faq_help[email_url]" value="<?php echo esc_attr($data['email_url']); ?>"></div>
    </div>

    <div class="kp-hf-grp">Кнопки действий</div>
    <div class="kp-hf-row2">
        <div class="kp-hf"><label>Кнопка 1 — текст</label><input type="text" name="kp_faq_help[btn1_text]" value="<?php echo esc_attr($data['btn1_text']); ?>"></div>
        <div class="kp-hf"><label>Кнопка 1 — ссылка</label><input type="text" name="kp_faq_help[btn1_link]" value="<?php echo esc_attr($data['btn1_link']); ?>"></div>
    </div>
    <div class="kp-hf-row2">
        <div class="kp-hf"><label>Кнопка 2 — текст</label><input type="text" name="kp_faq_help[btn2_text]" value="<?php echo esc_attr($data['btn2_text']); ?>"></div>
        <div class="kp-hf"><label>Кнопка 2 — ссылка</label><input type="text" name="kp_faq_help[btn2_link]" value="<?php echo esc_attr($data['btn2_link']); ?>"></div>
    </div>

    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['kp_faq_help_nonce_field'])) return;
    if (!wp_verify_nonce($_POST['kp_faq_help_nonce_field'], 'kp_faq_help_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $raw = $_POST['kp_faq_help'] ?? array();

    $data = array(
        'eyebrow'      => sanitize_text_field($raw['eyebrow'] ?? ''),
        'title'        => wp_kses_post($raw['title'] ?? ''),
        'lead'         => sanitize_textarea_field($raw['lead'] ?? ''),
        'phone_label'  => sanitize_text_field($raw['phone_label'] ?? ''),
        'phone_value'  => sanitize_text_field($raw['phone_value'] ?? ''),
        'phone_url'    => esc_url_raw($raw['phone_url'] ?? ''),
        'tg_label'     => sanitize_text_field($raw['tg_label'] ?? ''),
        'tg_value'     => sanitize_text_field($raw['tg_value'] ?? ''),
        'tg_url'       => esc_url_raw($raw['tg_url'] ?? ''),
        'email_label'  => sanitize_text_field($raw['email_label'] ?? ''),
        'email_value'  => sanitize_text_field($raw['email_value'] ?? ''),
        'email_url'    => esc_url_raw($raw['email_url'] ?? ''),
        'btn1_text'    => sanitize_text_field($raw['btn1_text'] ?? ''),
        'btn1_link'    => esc_url_raw($raw['btn1_link'] ?? ''),
        'btn2_text'    => sanitize_text_field($raw['btn2_text'] ?? ''),
        'btn2_link'    => esc_url_raw($raw['btn2_link'] ?? ''),
    );

    update_post_meta($post_id, 'kp_faq_help', $data);
});