<?php
/**
 * Meta box: FAQ Section (spa_faq)
 */

/* ── Register ─────────────────────────────────────── */
add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'spa_faq_mb',
        'Секция: FAQ — Вопросы и ответы',
        'spa_faq_mb_callback',
        'page',
        'normal',
        'high'
    );
} );

/* ── Callback ────────────────────────────────────── */
function spa_faq_mb_callback( $post ) {

    wp_nonce_field( 'spa_faq_save', 'spa_faq_nonce' );

    $values = get_post_meta( $post->ID, 'spa_faq', true ) ?: [];

    $eyebrow       = $values['eyebrow']       ?? '';
    $title         = $values['title']         ?? '';
    $gold_text     = $values['gold_text']     ?? '';
    $subtitle      = $values['subtitle']       ?? '';
    $bg_image      = $values['bg_image']      ?? '';
    $section_id    = $values['section_id']    ?? 'faq';
    $btn_text      = $values['btn_text']      ?? '';
    $btn_link      = $values['btn_link']      ?? '';
    $items         = $values['items']         ?? [];

    ?>
    <style>
        #spa_faq_mb .field-group { margin-bottom: 14px; }
        #spa_faq_mb label { display: block; font-weight: 600; margin-bottom: 4px; }
        #spa_faq_mb input[type="text"],
        #spa_faq_mb input[type="url"],
        #spa_faq_mb textarea { width: 100%; }
        #spa_faq_mb .repeater-item { background: #fff; border: 1px solid #dcdcde; padding: 14px; margin-bottom: 10px; }
        #spa_faq_mb .faq-item-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        #spa_faq_mb .faq-item-head strong { font-size: 13px; color: #555; }
        #spa_faq_mb .remove-row { color: #b00; border: none; cursor: pointer; }
        #spa_faq_mb .add-row { margin-top: 8px; }
    </style>

    <div class="field-group">
        <label>Аннотация (eyebrow)</label>
        <input type="text" name="spa_faq[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>" />
    </div>

    <div class="field-group">
        <label>Заголовок (до gold-текста)</label>
        <input type="text" name="spa_faq[title]" value="<?php echo esc_attr( $title ); ?>" />
    </div>

    <div class="field-group">
        <label>Gold-текст (обёрнется в &lt;em&gt;)</label>
        <input type="text" name="spa_faq[gold_text]" value="<?php echo esc_attr( $gold_text ); ?>" />
    </div>

    <div class="field-group">
        <label>Подзаголовок</label>
        <textarea name="spa_faq[subtitle]" rows="2"><?php echo esc_textarea( $subtitle ); ?></textarea>
    </div>

    <div class="field-group">
        <label>Фоновое изображение</label>
        <input type="hidden" name="spa_faq[bg_image]" value="<?php echo esc_attr( $bg_image ); ?>" data-kp-media />
    </div>

    <div class="field-group">
        <label>ID секции (anchor)</label>
        <input type="text" name="spa_faq[section_id]" value="<?php echo esc_attr( $section_id ); ?>" />
    </div>

    <hr />

    <h4>Вопросы и ответы</h4>
    <div id="spa_faq_items_wrap">
    <?php if ( ! empty( $items ) && is_array( $items ) ) :
        foreach ( $items as $i => $item ) : ?>
        <div class="repeater-item">
            <div class="faq-item-head">
                <strong><?php echo esc_html( mb_substr( $item['question'] ?? '', 0, 40 ) ); ?>…</strong>
                <button type="button" class="remove-row button button-small">Удалить</button>
            </div>
            <div class="field-group">
                <label>Вопрос</label>
                <input type="text" name="spa_faq[items][<?php echo $i; ?>][question]" value="<?php echo esc_attr( $item['question'] ?? '' ); ?>" />
            </div>
            <div class="field-group">
                <label>Ответ</label>
                <textarea name="spa_faq[items][<?php echo $i; ?>][answer]" rows="4"><?php echo esc_textarea( $item['answer'] ?? '' ); ?></textarea>
            </div>
        </div>
    <?php endforeach;
    endif; ?>
    </div>

    <button type="button" class="add-row button" id="add_faq_item">+ Вопрос</button>

    <hr />

    <div class="field-group">
        <label>Текст кнопки</label>
        <input type="text" name="spa_faq[btn_text]" value="<?php echo esc_attr( $btn_text ); ?>" />
    </div>

    <div class="field-group">
        <label>Ссылка кнопки</label>
        <input type="text" name="spa_faq[btn_link]" value="<?php echo esc_attr( $btn_link ); ?>" />
    </div>

    <script>
    jQuery(function($){
        $('#add_faq_item').on('click', function(){
            var wrap = $('#spa_faq_items_wrap');
            var idx  = wrap.find('.repeater-item').length;
            var html =
                '<div class="repeater-item">' +
                    '<div class="faq-item-head">' +
                        '<strong>Новый вопрос</strong>' +
                        '<button type="button" class="remove-row button button-small">Удалить</button>' +
                    '</div>' +
                    '<div class="field-group">' +
                        '<label>Вопрос</label>' +
                        '<input type="text" name="spa_faq[items][' + idx + '][question]" value="" />' +
                    '</div>' +
                    '<div class="field-group">' +
                        '<label>Ответ</label>' +
                        '<textarea name="spa_faq[items][' + idx + '][answer]" rows="4"></textarea>' +
                    '</div>' +
                '</div>';
            wrap.append(html);
        });
        $('#spa_faq_items_wrap').on('click', '.remove-row', function(){
            if( confirm('Удалить этот вопрос?') ) jQuery(this).closest('.repeater-item').remove();
        });
    });
    </script>
    <?php
}

/* ── Save ────────────────────────────────────────── */
add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['spa_faq_nonce'] ) || ! wp_verify_nonce( $_POST['spa_faq_nonce'], 'spa_faq_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['spa_faq'] ?? [];

    $clean = [
        'eyebrow'    => sanitize_text_field( $raw['eyebrow']    ?? '' ),
        'title'      => sanitize_text_field( $raw['title']      ?? '' ),
        'gold_text'  => sanitize_text_field( $raw['gold_text']  ?? '' ),
        'subtitle'   => sanitize_textarea_field( $raw['subtitle'] ?? '' ),
        'bg_image'   => esc_url_raw( $raw['bg_image'] ?? '' ),
        'section_id' => sanitize_text_field( $raw['section_id'] ?? '' ),
        'btn_text'   => sanitize_text_field( $raw['btn_text']   ?? '' ),
        'btn_link'   => esc_url_raw( $raw['btn_link'] ?? '' ),
        'items'      => [],
    ];

    if ( ! empty( $raw['items'] ) && is_array( $raw['items'] ) ) {
        foreach ( $raw['items'] as $item ) {
            $q = sanitize_text_field( $item['question'] ?? '' );
            $a = sanitize_textarea_field( $item['answer'] ?? '' );
            if ( $q !== '' ) {
                $clean['items'][] = [ 'question' => $q, 'answer' => $a ];
            }
        }
    }

    update_post_meta( $post_id, 'spa_faq', $clean );
} );

function karpovka_home_faq_import_items() {
    return [
        [
            'question' => 'Как забронировать проживание?',
            'answer'   => 'Забронировать можно онлайн на сайте, по телефонам, указанным на сайте, или написав нам. После согласования условий бронь закрепляется задатком — минимум 50% от суммы, оплата вносится не позднее 3 часов с момента согласования. Аннуляция возможна не позднее чем за 7 календарных дней до заезда — с возвратом задатка.',
        ],
        [
            'question' => 'Во сколько заезд и выезд?',
            'answer'   => 'Заселение — с 14:00, выселение — до 12:00 (единый расчётный час).',
        ],
        [
            'question' => 'Можно ли приехать с питомцами?',
            'answer'   => 'Да, но только по согласованию с администрацией. Размещение возможно с собаками весом до 10 кг и ростом не более 45 см в холке, доплата — 1000 ₽ в сутки. Понадобится ветеринарная справка с прививками и переноска (клетка). Проживание с питомцем — только в отдельном доме; вход в ресторан и SPA с животными исключён.',
        ],
        [
            'question' => 'Есть ли Wi-Fi?',
            'answer'   => 'Да, бесплатный Wi-Fi доступен на всей территории комплекса — в домиках, номерах и общественных зонах. Можно спокойно работать удалённо.',
        ],
        [
            'question' => 'Предоставляются ли детские кроватки?',
            'answer'   => 'Да, детские кроватки предоставляются бесплатно по предварительному запросу. Сообщите о необходимости при бронировании — количество ограничено.',
        ],
        [
            'question' => 'Возможен ли ранний заезд или поздний выезд?',
            'answer'   => 'Ранний заезд до 14:00 и поздний выезд возможны при наличии свободных мест за дополнительную плату. Задержка выезда после расчётного часа оплачивается: почасово — до 6 часов, половина суток — от 6 до 12 часов, полные сутки — от 12 до 24 часов. Уточните у администратора заранее.',
        ],
        [
            'question' => 'Могут ли приехать к нам в Карповку на время наши друзья?',
            'answer'   => 'Да. Для гостей, желающих приехать на время к проживающим, действуют условия: 1000 ₽ с человека, пребывание до 6 часов. Находиться на территории допустимо до 22:00. Необходима регистрация на рецепции и копия паспорта, а также подпись в правилах парк-отеля.',
        ],
        [
            'question' => 'Сколько человек вмещает база (количество спальных мест)?',
            'answer'   => 'Количество спальных мест, включая диваны, — 103 человека.',
        ],
    ];
}

function karpovka_import_home_faq( $post_id ) {
    if ( 'index.php' !== get_page_template_slug( $post_id ) ) return false;
    if ( get_post_meta( $post_id, '_karpovka_faq_import_20260824', true ) ) return false;

    $faq = get_post_meta( $post_id, 'spa_faq', true );
    $faq = is_array( $faq ) ? $faq : [];
    $faq['items'] = isset( $faq['items'] ) && is_array( $faq['items'] ) ? array_values( $faq['items'] ) : [];

    $known = [];
    foreach ( $faq['items'] as $item ) {
        $known[ md5( ( $item['question'] ?? '' ) . '|' . ( $item['answer'] ?? '' ) ) ] = true;
    }

    foreach ( karpovka_home_faq_import_items() as $item ) {
        $key = md5( $item['question'] . '|' . $item['answer'] );
        if ( isset( $known[ $key ] ) ) continue;
        $faq['items'][] = $item;
        $known[ $key ] = true;
    }

    update_post_meta( $post_id, 'spa_faq', $faq );
    update_post_meta( $post_id, '_karpovka_faq_import_20260824', '1' );
    return true;
}

add_action( 'admin_init', function () {
    if ( ! isset( $_GET['post'] ) ) return;
    $post_id = absint( $_GET['post'] );
    if ( ! $post_id || ! current_user_can( 'edit_page', $post_id ) ) return;
    karpovka_import_home_faq( $post_id );
} );
