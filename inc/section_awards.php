<?php
// Meta box: Награды и премии
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'awards_section', 'Награды и премии', 'awards_section_cb', 'page', 'normal', 'high' );
} );

function awards_section_cb( $post ) {
    wp_nonce_field( 'awards_section_nonce', 'awards_section_nonce_field' );
    $v = get_post_meta( $post->ID, 'awards', true );

    $eyebrow = $v['eyebrow'] ?? '';
    $title   = $v['title'] ?? '';
    $items   = $v['items'] ?? [];
    ?>

    <p>
        <label><b>Бровь (надзаголовок)</b></label><br>
        <input type="text" name="awards[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>" style="width:100%;max-width:500px;">
    </p>

    <p>
        <label><b>Заголовок</b> (<code>&lt;em&gt;</code>)</label><br>
        <input type="text" name="awards[title]" value="<?php echo esc_attr( $title ); ?>" style="width:100%;">
    </p>

    <hr>
    <h4>Награды</h4>

    <div id="awards-items-wrap">
        <?php if ( ! empty( $items ) ) : foreach ( $items as $i => $item ) :
            $badge_type = $item['badge_type'] ?? 'image';
        ?>
        <div class="award-item-block" style="border:1px solid #ccc;padding:14px;margin-bottom:14px;background:#fafafa;">

            <p>
                <label><b>Тип бейджа</b></label><br>
                <select name="awards[items][<?php echo $i; ?>][badge_type]" class="award-badge-type-sel">
                    <option value="image" <?php selected( $badge_type, 'image' ); ?>>Изображение (img)</option>
                    <option value="iframe" <?php selected( $badge_type, 'iframe' ); ?>>Виджет (iframe)</option>
                </select>
            </p>

            <!-- Image badge -->
            <div class="award-badge-fields-image" style="<?php echo $badge_type === 'image' ? '' : 'display:none;'; ?>">
                <p>
                    <label><b>Логотип (изображение)</b></label><br>
                    <img class="award-logo-preview" src="<?php echo esc_url( $item['logo'] ?? '' ); ?>" style="max-width:200px;height:auto;display:<?php echo ( $item['logo'] ?? '' ) ? 'block' : 'none'; ?>;margin:6px 0;border:1px solid #ddd;">
                    <input type="hidden" name="awards[items][<?php echo $i; ?>][logo]" value="<?php echo esc_attr( $item['logo'] ?? '' ); ?>" class="award-logo-input">
                    <button type="button" class="button award-upload-logo">📁 Загрузить</button>
                    <button type="button" class="button award-remove-logo" style="<?php echo ( $item['logo'] ?? '' ) ? '' : 'display:none;'; ?>">✕</button>
                </p>
            </div>

            <!-- Iframe badge -->
            <div class="award-badge-fields-iframe" style="<?php echo $badge_type === 'iframe' ? '' : 'display:none;'; ?>">
                <p>
                    <label><b>iframe src</b></label><br>
                    <input type="text" name="awards[items][<?php echo $i; ?>][iframe_src]" value="<?php echo esc_attr( $item['iframe_src'] ?? '' ); ?>" style="width:100%;" placeholder="https://yandex.ru/sprav/widget/...">
                </p>
                <p>
                    <label><b>iframe ширина</b> (px)</label><br>
                    <input type="number" name="awards[items][<?php echo $i; ?>][iframe_w]" value="<?php echo esc_attr( $item['iframe_w'] ?? '150' ); ?>" style="width:100px;" min="1">
                </p>
                <p>
                    <label><b>iframe высота</b> (px)</label><br>
                    <input type="number" name="awards[items][<?php echo $i; ?>][iframe_h]" value="<?php echo esc_attr( $item['iframe_h'] ?? '50' ); ?>" style="width:100px;" min="1">
                </p>
                <p>
                    <label><b>iframe title</b></label><br>
                    <input type="text" name="awards[items][<?php echo $i; ?>][iframe_title]" value="<?php echo esc_attr( $item['iframe_title'] ?? '' ); ?>" style="width:100%;">
                </p>
            </div>

            <hr style="margin:8px 0;">

            <p>
                <label><b>Источник</b> (например «Яндекс Карты»)</label><br>
                <input type="text" name="awards[items][<?php echo $i; ?>][source]" value="<?php echo esc_attr( $item['source'] ?? '' ); ?>" style="width:100%;max-width:400px;">
            </p>

            <p>
                <label><b>Название награды</b></label><br>
                <input type="text" name="awards[items][<?php echo $i; ?>][award_title]" value="<?php echo esc_attr( $item['award_title'] ?? '' ); ?>" style="width:100%;">
            </p>

            <p>
                <label><b>Описание</b></label><br>
                <textarea name="awards[items][<?php echo $i; ?>][desc]" rows="3" style="width:100%;"><?php echo esc_textarea( $item['desc'] ?? '' ); ?></textarea>
            </p>

            <p>
                <label><b>Годы</b> (каждый год на новой строке)</label><br>
                <textarea name="awards[items][<?php echo $i; ?>][years]" rows="3" style="width:200px;" placeholder="2026&#10;2025&#10;2024"><?php echo esc_textarea( $item['years'] ?? '' ); ?></textarea>
            </p>

            <hr style="margin:10px 0;">
            <button type="button" class="button award-remove-item" style="color:#a00;">Удалить награду</button>
        </div>
        <?php endforeach; endif; ?>
    </div>

    <button type="button" class="button" id="award-add-item">+ Добавить награду</button>

    <script>
    (function($){
        var idx = $('#awards-items-wrap .award-item-block').length;

        // Переключатель типа бейджа
        $(document).on('change', '.award-badge-type-sel', function(){
            var block = $(this).closest('.award-item-block');
            var t = $(this).val();
            block.find('.award-badge-fields-image').toggle( t === 'image' );
            block.find('.award-badge-fields-iframe').toggle( t === 'iframe' );
        });

        // Добавить награду
        $('#award-add-item').on('click', function(){
            var h = '<div class="award-item-block" style="border:1px solid #ccc;padding:14px;margin-bottom:14px;background:#fafafa;">'
                + '<p><label><b>Тип бейджа</b></label><br>'
                + '<select name="awards[items][' + idx + '][badge_type]" class="award-badge-type-sel">'
                + '<option value="image">Изображение (img)</option>'
                + '<option value="iframe">Виджет (iframe)</option></select></p>'
                + '<div class="award-badge-fields-image">'
                + '<p><label><b>Логотип (изображение)</b></label><br>'
                + '<img class="award-logo-preview" src="" style="max-width:200px;height:auto;display:none;margin:6px 0;border:1px solid #ddd;">'
                + '<input type="hidden" name="awards[items][' + idx + '][logo]" value="" class="award-logo-input">'
                + '<button type="button" class="button award-upload-logo">📁 Загрузить</button> '
                + '<button type="button" class="button award-remove-logo" style="display:none;">✕</button></p></div>'
                + '<div class="award-badge-fields-iframe" style="display:none;">'
                + '<p><label><b>iframe src</b></label><br>'
                + '<input type="text" name="awards[items][' + idx + '][iframe_src]" style="width:100%;"></p>'
                + '<p><label><b>iframe ширина</b> (px)</label><br>'
                + '<input type="number" name="awards[items][' + idx + '][iframe_w]" value="150" style="width:100px;" min="1"></p>'
                + '<p><label><b>iframe высота</b> (px)</label><br>'
                + '<input type="number" name="awards[items][' + idx + '][iframe_h]" value="50" style="width:100px;" min="1"></p>'
                + '<p><label><b>iframe title</b></label><br>'
                + '<input type="text" name="awards[items][' + idx + '][iframe_title]" style="width:100%;"></p></div>'
                + '<hr style="margin:8px 0;">'
                + '<p><label><b>Источник</b></label><br>'
                + '<input type="text" name="awards[items][' + idx + '][source]" style="width:100%;max-width:400px;"></p>'
                + '<p><label><b>Название награды</b></label><br>'
                + '<input type="text" name="awards[items][' + idx + '][award_title]" style="width:100%;"></p>'
                + '<p><label><b>Описание</b></label><br>'
                + '<textarea name="awards[items][' + idx + '][desc]" rows="3" style="width:100%;"></textarea></p>'
                + '<p><label><b>Годы</b> (каждый год на новой строке)</label><br>'
                + '<textarea name="awards[items][' + idx + '][years]" rows="3" style="width:200px;"></textarea></p>'
                + '<hr style="margin:10px 0;">'
                + '<button type="button" class="button award-remove-item" style="color:#a00;">Удалить награду</button>'
                + '</div>';
            $('#awards-items-wrap').append(h);
            idx++;
        });

        // Удалить
        $(document).on('click', '.award-remove-item', function(){
            if( confirm('Удалить эту награду?') ) $(this).closest('.award-item-block').remove();
        });

        // Загрузка логотипа
        $(document).on('click', '.award-upload-logo', function(){
            var btn = this;
            var block = $(btn).closest('.award-item-block');
            var frame = wp.media({ title: 'Выбрать логотип награды', multiple: false, library: { type: 'image' } });
            frame.on('select', function(){
                var url = frame.state().get('selection').first().toJSON().url;
                block.find('.award-logo-input').val(url);
                block.find('.award-logo-preview').attr('src', url).show();
                block.find('.award-remove-logo').show();
            });
            frame.open();
        });

        // Удалить логотип
        $(document).on('click', '.award-remove-logo', function(){
            var block = $(this).closest('.award-item-block');
            block.find('.award-logo-input').val('');
            block.find('.award-logo-preview').hide().attr('src', '');
            $(this).hide();
        });
    })(jQuery);
    </script>
    <?php
}

// Сохранение
add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['awards_section_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['awards_section_nonce_field'], 'awards_section_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['awards'] ?? [];

    $data = [
        'eyebrow' => sanitize_text_field( $raw['eyebrow'] ?? '' ),
        'title'   => wp_kses_post( $raw['title'] ?? '' ),
        'items'   => [],
    ];

    if ( ! empty( $raw['items'] ) && is_array( $raw['items'] ) ) {
        foreach ( $raw['items'] as $item ) {
            $award_title = sanitize_text_field( $item['award_title'] ?? '' );
            if ( empty( $award_title ) ) continue;

            $badge_type = ( $item['badge_type'] === 'iframe' ) ? 'iframe' : 'image';

            // Годы: разбиваем по строкам, фильтруем
            $years_raw = $item['years'] ?? '';
            $years = [];
            if ( ! empty( $years_raw ) ) {
                $lines = explode( "\n", $years_raw );
                foreach ( $lines as $line ) {
                    $y = trim( sanitize_text_field( $line ) );
                    if ( $y !== '' ) $years[] = $y;
                }
            }

            $data['items'][] = [
                'badge_type'   => $badge_type,
                'logo'         => esc_url_raw( $item['logo'] ?? '' ),
                'iframe_src'   => esc_url_raw( $item['iframe_src'] ?? '' ),
                'iframe_w'     => absint( $item['iframe_w'] ?? 150 ),
                'iframe_h'     => absint( $item['iframe_h'] ?? 50 ),
                'iframe_title' => sanitize_text_field( $item['iframe_title'] ?? '' ),
                'source'       => sanitize_text_field( $item['source'] ?? '' ),
                'award_title'  => $award_title,
                'desc'         => sanitize_textarea_field( $item['desc'] ?? '' ),
                'years'        => $years,
            ];
        }
    }

    update_post_meta( $post_id, 'awards', $data );
} );