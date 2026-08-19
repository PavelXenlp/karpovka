<?php
/**
 * Meta box: Events Includes Section (events_includes)
 */

add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'events_includes_mb',
        'Секция: Что включено',
        'events_includes_mb_callback',
        'page',
        'normal',
        'high'
    );
} );

function events_includes_mb_callback( $post ) {

    wp_nonce_field( 'events_includes_save', 'events_includes_nonce' );

    $v     = get_post_meta( $post->ID, 'events_includes', true ) ?: [];
    $items = $v['items'] ?? [];

    ?>
    <style>
        #events_includes_mb .field-group { margin-bottom: 14px; }
        #events_includes_mb label { display: block; font-weight: 600; margin-bottom: 4px; }
        #events_includes_mb input[type="text"],
        #events_includes_mb textarea { width: 100%; }
        #events_includes_mb .ei-item { background: #fff; border: 1px solid #dcdcde; padding: 14px; margin-bottom: 10px; }
        #events_includes_mb .ei-item .ei-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        #events_includes_mb .ei-item .ei-head strong { font-size: 13px; color: #555; }
        #events_includes_mb .remove-item { color: #b00; border: none; cursor: pointer; }
    </style>

    <div class="field-group">
        <label>Аннотация (eyebrow)</label>
        <input type="text" name="events_includes[eyebrow]" value="<?php echo esc_attr( $v['eyebrow'] ?? '' ); ?>" />
    </div>

    <div class="field-group">
        <label>Заголовок (до gold-текста)</label>
        <input type="text" name="events_includes[title]" value="<?php echo esc_attr( $v['title'] ?? '' ); ?>" />
    </div>

    <div class="field-group">
        <label>Gold-текст (обёрнется в &lt;em&gt;)</label>
        <input type="text" name="events_includes[gold_text]" value="<?php echo esc_attr( $v['gold_text'] ?? '' ); ?>" />
    </div>

    <div class="field-group">
        <label>Подзаголовок</label>
        <textarea name="events_includes[subtitle]" rows="2"><?php echo esc_textarea( $v['subtitle'] ?? '' ); ?></textarea>
    </div>

    <div class="field-group">
        <label>ID секции (anchor)</label>
        <input type="text" name="events_includes[section_id]" value="<?php echo esc_attr( $v['section_id'] ?? '' ); ?>" />
    </div>

    <hr />

    <h4>Пункты «Что включено»</h4>
    <div id="ei_items_wrap">
    <?php if ( ! empty( $items ) && is_array( $items ) ) :
        foreach ( $items as $i => $it ) : ?>
        <div class="ei-item">
            <div class="ei-head">
                <strong><?php echo esc_html( mb_substr( $it['title'] ?? '', 0, 30 ) ); ?></strong>
                <button type="button" class="remove-item button button-small">Удалить</button>
            </div>
            <div class="field-group">
                <label>Иконка (FA класс)</label>
                <input type="text" name="events_includes[items][<?php echo $i; ?>][icon]" value="<?php echo esc_attr( $it['icon'] ?? '' ); ?>" placeholder="fa-solid fa-utensils" />
            </div>
            <div class="field-group">
                <label>Название</label>
                <input type="text" name="events_includes[items][<?php echo $i; ?>][title]" value="<?php echo esc_attr( $it['title'] ?? '' ); ?>" />
            </div>
            <div class="field-group">
                <label>Описание</label>
                <textarea name="events_includes[items][<?php echo $i; ?>][text]" rows="3"><?php echo esc_textarea( $it['text'] ?? '' ); ?></textarea>
            </div>
        </div>
        <?php endforeach;
    endif; ?>
    </div>

    <button type="button" class="button" id="add_ei_item">+ Пункт</button>

    <script>
    jQuery(function($){

        var count = 0;
        $('#ei_items_wrap .ei-item').each(function(){
            var name = $(this).find('input[name*="[title]"]').attr('name');
            if(name){
                var m = name.match(/\[items\]\[(\d+)\]/);
                if(m) var idx = parseInt(m[1]);
                if(idx >= count) count = idx + 1;
            }
        });

        $('#add_ei_item').on('click', function(){
            var i = count; count++;
            var h =
                '<div class="ei-item">' +
                    '<div class="ei-head"><strong>Новый пункт</strong><button type="button" class="remove-item button button-small">Удалить</button></div>' +
                    '<div class="field-group"><label>Иконка (FA класс)</label><input type="text" name="events_includes[items][' + i + '][icon]" value="" placeholder="fa-solid fa-utensils" /></div>' +
                    '<div class="field-group"><label>Название</label><input type="text" name="events_includes[items][' + i + '][title]" value="" /></div>' +
                    '<div class="field-group"><label>Описание</label><textarea name="events_includes[items][' + i + '][text]" rows="3"></textarea></div>' +
                '</div>';
            $('#ei_items_wrap').append(h);
        });

        $('#ei_items_wrap').on('click', '.remove-item', function(){
            if( confirm('Удалить?') ) $(this).closest('.ei-item').remove();
        });

    });
    </script>
    <?php
}

/* ── Save ────────────────────────────────────────── */
add_action( 'save_post', function ( $post_id ) {

    if ( ! isset( $_POST['events_includes_nonce'] ) || ! wp_verify_nonce( $_POST['events_includes_nonce'], 'events_includes_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['events_includes'] ?? [];

    $clean = [
        'eyebrow'    => sanitize_text_field( $raw['eyebrow']    ?? '' ),
        'title'      => sanitize_text_field( $raw['title']      ?? '' ),
        'gold_text'  => sanitize_text_field( $raw['gold_text']  ?? '' ),
        'subtitle'   => sanitize_textarea_field( $raw['subtitle'] ?? '' ),
        'section_id' => sanitize_text_field( $raw['section_id'] ?? '' ),
        'items'      => [],
    ];

    if ( ! empty( $raw['items'] ) && is_array( $raw['items'] ) ) {
        foreach ( $raw['items'] as $it ) {
            $t = sanitize_text_field( $it['title'] ?? '' );
            if ( $t === '' ) continue;
            $clean['items'][] = [
                'icon'  => sanitize_text_field( $it['icon'] ?? '' ),
                'title' => $t,
                'text'  => sanitize_textarea_field( $it['text'] ?? '' ),
            ];
        }
    }

    update_post_meta( $post_id, 'events_includes', $clean );
} );