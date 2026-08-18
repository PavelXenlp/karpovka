<?php
/**
 * Meta box: Bath Types Section (bath_types)
 * Cards repeater + modal for each card
 */

add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'bath_types_mb',
        'Секция: Виды бань — Направления',
        'bath_types_mb_callback',
        'page',
        'normal',
        'high'
    );
} );

function bath_types_mb_callback( $post ) {

    wp_nonce_field( 'bath_types_save', 'bath_types_nonce' );

    $v = get_post_meta( $post->ID, 'bath_types', true ) ?: [];

    $eyebrow    = $v['eyebrow']    ?? '';
    $title      = $v['title']      ?? '';
    $gold_text  = $v['gold_text']  ?? '';
    $subtitle   = $v['subtitle']   ?? '';
    $section_id = $v['section_id'] ?? 'directions';
    $cards      = $v['cards']      ?? [];

    ?>
    <style>
        #bath_types_mb .field-group { margin-bottom: 14px; }
        #bath_types_mb label { display: block; font-weight: 600; margin-bottom: 4px; }
        #bath_types_mb input[type="text"],
        #bath_types_mb textarea,
        #bath_types_mb select { width: 100%; }
        #bath_types_mb .card-block { background: #fff; border: 1px solid #dcdcde; padding: 16px; margin-bottom: 16px; }
        #bath_types_mb .card-block h4 { margin: 0 0 10px; color: #1d3557; }
        #bath_types_mb .card-block .head-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        #bath_types_mb .card-block .head-row strong { font-size: 13px; color: #555; }
        #bath_types_mb .remove-card { color: #b00; border: none; cursor: pointer; }
        #bath_types_mb .incl-block { background: #f9f9f9; border: 1px solid #e0e0e0; padding: 12px; margin-bottom: 10px; }
        #bath_types_mb .incl-block .incl-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
        #bath_types_mb .incl-block label { font-weight: 400; }
        #bath_types_mb .sub-label { display: inline-block; font-weight: 600; margin: 8px 0 4px; font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: .5px; }
    </style>

    <!-- Шапка секции -->
    <div class="field-group">
        <label>Аннотация (eyebrow)</label>
        <input type="text" name="bath_types[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>" />
    </div>

    <div class="field-group">
        <label>Заголовок (до gold-текста)</label>
        <input type="text" name="bath_types[title]" value="<?php echo esc_attr( $title ); ?>" />
    </div>

    <div class="field-group">
        <label>Gold-текст (обёрнется в &lt;em&gt;)</label>
        <input type="text" name="bath_types[gold_text]" value="<?php echo esc_attr( $gold_text ); ?>" />
    </div>

    <div class="field-group">
        <label>Подзаголовок</label>
        <textarea name="bath_types[subtitle]" rows="2"><?php echo esc_textarea( $subtitle ); ?></textarea>
    </div>

    <div class="field-group">
        <label>ID секции (anchor)</label>
        <input type="text" name="bath_types[section_id]" value="<?php echo esc_attr( $section_id ); ?>" />
    </div>

    <hr />

    <!-- Карточки бань -->
    <h4>Карточки бань (к каждой — свой попап)</h4>
    <div id="bath_cards_wrap">
    <?php if ( ! empty( $cards ) && is_array( $cards ) ) :
        foreach ( $cards as $i => $c ) : ?>
        <div class="card-block">
            <div class="head-row">
                <strong><?php echo esc_html( mb_substr( $c['card_title'] ?? '', 0, 30 ) ); ?></strong>
                <button type="button" class="remove-card button button-small">Удалить</button>
            </div>

            <span class="sub-label">Карточка</span>

            <div class="field-group">
                <label>Slug для попапа (ID модалки)</label>
                <input type="text" name="bath_types[cards][<?php echo $i; ?>][slug]" value="<?php echo esc_attr( $c['slug'] ?? '' ); ?>" placeholder="modal-bath-russian" />
            </div>

            <div class="field-group">
                <label>Изображение карточки</label>
                <input type="text" name="bath_types[cards][<?php echo $i; ?>][card_image]" class="bath-card-img" value="<?php echo esc_attr( $c['card_image'] ?? '' ); ?>" />
                <button type="button" class="button bath-card-img-btn">Выбрать</button>
            </div>

            <div class="field-group">
                <label>Тег (например: 90°C · 60–70% влажность)</label>
                <input type="text" name="bath_types[cards][<?php echo $i; ?>][card_tag]" value="<?php echo esc_attr( $c['card_tag'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Название бани</label>
                <input type="text" name="bath_types[cards][<?php echo $i; ?>][card_title]" value="<?php echo esc_attr( $c['card_title'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Описание карточки</label>
                <input type="text" name="bath_types[cards][<?php echo $i; ?>][card_desc]" value="<?php echo esc_attr( $c['card_desc'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Стиль карточки</label>
                <select name="bath_types[cards][<?php echo $i; ?>][card_style]">
                    <option value="" <?php selected( $c['card_style'] ?? '', '' ); ?>>Обычная</option>
                    <option value="wide" <?php selected( $c['card_style'] ?? '', 'wide' ); ?>>Широкая (wide)</option>
                    <option value="wide-alt" <?php selected( $c['card_style'] ?? '', 'wide-alt' ); ?>>Широкая альт (wide-alt)</option>
                </select>
            </div>

            <hr />

            <span class="sub-label">Попап</span>

            <div class="field-group">
                <label>Фото в попапе</label>
                <input type="text" name="bath_types[cards][<?php echo $i; ?>][modal_photo]" class="bath-modal-img" value="<?php echo esc_attr( $c['modal_photo'] ?? '' ); ?>" />
                <button type="button" class="button bath-modal-img-btn">Выбрать</button>
            </div>

            <div class="field-group">
                <label>Тег попапа</label>
                <input type="text" name="bath_types[cards][<?php echo $i; ?>][modal_tag]" value="<?php echo esc_attr( $c['modal_tag'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Заголовок попапа</label>
                <input type="text" name="bath_types[cards][<?php echo $i; ?>][modal_title]" value="<?php echo esc_attr( $c['modal_title'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Мета-строка попапа</label>
                <input type="text" name="bath_types[cards][<?php echo $i; ?>][modal_meta]" value="<?php echo esc_attr( $c['modal_meta'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Описание попапа (абзацы через пустую строку)</label>
                <textarea name="bath_types[cards][<?php echo $i; ?>][modal_text]" rows="5"><?php echo esc_textarea( $c['modal_text'] ?? '' ); ?></textarea>
            </div>

            <div class="field-group">
                <label>Заголовок списка «Что входит»</label>
                <input type="text" name="bath_types[cards][<?php echo $i; ?>][includes_title]" value="<?php echo esc_attr( $c['includes_title'] ?? '' ); ?>" />
            </div>

            <!-- Включения (repeater внутри repeater) -->
            <div class="field-group">
                <label>Список «Что входит»</label>
                <div class="bath-incl-wrap" data-card-idx="<?php echo $i; ?>">
                <?php if ( ! empty( $c['includes'] ) && is_array( $c['includes'] ) ) :
                    foreach ( $c['includes'] as $j => $incl ) : ?>
                    <div class="incl-block">
                        <div class="incl-head">
                            <input type="text" name="bath_types[cards][<?php echo $i; ?>][includes][<?php echo $j; ?>][text]" value="<?php echo esc_attr( $incl['text'] ?? '' ); ?>" />
                            <button type="button" class="remove-incl button button-small">✕</button>
                        </div>
                    </div>
                    <?php endforeach;
                endif; ?>
                </div>
                <button type="button" class="button add-incl-btn">+ Пункт</button>
            </div>

            <div class="field-group">
                <label>Цена (например: от 3 500 ₽)</label>
                <input type="text" name="bath_types[cards][<?php echo $i; ?>][price]" value="<?php echo esc_attr( $c['price'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Пояснение к цене (например: / 2 часа)</label>
                <input type="text" name="bath_types[cards][<?php echo $i; ?>][price_small]" value="<?php echo esc_attr( $c['price_small'] ?? '' ); ?>" />
            </div>
        </div>
        <?php endforeach;
    endif; ?>
    </div>

    <button type="button" class="button" id="add_bath_card">+ Карточка бани</button>

    <script>
    jQuery(function($){

        /* Upload card image */
        $('#bath_cards_wrap').on('click', '.bath-card-img-btn', function(){
            var input = $(this).prev('input');
            var frame = wp.media({ title:'Выберите изображение карточки', multiple:false, library:{type:'image'} });
            frame.on('select', function(){ input.val(frame.state().get('selection').first().toJSON().url); });
            frame.open();
        });

        /* Upload modal photo */
        $('#bath_cards_wrap').on('click', '.bath-modal-img-btn', function(){
            var input = $(this).prev('input');
            var frame = wp.media({ title:'Выберите фото для попапа', multiple:false, library:{type:'image'} });
            frame.on('select', function(){ input.val(frame.state().get('selection').first().toJSON().url); });
            frame.open();
        });

        /* Add includes item */
        $('#bath_cards_wrap').on('click', '.add-incl-btn', function(){
            var wrap = $(this).prev('.bath-incl-wrap');
            var idx  = wrap.find('.incl-block').length;
            var cardIdx = wrap.data('card-idx');
            var h = '<div class="incl-block"><div class="incl-head">' +
                '<input type="text" name="bath_types[cards][' + cardIdx + '][includes][' + idx + '][text]" value="" />' +
                '<button type="button" class="remove-incl button button-small">✕</button>' +
                '</div></div>';
            wrap.append(h);
        });

        /* Remove includes item */
        $('#bath_cards_wrap').on('click', '.remove-incl', function(){
            $(this).closest('.incl-block').remove();
        });

        /* Add card */
        $('#add_bath_card').on('click', function(){
            var wrap = $('#bath_cards_wrap');
            var i = wrap.find('.card-block').length;
            var h =
                '<div class="card-block">' +
                    '<div class="head-row"><strong>Новая баня</strong><button type="button" class="remove-card button button-small">Удалить</button></div>' +
                    '<span class="sub-label">Карточка</span>' +
                    '<div class="field-group"><label>Slug для попапа</label><input type="text" name="bath_types[cards][' + i + '][slug]" value="" placeholder="modal-bath-name" /></div>' +
                    '<div class="field-group"><label>Изображение карточки</label><input type="text" name="bath_types[cards][' + i + '][card_image]" class="bath-card-img" value="" /><button type="button" class="button bath-card-img-btn">Выбрать</button></div>' +
                    '<div class="field-group"><label>Тег</label><input type="text" name="bath_types[cards][' + i + '][card_tag]" value="" /></div>' +
                    '<div class="field-group"><label>Название бани</label><input type="text" name="bath_types[cards][' + i + '][card_title]" value="" /></div>' +
                    '<div class="field-group"><label>Описание карточки</label><input type="text" name="bath_types[cards][' + i + '][card_desc]" value="" /></div>' +
                    '<div class="field-group"><label>Стиль карточки</label><select name="bath_types[cards][' + i + '][card_style]"><option value="">Обычная</option><option value="wide">Широкая (wide)</option><option value="wide-alt">Широкая альт (wide-alt)</option></select></div>' +
                    '<hr /><span class="sub-label">Попап</span>' +
                    '<div class="field-group"><label>Фото в попапе</label><input type="text" name="bath_types[cards][' + i + '][modal_photo]" class="bath-modal-img" value="" /><button type="button" class="button bath-modal-img-btn">Выбрать</button></div>' +
                    '<div class="field-group"><label>Тег попапа</label><input type="text" name="bath_types[cards][' + i + '][modal_tag]" value="" /></div>' +
                    '<div class="field-group"><label>Заголовок попапа</label><input type="text" name="bath_types[cards][' + i + '][modal_title]" value="" /></div>' +
                    '<div class="field-group"><label>Мета-строка</label><input type="text" name="bath_types[cards][' + i + '][modal_meta]" value="" /></div>' +
                    '<div class="field-group"><label>Описание (абзацы через пустую строку)</label><textarea name="bath_types[cards][' + i + '][modal_text]" rows="5"></textarea></div>' +
                    '<div class="field-group"><label>Заголовок «Что входит»</label><input type="text" name="bath_types[cards][' + i + '][includes_title]" value="" /></div>' +
                    '<div class="field-group"><label>Список «Что входит»</label><div class="bath-incl-wrap" data-card-idx="' + i + '"></div><button type="button" class="button add-incl-btn">+ Пункт</button></div>' +
                    '<div class="field-group"><label>Цена</label><input type="text" name="bath_types[cards][' + i + '][price]" value="" /></div>' +
                    '<div class="field-group"><label>Пояснение к цене</label><input type="text" name="bath_types[cards][' + i + '][price_small]" value="" /></div>' +
                '</div>';
            wrap.append(h);
        });

        /* Remove card */
        $('#bath_cards_wrap').on('click', '.remove-card', function(){
            if( confirm('Удалить эту баню и её попап?') ) $(this).closest('.card-block').remove();
        });

    });
    </script>
    <?php
}

/* ── Save ────────────────────────────────────────── */
add_action( 'save_post', function ( $post_id ) {

    if ( ! isset( $_POST['bath_types_nonce'] ) || ! wp_verify_nonce( $_POST['bath_types_nonce'], 'bath_types_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['bath_types'] ?? [];

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
            if ( $c === '' ) continue;

            $card = [
                'slug'           => sanitize_text_field( $c['slug'] ?? '' ),
                'card_image'     => esc_url_raw( $c['card_image'] ?? '' ),
                'card_tag'       => sanitize_text_field( $c['card_tag'] ?? '' ),
                'card_title'     => $ct,
                'card_desc'      => sanitize_text_field( $c['card_desc'] ?? '' ),
                'card_style'     => sanitize_text_field( $c['card_style'] ?? '' ),
                'modal_photo'    => esc_url_raw( $c['modal_photo'] ?? '' ),
                'modal_tag'       => sanitize_text_field( $c['modal_tag'] ?? '' ),
                'modal_title'     => sanitize_text_field( $c['modal_title'] ?? '' ),
                'modal_meta'      => sanitize_text_field( $c['modal_meta'] ?? '' ),
                'modal_text'      => sanitize_textarea_field( $c['modal_text'] ?? '' ),
                'includes_title'  => sanitize_text_field( $c['includes_title'] ?? '' ),
                'includes'        => [],
                'price'           => sanitize_text_field( $c['price'] ?? '' ),
                'price_small'     => sanitize_text_field( $c['price_small'] ?? '' ),
            ];

            if ( ! empty( $c['includes'] ) && is_array( $c['includes'] ) ) {
                foreach ( $c['includes'] as $incl ) {
                    $t = sanitize_text_field( $incl['text'] ?? '' );
                    if ( $t !== '' ) {
                        $card['includes'][] = [ 'text' => $t ];
                    }
                }
            }

            $clean['cards'][] = $card;
        }
    }

    update_post_meta( $post_id, 'bath_types', $clean );
} );