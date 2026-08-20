<?php
// Meta box: SPA-программы
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'spa_programs_section', 'SPA-программы', 'spa_programs_section_cb', 'page', 'normal', 'high' );
} );

function spa_programs_section_cb( $post ) {
    wp_nonce_field( 'spa_programs_section_nonce', 'spa_programs_section_nonce_field' );
    $v = get_post_meta( $post->ID, 'spa_programs', true );

    $eyebrow      = $v['eyebrow'] ?? '';
    $title_before = $v['title_before'] ?? 'SPA-';
    $title_em     = $v['title_em'] ?? 'программы';
    $subtitle     = $v['subtitle'] ?? '';
    $bg_img       = $v['bg_img'] ?? '';
    $cards        = $v['cards'] ?? [];
    ?>

    <p>
        <label><b>Бровь</b></label><br>
        <input type="text" name="spa_programs[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>" style="width:100%;max-width:500px;">
    </p>

    <p>
        <label><b>Заголовок — текст до золотой части</b></label><br>
        <input type="text" name="spa_programs[title_before]" value="<?php echo esc_attr( $title_before ); ?>" style="width:300px;" placeholder="SPA-">
    </p>
    <p>
        <label><b>Заголовок — золотая часть (курсив)</b></label><br>
        <input type="text" name="spa_programs[title_em]" value="<?php echo esc_attr( $title_em ); ?>" style="width:300px;" placeholder="программы">
        <span class="description">Выводится внутри <code>&lt;span class="gold-header"&gt;&lt;em&gt;...&lt;/em&gt;&lt;/span&gt;</code></span>
    </p>

    <p>
        <label><b>Подзаголовок</b></label><br>
        <input type="text" name="spa_programs[subtitle]" value="<?php echo esc_attr( $subtitle ); ?>" style="width:100%;max-width:700px;">
    </p>

    <p>
        <label><b>Фоновое изображение</b></label><br>
        <img class="sp-bg-preview" src="<?php echo esc_url( $bg_img ); ?>" style="max-width:250px;height:auto;display:<?php echo $bg_img ? 'block' : 'none'; ?>;margin:6px 0;border:1px solid #ddd;">
        <input type="hidden" name="spa_programs[bg_img]" value="<?php echo esc_attr( $bg_img ); ?>" class="sp-bg-input">
        <button type="button" class="button sp-upload-bg">📁 Загрузить</button>
        <button type="button" class="button sp-rm-bg" style="<?php echo $bg_img ? '' : 'display:none;'; ?>">✕</button>
    </p>

    <hr>
    <h4>Карточки программ</h4>

    <div id="sp-cards-wrap">
        <?php if ( ! empty( $cards ) ) : foreach ( $cards as $i => $card ) : ?>
        <div class="sp-card-block" style="border:1px solid #ccc;padding:14px;margin-bottom:14px;background:#fafafa;">
            <div style="display:flex;gap:12px;align-items:start;">
                <div style="flex:1;">
                    <p>
                        <label><b>Бейдж</b></label><br>
                        <input type="text" name="spa_programs[cards][<?php echo $i; ?>][badge]" value="<?php echo esc_attr( $card['badge'] ?? '' ); ?>" style="width:200px;" placeholder="Хит / Новинка">
                    </p>
                    <p>
                        <label><b>Название</b></label><br>
                        <input type="text" name="spa_programs[cards][<?php echo $i; ?>][name]" value="<?php echo esc_attr( $card['name'] ?? '' ); ?>" style="width:100%;">
                    </p>
                    <p>
                        <label><b>Длительность</b></label><br>
                        <input type="text" name="spa_programs[cards][<?php echo $i; ?>][duration]" value="<?php echo esc_attr( $card['duration'] ?? '' ); ?>" style="width:200px;" placeholder="3 часа">
                    </p>
                    <p>
                        <label><b>Описание</b></label><br>
                        <input type="text" name="spa_programs[cards][<?php echo $i; ?>][desc]" value="<?php echo esc_attr( $card['desc'] ?? '' ); ?>" style="width:100%;">
                    </p>

                    <p>
                        <label><b>Состав программы</b> (список)</label><br>
                        <div class="sp-items-wrap">
                            <?php $items = $card['items'] ?? []; if ( ! empty( $items ) ) : foreach ( $items as $j => $item ) : ?>
                            <div class="sp-item-row" style="display:flex;gap:6px;align-items:center;margin-bottom:4px;">
                                <input type="text" name="spa_programs[cards][<?php echo $i; ?>][items][<?php echo $j; ?>][text]" value="<?php echo esc_attr( $item['text'] ?? '' ); ?>" style="flex:1;" placeholder="Бассейн и джакузи — 2 часа">
                                <button type="button" class="button sp-rm-item">✕</button>
                            </div>
                            <?php endforeach; endif; ?>
                        </div>
                        <button type="button" class="button sp-add-item" data-ci="<?php echo $i; ?>">+ Пункт</button>
                    </p>

                    <p>
                        <label><b>Цена</b></label><br>
                        <input type="text" name="spa_programs[cards][<?php echo $i; ?>][price]" value="<?php echo esc_attr( $card['price'] ?? '' ); ?>" style="width:250px;" placeholder="от 8 900 ₽">
                    </p>
                    <p>
                        <label><b>Дополнение к цене</b> (small)</label><br>
                        <input type="text" name="spa_programs[cards][<?php echo $i; ?>][price_suffix]" value="<?php echo esc_attr( $card['price_suffix'] ?? '' ); ?>" style="width:250px;" placeholder="/ за пару">
                    </p>
                    <p>
                        <label><b>Ссылка «Подробнее»</b></label><br>
                        <input type="text" name="spa_programs[cards][<?php echo $i; ?>][link]" value="<?php echo esc_attr( $card['link'] ?? '' ); ?>" style="width:100%;max-width:500px;" placeholder="/spa/programma-dlya-dvoih">
                    </p>
                    <p>
                        <label><b>Текст кнопки</b></label><br>
                        <input type="text" name="spa_programs[cards][<?php echo $i; ?>][btn_text]" value="<?php echo esc_attr( $card['btn_text'] ?? 'Подробнее' ); ?>" style="width:200px;">
                    </p>
                </div>
                <p>
                    <label><b>Модификатор</b></label><br>
                    <select name="spa_programs[cards][<?php echo $i; ?>][modifier]">
                        <option value="" <?php selected( $card['modifier'] ?? '', '' ); ?>>Обычная</option>
                        <option value="popular" <?php selected( $card['modifier'] ?? '', 'popular' ); ?>>Популярная (c-spa-program--popular)</option>
                    </select>
                </p>
            </div>

            <hr style="margin:10px 0;">
            <button type="button" class="button sp-rm-card" style="color:#a00;">Удалить карточку</button>
        </div>
        <?php endforeach; endif; ?>
    </div>

    <button type="button" class="button" id="sp-add-card">+ Добавить программу</button>

    <script>
    (function($){
        var cIdx = $('#sp-cards-wrap .sp-card-block').length;

        // Фоновое изображение
        $('.sp-upload-bg').on('click', function(){
            var btn = this;
            var frame = wp.media({ title: 'Выбрать фоновое изображение', multiple: false, library: { type: 'image' } });
            frame.on('select', function(){
                var url = frame.state().get('selection').first().toJSON().url;
                $('.sp-bg-input').val(url);
                $('.sp-bg-preview').attr('src', url).show();
                $('.sp-rm-bg').show();
            });
            frame.open();
        });
        $('.sp-rm-bg').on('click', function(){
            $('.sp-bg-input').val('');
            $('.sp-bg-preview').hide().attr('src','');
            $(this).hide();
        });

        // Добавить карточку
        $('#sp-add-card').on('click', function(){
            var i = cIdx;
            var h = '<div class="sp-card-block" style="border:1px solid #ccc;padding:14px;margin-bottom:14px;background:#fafafa;">'
                + '<div style="display:flex;gap:12px;align-items:start;"><div style="flex:1;">'
                + '<p><label><b>Бейдж</b></label><br>'
                + '<input type="text" name="spa_programs[cards][' + i + '][badge]" style="width:200px;"></p>'
                + '<p><label><b>Название</b></label><br>'
                + '<input type="text" name="spa_programs[cards][' + i + '][name]" style="width:100%;"></p>'
                + '<p><label><b>Длительность</b></label><br>'
                + '<input type="text" name="spa_programs[cards][' + i + '][duration]" style="width:200px;"></p>'
                + '<p><label><b>Описание</b></label><br>'
                + '<input type="text" name="spa_programs[cards][' + i + '][desc]" style="width:100%;"></p>'
                + '<p><label><b>Состав программы</b></label><br>'
                + '<div class="sp-items-wrap"></div>'
                + '<button type="button" class="button sp-add-item" data-ci="' + i + '">+ Пункт</button></p>'
                + '<p><label><b>Цена</b></label><br>'
                + '<input type="text" name="spa_programs[cards][' + i + '][price]" style="width:250px;"></p>'
                + '<p><label><b>Дополнение к цене</b> (small)</label><br>'
                + '<input type="text" name="spa_programs[cards][' + i + '][price_suffix]" style="width:250px;"></p>'
                + '<p><label><b>Ссылка «Подробнее»</b></label><br>'
                + '<input type="text" name="spa_programs[cards][' + i + '][link]" style="width:100%;max-width:500px;"></p>'
                + '<p><label><b>Текст кнопки</b></label><br>'
                + '<input type="text" name="spa_programs[cards][' + i + '][btn_text]" value="Подробнее" style="width:200px;"></p>'
                + '</div>'
                + '<p><label><b>Модификатор</b></label><br>'
                + '<select name="spa_programs[cards][' + i + '][modifier]">'
                + '<option value="">Обычная</option>'
                + '<option value="popular">Популярная (c-spa-program--popular)</option></select></p>'
                + '</div>'
                + '<hr style="margin:10px 0;">'
                + '<button type="button" class="button sp-rm-card" style="color:#a00;">Удалить карточку</button></div>';
            $('#sp-cards-wrap').append(h);
            cIdx++;
        });

        $(document).on('click', '.sp-rm-card', function(){
            if( confirm('Удалить программу?') ) $(this).closest('.sp-card-block').remove();
        });

        // Пункты списка
        $(document).on('click', '.sp-add-item', function(){
            var ci = $(this).data('ci');
            var wrap = $(this).prev('.sp-items-wrap');
            var k = wrap.find('.sp-item-row').length;
            wrap.append(
                '<div class="sp-item-row" style="display:flex;gap:6px;align-items:center;margin-bottom:4px;">'
                + '<input type="text" name="spa_programs[cards][' + ci + '][items][' + k + '][text]" style="flex:1;" placeholder="Бассейн и джакузи — 2 часа">'
                + '<button type="button" class="button sp-rm-item">✕</button></div>'
            );
        });
        $(document).on('click', '.sp-rm-item', function(){ $(this).closest('.sp-item-row').remove(); });
    })(jQuery);
    </script>
    <?php
}

// Сохранение
add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['spa_programs_section_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['spa_programs_section_nonce_field'], 'spa_programs_section_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['spa_programs'] ?? [];

    $cards = [];
    if ( ! empty( $raw['cards'] ) && is_array( $raw['cards'] ) ) {
        foreach ( $raw['cards'] as $card ) {
            $name = sanitize_text_field( $card['name'] ?? '' );
            if ( $name === '' ) continue;

            $items = [];
            if ( ! empty( $card['items'] ) && is_array( $card['items'] ) ) {
                foreach ( $card['items'] as $item ) {
                    $t = sanitize_text_field( $item['text'] ?? '' );
                    if ( $t !== '' ) $items[] = [ 'text' => $t ];
                }
            }

            $cards[] = [
                'badge'        => sanitize_text_field( $card['badge'] ?? '' ),
                'name'         => $name,
                'duration'     => sanitize_text_field( $card['duration'] ?? '' ),
                'desc'         => sanitize_text_field( $card['desc'] ?? '' ),
                'items'        => $items,
                'price'        => sanitize_text_field( $card['price'] ?? '' ),
                'price_suffix' => sanitize_text_field( $card['price_suffix'] ?? '' ),
                'link'         => esc_url_raw( $card['link'] ?? '' ),
                'btn_text'     => sanitize_text_field( $card['btn_text'] ?? 'Подробнее' ),
                'modifier'     => ( $card['modifier'] === 'popular' ) ? 'popular' : '',
            ];
        }
    }

    $data = [
        'eyebrow'      => sanitize_text_field( $raw['eyebrow'] ?? '' ),
        'title_before' => sanitize_text_field( $raw['title_before'] ?? '' ),
        'title_em'     => sanitize_text_field( $raw['title_em'] ?? '' ),
        'subtitle'     => sanitize_text_field( $raw['subtitle'] ?? '' ),
        'bg_img'       => esc_url_raw( $raw['bg_img'] ?? '' ),
        'cards'        => $cards,
    ];

    update_post_meta( $post_id, 'spa_programs', $data );
} );