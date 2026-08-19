<?php
// Meta box: Промо-панели (лояльность, сертификаты)
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'promo_section', 'Промо-панели (лояльность, сертификаты)', 'promo_section_cb', 'page', 'normal', 'high' );
} );

function promo_section_cb( $post ) {
    wp_nonce_field( 'promo_section_nonce', 'promo_section_nonce_field' );
    $v = get_post_meta( $post->ID, 'promo_panels', true );
    $panels = $v['panels'] ?? [];
    ?>

    <p class="description">Каждая панель — блок с фоновым изображением, текстом и кнопкой. Модификатор стиля определяет визуальное оформление.</p>

    <div id="promo-panels-wrap">
        <?php if ( ! empty( $panels ) ) : foreach ( $panels as $i => $panel ) : ?>
        <div class="promo-panel-block" style="border:1px solid #ccc;padding:14px;margin-bottom:14px;background:#fafafa;">
            <p>
                <label><b>Модификатор панели</b></label><br>
                <select name="promo_panels[panels][<?php echo $i; ?>][modifier]">
                    <option value="loyalty" <?php selected( $panel['modifier'] ?? '', 'loyalty' ); ?>>Лояльность (promo__panel--loyalty)</option>
                    <option value="gift" <?php selected( $panel['modifier'] ?? '', 'gift' ); ?>>Подарок (promo__panel--gift)</option>
                </select>
            </p>

            <p>
                <label><b>Фоновое изображение</b></label><br>
                <img class="promo-bg-preview" src="<?php echo esc_url( $panel['bg'] ?? '' ); ?>" style="max-width:300px;height:auto;display:<?php echo ( $panel['bg'] ?? '' ) ? 'block' : 'none'; ?>;margin:6px 0;border:1px solid #ddd;">
                <input type="hidden" name="promo_panels[panels][<?php echo $i; ?>][bg]" value="<?php echo esc_attr( $panel['bg'] ?? '' ); ?>" class="promo-bg-input">
                <button type="button" class="button promo-upload-bg">📁 Загрузить</button>
                <button type="button" class="button promo-remove-bg" style="<?php echo ( $panel['bg'] ?? '' ) ? '' : 'display:none;'; ?>">✕ Удалить</button>
            </p>

            <p>
                <label><b>Позиция фона</b></label><br>
                <select name="promo_panels[panels][<?php echo $i; ?>][bg_pos]">
                    <option value="" <?php selected( $panel['bg_pos'] ?? '', '' ); ?>>По умолчанию</option>
                    <option value="top" <?php selected( $panel['bg_pos'] ?? '', 'top' ); ?>>Сверху (promo__bg--top)</option>
                </select>
                <span class="description">— добавляет класс <code>promo__bg--top</code></span>
            </p>

            <p>
                <label><b>Бровь (надзаголовок)</b></label><br>
                <input type="text" name="promo_panels[panels][<?php echo $i; ?>][eyebrow]" value="<?php echo esc_attr( $panel['eyebrow'] ?? '' ); ?>" style="width:100%;max-width:500px;">
            </p>

            <p>
                <label><b>Заголовок</b> (<code>&lt;em&gt;</code>, <code>&lt;br&gt;</code>)</label><br>
                <input type="text" name="promo_panels[panels][<?php echo $i; ?>][title]" value="<?php echo esc_attr( $panel['title'] ?? '' ); ?>" style="width:100%;">
            </p>

            <p>
                <label><b>Подзаголовок (lead)</b></label><br>
                <input type="text" name="promo_panels[panels][<?php echo $i; ?>][lead]" value="<?php echo esc_attr( $panel['lead'] ?? '' ); ?>" style="width:100%;max-width:600px;">
            </p>

            <p>
                <label><b>Текст кнопки</b></label><br>
                <input type="text" name="promo_panels[panels][<?php echo $i; ?>][btn_text]" value="<?php echo esc_attr( $panel['btn_text'] ?? '' ); ?>" style="width:300px;">
            </p>

            <p>
                <label><b>Ссылка кнопки</b></label><br>
                <input type="text" name="promo_panels[panels][<?php echo $i; ?>][btn_link]" value="<?php echo esc_attr( $panel['btn_link'] ?? '' ); ?>" style="width:100%;max-width:500px;" placeholder="/loyalty">
            </p>

            <p>
                <label><b>Стиль кнопки</b></label><br>
                <select name="promo_panels[panels][<?php echo $i; ?>][btn_style]">
                    <option value="gold" <?php selected( $panel['btn_style'] ?? 'gold', 'gold' ); ?>>Золотая (btn--gold)</option>
                    <option value="ghost" <?php selected( $panel['btn_style'] ?? 'gold', 'ghost' ); ?>>Призрачная (btn--ghost)</option>
                </select>
            </p>

            <hr style="margin:10px 0;">
            <button type="button" class="button promo-remove-panel" style="color:#a00;">Удалить панель</button>
        </div>
        <?php endforeach; endif; ?>
    </div>

    <button type="button" class="button" id="promo-add-panel">+ Добавить панель</button>

    <script>
    (function($){
        var idx = $('#promo-panels-wrap .promo-panel-block').length;

        $('#promo-add-panel').on('click', function(){
            var h = '<div class="promo-panel-block" style="border:1px solid #ccc;padding:14px;margin-bottom:14px;background:#fafafa;">'
                + '<p><label><b>Модификатор панели</b></label><br>'
                + '<select name="promo_panels[panels][' + idx + '][modifier]">'
                + '<option value="loyalty">Лояльность (promo__panel--loyalty)</option>'
                + '<option value="gift">Подарок (promo__panel--gift)</option></select></p>'
                + '<p><label><b>Фоновое изображение</b></label><br>'
                + '<img class="promo-bg-preview" src="" style="max-width:300px;height:auto;display:none;margin:6px 0;border:1px solid #ddd;">'
                + '<input type="hidden" name="promo_panels[panels][' + idx + '][bg]" value="" class="promo-bg-input">'
                + '<button type="button" class="button promo-upload-bg">📁 Загрузить</button> '
                + '<button type="button" class="button promo-remove-bg" style="display:none;">✕ Удалить</button></p>'
                + '<p><label><b>Позиция фона</b></label><br>'
                + '<select name="promo_panels[panels][' + idx + '][bg_pos]">'
                + '<option value="">По умолчанию</option>'
                + '<option value="top">Сверху (promo__bg--top)</option></select></p>'
                + '<p><label><b>Бровь (надзаголовок)</b></label><br>'
                + '<input type="text" name="promo_panels[panels][' + idx + '][eyebrow]" style="width:100%;max-width:500px;"></p>'
                + '<p><label><b>Заголовок</b> (<code>&lt;em&gt;</code>, <code>&lt;br&gt;</code>)</label><br>'
                + '<input type="text" name="promo_panels[panels][' + idx + '][title]" style="width:100%;"></p>'
                + '<p><label><b>Подзаголовок (lead)</b></label><br>'
                + '<input type="text" name="promo_panels[panels][' + idx + '][lead]" style="width:100%;max-width:600px;"></p>'
                + '<p><label><b>Текст кнопки</b></label><br>'
                + '<input type="text" name="promo_panels[panels][' + idx + '][btn_text]" style="width:300px;"></p>'
                + '<p><label><b>Ссылка кнопки</b></label><br>'
                + '<input type="text" name="promo_panels[panels][' + idx + '][btn_link]" style="width:100%;max-width:500px;" placeholder="/loyalty"></p>'
                + '<p><label><b>Стиль кнопки</b></label><br>'
                + '<select name="promo_panels[panels][' + idx + '][btn_style]">'
                + '<option value="gold">Золотая (btn--gold)</option>'
                + '<option value="ghost">Призрачная (btn--ghost)</option></select></p>'
                + '<hr style="margin:10px 0;">'
                + '<button type="button" class="button promo-remove-panel" style="color:#a00;">Удалить панель</button>'
                + '</div>';
            $('#promo-panels-wrap').append(h);
            idx++;
        });

        $(document).on('click', '.promo-remove-panel', function(){
            if( confirm('Удалить эту панель?') ) $(this).closest('.promo-panel-block').remove();
        });

        // Загрузка изображения
        $(document).on('click', '.promo-upload-bg', function(){
            var btn = this;
            var block = $(btn).closest('.promo-panel-block');
            var frame = wp.media({ title: 'Выбрать фоновое изображение', multiple: false, library: { type: 'image' } });
            frame.on('select', function(){
                var url = frame.state().get('selection').first().toJSON().url;
                block.find('.promo-bg-input').val(url);
                block.find('.promo-bg-preview').attr('src', url).show();
                block.find('.promo-remove-bg').show();
            });
            frame.open();
        });

        // Удалить изображение
        $(document).on('click', '.promo-remove-bg', function(){
            var block = $(this).closest('.promo-panel-block');
            block.find('.promo-bg-input').val('');
            block.find('.promo-bg-preview').hide().attr('src', '');
            $(this).hide();
        });
    })(jQuery);
    </script>
    <?php
}

// Сохранение
add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['promo_section_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['promo_section_nonce_field'], 'promo_section_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['promo_panels'] ?? [];

    $data = [ 'panels' => [] ];

    if ( ! empty( $raw['panels'] ) && is_array( $raw['panels'] ) ) {
        foreach ( $raw['panels'] as $panel ) {
            $bg = esc_url_raw( $panel['bg'] ?? '' );
            $title = wp_kses_post( $panel['title'] ?? '' );
            if ( empty( $title ) && empty( $panel['eyebrow'] ) ) continue;

            $data['panels'][] = [
                'modifier' => sanitize_text_field( $panel['modifier'] ?? 'loyalty' ),
                'bg'       => $bg,
                'bg_pos'   => ( $panel['bg_pos'] === 'top' ) ? 'top' : '',
                'eyebrow'  => sanitize_text_field( $panel['eyebrow'] ?? '' ),
                'title'    => $title,
                'lead'     => sanitize_text_field( $panel['lead'] ?? '' ),
                'btn_text' => sanitize_text_field( $panel['btn_text'] ?? '' ),
                'btn_link' => esc_url_raw( $panel['btn_link'] ?? '' ),
                'btn_style'=> ( $panel['btn_style'] === 'ghost' ) ? 'ghost' : 'gold',
            ];
        }
    }

    update_post_meta( $post_id, 'promo_panels', $data );
} );