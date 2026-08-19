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
    $activities     = $v['activities'] ?? [];
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
        .cb-upload{display:flex;align-items:center;gap:8px;margin-bottom:6px}
        .cb-upload input[type="text"]{flex:1}
        .cb-upload button{padding:3px 8px;cursor:pointer;font-size:12px}
        .cb-upload img.prev{max-width:80px;height:auto;border:1px solid #ccc;border-radius:3px;display:none}
        .cb-check{display:flex;align-items:center;gap:4px;font-weight:normal;font-size:12px;margin:4px 0}
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
        <p style="font-size:11px;color:#888">Картинка каждого варианта показывается справа при клике (через data-атрибуты + JS). Галочка «Активный» = первый отображаемый.</p>
        <div id="cb-stay-opts">
            <?php if ( ! empty( $stay_options ) ) : ?>
                <?php foreach ( $stay_options as $oi => $o ) : ?>
                    <div class="cb-item">
                        <div class="cb-row">
                            <div><label>Название (в списке)</label><input type="text" name="combined_section[stay_options][<?php echo $oi; ?>][name]" value="<?php echo esc_attr( $o['name'] ?? '' ); ?>"></div>
                            <div><label>Цена (в списке)</label><input type="text" name="combined_section[stay_options][<?php echo $oi; ?>][price]" value="<?php echo esc_attr( $o['price'] ?? '' ); ?>"></div>
                        </div>
                        <div class="cb-upload">
                            <button type="button" class="cb-btn cb-upload-stay">📁 Фото</button>
                            <input type="text" name="combined_section[stay_options][<?php echo $oi; ?>][img]" value="<?php echo esc_attr( $o['img'] ?? '' ); ?>">
                            <img class="prev" src="<?php echo esc_url( $o['img'] ?? '' ); ?>" alt="">
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

        <div class="cb-upload">
            <button type="button" class="cb-btn cb-upload-rec">📁 Фото</button>
            <input type="text" name="combined_section[rec_img]" value="<?php echo esc_attr( $rec_img ); ?>">
            <img class="prev cb-rec-prev" src="<?php echo esc_url( $rec_img ); ?>" alt="">
        </div>

        <h4 style="margin-top:12px">Кнопки сезонов</h4>
        <p style="font-size:11px;color:#888">data-season определяет фильтрацию активностей. Галочка = активная по умолчанию.</p>
        <div id="cb-seasons">
            <?php if ( ! empty( $seasons ) ) : ?>
                <?php foreach ( $seasons as $si => $s ) : ?>
                    <div class="cb-item" style="padding:10px">
                        <div class="cb-row" style="margin-bottom:0">
                            <div><label>Текст кнопки</label><input type="text" name="combined_section[seasons][<?php echo $si; ?>][text]" value="<?php echo esc_attr( $s['text'] ?? '' ); ?>"></div>
                            <div style="flex:0 0 140px"><label>data-season</label><input type="text" name="combined_section[seasons][<?php echo $si; ?>][slug]" value="<?php echo esc_attr( $s['slug'] ?? '' ); ?>"></div>
                            <div style="flex:0 0 120px;display:flex;align-items:flex-end;padding-bottom:2px">
                                <label class="cb-check"><input type="checkbox" name="combined_section[seasons][<?php echo $si; ?>][is_active]" value="1" <?php checked( $s['is_active'] ?? 0, 1 ); ?>> Активный</label>
                            </div>
                        </div>
                        <button type="button" class="cb-btn cb-btn--del" onclick="this.closest('.cb-item').remove()">Удалить</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" class="cb-btn" id="cb-add-season">+ Добавить сезон</button>

        <h4 style="margin-top:14px">Активности</h4>
        <p style="font-size:11px;color:#888">data-season привязывает активность к сезону (summer/winter/year)</p>
        <div id="cb-activities">
            <?php if ( ! empty( $activities ) ) : ?>
                <?php foreach ( $activities as $ai => $a ) : ?>
                    <div class="cb-item" style="padding:10px">
                        <div class="cb-row" style="margin-bottom:0">
                            <div><label>Текст</label><input type="text" name="combined_section[activities][<?php echo $ai; ?>][text]" value="<?php echo esc_attr( $a['text'] ?? '' ); ?>"></div>
                            <div style="flex:0 0 140px"><label>data-season</label><input type="text" name="combined_section[activities][<?php echo $ai; ?>][season]" value="<?php echo esc_attr( $a['season'] ?? '' ); ?>"></div>
                        </div>
                        <button type="button" class="cb-btn cb-btn--del" onclick="this.closest('.cb-item').remove()">Удалить</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" class="cb-btn" id="cb-add-activity">+ Добавить активность</button>
    </div>

    <script>
    jQuery(function($){

        /* ── Загрузка картинок ── */
        function uploadImg(btn){
            var frame = wp.media({title:'Выберите изображение',multiple:false,library:{type:'image'}});
            frame.on('select',function(){
                var url = frame.state().get('selection').first().toJSON().url;
                $(btn).closest('.cb-upload').find('input[type="text"]').val(url);
                $(btn).closest('.cb-upload').find('img.prev').attr('src',url).show();
            });
            frame.open();
        }
        // Делегированные обработчики для динамически добавляемых кнопок
        $(document).on('click','.cb-upload-stay,.cb-upload-rec',function(e){
            e.preventDefault();
            uploadImg(this);
        });

        /* Превью при загрузке */
        $('.cb-upload img.prev').each(function(){
            if($(this).attr('src')) $(this).show();
        });

        /* ── Варианты проживания ── */
        $('#cb-add-stay').on('click',function(){
            var box=$('#cb-stay-opts'), i=box.find('.cb-item').length;
            box.append(
                '<div class="cb-item">' +
                '<div class="cb-row"><div><label>Название (в списке)</label><input type="text" name="combined_section[stay_options]['+i+'][name]"></div><div><label>Цена (в списке)</label><input type="text" name="combined_section[stay_options]['+i+'][price]"></div></div>' +
                '<div class="cb-upload"><button type="button" class="cb-btn cb-upload-stay">\uD83D\uDCC1 \u0424\u043E\u0442\u043E</button><input type="text" name="combined_section[stay_options]['+i+'][img]"><img class="prev" src="" alt=""></div>' +
                '<div class="cb-row"><div><label>\u0417\u0430\u0433\u043E\u043B\u043E\u0432\u043E\u043A (\u0432 \u043A\u0430\u0440\u0442\u043E\u0447\u043A\u0435)</label><input type="text" name="combined_section[stay_options]['+i+'][card_title]"></div><div><label>\u0426\u0435\u043D\u0430 \u043F\u043E\u043B\u043D\u0430\u044F</label><input type="text" name="combined_section[stay_options]['+i+'][card_price]"></div></div>' +
                '<div class="cb-field"><label>Подпись (caption)</label><input type="text" name="combined_section[stay_options]['+i+'][caption]"></div>' +
                '<div class="cb-field"><label>Описание</label><textarea name="combined_section[stay_options]['+i+'][desc]" rows="2"></textarea></div>' +
                '<label class="cb-check"><input type="checkbox" name="combined_section[stay_options]['+i+'][is_active]" value="1"> \u0410\u043A\u0442\u0438\u0432\u043D\u044B\u0439 \u043F\u043E \u0443\u043C\u043E\u043B\u0447\u0430\u043D\u0438\u044E</label>' +
                '<button type="button" class="cb-btn cb-btn--del" onclick="this.closest(\'.cb-item\').remove()">\u0423\u0434\u0430\u043B\u0438\u0442\u044C \u0432\u0430\u0440\u0438\u0430\u043D\u0442</button>' +
                '</div>'
            );
        });

        /* ── Сезоны ── */
        $('#cb-add-season').on('click',function(){
            var box=$('#cb-seasons'), i=box.find('.cb-item').length;
            box.append(
                '<div class="cb-item" style="padding:10px">' +
                '<div class="cb-row" style="margin-bottom:0"><div><label>Текст кнопки</label><input type="text" name="combined_section[seasons]['+i+'][text]"></div><div style="flex:0 0 140px"><label>data-season</label><input type="text" name="combined_section[seasons]['+i+'][slug]"></div><div style="flex:0 0 120px;display:flex;align-items:flex-end;padding-bottom:2px"><label class="cb-check"><input type="checkbox" name="combined_section[seasons]['+i+'][is_active]" value="1"> \u0410\u043A\u0442\u0438\u0432\u043D\u044B\u0439</label></div></div>' +
                '<button type="button" class="cb-btn cb-btn--del" onclick="this.closest(\'.cb-item\').remove()">\u0423\u0434\u0430\u043B\u0438\u0442\u044C</button>' +
                '</div>'
            );
        });

        /* ── Активности ── */
        $('#cb-add-activity').on('click',function(){
            var box=$('#cb-activities'), i=box.find('.cb-item').length;
            box.append(
                '<div class="cb-item" style="padding:10px">' +
                '<div class="cb-row" style="margin-bottom:0"><div><label>Текст</label><input type="text" name="combined_section[activities]['+i+'][text]"></div><div style="flex:0 0 140px"><label>data-season</label><input type="text" name="combined_section[activities]['+i+'][season]"></div></div>' +
                '<button type="button" class="cb-btn cb-btn--del" onclick="this.closest(\'.cb-item\').remove()">\u0423\u0434\u0430\u043B\u0438\u0442\u044C</button>' +
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

    // Сезоны
    $seasons = [];
    if ( ! empty( $raw['seasons'] ) && is_array( $raw['seasons'] ) ) {
        foreach ( $raw['seasons'] as $s ) {
            $seasons[] = [
                'text'     => sanitize_text_field( $s['text'] ?? '' ),
                'slug'     => sanitize_text_field( $s['slug'] ?? '' ),
                'is_active'=> ! empty( $s['is_active'] ) ? 1 : 0,
            ];
        }
    }

    // Активности
    $activities = [];
    if ( ! empty( $raw['activities'] ) && is_array( $raw['activities'] ) ) {
        foreach ( $raw['activities'] as $a ) {
            $t = sanitize_text_field( $a['text'] ?? '' );
            if ( $t ) {
                $activities[] = [
                    'text'   => $t,
                    'season' => sanitize_text_field( $a['season'] ?? '' ),
                ];
            }
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
        'activities'    => $activities,
    ];

    update_post_meta( $post_id, 'combined_section', $data );
} );