<?php
/**
 * Метабокс: Секция 1 — введение (изображение + текст)
 * Подключить: require __DIR__ . '/inc/loyalty_one.php';
 *
 * Заполняет секцию <section class="l-intro"> (файл /includes/loyalty-one.php).
 * Мета-ключ: kp_loyalty_one
 *
 * ИЗМЕНЕНИЕ: поле «Заголовок» теперь textarea (большое поле), HTML разрешён —
 * можно оборачивать отдельные слова в <em> для золотого курсива.
 * Поле «Текст» тоже поддерживает <em>.
 */

add_action('add_meta_boxes', function () {
    add_meta_box('kp_loyalty_one_box', 'Лояльность — Секция 1 (изображение + текст)', 'kp_loyalty_one_box_cb', 'page', 'normal', 'high');
});

function kp_loyalty_one_box_cb($post) {
    wp_nonce_field('kp_loyalty_one_nonce', 'kp_loyalty_one_nonce_field');

    $defaults = array(
        'image' => '',
        'title' => 'Теперь Вы можете получать <em>кешбэк от 3% до 10%</em> и оплачивать <em>баллами до 20%</em> от суммы оплаты',
        'text'  => 'Получите нашу бонусную карту и <em>1000 баллов</em> в подарок!<br><br>1 балл = 1 рублю.<br><br>Программа лояльности <em>накопительная</em> – чем больше сумма ваших оплат, тем выше ваш кешбэк и статус в программе лояльности.',
    );

    $data = get_post_meta($post->ID, 'kp_loyalty_one', true);
    if (!is_array($data)) $data = array();
    $data = array_merge($defaults, $data);
    ?>

    <style>
        .kp-l1 { margin-bottom: 12px; }
        .kp-l1 label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; }
        .kp-l1 input[type="text"], .kp-l1 textarea { width: 100%; padding: 7px 10px; box-sizing: border-box; }
        .kp-l1 textarea { min-height: 80px; resize: vertical; }
        .kp-l1 .kp-l1-title { min-height: 120px; font-size: 15px; line-height: 1.5; }
        .kp-hint { font-size: 12px; color: #646970; margin-top: 4px; }
        .kp-hint code { background: #f0f0f1; padding: 1px 4px; border-radius: 2px; font-size: 11px; }
    </style>

    <div class="kp-l1">
        <label>Изображение</label>
        <input type="hidden" name="kp_loyalty_one[image]" value="<?php echo esc_attr($data['image']); ?>" data-kp-media>
        <p class="kp-hint">Кликните на поле, чтобы выбрать изображение из медиабиблиотеки.</p>
    </div>

    <div class="kp-l1">
        <label>Заголовок (HTML разрешён — обёртывайте слова в <code>&lt;em&gt;...&lt;/em&gt;</code> для золотого курсива)</label>
        <textarea name="kp_loyalty_one[title]" class="kp-l1-title" rows="4" placeholder="Например: Теперь Вы можете получать &lt;em&gt;кешбэк от 3% до 10%&lt;/em&gt; и оплачивать &lt;em&gt;баллами до 20%&lt;/em&gt; от суммы оплаты"><?php echo esc_textarea($data['title']); ?></textarea>
        <p class="kp-hint">
            Большое поле — можно переносить строки и в разных местах выделять слова тегом <code>&lt;em&gt;слово&lt;/em&gt;</code>.
            Также работают: <code>&lt;strong&gt;</code>, <code>&lt;br&gt;</code>, <code>&lt;span style="color: ..."&gt;</code>.
        </p>
    </div>

    <div class="kp-l1">
        <label>Текст (HTML разрешён — <code>&lt;em&gt;слово&lt;/em&gt;</code> = золотой курсив, <code>&lt;br&gt;</code> = перенос)</label>
        <textarea name="kp_loyalty_one[text]" rows="6" placeholder="Получите нашу бонусную карту и &lt;em&gt;1000 баллов&lt;/em&gt; в подарок!&lt;br&gt;&lt;br&gt;1 балл = 1 рублю."><?php echo esc_textarea($data['text']); ?></textarea>
        <p class="kp-hint">
            Используйте <code>&lt;br&gt;</code> для переноса строки, <code>&lt;br&gt;&lt;br&gt;</code> для нового абзаца.
            Любое слово можно выделить: <code>&lt;em&gt;накопительная&lt;/em&gt;</code> — станет золотым курсивом.
        </p>
    </div>

    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['kp_loyalty_one_nonce_field'])) return;
    if (!wp_verify_nonce($_POST['kp_loyalty_one_nonce_field'], 'kp_loyalty_one_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $raw = $_POST['kp_loyalty_one'] ?? array();

    $data = array(
        'image' => esc_url_raw($raw['image'] ?? ''),
        /* ИЗМЕНЕНИЕ: title теперь сохраняется как HTML (разрешены <em>, <br>, <strong>) */
        'title' => wp_kses_post($raw['title'] ?? ''),
        'text'  => wp_kses_post($raw['text'] ?? ''),
    );

    update_post_meta($post_id, 'kp_loyalty_one', $data);
});