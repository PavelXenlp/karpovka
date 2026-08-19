<?php
/**
 * Meta box: Directions Section (directions_section)
 */

add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'directions_section_mb',
        'Секция: Как добраться',
        'directions_section_mb_callback',
        'page',
        'normal',
        'high'
    );
} );

function directions_section_mb_callback( $post ) {

    wp_nonce_field( 'directions_section_save', 'directions_section_nonce' );

    $v = get_post_meta( $post->ID, 'directions_section', true ) ?: [];

    $eyebrow     = $v['eyebrow']     ?? '';
    $title       = $v['title']       ?? '';
    $subtitle    = $v['subtitle']    ?? '';
    $section_id  = $v['section_id']  ?? 'directions';
    $side_image  = $v['side_image']  ?? '';
    $primary     = $v['primary']     ?? [];
    $steps       = $v['steps']       ?? [];

    ?>
    <style>
        #directions_section_mb .field-group { margin-bottom: 14px; }
        #directions_section_mb label { display: block; font-weight: 600; margin-bottom: 4px; }
        #directions_section_mb input[type="text"],
        #directions_section_mb textarea { width: 100%; }
        #directions_section_mb .sub-label { display: inline-block; font-weight: 600; margin: 10px 0 4px; font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: .5px; }
        #directions_section_mb .li-row { display: flex; gap: 8px; align-items: flex-end; margin-bottom: 6px; }
        #directions_section_mb .li-row input { flex: 1; }
        #directions_section_mb .ds-step { background: #f9f9f9; border: 1px solid #e0e0e0; padding: 14px; margin-bottom: 12px; }
        #directions_section_mb .ds-step .ds-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        #directions_section_mb .ds-step .ds-head strong { font-size: 13px; color: #555; }
        #directions_section_mb .dir-img-preview { max-width: 150px; max-height: 100px; margin-top: 6px; border: 1px solid #ddd; display: none; }
    </style>

    <!-- Шапка -->
    <div class="field-group">
        <label>Аннотация (eyebrow)</label>
        <input type="text" name="directions_section[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>" />
    </div>

    <div class="field-group">
        <label>Заголовок</label>
        <input type="text" name="directions_section[title]" value="<?php echo esc_attr( $title ); ?>" />
    </div>

    <div class="field-group">
        <label>Подзаголовок</label>
        <textarea name="directions_section[subtitle]" rows="2"><?php echo esc_textarea( $subtitle ); ?></textarea>
    </div>

    <div class="field-group">
        <label>ID секции (anchor)</label>
        <input type="text" name="directions_section[section_id]" value="<?php echo esc_attr( $section_id ); ?>" />
    </div>

    <div class="field-group">
        <label>Фото справа</label>
        <input type="text" name="directions_section[side_image]" id="dir_side_img" value="<?php echo esc_attr( $side_image ); ?>" />
        <img class="dir-img-preview" src="<?php echo esc_url( $side_image ); ?>" alt="" />
        <button type="button" class="button" id="dir_side_img_btn" style="margin-top:4px;">📁 Выбрать</button>
    </div>

    <hr />

    <!-- Primary step (01) -->
    <h4>Шаг 01 — Основной</h4>

    <div class="field-group">
        <label>Иконка (SVG href)</label>
        <input type="text" name="directions_section[primary][icon]" value="<?php echo esc_attr( $primary['icon'] ?? '' ); ?>" placeholder="#i-car" />
    </div>

    <div class="field-group">
        <label>Заголовок</label>
        <input type="text" name="directions_section[primary][title]" value="<?php echo esc_attr( $primary['title'] ?? '' ); ?>" />
    </div>

    <div class="field-group">
        <label>Подзаголовок</label>
        <input type="text" name="directions_section[primary][subtitle]" value="<?php echo esc_attr( $primary['subtitle'] ?? '' ); ?>" />
    </div>

    <div class="field-group">
        <label>Вводный текст (lead)</label>
        <textarea name="directions_section[primary][lead]" rows="3"><?php echo esc_textarea( $primary['lead'] ?? '' ); ?></textarea>
    </div>

    <div class="field-group">
        <label>Список шагов (можно использовать &lt;strong&gt; и &lt;em&gt;)</label>
        <div id="dir_primary_list">
        <?php if ( ! empty( $primary['list'] ) && is_array( $primary['list'] ) ) :
            foreach ( $primary['list'] as $j => $li ) : ?>
            <div class="li-row">
                <input type="text" name="directions_section[primary][list][<?php echo $j; ?>][text]" value="<?php echo esc_textarea( $li['text'] ?? '' ); ?>" placeholder="Текст пункта" />
                <button type="button" class="button button-small remove-pli">✕</button>
            </div>
            <?php endforeach;
        endif; ?>
        </div>
        <button type="button" class="button" id="add_pli">+ Пункт</button>
    </div>

    <div class="field-group">
        <label>Тег (под списком)</label>
        <input type="text" name="directions_section[primary][tag]" value="<?php echo esc_attr( $primary['tag'] ?? '' ); ?>" />
    </div>

    <hr />

    <!-- Secondary steps -->
    <h4>Дополнительные шаги (02, 03…)</h4>
    <div id="dir_steps_wrap">
    <?php if ( ! empty( $steps ) && is_array( $steps ) ) :
        foreach ( $steps as $i => $s ) : ?>
        <div class="ds-step">
            <div class="ds-head">
                <strong><?php echo esc_html( mb_substr( $s['title'] ?? '', 0, 30 ) ); ?></strong>
                <button type="button" class="remove-step button button-small">Удалить</button>
            </div>

            <div class="field-group">
                <label>Номер</label>
                <input type="text" name="directions_section[steps][<?php echo $i; ?>][num]" value="<?php echo esc_attr( $s['num'] ?? '' ); ?>" style="max-width:60px;" />
            </div>

            <div class="field-group">
                <label>Иконка (SVG href)</label>
                <input type="text" name="directions_section[steps][<?php echo $i; ?>][icon]" value="<?php echo esc_attr( $s['icon'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Заголовок</label>
                <input type="text" name="directions_section[steps][<?php echo $i; ?>][title]" value="<?php echo esc_attr( $s['title'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Подзаголовок</label>
                <input type="text" name="directions_section[steps][<?php echo $i; ?>][subtitle]" value="<?php echo esc_attr( $s['subtitle'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Список шагов</label>
                <div class="dir-step-list" data-idx="<?php echo $i; ?>">
                <?php if ( ! empty( $s['list'] ) && is_array( $s['list'] ) ) :
                    foreach ( $s['list'] as $j => $li ) : ?>
                    <div class="li-row">
                        <input type="text" name="directions_section[steps][<?php echo $i; ?>][list][<?php echo $j; ?>][text]" value="<?php echo esc_textarea( $li['text'] ?? '' ); ?>" placeholder="Текст пункта" />
                        <button type="button" class="button button-small remove-sli">✕</button>
                    </div>
                    <?php endforeach;
                endif; ?>
                </div>
                <button type="button" class="button add-sli-btn" data-idx="<?php echo $i; ?>">+ Пункт</button>
            </div>
        </div>
        <?php endforeach;
    endif; ?>
    </div>

    <button type="button" class="button" id="add_dir_step">+ Шаг</button>

    <script>
    jQuery(function($){

        /* Upload side image */
        $('#dir_side_img_btn').on('click', function(){
            var input = $('#dir_side_img');
            var preview = input.next('.dir-img-preview');
            var frame = wp.media({ title:'Выберите фото', multiple:false, library:{type:'image'} });
            frame.on('select', function(){ var u = frame.state().get('selection').first().toJSON().url; input.val(u); preview.attr('src',u).show(); });
            frame.open();
        });

        if( $('#dir_side_img').val() ) $('#dir_side_img').next('.dir-img-preview').show();

        /* Primary list */
        var pliCount = 0;
        $('#dir_primary_list .li-row').each(function(){
            var name = $(this).find('input').first().attr('name');
            if(name){ var m = name.match(/\[list\]\[(\d+)\]/); if(m && parseInt(m[1]) >= pliCount) pliCount = parseInt(m[1]) + 1; }
        });

        $('#add_pli').on('click', function(){
            var j = pliCount; pliCount++;
            var h = '<div class="li-row"><input type="text" name="directions_section[primary][list][' + j + '][text]" value="" placeholder="Текст пункта" /><button type="button" class="button button-small remove-pli">✕</button></div>';
            $('#dir_primary_list').append(h);
        });
        $('#dir_primary_list').on('click', '.remove-pli', function(){ $(this).closest('.li-row').remove(); });

        /* Step count */
        var stepCount = 0;
        $('#dir_steps_wrap .ds-step').each(function(){
            var name = $(this).find('input[name*="[num]"]').attr('name');
            if(name){ var m = name.match(/\[steps\]\[(\d+)\]/); if(m && parseInt(m[1]) >= stepCount) stepCount = parseInt(m[1]) + 1; }
        });

        /* Add step */
        $('#add_dir_step').on('click', function(){
            var i = stepCount; stepCount++;
            var num = String(i + 2).padStart(2, '0');
            var h =
                '<div class="ds-step">' +
                    '<div class="ds-head"><strong>Новый шаг</strong><button type="button" class="remove-step button button-small">Удалить</button></div>' +
                    '<div class="field-group"><label>Номер</label><input type="text" name="directions_section[steps][' + i + '][num]" value="' + num + '" style="max-width:60px;" /></div>' +
                    '<div class="field-group"><label>Иконка (SVG href)</label><input type="text" name="directions_section[steps][' + i + '][icon]" value="" /></div>' +
                    '<div class="field-group"><label>Заголовок</label><input type="text" name="directions_section[steps][' + i + '][title]" value="" /></div>' +
                    '<div class="field-group"><label>Подзаголовок</label><input type="text" name="directions_section[steps][' + i + '][subtitle]" value="" /></div>' +
                    '<div class="field-group"><label>Список шагов</label><div class="dir-step-list" data-idx="' + i + '"></div><button type="button" class="button add-sli-btn" data-idx="' + i + '">+ Пункт</button></div>' +
                '</div>';
            $('#dir_steps_wrap').append(h);
        });

        /* Remove step */
        $('#dir_steps_wrap').on('click', '.remove-step', function(){
            if( confirm('Удалить?') ) $(this).closest('.ds-step').remove();
        });

        /* Add step list item */
        $('#dir_steps_wrap').on('click', '.add-sli-btn', function(){
            var wrap = $(this).prev('.dir-step-list');
            var si = $(this).data('idx');
            var j = wrap.find('.li-row').length;
            var h = '<div class="li-row"><input type="text" name="directions_section[steps][' + si + '][list][' + j + '][text]" value="" placeholder="Текст пункта" /><button type="button" class="button button-small remove-sli">✕</button></div>';
            wrap.append(h);
        });

        /* Remove step list item */
        $('#dir_steps_wrap').on('click', '.remove-sli', function(){
            $(this).closest('.li-row').remove();
        });

    });
    </script>
    <?php
}

/* ── Save ────────────────────────────────────────── */
add_action( 'save_post', function ( $post_id ) {

    if ( ! isset( $_POST['directions_section_nonce'] ) || ! wp_verify_nonce( $_POST['directions_section_nonce'], 'directions_section_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['directions_section'] ?? [];

    /* Helper: sanitize list items, allow <strong> <em> */
    $sanitize_list = function( $arr ) {
        $out = [];
        if ( ! is_array( $arr ) ) return $out;
        foreach ( $arr as $item ) {
            $t = wp_kses_post( $item['text'] ?? '' );
            if ( $t !== '' ) $out[] = [ 'text' => $t ];
        }
        return $out;
    };

    $primary = $raw['primary'] ?? [];
    $clean_primary = [
        'icon'     => sanitize_text_field( $primary['icon']     ?? '' ),
        'title'    => sanitize_text_field( $primary['title']    ?? '' ),
        'subtitle' => sanitize_text_field( $primary['subtitle'] ?? '' ),
        'lead'     => wp_kses_post( $primary['lead'] ?? '' ),
        'tag'      => sanitize_text_field( $primary['tag']      ?? '' ),
        'list'     => $sanitize_list( $primary['list'] ?? [] ),
    ];

    $clean_steps = [];
    if ( ! empty( $raw['steps'] ) && is_array( $raw['steps'] ) ) {
        foreach ( $raw['steps'] as $s ) {
            $t = sanitize_text_field( $s['title'] ?? '' );
            if ( $t === '' ) continue;
            $clean_steps[] = [
                'num'      => sanitize_text_field( $s['num']      ?? '' ),
                'icon'     => sanitize_text_field( $s['icon']     ?? '' ),
                'title'    => $t,
                'subtitle' => sanitize_text_field( $s['subtitle'] ?? '' ),
                'list'     => $sanitize_list( $s['list'] ?? [] ),
            ];
        }
    }

    $clean = [
        'eyebrow'    => sanitize_text_field( $raw['eyebrow']    ?? '' ),
        'title'      => sanitize_text_field( $raw['title']      ?? '' ),
        'subtitle'   => sanitize_textarea_field( $raw['subtitle'] ?? '' ),
        'section_id' => sanitize_text_field( $raw['section_id'] ?? '' ),
        'side_image' => esc_url_raw( $raw['side_image'] ?? '' ),
        'primary'    => $clean_primary,
        'steps'      => $clean_steps,
    ];

    update_post_meta( $post_id, 'directions_section', $clean );
} );