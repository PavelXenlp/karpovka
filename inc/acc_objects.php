<?php
// Meta box: Дома и номера (с фильтрами)
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'acc_objects_section', 'Дома и номера (размещение)', 'acc_objects_section_cb', 'page', 'normal', 'high' );
} );

function acc_objects_section_cb( $post ) {
    wp_nonce_field( 'acc_objects_section_nonce', 'acc_objects_section_nonce_field' );
    $v = get_post_meta( $post->ID, 'acc_objects', true );

    $eyebrow = $v['eyebrow'] ?? '';
    $title   = $v['title'] ?? '';
    $filters = $v['filters'] ?? [];
    $cards   = $v['cards'] ?? [];
    ?>
    <p>
        <label><b>Бровь</b></label><br>
        <input type="text" name="acc_objects[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>" style="width:100%;max-width:500px;">
    </p>
    <p>
        <label><b>Заголовок</b> (<code>&lt;em&gt;</code>)</label><br>
        <input type="text" name="acc_objects[title]" value="<?php echo esc_attr( $title ); ?>" style="width:100%;">
    </p>

    <hr>
    <h4>Группы фильтров</h4>
    <p class="description">Каждая группа — строка кнопок-чипов. Слаг используется для фильтрации карточек по data-type / data-audience. Значение <code>all</code> — «показать все».</p>
    <div id="acc-filters-wrap">
        <?php if ( ! empty( $filters ) ) : foreach ( $filters as $fi => $fg ) : ?>
        <div class="acc-filter-group-block" style="border:1px solid #ccc;padding:12px;margin-bottom:12px;background:#fffbe6;">
            <p>
                <label><b>Метка группы</b></label><br>
                <input type="text" name="acc_objects[filters][<?php echo $fi; ?>][label]" value="<?php echo esc_attr( $fg['label'] ?? '' ); ?>" style="width:300px;" placeholder="Формат">
            </p>
            <div class="acc-filter-opts-wrap">
                <?php $opts = $fg['options'] ?? []; if ( ! empty( $opts ) ) : foreach ( $opts as $oi => $opt ) : ?>
                <div class="acc-filter-opt-row" style="display:flex;gap:6px;align-items:center;margin-bottom:4px;">
                    <input type="text" name="acc_objects[filters][<?php echo $fi; ?>][options][<?php echo $oi; ?>][text]" value="<?php echo esc_attr( $opt['text'] ?? '' ); ?>" placeholder="Текст" style="width:200px;">
                    <input type="text" name="acc_objects[filters][<?php echo $fi; ?>][options][<?php echo $oi; ?>][slug]" value="<?php echo esc_attr( $opt['slug'] ?? '' ); ?>" placeholder="Слаг (all/eco/family…)" style="width:200px;">
                    <label><input type="checkbox" name="acc_objects[filters][<?php echo $fi; ?>][options][<?php echo $oi; ?>][is_active]" value="1" <?php checked( $opt['is_active'] ?? false ); ?>> Активен</label>
                    <button type="button" class="button acc-filter-rm-opt">✕</button>
                </div>
                <?php endforeach; endif; ?>
            </div>
            <button type="button" class="button acc-filter-add-opt" data-fi="<?php echo $fi; ?>">+ Опция</button>
            <hr style="margin:10px 0;">
            <button type="button" class="button acc-filter-rm-group" style="color:#a00;">Удалить группу</button>
        </div>
        <?php endforeach; endif; ?>
    </div>
    <button type="button" class="button" id="acc-filter-add-group">+ Добавить группу фильтров</button>

    <hr>
    <h4>Карточки размещения</h4>
    <div id="acc-cards-wrap">
        <?php if ( ! empty( $cards ) ) : foreach ( $cards as $ci => $card ) :
            $aud = $card['audience'] ?? [];
            if ( ! is_array( $aud ) ) $aud = [];
        ?>
        <div class="acc-card-block" style="border:1px solid #ccd;padding:14px;margin-bottom:14px;background:#fafafa;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <p>
                    <label><b>Тип</b> (data-type)</label><br>
                    <select name="acc_objects[cards][<?php echo $ci; ?>][type]">
                        <option value="eco" <?php selected( $card['type'] ?? '', 'eco' ); ?>>eco</option>
                        <option value="hotel" <?php selected( $card['type'] ?? '', 'hotel' ); ?>>hotel</option>
                        <option value="guest" <?php selected( $card['type'] ?? '', 'guest' ); ?>>guest</option>
                    </select>
                </p>
                <p>
                    <label><b>Аудитория</b> (data-audience, ч/з пробел)</label><br>
                    <label><input type="checkbox" name="acc_objects[cards][<?php echo $ci; ?>][audience][family]" value="family" <?php checked( in_array( 'family', $aud ) ); ?>> family</label>&nbsp;
                    <label><input type="checkbox" name="acc_objects[cards][<?php echo $ci; ?>][audience][couple]" value="couple" <?php checked( in_array( 'couple', $aud ) ); ?>> couple</label>&nbsp;
                    <label><input type="checkbox" name="acc_objects[cards][<?php echo $ci; ?>][audience][company]" value="company" <?php checked( in_array( 'company', $aud ) ); ?>> company</label>
                </p>
            </div>

            <p>
                <label><b>Изображение</b></label><br>
                <img class="acc-card-img-preview" src="<?php echo esc_url( $card['img'] ?? '' ); ?>" style="max-width:250px;height:auto;display:<?php echo ( $card['img'] ?? '' ) ? 'block' : 'none'; ?>;margin:6px 0;border:1px solid #ddd;">
                <input type="hidden" name="acc_objects[cards][<?php echo $ci; ?>][img]" value="<?php echo esc_attr( $card['img'] ?? '' ); ?>" class="acc-card-img-input">
                <input type="text" name="acc_objects[cards][<?php echo $ci; ?>][img_alt]" value="<?php echo esc_attr( $card['img_alt'] ?? '' ); ?>" placeholder="Alt текст" style="width:400px;margin-left:8px;">
                <button type="button" class="button acc-card-upload-img">📁</button>
                <button type="button" class="button acc-card-rm-img" style="<?php echo ( $card['img'] ?? '' ) ? '' : 'display:none;'; ?>">✕</button>
            </p>

            <p>
                <label><b>Бейдж</b></label><br>
                <input type="text" name="acc_objects[cards][<?php echo $ci; ?>][badge]" value="<?php echo esc_attr( $card['badge'] ?? '' ); ?>" style="width:300px;" placeholder="VIP-дом">
            </p>
            <p>
                <label><b>Название</b></label><br>
                <input type="text" name="acc_objects[cards][<?php echo $ci; ?>][title]" value="<?php echo esc_attr( $card['title'] ?? '' ); ?>" style="width:100%;">
            </p>
            <p>
                <label><b>Мета</b> (до N гостей · санузлы · описание)</label><br>
                <input type="text" name="acc_objects[cards][<?php echo $ci; ?>][meta]" value="<?php echo esc_attr( $card['meta'] ?? '' ); ?>" style="width:100%;">
            </p>

            <p>
                <label><b>Преимущества</b> (features)</label><br>
                <div class="acc-feat-wrap">
                    <?php $feats = $card['features'] ?? []; if ( ! empty( $feats ) ) : foreach ( $feats as $k => $f ) : ?>
                    <div class="acc-feat-row" style="margin-bottom:4px;display:flex;gap:6px;">
                        <input type="text" name="acc_objects[cards][<?php echo $ci; ?>][features][<?php echo $k; ?>][text]" value="<?php echo esc_attr( $f['text'] ?? '' ); ?>" style="flex:1;" placeholder="Особенность">
                        <button type="button" class="button acc-feat-rm">✕</button>
                    </div>
                    <?php endforeach; endif; ?>
                </div>
                <button type="button" class="button acc-feat-add" data-ci="<?php echo $ci; ?>">+ Преимущество</button>
            </p>

            <div style="display:grid;grid-template-columns:120px 200px 140px;gap:8px;align-items:end;">
                <p>
                    <label><b>Префикс цены</b></label><br>
                    <input type="text" name="acc_objects[cards][<?php echo $ci; ?>][price_prefix]" value="<?php echo esc_attr( $card['price_prefix'] ?? '' ); ?>" placeholder="от" style="width:100%;">
                </p>
                <p>
                    <label><b>Цена</b></label><br>
                    <input type="text" name="acc_objects[cards][<?php echo $ci; ?>][price]" value="<?php echo esc_attr( $card['price'] ?? '' ); ?>" style="width:100%;" placeholder="48 000 ₽">
                </p>
                <p>
                    <label><b>Суффикс</b></label><br>
                    <input type="text" name="acc_objects[cards][<?php echo $ci; ?>][price_suffix]" value="<?php echo esc_attr( $card['price_suffix'] ?? '/ ночь' ); ?>" style="width:100%;">
                </p>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:8px;margin-top:8px;">
                <p>
                    <label><b>Текст «Подробнее»</b></label><br>
                    <input type="text" name="acc_objects[cards][<?php echo $ci; ?>][more_text]" value="<?php echo esc_attr( $card['more_text'] ?? 'Подробнее' ); ?>" style="width:100%;">
                </p>
                <p>
                    <label><b>Ссылка «Подробнее»</b></label><br>
                    <input type="text" name="acc_objects[cards][<?php echo $ci; ?>][more_link]" value="<?php echo esc_attr( $card['more_link'] ?? '/house' ); ?>" style="width:100%;">
                </p>
                <p>
                    <label><b>Текст «Забронировать»</b></label><br>
                    <input type="text" name="acc_objects[cards][<?php echo $ci; ?>][book_text]" value="<?php echo esc_attr( $card['book_text'] ?? 'Забронировать' ); ?>" style="width:100%;">
                </p>
                <p>
                    <label><b>Ссылка «Забронировать»</b></label><br>
                    <input type="text" name="acc_objects[cards][<?php echo $ci; ?>][book_link]" value="<?php echo esc_attr( $card['book_link'] ?? '#booking' ); ?>" style="width:100%;">
                </p>
            </div>

            <!-- Details (скрытый блок, formerly for popup) -->
            <details style="margin-top:10px;">
                <summary><b>Подробности (бывший попап)</b></summary>
                <p>
                    <label>Вступительный абзац</label><br>
                    <textarea name="acc_objects[cards][<?php echo $ci; ?>][details_intro]" rows="2" style="width:100%;"><?php echo esc_textarea( $card['details_intro'] ?? '' ); ?></textarea>
                </p>
                <p>
                    <label>Список подробностей (<code>&lt;strong&gt;…&lt;/strong&gt;</code> — жирный перед двоеточием)</label><br>
                    <div class="acc-det-list-wrap">
                        <?php $dl = $card['details_items'] ?? []; if ( ! empty( $dl ) ) : foreach ( $dl as $di => $d ) : ?>
                        <div class="acc-det-row" style="margin-bottom:4px;display:flex;gap:6px;">
                            <input type="text" name="acc_objects[cards][<?php echo $ci; ?>][details_items][<?php echo $di; ?>][text]" value="<?php echo esc_attr( $d['text'] ?? '' ); ?>" style="flex:1;" placeholder="<strong>Кухня:</strong> холодильник, микроволновка…">
                            <button type="button" class="button acc-det-rm">✕</button>
                        </div>
                        <?php endforeach; endif; ?>
                    </div>
                    <button type="button" class="button acc-det-add" data-ci="<?php echo $ci; ?>">+ Пункт</button>
                </p>
                <p>
                    <label>Примечание к цене</label><br>
                    <input type="text" name="acc_objects[cards][<?php echo $ci; ?>][details_pricenote]" value="<?php echo esc_attr( $card['details_pricenote'] ?? '' ); ?>" style="width:100%;">
                </p>
            </details>

            <hr style="margin:10px 0;">
            <button type="button" class="button acc-card-rm" style="color:#a00;">Удалить карточку</button>
        </div>
        <?php endforeach; endif; ?>
    </div>
    <button type="button" class="button" id="acc-card-add">+ Добавить карточку</button>

    <script>
    (function($){
        /* ── ФИЛЬТРЫ ── */
        var fgIdx = $('#acc-filters-wrap .acc-filter-group-block').length;

        $('#acc-filter-add-group').on('click', function(){
            var h = '<div class="acc-filter-group-block" style="border:1px solid #ccc;padding:12px;margin-bottom:12px;background:#fffbe6;">'
                + '<p><label><b>Метка группы</b></label><br>'
                + '<input type="text" name="acc_objects[filters][' + fgIdx + '][label]" style="width:300px;" placeholder="Формат"></p>'
                + '<div class="acc-filter-opts-wrap"></div>'
                + '<button type="button" class="button acc-filter-add-opt" data-fi="' + fgIdx + '">+ Опция</button>'
                + '<hr style="margin:10px 0;">'
                + '<button type="button" class="button acc-filter-rm-group" style="color:#a00;">Удалить группу</button>'
                + '</div>';
            $('#acc-filters-wrap').append(h);
            fgIdx++;
        });

        $(document).on('click', '.acc-filter-rm-group', function(){
            if( confirm('Удалить группу фильтров?') ) $(this).closest('.acc-filter-group-block').remove();
        });

        $(document).on('click', '.acc-filter-add-opt', function(){
            var fi = $(this).data('fi');
            var wrap = $(this).prev('.acc-filter-opts-wrap');
            var oi = wrap.find('.acc-filter-opt-row').length;
            wrap.append(
                '<div class="acc-filter-opt-row" style="display:flex;gap:6px;align-items:center;margin-bottom:4px;">'
                + '<input type="text" name="acc_objects[filters][' + fi + '][options][' + oi + '][text]" style="width:200px;" placeholder="Текст">'
                + '<input type="text" name="acc_objects[filters][' + fi + '][options][' + oi + '][slug]" style="width:200px;" placeholder="Слаг">'
                + '<label><input type="checkbox" name="acc_objects[filters][' + fi + '][options][' + oi + '][is_active]" value="1"> Активен</label>'
                + '<button type="button" class="button acc-filter-rm-opt">✕</button>'
                + '</div>'
            );
        });

        $(document).on('click', '.acc-filter-rm-opt', function(){
            $(this).closest('.acc-filter-opt-row').remove();
        });

        /* ── КАРТОЧКИ ── */
        var cIdx = $('#acc-cards-wrap .acc-card-block').length;

        $('#acc-card-add').on('click', function(){
            var i = cIdx;
            var h = '<div class="acc-card-block" style="border:1px solid #ccd;padding:14px;margin-bottom:14px;background:#fafafa;">'
                + '<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">'
                + '<p><label><b>Тип</b></label><br>'
                + '<select name="acc_objects[cards][' + i + '][type]">'
                + '<option value="eco">eco</option><option value="hotel">hotel</option><option value="guest">guest</option></select></p>'
                + '<p><label><b>Аудитория</b></label><br>'
                + '<label><input type="checkbox" name="acc_objects[cards][' + i + '][audience][family]" value="family"> family</label>&nbsp;'
                + '<label><input type="checkbox" name="acc_objects[cards][' + i + '][audience][couple]" value="couple"> couple</label>&nbsp;'
                + '<label><input type="checkbox" name="acc_objects[cards][' + i + '][audience][company]" value="company"> company</label></p></div>'
                + '<p><label><b>Изображение</b></label><br>'
                + '<img class="acc-card-img-preview" src="" style="max-width:250px;height:auto;display:none;margin:6px 0;border:1px solid #ddd;">'
                + '<input type="hidden" name="acc_objects[cards][' + i + '][img]" value="" class="acc-card-img-input">'
                + '<input type="text" name="acc_objects[cards][' + i + '][img_alt]" placeholder="Alt текст" style="width:400px;margin-left:8px;">'
                + '<button type="button" class="button acc-card-upload-img">📁</button> '
                + '<button type="button" class="button acc-card-rm-img" style="display:none;">✕</button></p>'
                + '<p><label><b>Бейдж</b></label><br>'
                + '<input type="text" name="acc_objects[cards][' + i + '][badge]" style="width:300px;"></p>'
                + '<p><label><b>Название</b></label><br>'
                + '<input type="text" name="acc_objects[cards][' + i + '][title]" style="width:100%;"></p>'
                + '<p><label><b>Мета</b></label><br>'
                + '<input type="text" name="acc_objects[cards][' + i + '][meta]" style="width:100%;"></p>'
                + '<p><label><b>Преимущества</b></label><br>'
                + '<div class="acc-feat-wrap"></div>'
                + '<button type="button" class="button acc-feat-add" data-ci="' + i + '">+ Преимущество</button></p>'
                + '<div style="display:grid;grid-template-columns:120px 200px 140px;gap:8px;align-items:end;">'
                + '<p><label><b>Префикс цены</b></label><br>'
                + '<input type="text" name="acc_objects[cards][' + i + '][price_prefix]" placeholder="от" style="width:100%;"></p>'
                + '<p><label><b>Цена</b></label><br>'
                + '<input type="text" name="acc_objects[cards][' + i + '][price]" style="width:100%;"></p>'
                + '<p><label><b>Суффикс</b></label><br>'
                + '<input type="text" name="acc_objects[cards][' + i + '][price_suffix]" value="/ ночь" style="width:100%;"></p></div>'
                + '<div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:8px;margin-top:8px;">'
                + '<p><label><b>«Подробнее» текст</b></label><br>'
                + '<input type="text" name="acc_objects[cards][' + i + '][more_text]" value="Подробнее" style="width:100%;"></p>'
                + '<p><label><b>«Подробнее» ссылка</b></label><br>'
                + '<input type="text" name="acc_objects[cards][' + i + '][more_link]" value="/house" style="width:100%;"></p>'
                + '<p><label><b>«Забронировать» текст</b></label><br>'
                + '<input type="text" name="acc_objects[cards][' + i + '][book_text]" value="Забронировать" style="width:100%;"></p>'
                + '<p><label><b>«Забронировать» ссылка</b></label><br>'
                + '<input type="text" name="acc_objects[cards][' + i + '][book_link]" value="#booking" style="width:100%;"></p></div>'
                + '<details style="margin-top:10px;"><summary><b>Подробности (бывший попап)</b></summary>'
                + '<p><label>Вступительный абзац</label><br>'
                + '<textarea name="acc_objects[cards][' + i + '][details_intro]" rows="2" style="width:100%;"></textarea></p>'
                + '<p><label>Список подробностей</label><br>'
                + '<div class="acc-det-list-wrap"></div>'
                + '<button type="button" class="button acc-det-add" data-ci="' + i + '">+ Пункт</button></p>'
                + '<p><label>Примечание к цене</label><br>'
                + '<input type="text" name="acc_objects[cards][' + i + '][details_pricenote]" style="width:100%;"></p>'
                + '</details>'
                + '<hr style="margin:10px 0;">'
                + '<button type="button" class="button acc-card-rm" style="color:#a00;">Удалить карточку</button>'
                + '</div>';
            $('#acc-cards-wrap').append(h);
            cIdx++;
        });

        $(document).on('click', '.acc-card-rm', function(){
            if( confirm('Удалить карточку?') ) $(this).closest('.acc-card-block').remove();
        });

        /* Features */
        $(document).on('click', '.acc-feat-add', function(){
            var ci = $(this).data('ci');
            var wrap = $(this).prev('.acc-feat-wrap');
            var k = wrap.find('.acc-feat-row').length;
            wrap.append(
                '<div class="acc-feat-row" style="margin-bottom:4px;display:flex;gap:6px;">'
                + '<input type="text" name="acc_objects[cards][' + ci + '][features][' + k + '][text]" style="flex:1;" placeholder="Особенность">'
                + '<button type="button" class="button acc-feat-rm">✕</button></div>'
            );
        });
        $(document).on('click', '.acc-feat-rm', function(){ $(this).closest('.acc-feat-row').remove(); });

        /* Details list */
        $(document).on('click', '.acc-det-add', function(){
            var ci = $(this).data('ci');
            var wrap = $(this).prev('.acc-det-list-wrap');
            var k = wrap.find('.acc-det-row').length;
            wrap.append(
                '<div class="acc-det-row" style="margin-bottom:4px;display:flex;gap:6px;">'
                + '<input type="text" name="acc_objects[cards][' + ci + '][details_items][' + k + '][text]" style="flex:1;" placeholder="<strong>Кухня:</strong> ...">'
                + '<button type="button" class="button acc-det-rm">✕</button></div>'
            );
        });
        $(document).on('click', '.acc-det-rm', function(){ $(this).closest('.acc-det-row').remove(); });

        /* Upload image */
        $(document).on('click', '.acc-card-upload-img', function(){
            var btn = this;
            var block = $(btn).closest('.acc-card-block');
            var frame = wp.media({ title: 'Выбрать фото карточки', multiple: false, library: { type: 'image' } });
            frame.on('select', function(){
                var url = frame.state().get('selection').first().toJSON().url;
                block.find('.acc-card-img-input').val(url);
                block.find('.acc-card-img-preview').attr('src', url).show();
                block.find('.acc-card-rm-img').show();
            });
            frame.open();
        });
        $(document).on('click', '.acc-card-rm-img', function(){
            var block = $(this).closest('.acc-card-block');
            block.find('.acc-card-img-input').val('');
            block.find('.acc-card-img-preview').hide().attr('src','');
            $(this).hide();
        });
    })(jQuery);
    </script>
    <?php
}

// Сохранение
add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['acc_objects_section_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['acc_objects_section_nonce_field'], 'acc_objects_section_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['acc_objects'] ?? [];
    $data = [
        'eyebrow' => sanitize_text_field( $raw['eyebrow'] ?? '' ),
        'title'   => wp_kses_post( $raw['title'] ?? '' ),
        'filters' => [],
        'cards'   => [],
    ];

    // Filters
    if ( ! empty( $raw['filters'] ) && is_array( $raw['filters'] ) ) {
        foreach ( $raw['filters'] as $fg ) {
            $label = sanitize_text_field( $fg['label'] ?? '' );
            $opts  = [];
            if ( ! empty( $fg['options'] ) && is_array( $fg['options'] ) ) {
                foreach ( $fg['options'] as $opt ) {
                    $t = sanitize_text_field( $opt['text'] ?? '' );
                    $s = sanitize_text_field( $opt['slug'] ?? '' );
                    if ( $t !== '' && $s !== '' ) {
                        $opts[] = [
                            'text'     => $t,
                            'slug'     => $s,
                            'is_active'=> ! empty( $opt['is_active'] ),
                        ];
                    }
                }
            }
            if ( $label !== '' && ! empty( $opts ) ) {
                $data['filters'][] = [ 'label' => $label, 'options' => $opts ];
            }
        }
    }

    // Cards
    if ( ! empty( $raw['cards'] ) && is_array( $raw['cards'] ) ) {
        foreach ( $raw['cards'] as $card ) {
            $card_title = sanitize_text_field( $card['title'] ?? '' );
            if ( empty( $card_title ) ) continue;

            $type = $card['type'] ?? 'eco';
            if ( ! in_array( $type, [ 'eco', 'hotel', 'guest' ], true ) ) $type = 'eco';

            $audience_raw = $card['audience'] ?? [];
            $audience = [];
            if ( is_array( $audience_raw ) ) {
                foreach ( $audience_raw as $a ) {
                    if ( in_array( $a, [ 'family', 'couple', 'company' ], true ) ) {
                        $audience[] = $a;
                    }
                }
            }

            $features = [];
            if ( ! empty( $card['features'] ) && is_array( $card['features'] ) ) {
                foreach ( $card['features'] as $f ) {
                    $ft = sanitize_text_field( $f['text'] ?? '' );
                    if ( $ft !== '' ) $features[] = [ 'text' => $ft ];
                }
            }

            $details_items = [];
            if ( ! empty( $card['details_items'] ) && is_array( $card['details_items'] ) ) {
                foreach ( $card['details_items'] as $d ) {
                    $dt = $d['text'] ?? '';
                    if ( $dt !== '' ) {
                        $allowed = [
                            'strong' => [],
                            'b'      => [],
                            'em'     => [],
                            'br'     => [],
                        ];
                        $details_items[] = [ 'text' => wp_kses( $dt, $allowed ) ];
                    }
                }
            }

            $data['cards'][] = [
                'type'            => $type,
                'audience'        => $audience,
                'img'             => esc_url_raw( $card['img'] ?? '' ),
                'img_alt'         => sanitize_text_field( $card['img_alt'] ?? '' ),
                'badge'           => sanitize_text_field( $card['badge'] ?? '' ),
                'title'           => $card_title,
                'meta'            => sanitize_text_field( $card['meta'] ?? '' ),
                'features'        => $features,
                'price_prefix'    => sanitize_text_field( $card['price_prefix'] ?? '' ),
                'price'           => sanitize_text_field( $card['price'] ?? '' ),
                'price_suffix'    => sanitize_text_field( $card['price_suffix'] ?? '' ),
                'more_text'       => sanitize_text_field( $card['more_text'] ?? 'Подробнее' ),
                'more_link'       => esc_url_raw( $card['more_link'] ?? '/house' ),
                'book_text'       => sanitize_text_field( $card['book_text'] ?? 'Забронировать' ),
                'book_link'       => esc_url_raw( $card['book_link'] ?? '#booking' ),
                'details_intro'   => wp_kses_post( $card['details_intro'] ?? '' ),
                'details_items'   => $details_items,
                'details_pricenote' => sanitize_text_field( $card['details_pricenote'] ?? '' ),
            ];
        }
    }

    update_post_meta( $post_id, 'acc_objects', $data );
} );