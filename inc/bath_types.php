<?php
/**
 * Meta box: Bath Types — карточки видов бань
 * Каждая карточка ссылается на секцию bath_details по ID
 */

add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'bath_types_mb',
        'Направления (виды бань)',
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
        #bath_types_mb .card-block .head-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        #bath_types_mb .card-block .head-row strong { font-size: 13px; color: #555; }
        #bath_types_mb .remove-card { color: #b00; border: none; cursor: pointer; }
    </style>

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

    <h4>Карточки видов бань</h4>
    <p class="description">Поле «Якорь (ID секции bath_details)» — сюда впишите <strong>section_id</strong> соответствующей детальной секции из блока «Бани — Детальные описания». Карточка будет ссылаться на <code>#этот-id</code>.</p>

    <div id="bath_cards_wrap">
    <?php if ( ! empty( $cards ) && is_array( $cards ) ) :
        foreach ( $cards as $i => $c ) : ?>
        <div class="card-block">
            <div class="head-row">
                <strong><?php echo esc_html( mb_substr( $c['card_title'] ?? '', 0, 30 ) ); ?></strong>
                <button type="button" class="remove-card button button-small">Удалить</button>
            </div>

            <div class="field-group">
                <label>Якорь (ID секции bath_details)</label>
                <input type="text" name="bath_types[cards][<?php echo $i; ?>][link]" value="<?php echo esc_attr( $c['link'] ?? '' ); ?>" placeholder="russian-bath" />
                <span class="description">Без решётки. Итоговая ссылка: <code>#<?php echo esc_attr( $c['link'] ?? '...' ); ?></code></span>
            </div>

            <div class="field-group">
                <label>Изображение</label>
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

        /* Add card */
        var idx = $('#bath_cards_wrap .card-block').length;
        $('#add_bath_card').on('click', function(){
            var i = idx;
            idx++;
            var h =
                '<div class="card-block">' +
                    '<div class="head-row"><strong>Новая баня</strong><button type="button" class="remove-card button button-small">Удалить</button></div>' +
                    '<div class="field-group"><label>Якорь (ID секции bath_details)</label><input type="text" name="bath_types[cards][' + i + '][link]" value="" placeholder="russian-bath" /></div>' +
                    '<div class="field-group"><label>Изображение</label><input type="text" name="bath_types[cards][' + i + '][card_image]" class="bath-card-img" value="" /><button type="button" class="button bath-card-img-btn">Выбрать</button></div>' +
                    '<div class="field-group"><label>Тег</label><input type="text" name="bath_types[cards][' + i + '][card_tag]" value="" /></div>' +
                    '<div class="field-group"><label>Название</label><input type="text" name="bath_types[cards][' + i + '][card_title]" value="" /></div>' +
                    '<div class="field-group"><label>Описание</label><input type="text" name="bath_types[cards][' + i + '][card_desc]" value="" /></div>' +
                    '<div class="field-group"><label>Стиль карточки</label><select name="bath_types[cards][' + i + '][card_style]"><option value="">Обычная</option><option value="wide">Широкая</option><option value="wide-alt">Широкая альт</option></select></div>' +
                '</div>';
            $('#bath_cards_wrap').append(h);
        });

        /* Remove card */
        $('#bath_cards_wrap').on('click', '.remove-card', function(){
            if( confirm('Удалить?') ) $(this).closest('.card-block').remove();
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
            if ( $ct === '' ) continue;

            $clean['cards'][] = [
                'link'       => sanitize_text_field( $c['link'] ?? '' ),
                'card_image' => esc_url_raw( $c['card_image'] ?? '' ),
                'card_tag'   => sanitize_text_field( $c['card_tag'] ?? '' ),
                'card_title' => $ct,
                'card_desc'  => sanitize_text_field( $c['card_desc'] ?? '' ),
                'card_style' => sanitize_text_field( $c['card_style'] ?? '' ),
            ];
        }
    }

    update_post_meta( $post_id, 'bath_types', $clean );
} );