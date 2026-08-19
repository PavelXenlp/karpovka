<?php
/**
 * Meta box: Dining Booking Section (dining_booking)
 * Аккордеон с нумерованными вопросами
 */

add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'dining_booking_mb',
        'Секция: Условия бронирования',
        'dining_booking_mb_callback',
        'page',
        'normal',
        'high'
    );
} );

function dining_booking_mb_callback( $post ) {

    wp_nonce_field( 'dining_booking_save', 'dining_booking_nonce' );

    $v = get_post_meta( $post->ID, 'dining_booking', true ) ?: [];

    $eyebrow    = $v['eyebrow']    ?? '';
    $title      = $v['title']      ?? '';
    $gold_text  = $v['gold_text']  ?? '';
    $subtitle   = $v['subtitle']   ?? '';
    $section_id = $v['section_id'] ?? 'booking';
    $items      = $v['items']      ?? [];

    ?>
    <style>
        #dining_booking_mb .field-group { margin-bottom: 14px; }
        #dining_booking_mb label { display: block; font-weight: 600; margin-bottom: 4px; }
        #dining_booking_mb input[type="text"],
        #dining_booking_mb textarea { width: 100%; }
        #dining_booking_mb .db-item { background: #fff; border: 1px solid #dcdcde; padding: 14px; margin-bottom: 10px; }
        #dining_booking_mb .db-item .db-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        #dining_booking_mb .db-item .db-head strong { font-size: 13px; color: #555; }
        #dining_booking_mb .remove-row { color: #b00; border: none; cursor: pointer; }
        #dining_booking_mb .db-item .num-field { max-width: 60px; }
    </style>

    <div class="field-group">
        <label>Аннотация (eyebrow)</label>
        <input type="text" name="dining_booking[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>" />
    </div>

    <div class="field-group">
        <label>Заголовок (до gold-текста)</label>
        <input type="text" name="dining_booking[title]" value="<?php echo esc_attr( $title ); ?>" />
    </div>

    <div class="field-group">
        <label>Gold-текст (обёрнется в &lt;em&gt;)</label>
        <input type="text" name="dining_booking[gold_text]" value="<?php echo esc_attr( $gold_text ); ?>" />
    </div>

    <div class="field-group">
        <label>Подзаголовок</label>
        <textarea name="dining_booking[subtitle]" rows="2"><?php echo esc_textarea( $subtitle ); ?></textarea>
    </div>

    <div class="field-group">
        <label>ID секции (anchor)</label>
        <input type="text" name="dining_booking[section_id]" value="<?php echo esc_attr( $section_id ); ?>" />
    </div>

    <hr />

    <h4>Вопросы (аккордеон)</h4>
    <div id="db_items_wrap">
    <?php if ( ! empty( $items ) && is_array( $items ) ) :
        foreach ( $items as $i => $item ) : ?>
        <div class="db-item">
            <div class="db-head">
                <strong><?php echo esc_html( mb_substr( $item['question'] ?? '', 0, 40 ) ); ?></strong>
                <button type="button" class="remove-row button button-small">Удалить</button>
            </div>
            <div class="field-group">
                <label>Номер</label>
                <input type="text" class="num-field" name="dining_booking[items][<?php echo $i; ?>][num]" value="<?php echo esc_attr( $item['num'] ?? '' ); ?>" />
            </div>
            <div class="field-group">
                <label>Вопрос</label>
                <input type="text" name="dining_booking[items][<?php echo $i; ?>][question]" value="<?php echo esc_attr( $item['question'] ?? '' ); ?>" />
            </div>
            <div class="field-group">
                <label>Ответ</label>
                <textarea name="dining_booking[items][<?php echo $i; ?>][answer]" rows="4"><?php echo esc_textarea( $item['answer'] ?? '' ); ?></textarea>
            </div>
        </div>
        <?php endforeach;
    endif; ?>
    </div>

    <button type="button" class="button" id="add_db_item">+ Вопрос</button>

    <script>
    jQuery(function($){

        var itemCount = $('#db_items_wrap .db-item').length;

        $('#db_items_wrap .db-item').each(function(){
            var name = $(this).find('input[name*="[num]"]').attr('name');
            if(name){
                var m = name.match(/\[items\]\[(\d+)\]/);
                if(m) var idx = parseInt(m[1]);
                if(idx >= itemCount) itemCount = idx + 1;
            }
        });

        $('#add_db_item').on('click', function(){
            var i = itemCount; itemCount++;
            var num = String(i + 1).padStart(2, '0');
            var h =
                '<div class="db-item">' +
                    '<div class="db-head"><strong>Новый вопрос</strong><button type="button" class="remove-row button button-small">Удалить</button></div>' +
                    '<div class="field-group"><label>Номер</label><input type="text" class="num-field" name="dining_booking[items][' + i + '][num]" value="' + num + '" /></div>' +
                    '<div class="field-group"><label>Вопрос</label><input type="text" name="dining_booking[items][' + i + '][question]" value="" /></div>' +
                    '<div class="field-group"><label>Ответ</label><textarea name="dining_booking[items][' + i + '][answer]" rows="4"></textarea></div>' +
                '</div>';
            $('#db_items_wrap').append(h);
        });

        $('#db_items_wrap').on('click', '.remove-row', function(){
            if( confirm('Удалить?') ) $(this).closest('.db-item').remove();
        });

    });
    </script>
    <?php
}

/* ── Save ────────────────────────────────────────── */
add_action( 'save_post', function ( $post_id ) {

    if ( ! isset( $_POST['dining_booking_nonce'] ) || ! wp_verify_nonce( $_POST['dining_booking_nonce'], 'dining_booking_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['dining_booking'] ?? [];

    $clean = [
        'eyebrow'    => sanitize_text_field( $raw['eyebrow']    ?? '' ),
        'title'      => sanitize_text_field( $raw['title']      ?? '' ),
        'gold_text'  => sanitize_text_field( $raw['gold_text']  ?? '' ),
        'subtitle'   => sanitize_textarea_field( $raw['subtitle'] ?? '' ),
        'section_id' => sanitize_text_field( $raw['section_id'] ?? '' ),
        'items'      => [],
    ];

    if ( ! empty( $raw['items'] ) && is_array( $raw['items'] ) ) {
        foreach ( $raw['items'] as $item ) {
            $q = sanitize_text_field( $item['question'] ?? '' );
            if ( $q !== '' ) {
                $clean['items'][] = [
                    'num'      => sanitize_text_field( $item['num'] ?? '' ),
                    'question' => $q,
                    'answer'   => sanitize_textarea_field( $item['answer'] ?? '' ),
                ];
            }
        }
    }

    update_post_meta( $post_id, 'dining_booking', $clean );
} );