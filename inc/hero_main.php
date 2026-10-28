<?php
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'hero_main_section', 'Секция: Главный Hero', 'hero_main_section_box', 'page', 'normal', 'high' );
} );

function hero_main_section_box( $post ) {
    wp_nonce_field( 'hero_main_nonce', 'hero_main_nonce_field' );
    $v = get_post_meta( $post->ID, 'hero_main', true );

    $bg_img         = $v['bg_img'] ?? '';
    $bg_video       = $v['bg_video'] ?? '';
    $eyebrow        = $v['eyebrow'] ?? '';
    $slides         = $v['slides'] ?? [];
    $lead           = $v['lead'] ?? '';
    $buttons        = $v['buttons'] ?? [];
    $socials        = $v['socials'] ?? [];
    $bk_title       = $v['bk_title'] ?? '';
    $bk_checkin_l   = $v['bk_checkin_l'] ?? '';
    $bk_checkout_l  = $v['bk_checkout_l'] ?? '';
    $bk_guests_l    = $v['bk_guests_l'] ?? '';
    $bk_submit      = $v['bk_submit'] ?? '';
    // Modal
    $m_badge       = $v['m_badge'] ?? '';
    $m_title       = $v['m_title'] ?? '';
    $m_subtitle    = $v['m_subtitle'] ?? '';
    $m_name_l      = $v['m_name_l'] ?? '';
    $m_name_ph     = $v['m_name_ph'] ?? '';
    $m_phone_l     = $v['m_phone_l'] ?? '';
    $m_phone_ph    = $v['m_phone_ph'] ?? '';
    $m_email_l     = $v['m_email_l'] ?? '';
    $m_email_ph    = $v['m_email_ph'] ?? '';
    $m_date_l      = $v['m_date_l'] ?? '';
    $m_date_ph     = $v['m_date_ph'] ?? '';
    $m_guests_l    = $v['m_guests_l'] ?? '';
    $m_guests_ph   = $v['m_guests_ph'] ?? '';
    $m_msg_l       = $v['m_msg_l'] ?? '';
    $m_msg_ph      = $v['m_msg_ph'] ?? '';
    $m_req_note    = $v['m_req_note'] ?? '';
    $m_submit      = $v['m_submit'] ?? '';
    $m_consent     = $v['m_consent'] ?? '';
    $m_consent_lnk = $v['m_consent_lnk'] ?? '';
    $m_consent_url = $v['m_consent_url'] ?? '';
    $m_ok_title    = $v['m_ok_title'] ?? '';
    $m_ok_text     = $v['m_ok_text'] ?? '';
    ?>
    <style>
        .hm-field{margin-bottom:10px}
        .hm-field label{display:block;font-weight:600;margin-bottom:3px;font-size:13px}
        .hm-field input[type="text"],.hm-field textarea{width:100%;max-width:700px}
        .hm-group{border:1px solid #c0c0c0;margin:14px 0;padding:14px;background:#fafafa;border-radius:4px}
        .hm-group>h4{margin-top:0}
        .hm-item{border:1px solid #ddd;margin:10px 0;padding:12px;background:#fff;border-radius:4px}
        .hm-item label{display:block;font-weight:600;margin-bottom:3px;font-size:12px}
        .hm-item input,.hm-item textarea,.hm-item select{width:100%}
        .hm-item .hm-row{display:flex;gap:12px;margin-bottom:8px}
        .hm-item .hm-row>div{flex:1}
        .hm-item .hm-row label{margin-bottom:2px}
        .hm-btn{padding:4px 12px;cursor:pointer;margin:3px 2px}
        .hm-btn--del{color:#a00;border-color:#a00}
        .hm-item textarea.svg-area{font-family:monospace;font-size:11px;min-height:80px}
    </style>

    <!-- ФОН -->
    <div class="hm-group">
        <h4>Фон Hero</h4>
        <div class="kp-field-grid">
            <div class="hm-field">
                <label>Фоновое изображение</label>
                <input type="hidden" name="hero_main[bg_img]" value="<?php echo esc_attr( $bg_img ); ?>" data-kp-media>
                <p class="description">Используется как основной фон и постер до загрузки видео.</p>
            </div>
            <div class="hm-field">
                <label>Фоновое видео</label>
                <input type="hidden" name="hero_main[bg_video]" value="<?php echo esc_attr( $bg_video ); ?>" data-kp-media data-kp-media-type="video">
                <p class="description">MP4 или WebM. На сайте воспроизводится без звука, по кругу.</p>
            </div>
        </div>
    </div>

    <div class="hm-field">
        <label>Eyebrow (над заголовком)</label>
        <input type="text" name="hero_main[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>">
    </div>

    <!-- СЛАЙДЫ ЗАГОЛОВКА -->
    <h3>Слайды заголовка (ротация)</h3>
    <p class="description">Заголовки сменяют друг друга автоматически. Перенос строки — &lt;br&gt;, выделение — &lt;em&gt;текст&lt;/em&gt;.</p>
    <div id="hm-slides">
        <?php if ( ! empty( $slides ) ) : ?>
            <?php foreach ( $slides as $si => $s ) : ?>
                <div class="hm-item">
                    <label>Текст слайда <?php echo $si + 1; ?></label>
                    <textarea name="hero_main[slides][<?php echo $si; ?>][text]" rows="3" class="svg-area" style="min-height:60px;font-family:inherit;font-size:13px"><?php echo esc_textarea( $s['text'] ?? '' ); ?></textarea>
                    <button type="button" class="hm-btn hm-btn--del" onclick="this.closest('.hm-item').remove()">Удалить слайд</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button type="button" class="hm-btn" id="hm-add-slide">+ Добавить слайд</button>

    <div class="hm-field" style="margin-top:14px">
        <label>Подзаголовок (lead)</label>
        <textarea name="hero_main[lead]" rows="3"><?php echo esc_textarea( $lead ); ?></textarea>
    </div>

    <!-- КНОПКИ -->
    <h3>Кнопки</h3>
    <div id="hm-buttons">
        <?php if ( ! empty( $buttons ) ) : ?>
            <?php foreach ( $buttons as $bi => $b ) : ?>
                <div class="hm-item">
                    <div class="hm-row">
                        <div><label>Текст</label><input type="text" name="hero_main[buttons][<?php echo $bi; ?>][text]" value="<?php echo esc_attr( $b['text'] ?? '' ); ?>"></div>
                        <div><label>Ссылка</label><input type="text" name="hero_main[buttons][<?php echo $bi; ?>][link]" value="<?php echo esc_attr( $b['link'] ?? '' ); ?>"></div>
                        <div><label>Оформление</label><select name="hero_main[buttons][<?php echo $bi; ?>][style]"><option value="gold" <?php selected( $b['style'] ?? 'gold', 'gold' ); ?>>Акцентная кнопка</option><option value="ghost" <?php selected( $b['style'] ?? '', 'ghost' ); ?>>Контурная кнопка</option></select></div>
                    </div>
                    <button type="button" class="hm-btn hm-btn--del" onclick="this.closest('.hm-item').remove()">Удалить</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button type="button" class="hm-btn" id="hm-add-btn">+ Добавить кнопку</button>

    <!-- СОЦСЕТИ -->
    <h3 style="margin-top:14px">Социальные ссылки</h3>
    <p style="font-size:12px;color:#666">Полный SVG-код иконки (включая тег &lt;svg&gt;...&lt;/svg&gt;)</p>
    <div id="hm-socials">
        <?php if ( ! empty( $socials ) ) : ?>
            <?php foreach ( $socials as $sci => $sc ) : ?>
                <div class="hm-item">
                    <div class="hm-row">
                        <div><label>Ссылка</label><input type="text" name="hero_main[socials][<?php echo $sci; ?>][href]" value="<?php echo esc_attr( $sc['href'] ?? '' ); ?>"></div>
                        <div><label>aria-label</label><input type="text" name="hero_main[socials][<?php echo $sci; ?>][aria]" value="<?php echo esc_attr( $sc['aria'] ?? '' ); ?>"></div>
                        <div style="flex:0 0 180px"><label>Доп. класс (необязательно)</label><input type="text" name="hero_main[socials][<?php echo $sci; ?>][css_class]" value="<?php echo esc_attr( $sc['css_class'] ?? '' ); ?>" placeholder="social--max"></div>
                    </div>
                    <div class="hm-field" style="margin-bottom:0">
                        <label>SVG-код иконки</label>
                        <textarea name="hero_main[socials][<?php echo $sci; ?>][svg]" rows="4" class="svg-area"><?php echo esc_textarea( $sc['svg'] ?? '' ); ?></textarea>
                    </div>
                    <button type="button" class="hm-btn hm-btn--del" onclick="this.closest('.hm-item').remove()">Удалить</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button type="button" class="hm-btn" id="hm-add-social">+ Добавить соцсеть</button>

    <!-- БРОНИРОВАНИЕ -->
    <div class="hm-group" style="margin-top:20px">
        <h4>Быстрое бронирование (форма)</h4>
        <div class="hm-field"><label>Заголовок формы</label><input type="text" name="hero_main[bk_title]" value="<?php echo esc_attr( $bk_title ); ?>"></div>
        <div class="hm-row" style="display:flex;gap:12px;margin-bottom:8px">
            <div style="flex:1"><label style="font-size:12px">Лейбл «Заезд»</label><input type="text" name="hero_main[bk_checkin_l]" value="<?php echo esc_attr( $bk_checkin_l ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">Лейбл «Выезд»</label><input type="text" name="hero_main[bk_checkout_l]" value="<?php echo esc_attr( $bk_checkout_l ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">Лейбл «Гости»</label><input type="text" name="hero_main[bk_guests_l]" value="<?php echo esc_attr( $bk_guests_l ); ?>"></div>
        </div>
        <div class="hm-field"><label>Текст кнопки</label><input type="text" name="hero_main[bk_submit]" value="<?php echo esc_attr( $bk_submit ); ?>"></div>
    </div>

    <!-- МОДАЛ -->
    <div class="hm-group">
        <h4>Попап «Оставить заявку» (#site-modal-booking)</h4>
        <div class="hm-field"><label>Бейдж</label><input type="text" name="hero_main[m_badge]" value="<?php echo esc_attr( $m_badge ); ?>"></div>
        <div class="hm-field"><label>Заголовок</label><input type="text" name="hero_main[m_title]" value="<?php echo esc_attr( $m_title ); ?>"></div>
        <div class="hm-field"><label>Подзаголовок</label><textarea name="hero_main[m_subtitle]" rows="2"><?php echo esc_textarea( $m_subtitle ); ?></textarea></div>

        <div class="hm-row" style="display:flex;gap:12px;margin-bottom:8px">
            <div style="flex:1"><label style="font-size:12px">Лейбл «Имя»</label><input type="text" name="hero_main[m_name_l]" value="<?php echo esc_attr( $m_name_l ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">Placeholder «Имя»</label><input type="text" name="hero_main[m_name_ph]" value="<?php echo esc_attr( $m_name_ph ); ?>"></div>
        </div>
        <div class="hm-row" style="display:flex;gap:12px;margin-bottom:8px">
            <div style="flex:1"><label style="font-size:12px">Лейбл «Телефон»</label><input type="text" name="hero_main[m_phone_l]" value="<?php echo esc_attr( $m_phone_l ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">Placeholder «Телефон»</label><input type="text" name="hero_main[m_phone_ph]" value="<?php echo esc_attr( $m_phone_ph ); ?>"></div>
        </div>
        <div class="hm-row" style="display:flex;gap:12px;margin-bottom:8px">
            <div style="flex:1"><label style="font-size:12px">Лейбл «Email»</label><input type="text" name="hero_main[m_email_l]" value="<?php echo esc_attr( $m_email_l ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">Placeholder «Email»</label><input type="text" name="hero_main[m_email_ph]" value="<?php echo esc_attr( $m_email_ph ); ?>"></div>
        </div>
        <div class="hm-row" style="display:flex;gap:12px;margin-bottom:8px">
            <div style="flex:1"><label style="font-size:12px">Лейбл «Даты»</label><input type="text" name="hero_main[m_date_l]" value="<?php echo esc_attr( $m_date_l ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">Placeholder «Даты»</label><input type="text" name="hero_main[m_date_ph]" value="<?php echo esc_attr( $m_date_ph ); ?>"></div>
        </div>
        <div class="hm-row" style="display:flex;gap:12px;margin-bottom:8px">
            <div style="flex:1"><label style="font-size:12px">Лейбл «Гости»</label><input type="text" name="hero_main[m_guests_l]" value="<?php echo esc_attr( $m_guests_l ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">Placeholder «Гости»</label><input type="text" name="hero_main[m_guests_ph]" value="<?php echo esc_attr( $m_guests_ph ); ?>"></div>
        </div>
        <div class="hm-row" style="display:flex;gap:12px;margin-bottom:8px">
            <div style="flex:1"><label style="font-size:12px">Лейбл «Комментарий»</label><input type="text" name="hero_main[m_msg_l]" value="<?php echo esc_attr( $m_msg_l ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">Placeholder «Комментарий»</label><input type="text" name="hero_main[m_msg_ph]" value="<?php echo esc_attr( $m_msg_ph ); ?>"></div>
        </div>
        <div class="hm-field"><label>Примечание *</label><input type="text" name="hero_main[m_req_note]" value="<?php echo esc_attr( $m_req_note ); ?>"></div>
        <div class="hm-field"><label>Текст кнопки</label><input type="text" name="hero_main[m_submit]" value="<?php echo esc_attr( $m_submit ); ?>"></div>
        <div class="hm-row" style="display:flex;gap:12px;margin-bottom:8px">
            <div style="flex:1"><label style="font-size:12px">Текст согласия</label><input type="text" name="hero_main[m_consent]" value="<?php echo esc_attr( $m_consent ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">Текст ссылки</label><input type="text" name="hero_main[m_consent_lnk]" value="<?php echo esc_attr( $m_consent_lnk ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">URL ссылки</label><input type="text" name="hero_main[m_consent_url]" value="<?php echo esc_attr( $m_consent_url ); ?>"></div>
        </div>
        <div class="hm-row" style="display:flex;gap:12px">
            <div style="flex:1"><label style="font-size:12px">Success — заголовок</label><input type="text" name="hero_main[m_ok_title]" value="<?php echo esc_attr( $m_ok_title ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">Success — текст</label><input type="text" name="hero_main[m_ok_text]" value="<?php echo esc_attr( $m_ok_text ); ?>"></div>
        </div>
    </div>

    <script>
jQuery(function($){

    /* Слайды */
    $('#hm-add-slide').on('click',function(){
        var box=$('#hm-slides'), i=box.find('.hm-item').length;
        box.append('<div class="hm-item"><label>Текст слайда '+(i+1)+'</label><textarea name="hero_main[slides]['+i+'][text]" rows="3" style="min-height:60px"></textarea><button type="button" class="hm-btn hm-btn--del" onclick="this.closest(\'.hm-item\').remove()">Удалить слайд</button></div>');
    });

    /* Кнопки */
    $('#hm-add-btn').on('click',function(){
        var box=$('#hm-buttons'), i=box.find('.hm-item').length;
        box.append('<div class="hm-item"><div class="hm-row"><div><label>Текст</label><input type="text" name="hero_main[buttons]['+i+'][text]"></div><div><label>Ссылка</label><input type="text" name="hero_main[buttons]['+i+'][link]"></div><div><label>Оформление</label><select name="hero_main[buttons]['+i+'][style]"><option value="gold">Акцентная кнопка</option><option value="ghost">Контурная кнопка</option></select></div></div><button type="button" class="hm-btn hm-btn--del" onclick="this.closest(\'.hm-item\').remove()">Удалить</button></div>');
    });

    /* Соцсети */
    $('#hm-add-social').on('click',function(){
        var box=$('#hm-socials'), i=box.find('.hm-item').length;
        box.append('<div class="hm-item"><div class="hm-row"><div><label>Ссылка</label><input type="text" name="hero_main[socials]['+i+'][href]"></div><div><label>aria-label</label><input type="text" name="hero_main[socials]['+i+'][aria]"></div><div style="flex:0 0 180px"><label>Доп. класс</label><input type="text" name="hero_main[socials]['+i+'][css_class]"></div></div><div class="hm-field" style="margin-bottom:0"><label>SVG-код</label><textarea name="hero_main[socials]['+i+'][svg]" rows="4" class="svg-area"></textarea></div><button type="button" class="hm-btn hm-btn--del" onclick="this.closest(\'.hm-item\').remove()">Удалить</button></div>');
    });

});
</script>
    <?php
}

add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['hero_main_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['hero_main_nonce_field'], 'hero_main_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['hero_main'] ?? [];

    $slides = [];
    if ( ! empty( $raw['slides'] ) && is_array( $raw['slides'] ) ) {
        foreach ( $raw['slides'] as $s ) {
            $t = wp_kses_post( $s['text'] ?? '' );
            if ( $t ) $slides[] = [ 'text' => $t ];
        }
    }

    $buttons = [];
    if ( ! empty( $raw['buttons'] ) && is_array( $raw['buttons'] ) ) {
        foreach ( $raw['buttons'] as $b ) {
            $t = sanitize_text_field( $b['text'] ?? '' );
            $l = esc_url_raw( $b['link'] ?? '' );
            if ( $t || $l ) {
                $buttons[] = [
                    'text'  => $t,
                    'link'  => $l,
                    'style' => ( $b['style'] === 'ghost' ) ? 'ghost' : 'gold',
                ];
            }
        }
    }

    $socials = [];
    if ( ! empty( $raw['socials'] ) && is_array( $raw['socials'] ) ) {
        foreach ( $raw['socials'] as $sc ) {
            $href = esc_url_raw( $sc['href'] ?? '' );
            $svg  = wp_kses( $sc['svg'] ?? '', [
                'svg'   => [ 'class','viewBox','fill','xmlns','width','height','aria-hidden','role','style' ],
                'path'  => [ 'd','fill','fill-rule','clip-rule','stroke','stroke-width','stroke-linecap','stroke-linejoin' ],
                'use'   => [ 'href','xlink:href' ],
                'polyline' => [ 'points','stroke','stroke-width','stroke-linecap','stroke-linejoin' ],
            ] );
            if ( $href || $svg ) {
                $socials[] = [
                    'href'      => $href,
                    'aria'      => sanitize_text_field( $sc['aria'] ?? '' ),
                    'css_class' => sanitize_text_field( $sc['css_class'] ?? '' ),
                    'svg'       => $svg,
                ];
            }
        }
    }

    $data = [
        'bg_img'         => esc_url_raw( $raw['bg_img'] ?? '' ),
        'bg_video'       => esc_url_raw( $raw['bg_video'] ?? '' ),
        'eyebrow'        => sanitize_text_field( $raw['eyebrow'] ?? '' ),
        'slides'         => $slides,
        'lead'           => sanitize_textarea_field( $raw['lead'] ?? '' ),
        'buttons'        => $buttons,
        'socials'        => $socials,
        'bk_title'       => sanitize_text_field( $raw['bk_title'] ?? '' ),
        'bk_checkin_l'   => sanitize_text_field( $raw['bk_checkin_l'] ?? '' ),
        'bk_checkout_l'  => sanitize_text_field( $raw['bk_checkout_l'] ?? '' ),
        'bk_guests_l'    => sanitize_text_field( $raw['bk_guests_l'] ?? '' ),
        'bk_submit'      => sanitize_text_field( $raw['bk_submit'] ?? '' ),
        'm_badge'       => sanitize_text_field( $raw['m_badge'] ?? '' ),
        'm_title'       => sanitize_text_field( $raw['m_title'] ?? '' ),
        'm_subtitle'    => sanitize_textarea_field( $raw['m_subtitle'] ?? '' ),
        'm_name_l'      => sanitize_text_field( $raw['m_name_l'] ?? '' ),
        'm_name_ph'     => sanitize_text_field( $raw['m_name_ph'] ?? '' ),
        'm_phone_l'     => sanitize_text_field( $raw['m_phone_l'] ?? '' ),
        'm_phone_ph'    => sanitize_text_field( $raw['m_phone_ph'] ?? '' ),
        'm_email_l'     => sanitize_text_field( $raw['m_email_l'] ?? '' ),
        'm_email_ph'    => sanitize_text_field( $raw['m_email_ph'] ?? '' ),
        'm_date_l'      => sanitize_text_field( $raw['m_date_l'] ?? '' ),
        'm_date_ph'     => sanitize_text_field( $raw['m_date_ph'] ?? '' ),
        'm_guests_l'    => sanitize_text_field( $raw['m_guests_l'] ?? '' ),
        'm_guests_ph'   => sanitize_text_field( $raw['m_guests_ph'] ?? '' ),
        'm_msg_l'       => sanitize_text_field( $raw['m_msg_l'] ?? '' ),
        'm_msg_ph'      => sanitize_text_field( $raw['m_msg_ph'] ?? '' ),
        'm_req_note'    => sanitize_text_field( $raw['m_req_note'] ?? '' ),
        'm_submit'      => sanitize_text_field( $raw['m_submit'] ?? '' ),
        'm_consent'     => sanitize_text_field( $raw['m_consent'] ?? '' ),
        'm_consent_lnk' => sanitize_text_field( $raw['m_consent_lnk'] ?? '' ),
        'm_consent_url' => esc_url_raw( $raw['m_consent_url'] ?? '' ),
        'm_ok_title'    => sanitize_text_field( $raw['m_ok_title'] ?? '' ),
        'm_ok_text'     => sanitize_text_field( $raw['m_ok_text'] ?? '' ),
    ];

    update_post_meta( $post_id, 'hero_main', $data );
} );
