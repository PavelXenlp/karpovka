<?php
// Meta box: Наши преимущества (marquee)
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'advantages_section', 'Наши преимущества (marquee)', 'advantages_section_cb', 'page', 'normal', 'high' );
} );

function advantages_section_cb( $post ) {
    wp_nonce_field( 'advantages_section_nonce', 'advantages_section_nonce_field' );
    $v = get_post_meta( $post->ID, 'advantages', true );

    $eyebrow   = $v['eyebrow'] ?? '';
    $title     = $v['title'] ?? '';
    $rows      = $v['rows'] ?? [];
    ?>

    <p>
        <label><b>Бровь (надзаголовок)</b></label><br>
        <input type="text" name="advantages[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>" style="width:100%;max-width:500px;">
    </p>

    <p>
        <label><b>Заголовок</b> (можно использовать <code>&lt;em&gt;...&lt;/em&gt;</code>)</label><br>
        <input type="text" name="advantages[title]" value="<?php echo esc_attr( $title ); ?>" style="width:100%;">
    </p>

    <hr>
    <h4>Строки маркиза</h4>
    <p class="description">Каждая строка — отдельная полоса с элементами. Направление и скорость задаются для строки.</p>

    <div id="adv-rows-wrap">
        <?php if ( ! empty( $rows ) ) : foreach ( $rows as $i => $row ) : ?>
        <div class="adv-row-block" style="border:1px solid #ccc;padding:12px;margin-bottom:12px;background:#fafafa;">
            <p>
                <label>Скорость (px/сек)</label><br>
                <input type="number" name="advantages[rows][<?php echo $i; ?>][speed]" value="<?php echo esc_attr( $row['speed'] ?? '55' ); ?>" min="10" max="200" style="width:120px;">
            </p>
            <p>
                <label>Направление</label><br>
                <select name="advantages[rows][<?php echo $i; ?>][direction]">
                    <option value="left" <?php selected( $row['direction'] ?? 'left', 'left' ); ?>>Влево</option>
                    <option value="right" <?php selected( $row['direction'] ?? 'left', 'right' ); ?>>Вправо</option>
                </select>
            </p>

            <div class="adv-items-wrap">
                <?php $items = $row['items'] ?? []; if ( ! empty( $items ) ) : foreach ( $items as $j => $item ) : ?>
                <div class="adv-item-row" style="margin-bottom:6px;display:flex;gap:6px;align-items:center;">
                    <input type="text" name="advantages[rows][<?php echo $i; ?>][items][<?php echo $j; ?>][text]" value="<?php echo esc_attr( $item['text'] ?? '' ); ?>" placeholder="Текст элемента" style="flex:1;">
                    <button type="button" class="button adv-remove-item">Удалить</button>
                </div>
                <?php endforeach; endif; ?>
            </div>
            <button type="button" class="button adv-add-item" data-row="<?php echo $i; ?>">+ Добавить элемент</button>

            <hr style="margin:10px 0;">
            <button type="button" class="button adv-remove-row" style="color:#a00;">Удалить строку</button>
        </div>
        <?php endforeach; endif; ?>
    </div>

    <button type="button" class="button" id="adv-add-row">+ Добавить строку</button>

    <script>
    (function($){
        var rowIdx = $('#adv-rows-wrap .adv-row-block').length;

        // Добавить строку
        $('#adv-add-row').on('click', function(){
            var h = '<div class="adv-row-block" style="border:1px solid #ccc;padding:12px;margin-bottom:12px;background:#fafafa;">'
                + '<p><label>Скорость (px/сек)</label><br>'
                + '<input type="number" name="advantages[rows][' + rowIdx + '][speed]" value="55" min="10" max="200" style="width:120px;"></p>'
                + '<p><label>Направление</label><br>'
                + '<select name="advantages[rows][' + rowIdx + '][direction]">'
                + '<option value="left">Влево</option><option value="right">Вправо</option></select></p>'
                + '<div class="adv-items-wrap"></div>'
                + '<button type="button" class="button adv-add-item" data-row="' + rowIdx + '">+ Добавить элемент</button>'
                + '<hr style="margin:10px 0;">'
                + '<button type="button" class="button adv-remove-row" style="color:#a00;">Удалить строку</button>'
                + '</div>';
            $('#adv-rows-wrap').append(h);
            rowIdx++;
        });

        // Удалить строку
        $(document).on('click', '.adv-remove-row', function(){
            if( confirm('Удалить эту строку?') ) $(this).closest('.adv-row-block').remove();
        });

        // Добавить элемент
        $(document).on('click', '.adv-add-item', function(){
            var r = $(this).data('row');
            var wrap = $(this).prev('.adv-items-wrap');
            var itemIdx = wrap.find('.adv-item-row').length;
            wrap.append(
                '<div class="adv-item-row" style="margin-bottom:6px;display:flex;gap:6px;align-items:center;">'
                + '<input type="text" name="advantages[rows][' + r + '][items][' + itemIdx + '][text]" placeholder="Текст элемента" style="flex:1;">'
                + '<button type="button" class="button adv-remove-item">Удалить</button>'
                + '</div>'
            );
        });

        // Удалить элемент
        $(document).on('click', '.adv-remove-item', function(){
            $(this).closest('.adv-item-row').remove();
        });
    })(jQuery);
    </script>
    <?php
}

// Сохранение
add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['advantages_section_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['advantages_section_nonce_field'], 'advantages_section_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['advantages'] ?? [];

    $data = [
        'eyebrow' => sanitize_text_field( $raw['eyebrow'] ?? '' ),
        'title'   => wp_kses_post( $raw['title'] ?? '' ),
        'rows'    => [],
    ];

    if ( ! empty( $raw['rows'] ) && is_array( $raw['rows'] ) ) {
        foreach ( $raw['rows'] as $row ) {
            $items = [];
            if ( ! empty( $row['items'] ) && is_array( $row['items'] ) ) {
                foreach ( $row['items'] as $item ) {
                    $t = sanitize_text_field( $item['text'] ?? '' );
                    if ( $t !== '' ) $items[] = [ 'text' => $t ];
                }
            }
            if ( ! empty( $items ) ) {
                $data['rows'][] = [
                    'speed'     => absint( $row['speed'] ?? 55 ),
                    'direction' => ( $row['direction'] === 'right' ) ? 'right' : 'left',
                    'items'     => $items,
                ];
            }
        }
    }

    update_post_meta( $post_id, 'advantages', $data );
} );