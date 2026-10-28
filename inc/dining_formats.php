<?php
/**
 * Meta box: Dining Formats — форматы обслуживания (кафе, банкеты, мероприятия)
 * Подключить: require __DIR__ . '/inc/dining_formats.php';
 *
 * Каждый формат = карточка + всплывающая модалка с подробным описанием.
 * Мета-ключ: dining_formats
 */

add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'dining_formats_mb',
        'Форматы обслуживания (кафе, банкеты, мероприятия)',
        'dining_formats_mb_callback',
        'page',
        'normal',
        'high'
    );
} );

function dining_formats_mb_callback( $post ) {
    wp_nonce_field( 'dining_formats_save', 'dining_formats_nonce' );

    $v = get_post_meta( $post->ID, 'dining_formats', true ) ?: [];

    $eyebrow    = $v['eyebrow']    ?? '';
    $title      = $v['title']      ?? '';
    $gold_text  = $v['gold_text']  ?? '';
    $subtitle   = $v['subtitle']   ?? '';
    $section_id = $v['section_id'] ?? 'formats';
    $cards      = $v['cards']      ?? [];
    ?>

    <style>
        #dining_formats_mb .field-group { margin-bottom: 14px; }
        #dining_formats_mb label { display: block; font-weight: 600; margin-bottom: 4px; }
        #dining_formats_mb input[type="text"],
        #dining_formats_mb textarea,
        #dining_formats_mb select { width: 100%; }
        #dining_formats_mb .df-card { background: #fff; border: 1px solid #dcdcde; padding: 16px; margin-bottom: 16px; border-radius: 4px; }
        #dining_formats_mb .df-card .head-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        #dining_formats_mb .df-card .head-row strong { font-size: 13px; color: #555; }
        #dining_formats_mb .remove-card { color: #b00; border: none; cursor: pointer; }
        #dining_formats_mb .sub-label { display: block; font-weight: 700; margin: 14px 0 6px; font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: .5px; }
        #dining_formats_mb details { margin: 10px 0; padding: 12px; border: 1px solid #dcdcde; background: #fafafa; border-radius: 4px; }
        #dining_formats_mb details summary { cursor: pointer; font-weight: 600; }
        #dining_formats_mb .meta-row { display: flex; gap: 10px; }
        #dining_formats_mb .meta-row .field-group { flex: 1; }
    </style>

    <!-- Шапка секции -->
    <div class="field-group">
        <label>Аннотация (eyebrow)</label>
        <input type="text" name="dining_formats[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>" />
    </div>
    <div class="field-group">
        <label>Заголовок (до gold-текста)</label>
        <input type="text" name="dining_formats[title]" value="<?php echo esc_attr( $title ); ?>" />
    </div>
    <div class="field-group">
        <label>Gold-текст (обёрнется в &lt;em&gt;)</label>
        <input type="text" name="dining_formats[gold_text]" value="<?php echo esc_attr( $gold_text ); ?>" />
    </div>
    <div class="field-group">
        <label>Подзаголовок</label>
        <textarea name="dining_formats[subtitle]" rows="2"><?php echo esc_textarea( $subtitle ); ?></textarea>
    </div>
    <div class="field-group">
        <label>ID секции (anchor)</label>
        <input type="text" name="dining_formats[section_id]" value="<?php echo esc_attr( $section_id ); ?>" />
    </div>

    <hr />
    <h4>Форматы обслуживания</h4>

    <div id="df_cards_wrap">
    <?php if ( ! empty( $cards ) && is_array( $cards ) ) :
        foreach ( $cards as $i => $c ) : ?>
        <div class="df-card">
            <div class="head-row">
                <strong><?php echo esc_html( mb_substr( $c['card_title'] ?? '', 0, 30 ) ); ?></strong>
                <button type="button" class="remove-card button button-small">Удалить</button>
            </div>

            <span class="sub-label">Карточка (видна на странице)</span>

            <div class="field-group">
                <label>Изображение карточки</label>
                <input type="hidden" name="dining_formats[cards][<?php echo $i; ?>][card_image]" class="df-card-img" value="<?php echo esc_attr( $c['card_image'] ?? '' ); ?>" data-kp-media />
            </div>

            <div class="field-group">
                <label>Тег (например: Банкет, Свадьба, Корпоратив)</label>
                <input type="text" name="dining_formats[cards][<?php echo $i; ?>][card_tag]" value="<?php echo esc_attr( $c['card_tag'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Название формата</label>
                <input type="text" name="dining_formats[cards][<?php echo $i; ?>][card_title]" value="<?php echo esc_attr( $c['card_title'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Короткое описание (для карточки)</label>
                <input type="text" name="dining_formats[cards][<?php echo $i; ?>][card_desc]" value="<?php echo esc_attr( $c['card_desc'] ?? '' ); ?>" />
            </div>

            <details>
                <summary>Подробное модальное окно</summary>

                <div class="field-group">
                    <label>Фото в модалке (можно другое, не как в карточке)</label>
                    <input type="hidden" name="dining_formats[cards][<?php echo $i; ?>][modal_image]" class="df-modal-img" value="<?php echo esc_attr( $c['modal_image'] ?? '' ); ?>" data-kp-media />
                </div>

                <div class="field-group">
                    <label>Бейдж в модалке</label>
                    <input type="text" name="dining_formats[cards][<?php echo $i; ?>][modal_badge]" value="<?php echo esc_attr( $c['modal_badge'] ?? '' ); ?>" placeholder="Хит / Рекомендуем" />
                </div>

                <div class="field-group">
                    <label>Заголовок в модалке (если пусто — возьмётся из названия карточки)</label>
                    <input type="text" name="dining_formats[cards][<?php echo $i; ?>][modal_title]" value="<?php echo esc_attr( $c['modal_title'] ?? '' ); ?>" />
                </div>

                <div class="field-group">
                    <label>Метаданные в модалке</label>
                    <input type="text" name="dining_formats[cards][<?php echo $i; ?>][modal_meta]" value="<?php echo esc_attr( $c['modal_meta'] ?? '' ); ?>" placeholder="Продолжительность: 4 часа · До 50 гостей" />
                </div>

                <div class="field-group">
                    <label>Описание в модалке (HTML разрешён, &lt;p&gt; для абзацев)</label>
                    <textarea name="dining_formats[cards][<?php echo $i; ?>][modal_desc]" rows="5"><?php echo esc_textarea( $c['modal_desc'] ?? '' ); ?></textarea>
                </div>

                <div class="field-group">
                    <label>Что входит — один пункт на строку</label>
                    <textarea name="dining_formats[cards][<?php echo $i; ?>][modal_items_text]" rows="7"><?php echo esc_textarea( implode( "\n", array_map( static function ( $item ) { return is_array( $item ) ? ( $item['text'] ?? '' ) : $item; }, $c['modal_items'] ?? [] ) ) ); ?></textarea>
                </div>
            </details>

            <span class="sub-label">CTA-кнопка в модалке</span>

            <div class="meta-row">
                <div class="field-group">
                    <label>Текст кнопки</label>
                    <input type="text" name="dining_formats[cards][<?php echo $i; ?>][cta_text]" value="<?php echo esc_attr( $c['cta_text'] ?? 'Обсудить формат' ); ?>" />
                </div>
                <div class="field-group">
                    <label>Ссылка кнопки (с #)</label>
                    <input type="text" name="dining_formats[cards][<?php echo $i; ?>][cta_link]" value="<?php echo esc_attr( $c['cta_link'] ?? '#booking' ); ?>" placeholder="#booking" />
                </div>
            </div>
        </div>
        <?php endforeach;
    endif; ?>
    </div>

    <button type="button" class="button" id="add_df_card">+ Добавить формат</button>

    <script>
    jQuery(function($){
        var idx = $('#df_cards_wrap .df-card').length;
        $('#add_df_card').on('click', function(){
            var i = idx; idx++;
            var h =
                '<div class="df-card">' +
                    '<div class="head-row"><strong>Новый формат</strong><button type="button" class="remove-card button button-small">Удалить</button></div>' +
                    '<span class="sub-label">Карточка (видна на странице)</span>' +
                    '<div class="field-group"><label>Изображение карточки</label><input type="hidden" name="dining_formats[cards][' + i + '][card_image]" class="df-card-img" value="" data-kp-media /></div>' +
                    '<div class="field-group"><label>Тег</label><input type="text" name="dining_formats[cards][' + i + '][card_tag]" value="" /></div>' +
                    '<div class="field-group"><label>Название</label><input type="text" name="dining_formats[cards][' + i + '][card_title]" value="" /></div>' +
                    '<div class="field-group"><label>Короткое описание</label><input type="text" name="dining_formats[cards][' + i + '][card_desc]" value="" /></div>' +
                    '<details><summary>Подробное модальное окно</summary>' +
                    '<div class="field-group"><label>Фото в модалке</label><input type="hidden" name="dining_formats[cards][' + i + '][modal_image]" class="df-modal-img" value="" data-kp-media /></div>' +
                    '<div class="field-group"><label>Бейдж в модалке</label><input type="text" name="dining_formats[cards][' + i + '][modal_badge]" value="" /></div>' +
                    '<div class="field-group"><label>Заголовок в модалке</label><input type="text" name="dining_formats[cards][' + i + '][modal_title]" value="" /></div>' +
                    '<div class="field-group"><label>Метаданные</label><input type="text" name="dining_formats[cards][' + i + '][modal_meta]" value="" /></div>' +
                    '<div class="field-group"><label>Описание в модалке (HTML)</label><textarea name="dining_formats[cards][' + i + '][modal_desc]" rows="5"></textarea></div>' +
                    '<div class="field-group"><label>Что входит — один пункт на строку</label><textarea name="dining_formats[cards][' + i + '][modal_items_text]" rows="7"></textarea></div>' +
                    '</details>' +
                    '<span class="sub-label">CTA-кнопка в модалке</span>' +
                    '<div class="meta-row"><div class="field-group"><label>Текст кнопки</label><input type="text" name="dining_formats[cards][' + i + '][cta_text]" value="Обсудить формат" /></div><div class="field-group"><label>Ссылка кнопки</label><input type="text" name="dining_formats[cards][' + i + '][cta_link]" value="#booking" /></div></div>' +
                '</div>';
            $('#df_cards_wrap').append(h);
        });

        $('#df_cards_wrap').on('click', '.remove-card', function(){
            if( confirm('Удалить формат?') ) $(this).closest('.df-card').remove();
        });
    });
    </script>
    <?php
}

/* ── Save ────────────────────────────────────────── */
add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['dining_formats_nonce'] ) || ! wp_verify_nonce( $_POST['dining_formats_nonce'], 'dining_formats_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['dining_formats'] ?? [];

    $clean = [
        'eyebrow'    => sanitize_text_field( $raw['eyebrow']    ?? '' ),
        'title'      => sanitize_text_field( $raw['title']      ?? '' ),
        'gold_text'  => sanitize_text_field( $raw['gold_text']  ?? '' ),
        'subtitle'   => sanitize_textarea_field( $raw['subtitle'] ?? '' ),
        'section_id' => sanitize_text_field( $raw['section_id'] ?? '' ),
        'cards'      => [],
    ];

    if ( ! empty( $raw['cards'] ) && is_array( $raw['cards'] ) ) {
        foreach ( $raw['cards'] as $c ) {
            $ct = sanitize_text_field( $c['card_title'] ?? '' );
            if ( $ct === '' ) continue;

            /* modal_items из текста — один пункт на строку */
            $modal_items = array_values( array_filter( array_map( static function ( $line ) {
                $line = sanitize_text_field( $line );
                return $line === '' ? null : [ 'text' => $line ];
            }, preg_split( '/\R/u', $c['modal_items_text'] ?? '' ) ?: [] ) ) );

            $clean['cards'][] = [
                'card_image'  => esc_url_raw( $c['card_image'] ?? '' ),
                'card_tag'    => sanitize_text_field( $c['card_tag'] ?? '' ),
                'card_title'  => $ct,
                'card_desc'   => sanitize_text_field( $c['card_desc'] ?? '' ),
                'modal_image' => esc_url_raw( $c['modal_image'] ?? '' ),
                'modal_badge' => sanitize_text_field( $c['modal_badge'] ?? '' ),
                'modal_title' => sanitize_text_field( $c['modal_title'] ?? '' ),
                'modal_meta'  => sanitize_text_field( $c['modal_meta'] ?? '' ),
                'modal_desc'  => wp_kses_post( $c['modal_desc'] ?? '' ),
                'modal_items' => $modal_items,
                'cta_text'    => sanitize_text_field( $c['cta_text'] ?? 'Обсудить формат' ),
                'cta_link'    => sanitize_text_field( $c['cta_link'] ?? '#booking' ),
            ];
        }
    }

    update_post_meta( $post_id, 'dining_formats', $clean );
} );