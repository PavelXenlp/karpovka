<?php
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'combined_section', 'Секция: Проживание + Активный отдых', 'combined_section_box', 'page', 'normal', 'high' );
} );

function combined_section_box( $post ) {
    wp_nonce_field( 'combined_section_nonce', 'combined_section_nonce_field' );
    $v = get_post_meta( $post->ID, 'combined_section', true );

    $section_id     = $v['section_id'] ?? '';
    // Проживание
    $stay_eyebrow   = $v['stay_eyebrow'] ?? '';
    $stay_title     = $v['stay_title'] ?? '';
    $stay_sub       = $v['stay_sub'] ?? '';
    $stay_options   = $v['stay_options'] ?? [];
    $stay_btn_text  = $v['stay_btn_text'] ?? '';
    $stay_btn_link  = $v['stay_btn_link'] ?? '';
    // Активный отдых
    $rec_eyebrow    = $v['rec_eyebrow'] ?? '';
    $rec_title      = $v['rec_title'] ?? '';
    $rec_sub        = $v['rec_sub'] ?? '';
    $rec_img        = $v['rec_img'] ?? '';
    $seasons        = $v['seasons'] ?? [];
    ?>
    <style>
        .cb-field{margin-bottom:10px}
        .cb-field label{display:block;font-weight:600;margin-bottom:3px;font-size:13px}
        .cb-field input[type="text"],.cb-field textarea{width:100%;max-width:600px}
        .cb-group{border:1px solid #c0c0c0;margin:14px 0;padding:14px;background:#fafafa;border-radius:4px}
        .cb-group>h4{margin-top:0}
        .cb-item{border:1px solid #ddd;margin:10px 0;padding:14px;background:#fff;border-radius:4px;position:relative}
        .cb-item label{display:block;font-weight:600;margin-bottom:3px;font-size:12px}
        .cb-item input,.cb-item textarea{width:100%}
        .cb-item .cb-row{display:flex;gap:12px;margin-bottom:8px}
        .cb-item .cb-row>div{flex:1}
        .cb-item .cb-row label{margin-bottom:2px}
        .cb-btn{padding:4px 12px;cursor:pointer;margin:3px 2px}
        .cb-btn--del{color:#a00;border-color:#a00}
        .cb-check{display:flex;align-items:center;gap:4px;font-weight:normal;font-size:12px;margin:4px 0}
        .cb-season-block{border:2px solid #2271b1;margin:14px 0;padding:14px;background:#f0f6fc;border-radius:6px}
        .cb-season-block>h4{color:#2271b1;margin-top:0}
        .cb-activity-row{display:flex;gap:8px;align-items:center;margin:6px 0}
        .cb-activity-row input{flex:1}
    </style>

    <div class="cb-field">
        <label>Section ID</label>
        <input type="text" name="combined_section[section_id]" value="<?php echo esc_attr( $section_id ); ?>">
    </div>

    <!-- ═══ ПРОЖИВАНИЕ ═══ -->
    <div class="cb-group">
        <h4>Проживание</h4>
        <div class="cb-field"><label>Eyebrow</label><input type="text" name="combined_section[stay_eyebrow]" value="<?php echo esc_attr( $stay_eyebrow ); ?>"></div>
        <div class="cb-field"><label>Заголовок (HTML: &lt;em&gt;)</label><textarea name="combined_section[stay_title]" rows="2"><?php echo esc_textarea( $stay_title ); ?></textarea></div>
        <div class="cb-field"><label>Подзаголовок</label><input type="text" name="combined_section[stay_sub]" value="<?php echo esc_attr( $stay_sub ); ?>"></div>

        <h4 style="margin-top:12px">Варианты размещения (табы)</h4>
        <p style="font-size:11px;color:#888">Картинка каждого варианта показывается справа при клике. Галочка «Активный» = первый отображаемый.</p>
        <div id="cb-stay-opts">
            <?php if ( ! empty( $stay_options ) ) : ?>
                <?php foreach ( $stay_options as $oi => $o ) : ?>
                    <div class="cb-item">
                        <div class="cb-row">
                            <div><label>Название (в списке)</label><input type="text" name="combined_section[stay_options][<?php echo $oi; ?>][name]" value="<?php echo esc_attr( $o['name'] ?? '' ); ?>"></div>
                            <div><label>Цена (в списке)</label><input type="text" name="combined_section[stay_options][<?php echo $oi; ?>][price]" value="<?php echo esc_attr( $o['price'] ?? '' ); ?>"></div>
                        </div>
                        <div class="cb-field">
                            <label>Фото</label>
                            <input type="hidden" name="combined_section[stay_options][<?php echo $oi; ?>][img]" value="<?php echo esc_attr( $o['img'] ?? '' ); ?>" data-kp-media>
                        </div>
                        <div class="cb-row">
                            <div><label>Заголовок (в карточке)</label><input type="text" name="combined_section[stay_options][<?php echo $oi; ?>][card_title]" value="<?php echo esc_attr( $o['card_title'] ?? '' ); ?>"></div>
                            <div><label>Цена полная (в карточке)</label><input type="text" name="combined_section[stay_options][<?php echo $oi; ?>][card_price]" value="<?php echo esc_attr( $o['card_price'] ?? '' ); ?>"></div>
                        </div>
                        <div class="cb-field"><label>Подпись (caption)</label><input type="text" name="combined_section[stay_options][<?php echo $oi; ?>][caption]" value="<?php echo esc_attr( $o['caption'] ?? '' ); ?>"></div>
                        <div class="cb-field"><label>Описание</label><textarea name="combined_section[stay_options][<?php echo $oi; ?>][desc]" rows="2"><?php echo esc_textarea( $o['desc'] ?? '' ); ?></textarea></div>
                        <label class="cb-check"><input type="checkbox" name="combined_section[stay_options][<?php echo $oi; ?>][is_active]" value="1" <?php checked( $o['is_active'] ?? 0, 1 ); ?>> Активный по умолчанию</label>
                        <button type="button" class="cb-btn cb-btn--del" onclick="this.closest('.cb-item').remove()">Удалить вариант</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" class="cb-btn" id="cb-add-stay">+ Добавить вариант</button>

        <div class="cb-row" style="margin-top:12px">
            <div><label style="font-size:12px">Кнопка «Все варианты» — текст</label><input type="text" name="combined_section[stay_btn_text]" value="<?php echo esc_attr( $stay_btn_text ); ?>"></div>
            <div><label style="font-size:12px">Кнопка — ссылка</label><input type="text" name="combined_section[stay_btn_link]" value="<?php echo esc_attr( $stay_btn_link ); ?>"></div>
        </div>
    </div>

    <!-- ═══ АКТИВНЫЙ ОТДЫХ ═══ -->
    <div class="cb-group">
        <h4>Активный отдых</h4>
        <div class="cb-field"><label>Eyebrow</label><input type="text" name="combined_section[rec_eyebrow]" value="<?php echo esc_attr( $rec_eyebrow ); ?>"></div>
        <div class="cb-field"><label>Заголовок (HTML: &lt;em&gt;)</label><textarea name="combined_section[rec_title]" rows="2"><?php echo esc_textarea( $rec_title ); ?></textarea></div>
        <div class="cb-field"><label>Подзаголовок</label><input type="text" name="combined_section[rec_sub]" value="<?php echo esc_attr( $rec_sub ); ?>"></div>

        <div class="cb-field">
            <label>Фото</label>
            <input type="hidden" name="combined_section[rec_img]" value="<?php echo esc_attr( $rec_img ); ?>" data-kp-media>
        </div>

        <h4 style="margin-top:12px">Сезоны со своими списками активностей</h4>
        <p style="font-size:11px;color:#888">У каждого сезона — свой список активностей. Галочка = активный по умолчанию.</p>
        <div id="cb-seasons">
            <?php if ( ! empty( $seasons ) ) : ?>
                <?php foreach ( $seasons as $si => $s ) : 
                    $s_activities = $s['activities'] ?? [];
                ?>
                    <div class="cb-season-block" data-season-index="<?php echo $si; ?>">
                        <h4>Сезон #<?php echo $si + 1; ?></h4>
                        <div class="cb-row" style="margin-bottom:0">
                            <div><label>Текст кнопки</label><input type="text" name="combined_section[seasons][<?php echo $si; ?>][text]" value="<?php echo esc_attr( $s['text'] ?? '' ); ?>"></div>
                            <div style="flex:0 0 140px"><label>data-season</label><input type="text" name="combined_section[seasons][<?php echo $si; ?>][slug]" value="<?php echo esc_attr( $s['slug'] ?? '' ); ?>"></div>
                            <div style="flex:0 0 120px;display:flex;align-items:flex-end;padding-bottom:2px">
                                <label class="cb-check"><input type="checkbox" name="combined_section[seasons][<?php echo $si; ?>][is_active]" value="1" <?php checked( $s['is_active'] ?? 0, 1 ); ?>> Активный</label>
                            </div>
                        </div>
                        
                        <div style="margin-top:12px">
                            <label style="font-weight:600;font-size:12px;display:block;margin-bottom:6px">Активности этого сезона:</label>
                            <div class="cb-season-activities" data-season-activities="<?php echo $si; ?>">
                                <?php if ( ! empty( $s_activities ) ) : foreach ( $s_activities as $ai => $a ) : ?>
                                    <div class="cb-activity-row">
                                        <input type="text" name="combined_section[seasons][<?php echo $si; ?>][activities][<?php echo $ai; ?>][text]" value="<?php echo esc_attr( $a['text'] ?? '' ); ?>" placeholder="Активность">
                                        <button type="button" class="cb-btn cb-btn--del" onclick="this.closest('.cb-activity-row').remove()">✕</button>
                                    </div>
                                <?php endforeach; endif; ?>
                            </div>
                            <button type="button" class="cb-btn cb-add-activity" data-season-idx="<?php echo $si; ?>">+ Добавить активность</button>
                        </div>
                        
                        <button type="button" class="cb-btn cb-btn--del" style="margin-top:10px" onclick="this.closest('.cb-season-block').remove()">Удалить сезон целиком</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" class="cb-btn" id="cb-add-season">+ Добавить сезон</button>
    </div>

    <script>
    jQuery(function($){
        var seasonCounter = $('#cb-seasons .cb-season-block').length;

        /* ── Варианты проживания ── */
        $('#cb-add-stay').on('click',function(){
            var box=$('#cb-stay-opts'), i=box.find('.cb-item').length;
            box.append(
                '<div class="cb-item">' +
                '<div class="cb-row"><div><label>Название (в списке)</label><input type="text" name="combined_section[stay_options]['+i+'][name]"></div><div><label>Цена (в списке)</label><input type="text" name="combined_section[stay_options]['+i+'][price]"></div></div>' +
                '<div class="cb-field"><label>Фото</label><input type="hidden" name="combined_section[stay_options]['+i+'][img]" value="" data-kp-media></div>' +
                '<div class="cb-row"><div><label>Заголовок (в карточке)</label><input type="text" name="combined_section[stay_options]['+i+'][card_title]"></div><div><label>Цена полная</label><input type="text" name="combined_section[stay_options]['+i+'][card_price]"></div></div>' +
                '<div class="cb-field"><label>Подпись (caption)</label><input type="text" name="combined_section[stay_options]['+i+'][caption]"></div>' +
                '<div class="cb-field"><label>Описание</label><textarea name="combined_section[stay_options]['+i+'][desc]" rows="2"></textarea></div>' +
                '<label class="cb-check"><input type="checkbox" name="combined_section[stay_options]['+i+'][is_active]" value="1"> Активный по умолчанию</label>' +
                '<button type="button" class="cb-btn cb-btn--del" onclick="this.closest(\'.cb-item\').remove()">Удалить вариант</button>' +
                '</div>'
            );
        });

        /* ── Добавить сезон ── */
        $('#cb-add-season').on('click',function(){
            var i = seasonCounter;
            var html =
                '<div class="cb-season-block" data-season-index="'+i+'">' +
                '<h4>Сезон #'+(i+1)+'</h4>' +
                '<div class="cb-row" style="margin-bottom:0">' +
                    '<div><label>Текст кнопки</label><input type="text" name="combined_section[seasons]['+i+'][text]"></div>' +
                    '<div style="flex:0 0 140px"><label>data-season</label><input type="text" name="combined_section[seasons]['+i+'][slug]"></div>' +
                    '<div style="flex:0 0 120px;display:flex;align-items:flex-end;padding-bottom:2px">' +
                        '<label class="cb-check"><input type="checkbox" name="combined_section[seasons]['+i+'][is_active]" value="1"> Активный</label>' +
                    '</div>' +
                '</div>' +
                '<div style="margin-top:12px">' +
                    '<label style="font-weight:600;font-size:12px;display:block;margin-bottom:6px">Активности этого сезона:</label>' +
                    '<div class="cb-season-activities" data-season-activities="'+i+'"></div>' +
                    '<button type="button" class="cb-btn cb-add-activity" data-season-idx="'+i+'">+ Добавить активность</button>' +
                '</div>' +
                '<button type="button" class="cb-btn cb-btn--del" style="margin-top:10px" onclick="this.closest(\'.cb-season-block\').remove()">Удалить сезон целиком</button>' +
                '</div>';
            $('#cb-seasons').append(html);
            seasonCounter++;
        });

        /* ── Добавить активность в конкретный сезон ── */
        $(document).on('click', '.cb-add-activity', function(){
            var si = $(this).data('season-idx');
            var wrap = $('[data-season-activities="'+si+'"]');
            var k = wrap.find('.cb-activity-row').length;
            wrap.append(
                '<div class="cb-activity-row">' +
                '<input type="text" name="combined_section[seasons]['+si+'][activities]['+k+'][text]" placeholder="Активность">' +
                '<button type="button" class="cb-btn cb-btn--del" onclick="this.closest(\'.cb-activity-row\').remove()">✕</button>' +
                '</div>'
            );
        });

    });
    </script>
    <?php
}

add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['combined_section_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['combined_section_nonce_field'], 'combined_section_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['combined_section'] ?? [];

    // Варианты проживания
    $stay_options = [];
    if ( ! empty( $raw['stay_options'] ) && is_array( $raw['stay_options'] ) ) {
        foreach ( $raw['stay_options'] as $o ) {
            $name = sanitize_text_field( $o['name'] ?? '' );
            if ( $name ) {
                $stay_options[] = [
                    'name'       => $name,
                    'price'      => sanitize_text_field( $o['price'] ?? '' ),
                    'img'        => esc_url_raw( $o['img'] ?? '' ),
                    'card_title' => sanitize_text_field( $o['card_title'] ?? '' ),
                    'card_price' => sanitize_text_field( $o['card_price'] ?? '' ),
                    'caption'    => sanitize_text_field( $o['caption'] ?? '' ),
                    'desc'       => sanitize_textarea_field( $o['desc'] ?? '' ),
                    'is_active'  => ! empty( $o['is_active'] ) ? 1 : 0,
                ];
            }
        }
    }

    // Сезоны СО СВОИМИ АКТИВНОСТЯМИ
    $seasons = [];
    if ( ! empty( $raw['seasons'] ) && is_array( $raw['seasons'] ) ) {
        foreach ( $raw['seasons'] as $s ) {
            // Активности текущего сезона
            $s_activities = [];
            if ( ! empty( $s['activities'] ) && is_array( $s['activities'] ) ) {
                foreach ( $s['activities'] as $a ) {
                    $t = sanitize_text_field( $a['text'] ?? '' );
                    if ( $t ) {
                        $s_activities[] = [ 'text' => $t ];
                    }
                }
            }
            $seasons[] = [
                'text'       => sanitize_text_field( $s['text'] ?? '' ),
                'slug'       => sanitize_text_field( $s['slug'] ?? '' ),
                'is_active'  => ! empty( $s['is_active'] ) ? 1 : 0,
                'activities' => $s_activities,
            ];
        }
    }

    $data = [
        'section_id'    => sanitize_text_field( $raw['section_id'] ?? '' ),
        'stay_eyebrow'  => sanitize_text_field( $raw['stay_eyebrow'] ?? '' ),
        'stay_title'    => wp_kses_post( $raw['stay_title'] ?? '' ),
        'stay_sub'      => sanitize_text_field( $raw['stay_sub'] ?? '' ),
        'stay_options'  => $stay_options,
        'stay_btn_text' => sanitize_text_field( $raw['stay_btn_text'] ?? '' ),
        'stay_btn_link' => esc_url_raw( $raw['stay_btn_link'] ?? '' ),
        'rec_eyebrow'   => sanitize_text_field( $raw['rec_eyebrow'] ?? '' ),
        'rec_title'     => wp_kses_post( $raw['rec_title'] ?? '' ),
        'rec_sub'       => sanitize_text_field( $raw['rec_sub'] ?? '' ),
        'rec_img'       => esc_url_raw( $raw['rec_img'] ?? '' ),
        'seasons'       => $seasons,
    ];

    update_post_meta( $post_id, 'combined_section', $data );
} );