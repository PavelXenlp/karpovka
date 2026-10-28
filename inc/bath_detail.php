<?php
/**
 * Meta box: Bath Detail Sections (bath_details)
 * Repeater: 1, 2, 3+ одинаковых секций с выбором стороны карусели
 */

add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'bath_details_mb',
        'Секция: Бани — Детальные описания (Русская, Финская, Хаммам…)',
        'bath_details_mb_callback',
        'page',
        'normal',
        'high'
    );
} );

function bath_details_mb_callback( $post ) {

    wp_nonce_field( 'bath_details_save', 'bath_details_nonce' );

    $v     = get_post_meta( $post->ID, 'bath_details', true ) ?: [];
    $items = $v['items'] ?? [];

    ?>
    <style>
        #bath_details_mb .field-group { margin-bottom: 14px; }
        #bath_details_mb label { display: block; font-weight: 600; margin-bottom: 4px; }
        #bath_details_mb input[type="text"],
        #bath_details_mb textarea,
        #bath_details_mb select { width: 100%; }
        #bath_details_mb .bd-section { background: #fff; border: 1px solid #dcdcde; padding: 16px; margin-bottom: 16px; }
        #bath_details_mb .bd-section .head-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        #bath_details_mb .bd-section .head-row strong { font-size: 13px; color: #555; }
        #bath_details_mb .remove-section { color: #b00; border: none; cursor: pointer; }
        #bath_details_mb .sub-label { display: inline-block; font-weight: 600; margin: 10px 0 4px; font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: .5px; }
        #bath_details_mb .slide-block { background: #f9f9f9; border: 1px solid #e0e0e0; padding: 10px; margin-bottom: 8px; }
        #bath_details_mb .slide-block .slide-row { display: flex; gap: 8px; align-items: flex-start; }
        #bath_details_mb .slide-block .slide-row .slide-img-field { flex: 1; }
        #bath_details_mb .slide-block .slide-row .slide-alt-field { max-width: 200px; }
        #bath_details_mb .feat-block { background: #f9f9f9; border: 1px solid #e0e0e0; padding: 12px; margin-bottom: 8px; }
        #bath_details_mb .feat-block .feat-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
    </style>

    <p style="color:#666;font-style:italic;margin-bottom:14px;">
        Добавляйте сколько угодно секций. Для каждой выберите, с какой стороны будет карусель.
    </p>

    <div id="bd_items_wrap">
    <?php if ( ! empty( $items ) && is_array( $items ) ) :
        foreach ( $items as $i => $s ) : ?>
        <div class="bd-section">
            <div class="head-row">
                <strong><?php echo esc_html( mb_substr( $s['title'] ?? '', 0, 35 ) ); ?></strong>
                <button type="button" class="remove-section button button-small">Удалить</button>
            </div>

            <div class="field-group">
                <label>Аннотация (eyebrow)</label>
                <input type="text" name="bath_details[items][<?php echo $i; ?>][eyebrow]" value="<?php echo esc_attr( $s['eyebrow'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Заголовок (до gold-текста)</label>
                <input type="text" name="bath_details[items][<?php echo $i; ?>][title]" value="<?php echo esc_attr( $s['title'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Gold-текст (обёрнется в &lt;em&gt;)</label>
                <input type="text" name="bath_details[items][<?php echo $i; ?>][gold_text]" value="<?php echo esc_attr( $s['gold_text'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Подзаголовок</label>
                <textarea name="bath_details[items][<?php echo $i; ?>][subtitle]" rows="2"><?php echo esc_textarea( $s['subtitle'] ?? '' ); ?></textarea>
            </div>

            <div class="field-group">
                <label>ID секции (anchor)</label>
                <input type="text" name="bath_details[items][<?php echo $i; ?>][section_id]" value="<?php echo esc_attr( $s['section_id'] ?? '' ); ?>" />
            </div>

            <div class="field-group">
                <label>Расположение карусели</label>
                <select name="bath_details[items][<?php echo $i; ?>][direction]">
                    <option value="normal" <?php selected( $s['direction'] ?? '', 'normal' ); ?>>Слева (карусель — слева, текст — справа)</option>
                    <option value="reversed" <?php selected( $s['direction'] ?? '', 'reversed' ); ?>>Справа (текст — слева, карусель — справа)</option>
                </select>
            </div>

            <span class="sub-label">Галерея (карусель)</span>

            <div class="bd-gallery-wrap" data-sec-idx="<?php echo $i; ?>">
            <?php if ( ! empty( $s['gallery'] ) && is_array( $s['gallery'] ) ) :
                foreach ( $s['gallery'] as $j => $g ) : ?>
                <div class="slide-block">
                    <div class="slide-row">
                        <div class="slide-img-field">
                            <input type="hidden" name="bath_details[items][<?php echo $i; ?>][gallery][<?php echo $j; ?>][img]" value="<?php echo esc_attr( $g['img'] ?? '' ); ?>" data-kp-media />
                        </div>
                        <input type="text" class="slide-alt-field" name="bath_details[items][<?php echo $i; ?>][gallery][<?php echo $j; ?>][alt]" value="<?php echo esc_attr( $g['alt'] ?? '' ); ?>" placeholder="Alt" />
                        <button type="button" class="button button-small remove-slide">✕</button>
                    </div>
                </div>
                <?php endforeach;
            endif; ?>
            </div>
            <button type="button" class="button add-slide-btn" data-sec-idx="<?php echo $i; ?>">+ Слайд</button>

            <span class="sub-label">Описание</span>
            <div class="field-group">
                <textarea name="bath_details[items][<?php echo $i; ?>][text]" rows="6" placeholder="Абзацы разделяйте пустой строкой"><?php echo esc_textarea( $s['text'] ?? '' ); ?></textarea>
            </div>

            <span class="sub-label">Характеристики (фичи)</span>

            <div class="bd-feat-wrap" data-sec-idx="<?php echo $i; ?>">
            <?php if ( ! empty( $s['features'] ) && is_array( $s['features'] ) ) :
                foreach ( $s['features'] as $k => $f ) : ?>
                <div class="feat-block">
                    <div class="feat-head">
                        <strong style="font-size:12px;"><?php echo esc_html( mb_substr( $f['bold'] ?? '', 0, 30 ) ); ?></strong>
                        <button type="button" class="remove-feat button button-small">✕</button>
                    </div>
                    <div class="field-group" style="margin-bottom:6px;">
                        <label style="font-weight:400;">Номер</label>
                        <input type="text" name="bath_details[items][<?php echo $i; ?>][features][<?php echo $k; ?>][num]" value="<?php echo esc_attr( $f['num'] ?? '' ); ?>" style="max-width:80px;" />
                    </div>
                    <div class="field-group" style="margin-bottom:6px;">
                        <label style="font-weight:400;">Жирный текст</label>
                        <input type="text" name="bath_details[items][<?php echo $i; ?>][features][<?php echo $k; ?>][bold]" value="<?php echo esc_attr( $f['bold'] ?? '' ); ?>" />
                    </div>
                    <div class="field-group" style="margin-bottom:0;">
                        <label style="font-weight:400;">Описание</label>
                        <input type="text" name="bath_details[items][<?php echo $i; ?>][features][<?php echo $k; ?>][desc]" value="<?php echo esc_attr( $f['desc'] ?? '' ); ?>" />
                    </div>
                </div>
                <?php endforeach;
            endif; ?>
            </div>
            <button type="button" class="button add-feat-btn" data-sec-idx="<?php echo $i; ?>">+ Характеристика</button>
        </div>
        <?php endforeach;
    endif; ?>
    </div>

    <button type="button" class="button" id="add_bd_section" style="margin-top:8px;">+ Добавить секцию бани</button>

    <script>
    jQuery(function($){

        /* Add section */
        var sectionCount = $('#bd_items_wrap .bd-section').length;

        /* Set initial sectionCount from existing sections */
        $('#bd_items_wrap .bd-section').each(function(idx){
            var name = $(this).find('input[name*="[eyebrow]"]').attr('name');
            if(name){
                var m = name.match(/\[items\]\[(\d+)\]/);
                if(m && parseInt(m[1]) >= sectionCount) sectionCount = parseInt(m[1]) + 1;
            }
        });

        $('#add_bd_section').on('click', function(){
            var i = sectionCount;
            sectionCount++;
            var h =
                '<div class="bd-section">' +
                    '<div class="head-row"><strong>Новая секция</strong><button type="button" class="remove-section button button-small">Удалить</button></div>' +
                    '<div class="field-group"><label>Аннотация (eyebrow)</label><input type="text" name="bath_details[items][' + i + '][eyebrow]" value="" /></div>' +
                    '<div class="field-group"><label>Заголовок</label><input type="text" name="bath_details[items][' + i + '][title]" value="" /></div>' +
                    '<div class="field-group"><label>Gold-текст</label><input type="text" name="bath_details[items][' + i + '][gold_text]" value="" /></div>' +
                    '<div class="field-group"><label>Подзаголовок</label><textarea name="bath_details[items][' + i + '][subtitle]" rows="2"></textarea></div>' +
                    '<div class="field-group"><label>ID секции</label><input type="text" name="bath_details[items][' + i + '][section_id]" value="" /></div>' +
                    '<div class="field-group"><label>Расположение карусели</label><select name="bath_details[items][' + i + '][direction]"><option value="normal">Слева (карусель — слева)</option><option value="reversed">Справа (карусель — справа)</option></select></div>' +
                    '<span class="sub-label">Галерея (карусель)</span>' +
                    '<div class="bd-gallery-wrap" data-sec-idx="' + i + '"></div>' +
                    '<button type="button" class="button add-slide-btn" data-sec-idx="' + i + '">+ Слайд</button>' +
                    '<span class="sub-label">Описание</span>' +
                    '<div class="field-group"><textarea name="bath_details[items][' + i + '][text]" rows="6" placeholder="Абзацы разделяйте пустой строкой"></textarea></div>' +
                    '<span class="sub-label">Характеристики</span>' +
                    '<div class="bd-feat-wrap" data-sec-idx="' + i + '"></div>' +
                    '<button type="button" class="button add-feat-btn" data-sec-idx="' + i + '">+ Характеристика</button>' +
                '</div>';
            $('#bd_items_wrap').append(h);
        });

        /* Remove section */
        $('#bd_items_wrap').on('click', '.remove-section', function(){
            if( confirm('Удалить эту секцию?') ) $(this).closest('.bd-section').remove();
        });

        /* Add slide */
        $('#bd_items_wrap').on('click', '.add-slide-btn', function(){
            var wrap = $(this).prev('.bd-gallery-wrap');
            var si = $(this).data('sec-idx');
            var j = wrap.find('.slide-block').length;
            var h = '<div class="slide-block">' +
                '<div class="slide-row">' +
                    '<div class="slide-img-field">' +
                        '<input type="hidden" name="bath_details[items][' + si + '][gallery][' + j + '][img]" value="" data-kp-media />' +
                    '</div>' +
                    '<input type="text" class="slide-alt-field" name="bath_details[items][' + si + '][gallery][' + j + '][alt]" value="" placeholder="Alt" />' +
                    '<button type="button" class="button button-small remove-slide">✕</button>' +
                '</div>' +
            '</div>';
            wrap.append(h);
        });

        /* Remove slide */
        $('#bd_items_wrap').on('click', '.remove-slide', function(){
            $(this).closest('.slide-block').remove();
        });

        /* Add feature */
        $('#bd_items_wrap').on('click', '.add-feat-btn', function(){
            var wrap = $(this).prev('.bd-feat-wrap');
            var si = $(this).data('sec-idx');
            var k = wrap.find('.feat-block').length;
            var num = String(k + 1).padStart(2, '0');
            var h = '<div class="feat-block">' +
                '<div class="feat-head"><strong style="font-size:12px;">Новая</strong><button type="button" class="remove-feat button button-small">✕</button></div>' +
                '<div class="field-group" style="margin-bottom:6px;"><label style="font-weight:400;">Номер</label><input type="text" name="bath_details[items][' + si + '][features][' + k + '][num]" value="' + num + '" style="max-width:80px;" /></div>' +
                '<div class="field-group" style="margin-bottom:6px;"><label style="font-weight:400;">Жирный текст</label><input type="text" name="bath_details[items][' + si + '][features][' + k + '][bold]" value="" /></div>' +
                '<div class="field-group" style="margin-bottom:0;"><label style="font-weight:400;">Описание</label><input type="text" name="bath_details[items][' + si + '][features][' + k + '][desc]" value="" /></div>' +
                '</div>';
            wrap.append(h);
        });

        /* Remove feature */
        $('#bd_items_wrap').on('click', '.remove-feat', function(){
            $(this).closest('.feat-block').remove();
        });

    });
    </script>
    <?php
}

/* ── Save ────────────────────────────────────────── */
add_action( 'save_post', function ( $post_id ) {

    if ( ! isset( $_POST['bath_details_nonce'] ) || ! wp_verify_nonce( $_POST['bath_details_nonce'], 'bath_details_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['bath_details'] ?? [];
    $clean = [ 'items' => [] ];

    if ( ! empty( $raw['items'] ) && is_array( $raw['items'] ) ) {
        foreach ( $raw['items'] as $s ) {
            $sec = [
                'eyebrow'    => sanitize_text_field( $s['eyebrow'] ?? '' ),
                'title'      => sanitize_text_field( $s['title'] ?? '' ),
                'gold_text'  => sanitize_text_field( $s['gold_text'] ?? '' ),
                'subtitle'   => sanitize_textarea_field( $s['subtitle'] ?? '' ),
                'section_id' => sanitize_text_field( $s['section_id'] ?? '' ),
                'direction'  => ( $s['direction'] ?? '' ) === 'reversed' ? 'reversed' : 'normal',
                'text'       => sanitize_textarea_field( $s['text'] ?? '' ),
                'gallery'    => [],
                'features'   => [],
            ];

            if ( ! empty( $s['gallery'] ) && is_array( $s['gallery'] ) ) {
                foreach ( $s['gallery'] as $g ) {
                    $img = esc_url_raw( $g['img'] ?? '' );
                    if ( $img ) {
                        $sec['gallery'][] = [
                            'img' => $img,
                            'alt' => sanitize_text_field( $g['alt'] ?? '' ),
                        ];
                    }
                }
            }

            if ( ! empty( $s['features'] ) && is_array( $s['features'] ) ) {
                foreach ( $s['features'] as $f ) {
                    $b = sanitize_text_field( $f['bold'] ?? '' );
                    if ( $b !== '' ) {
                        $sec['features'][] = [
                            'num'  => sanitize_text_field( $f['num'] ?? '' ),
                            'bold' => $b,
                            'desc' => sanitize_text_field( $f['desc'] ?? '' ),
                        ];
                    }
                }
            }

            $clean['items'][] = $sec;
        }
    }

    update_post_meta( $post_id, 'bath_details', $clean );
} );
