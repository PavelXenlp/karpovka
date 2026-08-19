<?php
/**
 * Meta box: Events Weekend Section (events_weekend)
 */

add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'events_weekend_mb',
        'Секция: Программы уикенда',
        'events_weekend_mb_callback',
        'page',
        'normal',
        'high'
    );
} );

function events_weekend_mb_callback( $post ) {

    wp_nonce_field( 'events_weekend_save', 'events_weekend_nonce' );

    $v     = get_post_meta( $post->ID, 'events_weekend', true ) ?: [];
    $cards = $v['cards'] ?? [];

    ?>
    <style>
        #events_weekend_mb .field-group { margin-bottom: 14px; }
        #events_weekend_mb label { display: block; font-weight: 600; margin-bottom: 4px; }
        #events_weekend_mb input[type="text"],
        #events_weekend_mb textarea { width: 100%; }
        #events_weekend_mb .ew-card { background: #fff; border: 1px solid #dcdcde; padding: 14px; margin-bottom: 10px; }
        #events_weekend_mb .ew-card .ew-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        #events_weekend_mb .ew-card .ew-head strong { font-size: 13px; color: #555; }
        #events_weekend_mb .remove-card { color: #b00; border: none; cursor: pointer; }
    </style>

    <div class="field-group">
        <label>Аннотация (eyebrow)</label>
        <input type="text" name="events_weekend[eyebrow]" value="<?php echo esc_attr( $v['eyebrow'] ?? '' ); ?>" />
    </div>

    <div class="field-group">
        <label>Заголовок (до gold-текста)</label>
        <input type="text" name="events_weekend[title]" value="<?php echo esc_attr( $v['title'] ?? '' ); ?>" />
    </div>

    <div class="field-group">
        <label>Gold-текст (обёрнется в &lt;em&gt;)</label>
        <input type="text" name="events_weekend[gold_text]" value="<?php echo esc_attr( $v['gold_text'] ?? '' ); ?>" />
    </div>

    <div class="field-group">
        <label>Подзаголовок</label>
        <textarea name="events_weekend[subtitle]" rows="2"><?php echo esc_textarea( $v['subtitle'] ?? '' ); ?></textarea>
    </div>

    <div class="field-group">
        <label>ID секции (anchor)</label>
        <input type="text" name="events_weekend[section_id]" value="<?php echo esc_attr( $v['section_id'] ?? '' ); ?>" />
    </div>

    <hr />

    <h4>Карточки программ</h4>
    <div id="ew_cards_wrap">
    <?php if ( ! empty( $cards ) && is_array( $cards ) ) :
        foreach ( $cards as $i => $c ) : ?>
        <div class="ew-card">
            <div class="ew-head">
                <strong><?php echo esc_html( mb_substr( $c['title'] ?? '', 0, 30 ) ); ?></strong>
                <button type="button" class="remove-card button button-small">Удалить</button>
            </div>
            <div class="field-group">
                <label>Иконка (FA класс)</label>
                <input type="text" name="events_weekend[cards][<?php echo $i; ?>][icon]" value="<?php echo esc_attr( $c['icon'] ?? '' ); ?>" placeholder="fa-solid fa-champagne-glasses" />
            </div>
            <div class="field-group">
                <label>Название</label>
                <input type="text" name="events_weekend[cards][<?php echo $i; ?>][title]" value="<?php echo esc_attr( $c['title'] ?? '' ); ?>" />
            </div>
            <div class="field-group">
                <label>Описание</label>
                <textarea name="events_weekend[cards][<?php echo $i; ?>][desc]" rows="3"><?php echo esc_textarea( $c['desc'] ?? '' ); ?></textarea>
            </div>
            <div class="field-group">
                <label>Тег</label>
                <input type="text" name="events_weekend[cards][<?php echo $i; ?>][tag]" value="<?php echo esc_attr( $c['tag'] ?? '' ); ?>" placeholder="2–3 дня · до 120 гостей" />
            </div>
        </div>
        <?php endforeach;
    endif; ?>
    </div>

    <button type="button" class="button" id="add_ew_card">+ Карточка</button>

    <script>
    jQuery(function($){

        var count = 0;
        $('#ew_cards_wrap .ew-card').each(function(){
            var name = $(this).find('input[name*="[title]"]').attr('name');
            if(name){
                var m = name.match(/\[cards\]\[(\d+)\]/);
                if(m) var idx = parseInt(m[1]);
                if(idx >= count) count = idx + 1;
            }
        });

        $('#add_ew_card').on('click', function(){
            var i = count; count++;
            var h =
                '<div class="ew-card">' +
                    '<div class="ew-head"><strong>Новая программа</strong><button type="button" class="remove-card button button-small">Удалить</button></div>' +
                    '<div class="field-group"><label>Иконка (FA класс)</label><input type="text" name="events_weekend[cards][' + i + '][icon]" value="" placeholder="fa-solid fa-champagne-glasses" /></div>' +
                    '<div class="field-group"><label>Название</label><input type="text" name="events_weekend[cards][' + i + '][title]" value="" /></div>' +
                    '<div class="field-group"><label>Описание</label><textarea name="events_weekend[cards][' + i + '][desc]" rows="3"></textarea></div>' +
                    '<div class="field-group"><label>Тег</label><input type="text" name="events_weekend[cards][' + i + '][tag]" value="" placeholder="2–3 дня · до 120 гостей" /></div>' +
                '</div>';
            $('#ew_cards_wrap').append(h);
        });

        $('#ew_cards_wrap').on('click', '.remove-card', function(){
            if( confirm('Удалить?') ) $(this).closest('.ew-card').remove();
        });

    });
    </script>
    <?php
}

/* ── Save ────────────────────────────────────────── */
add_action( 'save_post', function ( $post_id ) {

    if ( ! isset( $_POST['events_weekend_nonce'] ) || ! wp_verify_nonce( $_POST['events_weekend_nonce'], 'events_weekend_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['events_weekend'] ?? [];

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
            $t = sanitize_text_field( $c['title'] ?? '' );
            if ( $t === '' ) continue;
            $clean['cards'][] = [
                'icon' => sanitize_text_field( $c['icon'] ?? '' ),
                'title'=> $t,
                'desc' => sanitize_textarea_field( $c['desc'] ?? '' ),
                'tag'  => sanitize_text_field( $c['tag'] ?? '' ),
            ];
        }
    }

    update_post_meta( $post_id, 'events_weekend', $clean );
} );