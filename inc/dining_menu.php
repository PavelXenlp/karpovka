<?php
/**
 * Meta box: Dining Menu Section (dining_menu)
 * Cards repeater + modal для каждой карточки с позициями и ценами
 */

add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'dining_menu_mb',
        'Секция: Меню и примеры блюд',
        'dining_menu_mb_callback',
        'page',
        'normal',
        'high'
    );
} );

function dining_menu_mb_callback( $post ) {

    wp_nonce_field( 'dining_menu_save', 'dining_menu_nonce' );

    $v     = get_post_meta( $post->ID, 'dining_menu', true ) ?: [];
    $cards = $v['cards'] ?? [];

    ?>
    <style>
        #dining_menu_mb .field-group { margin-bottom: 14px; }
        #dining_menu_mb label { display: block; font-weight: 600; margin-bottom: 4px; }
        #dining_menu_mb input[type="text"],
        #dining_menu_mb textarea,
        #dining_menu_mb select { width: 100%; }
        #dining_menu_mb .dm-card { background: #fff; border: 1px solid #dcdcde; padding: 16px; margin-bottom: 16px; }
        #dining_menu_mb .dm-card .head-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        #dining_menu_mb .dm-card .head-row strong { font-size: 13px; color: #555; }
        #dining_menu_mb .remove-card { color: #b00; border: none; cursor: pointer; }
        #dining_menu_mb .sub-label { display: inline-block; font-weight: 600; margin: 10px 0 4px; font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: .5px; }
        #dining_menu_mb .meta-row { display: flex; gap: 10px; }
        #dining_menu_mb .meta-row .field-group { flex: 1; }
        #dining_menu_mb .mi-row { display: flex; gap: 8px; align-items: flex-end; margin-bottom: 6px; }
        #dining_menu_mb .mi-row .mi-text { flex: 1; }
        #dining_menu_mb .mi-row .mi-price { max-width: 140px; }
        #dining_menu_mb .dm-img-preview { max-width: 120px; max-height: 80px; margin-top: 6px; border: 1px solid #ddd; display: none; }
    </style>

    <!-- Шапка секции -->
    <div class="field-group">
        <label>Аннотация (eyebrow)</label>
        <input type="text" name="dining_menu[eyebrow]" value="<?php echo esc_attr( $v['eyebrow'] ?? '' ); ?>" />
    </div>

    <div class="field-group">
        <label>Заголовок (до gold-текста)</label>
        <input type="text" name="dining_menu[title]" value="<?php echo esc_attr( $v['title'] ?? '' ); ?>" />
    </div>

    <div class="field-group">
        <label>Gold-текст (обёрнется в &lt;em&gt;)</label>
        <input type="text" name="dining_menu[gold_text]" value="<?php echo esc_attr( $v['gold_text'] ?? '' ); ?>" />
    </div>

    <div class="field-group">
        <label>Подзаголовок</label>
        <textarea name="dining_menu[subtitle]" rows="2"><?php echo esc_textarea( $v['subtitle'] ?? '' ); ?></textarea>
    </div>

    <div class="field-group">
        <label>ID секции (anchor)</label>
        <input type="text" name="dining_menu[section_id]" value="<?php echo esc_attr( $v['section_id'] ?? '' ); ?>" />
    </div>

    <hr />

    <h4>Карточки меню (к каждой — свой попап)</h4>

    <div id="dm_cards_wrap">
    <?php if ( ! empty( $cards ) && is_array( $cards ) ) :
        foreach ( $cards as $i => $c ) : ?>
        <div class="dm-card">
            <div class="head-row">
                <strong><?php echo esc_html( mb_substr( $c['card_title'] ?? '', 0, 30 ) ); ?></strong>
                <button type="button" class="remove-card button button-small">Удалить</button>
            </div>

            <div class="field-group">
                <label>Slug для попапа</label>
                <input type="text" name="dining_menu[cards][<?php echo $i; ?>][slug]" value="<?php echo esc_attr( $c['slug'] ?? '' ); ?>" placeholder="modal-dining-breakfast" />
            </div>

            <span class="sub-label">Карточка</span>

            <div class="field-group">
                <label>Изображение</label>
                <input type="text" name="dining_menu[cards][<?php echo $i; ?>][card_image]" class="dm-card-img" value="<?php echo esc_attr( $c['card_image'] ?? '' ); ?>" />
                <img class="dm-img-preview dm-card-preview" src="<?php echo esc_url( $c['card_image'] ?? '' ); ?>" alt="" />
                <button type="button" class="button dm-card-img-btn" style="margin-top:4px;">📁 Выбрать</button>
            </div>

            <div class="field-group">
                <label>Название</label>
                <input type="text" name="dining_menu[cards][<?php echo $i; ?>][card_title]" value="<?php echo esc_attr( $c['card_title'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Описание</label>
                <textarea name="dining_menu[cards][<?php echo $i; ?>][card_desc]" rows="2"><?php echo esc_textarea( $c['card_desc'] ?? '' ); ?></textarea>
            </div>

            <div class="meta-row">
                <div class="field-group">
                    <label>Мета 1: иконка (FA класс)</label>
                    <input type="text" name="dining_menu[cards][<?php echo $i; ?>][meta1_icon]" value="<?php echo esc_attr( $c['meta1_icon'] ?? '' ); ?>" placeholder="fa-regular fa-clock" />
                </div>
                <div class="field-group">
                    <label>Мета 1: текст</label>
                    <input type="text" name="dining_menu[cards][<?php echo $i; ?>][meta1_text]" value="<?php echo esc_attr( $c['meta1_text'] ?? '' ); ?>" placeholder="08:00 – 10:30" />
                </div>
            </div>

            <div class="meta-row">
                <div class="field-group">
                    <label>Мета 2: иконка (FA класс)</label>
                    <input type="text" name="dining_menu[cards][<?php echo $i; ?>][meta2_icon]" value="<?php echo esc_attr( $c['meta2_icon'] ?? '' ); ?>" placeholder="fa-solid fa-check" />
                </div>
                <div class="field-group">
                    <label>Мета 2: текст</label>
                    <input type="text" name="dining_menu[cards][<?php echo $i; ?>][meta2_text]" value="<?php echo esc_attr( $c['meta2_text'] ?? '' ); ?>" placeholder="Включено в проживание" />
                </div>
            </div>

            <hr />

            <span class="sub-label">Попап</span>

            <div class="field-group">
                <label>Фото в попапе</label>
                <input type="text" name="dining_menu[cards][<?php echo $i; ?>][modal_photo]" class="dm-modal-img" value="<?php echo esc_attr( $c['modal_photo'] ?? '' ); ?>" />
                <img class="dm-img-preview dm-modal-preview" src="<?php echo esc_url( $c['modal_photo'] ?? '' ); ?>" alt="" />
                <button type="button" class="button dm-modal-img-btn" style="margin-top:4px;">📁 Выбрать</button>
            </div>

            <div class="field-group">
                <label>Бейдж попапа</label>
                <input type="text" name="dining_menu[cards][<?php echo $i; ?>][modal_badge]" value="<?php echo esc_attr( $c['modal_badge'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Заголовок попапа</label>
                <input type="text" name="dining_menu[cards][<?php echo $i; ?>][modal_title]" value="<?php echo esc_attr( $c['modal_title'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Мета-строка попапа</label>
                <input type="text" name="dining_menu[cards][<?php echo $i; ?>][modal_meta]" value="<?php echo esc_attr( $c['modal_meta'] ?? '' ); ?>" placeholder="08:00 – 10:30 · Включено в проживание" />
            </div>

            <div class="field-group">
                <label>Описание попапа</label>
                <textarea name="dining_menu[cards][<?php echo $i; ?>][modal_desc]" rows="4"><?php echo esc_textarea( $c['modal_desc'] ?? '' ); ?></textarea>
            </div>

            <div class="field-group">
                <label>Заголовок списка позиций</label>
                <input type="text" name="dining_menu[cards][<?php echo $i; ?>][items_title]" value="<?php echo esc_attr( $c['items_title'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Позиции меню</label>
                <div class="dm-items-wrap" data-idx="<?php echo $i; ?>">
                <?php if ( ! empty( $c['items'] ) && is_array( $c['items'] ) ) :
                    foreach ( $c['items'] as $j => $it ) : ?>
                    <div class="mi-row">
                        <input type="text" class="mi-text" name="dining_menu[cards][<?php echo $i; ?>][items][<?php echo $j; ?>][text]" value="<?php echo esc_attr( $it['text'] ?? '' ); ?>" placeholder="Название блюда" />
                        <input type="text" class="mi-price" name="dining_menu[cards][<?php echo $i; ?>][items][<?php echo $j; ?>][price]" value="<?php echo esc_attr( $it['price'] ?? '' ); ?>" placeholder="420 ₽" />
                        <button type="button" class="button button-small remove-mi">✕</button>
                    </div>
                    <?php endforeach;
                endif; ?>
                </div>
                <button type="button" class="button add-mi-btn" data-idx="<?php echo $i; ?>">+ Позиция</button>
            </div>

            <hr />

            <span class="sub-label">Футер попапа</span>

            <div class="meta-row">
                <div class="field-group">
                    <label>Цена</label>
                    <input type="text" name="dining_menu[cards][<?php echo $i; ?>][foot_price]" value="<?php echo esc_attr( $c['foot_price'] ?? '' ); ?>" placeholder="от 380 ₽" />
                </div>
                <div class="field-group">
                    <label>Пояснение к цене</label>
                    <input type="text" name="dining_menu[cards][<?php echo $i; ?>][foot_price_small]" value="<?php echo esc_attr( $c['foot_price_small'] ?? '' ); ?>" placeholder="средний чек 800 ₽ / персона" />
                </div>
            </div>

            <div class="meta-row">
                <div class="field-group">
                    <label>Текст кнопки CTA</label>
                    <input type="text" name="dining_menu[cards][<?php echo $i; ?>][foot_cta]" value="<?php echo esc_attr( $c['foot_cta'] ?? '' ); ?>" placeholder="Забронировать стол" />
                </div>
                <div class="field-group">
                    <label>Ссылка кнопки CTA</label>
                    <input type="text" name="dining_menu[cards][<?php echo $i; ?>][foot_cta_link]" value="<?php echo esc_attr( $c['foot_cta_link'] ?? '' ); ?>" placeholder="#booking" />
                </div>
            </div>
        </div>
        <?php endforeach;
    endif; ?>
    </div>

    <button type="button" class="button" id="add_dm_card">+ Карточка меню</button>

    <!-- SVG для превью в JS -->
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c8a96e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><polyline points="20 6 9 17 4 12" id="dm-check-svg-def"></polyline></svg>

    <script>
    jQuery(function($){

        var cardCount = 0;
        $('#dm_cards_wrap .dm-card').each(function(){
            var name = $(this).find('input[name*="[card_title]"]').attr('name');
            if(name){
                var m = name.match(/\[cards\]\[(\d+)\]/);
                if(m) var idx = parseInt(m[1]);
                if(idx >= cardCount) cardCount = idx + 1;
            }
        });

        /* Add card */
        $('#add_dm_card').on('click', function(){
            var i = cardCount; cardCount++;
            var h =
                '<div class="dm-card">' +
                    '<div class="head-row"><strong>Новое меню</strong><button type="button" class="remove-card button button-small">Удалить</button></div>' +
                    '<div class="field-group"><label>Slug для попапа</label><input type="text" name="dining_menu[cards][' + i + '][slug]" value="" placeholder="modal-dining-name" /></div>' +
                    '<span class="sub-label">Карточка</span>' +
                    '<div class="field-group"><label>Изображение</label><input type="text" name="dining_menu[cards][' + i + '][card_image]" class="dm-card-img" value="" /><img class="dm-img-preview dm-card-preview" src="" alt="" /><button type="button" class="button dm-card-img-btn" style="margin-top:4px;">📁 Выбрать</button></div>' +
                    '<div class="field-group"><label>Название</label><input type="text" name="dining_menu[cards][' + i + '][card_title]" value="" /></div>' +
                    '<div class="field-group"><label>Описание</label><textarea name="dining_menu[cards][' + i + '][card_desc]" rows="2"></textarea></div>' +
                    '<div class="meta-row"><div class="field-group"><label>Мета 1: иконка</label><input type="text" name="dining_menu[cards][' + i + '][meta1_icon]" value="" placeholder="fa-regular fa-clock" /></div><div class="field-group"><label>Мета 1: текст</label><input type="text" name="dining_menu[cards][' + i + '][meta1_text]" value="" /></div></div>' +
                    '<div class="meta-row"><div class="field-group"><label>Мета 2: иконка</label><input type="text" name="dining_menu[cards][' + i + '][meta2_icon]" value="" placeholder="fa-solid fa-check" /></div><div class="field-group"><label>Мета 2: текст</label><input type="text" name="dining_menu[cards][' + i + '][meta2_text]" value="" /></div></div>' +
                    '<hr /><span class="sub-label">Попап</span>' +
                    '<div class="field-group"><label>Фото в попапе</label><input type="text" name="dining_menu[cards][' + i + '][modal_photo]" class="dm-modal-img" value="" /><img class="dm-img-preview dm-modal-preview" src="" alt="" /><button type="button" class="button dm-modal-img-btn" style="margin-top:4px;">📁 Выбрать</button></div>' +
                    '<div class="field-group"><label>Бейдж</label><input type="text" name="dining_menu[cards][' + i + '][modal_badge]" value="" /></div>' +
                    '<div class="field-group"><label>Заголовок попапа</label><input type="text" name="dining_menu[cards][' + i + '][modal_title]" value="" /></div>' +
                    '<div class="field-group"><label>Мета-строка</label><input type="text" name="dining_menu[cards][' + i + '][modal_meta]" value="" /></div>' +
                    '<div class="field-group"><label>Описание попапа</label><textarea name="dining_menu[cards][' + i + '][modal_desc]" rows="4"></textarea></div>' +
                    '<div class="field-group"><label>Заголовок списка</label><input type="text" name="dining_menu[cards][' + i + '][items_title]" value="" /></div>' +
                    '<div class="field-group"><label>Позиции меню</label><div class="dm-items-wrap" data-idx="' + i + '"></div><button type="button" class="button add-mi-btn" data-idx="' + i + '">+ Позиция</button></div>' +
                    '<hr /><span class="sub-label">Футер попапа</span>' +
                    '<div class="meta-row"><div class="field-group"><label>Цена</label><input type="text" name="dining_menu[cards][' + i + '][foot_price]" value="" /></div><div class="field-group"><label>Пояснение к цене</label><input type="text" name="dining_menu[cards][' + i + '][foot_price_small]" value="" /></div></div>' +
                    '<div class="meta-row"><div class="field-group"><label>Текст кнопки</label><input type="text" name="dining_menu[cards][' + i + '][foot_cta]" value="" /></div><div class="field-group"><label>Ссылка кнопки</label><input type="text" name="dining_menu[cards][' + i + '][foot_cta_link]" value="" /></div></div>' +
                '</div>';
            $('#dm_cards_wrap').append(h);
        });

        /* Remove card */
        $('#dm_cards_wrap').on('click', '.remove-card', function(){
            if( confirm('Удалить эту карточку и попап?') ) $(this).closest('.dm-card').remove();
        });

        /* Upload card image */
        $('#dm_cards_wrap').on('click', '.dm-card-img-btn', function(){
            var input = $(this).prevAll('input.dm-card-img').first();
            var preview = input.next('.dm-card-preview');
            var frame = wp.media({ title:'Выберите изображение карточки', multiple:false, library:{type:'image'} });
            frame.on('select', function(){ var u = frame.state().get('selection').first().toJSON().url; input.val(u); preview.attr('src',u).show(); });
            frame.open();
        });

        /* Upload modal photo */
        $('#dm_cards_wrap').on('click', '.dm-modal-img-btn', function(){
            var input = $(this).prevAll('input.dm-modal-img').first();
            var preview = input.next('.dm-modal-preview');
            var frame = wp.media({ title:'Выберите фото для попапа', multiple:false, library:{type:'image'} });
            frame.on('select', function(){ var u = frame.state().get('selection').first().toJSON().url; input.val(u); preview.attr('src',u).show(); });
            frame.open();
        });

        /* Add menu item */
        $('#dm_cards_wrap').on('click', '.add-mi-btn', function(){
            var wrap = $(this).prev('.dm-items-wrap');
            var ci = $(this).data('idx');
            var j = wrap.find('.mi-row').length;
            var h = '<div class="mi-row">' +
                '<input type="text" class="mi-text" name="dining_menu[cards][' + ci + '][items][' + j + '][text]" value="" placeholder="Название блюда" />' +
                '<input type="text" class="mi-price" name="dining_menu[cards][' + ci + '][items][' + j + '][price]" value="" placeholder="420 ₽" />' +
                '<button type="button" class="button button-small remove-mi">✕</button>' +
                '</div>';
            wrap.append(h);
        });

        /* Remove menu item */
        $('#dm_cards_wrap').on('click', '.remove-mi', function(){
            $(this).closest('.mi-row').remove();
        });

        /* Show existing previews */
        $('#dm_cards_wrap .dm-img-preview').each(function(){
            if( $(this).attr('src') && $(this).attr('src') !== '' ) $(this).show();
        });

    });
    </script>
    <?php
}

/* ── Save ────────────────────────────────────────── */
add_action( 'save_post', function ( $post_id ) {

    if ( ! isset( $_POST['dining_menu_nonce'] ) || ! wp_verify_nonce( $_POST['dining_menu_nonce'], 'dining_menu_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['dining_menu'] ?? [];

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

            $card = [
                'slug'            => sanitize_text_field( $c['slug'] ?? '' ),
                'card_image'      => esc_url_raw( $c['card_image'] ?? '' ),
                'card_title'      => $ct,
                'card_desc'       => sanitize_textarea_field( $c['card_desc'] ?? '' ),
                'meta1_icon'      => sanitize_text_field( $c['meta1_icon'] ?? '' ),
                'meta1_text'      => sanitize_text_field( $c['meta1_text'] ?? '' ),
                'meta2_icon'      => sanitize_text_field( $c['meta2_icon'] ?? '' ),
                'meta2_text'      => sanitize_text_field( $c['meta2_text'] ?? '' ),
                'modal_photo'     => esc_url_raw( $c['modal_photo'] ?? '' ),
                'modal_badge'     => sanitize_text_field( $c['modal_badge'] ?? '' ),
                'modal_title'     => sanitize_text_field( $c['modal_title'] ?? '' ),
                'modal_meta'      => sanitize_text_field( $c['modal_meta'] ?? '' ),
                'modal_desc'      => sanitize_textarea_field( $c['modal_desc'] ?? '' ),
                'items_title'     => sanitize_text_field( $c['items_title'] ?? '' ),
                'items'           => [],
                'foot_price'      => sanitize_text_field( $c['foot_price'] ?? '' ),
                'foot_price_small'=> sanitize_text_field( $c['foot_price_small'] ?? '' ),
                'foot_cta'        => sanitize_text_field( $c['foot_cta'] ?? '' ),
                'foot_cta_link'   => sanitize_text_field( $c['foot_cta_link'] ?? '' ),
            ];

            if ( ! empty( $c['items'] ) && is_array( $c['items'] ) ) {
                foreach ( $c['items'] as $it ) {
                    $t = sanitize_text_field( $it['text'] ?? '' );
                    if ( $t !== '' ) {
                        $card['items'][] = [
                            'text'  => $t,
                            'price' => sanitize_text_field( $it['price'] ?? '' ),
                        ];
                    }
                }
            }

            $clean['cards'][] = $card;
        }
    }

    update_post_meta( $post_id, 'dining_menu', $clean );
} );