<?php
add_action( 'add_meta_boxes', function () {
    add_meta_box('articles_seo_section', 'Секция: О загородном отдыхе + Попап заявки', 'articles_seo_section_box', 'page', 'normal', 'high');
} );

function articles_seo_section_box( $post ) {
    wp_nonce_field( 'articles_seo_section_nonce', 'articles_seo_section_nonce_field' );
    $v = get_post_meta( $post->ID, 'articles_seo_section', true );

    $eyebrow       = $v['eyebrow'] ?? '';
    $title         = $v['title'] ?? '';
    $section_id    = $v['section_id'] ?? '';
    $bg_img        = $v['bg_img'] ?? '';
    $text          = $v['text'] ?? '';
    $media_img     = $v['media_img'] ?? '';
    $cta_title     = $v['cta_title'] ?? '';
    $cta_text      = $v['cta_text'] ?? '';
    $cta_btn_text  = $v['cta_btn_text'] ?? '';
    $cta_btn_link  = $v['cta_btn_link'] ?? '';
    $cta_btn2_text = $v['cta_btn2_text'] ?? '';
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
        .as-field{margin-bottom:10px}
        .as-field label{display:block;font-weight:600;margin-bottom:3px;font-size:13px}
        .as-field input[type="text"],.as-field textarea{width:100%;max-width:600px}
        .as-group{border:1px solid #c0c0c0;margin:14px 0;padding:14px;background:#fafafa;border-radius:4px}
        .as-group>h4{margin-top:0}
    </style>

    <p><strong>Секция «О загородном отдыхе»</strong></p>

    <div class="as-field">
        <label>Надсекция (eyebrow)</label>
        <input type="text" name="articles_seo_section[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>">
    </div>
    <div class="as-field">
        <label>Заголовок (HTML: &lt;span class="gold-header"&gt;&lt;em&gt;...&lt;/em&gt;&lt;/span&gt;)</label>
        <textarea name="articles_seo_section[title]" rows="2"><?php echo esc_textarea( $title ); ?></textarea>
    </div>
    <div class="as-field">
        <label>Section ID</label>
        <input type="text" name="articles_seo_section[section_id]" value="<?php echo esc_attr( $section_id ); ?>">
    </div>

    <input type="hidden" name="articles_seo_section[bg_img]" value="<?php echo esc_attr( $bg_img ); ?>" data-kp-media>
    <input type="hidden" name="articles_seo_section[media_img]" value="<?php echo esc_attr( $media_img ); ?>" data-kp-media>

    <div class="as-field">
        <label>Текст (абзацы разделяйте пустой строкой — двойным переносом)</label>
        <textarea name="articles_seo_section[text]" rows="10" style="max-width:800px"><?php echo esc_textarea( $text ); ?></textarea>
    </div>

    <div class="as-group">
        <p><strong>CTA-блок справа под фото</strong></p>
        <div class="as-field"><label>Заголовок CTA</label><input type="text" name="articles_seo_section[cta_title]" value="<?php echo esc_attr( $cta_title ); ?>"></div>
        <div class="as-field"><label>Текст CTA</label><textarea name="articles_seo_section[cta_text]" rows="2"><?php echo esc_textarea( $cta_text ); ?></textarea></div>
        <div class="as-field"><label>Кнопка 1 — текст</label><input type="text" name="articles_seo_section[cta_btn_text]" value="<?php echo esc_attr( $cta_btn_text ); ?>"></div>
        <div class="as-field"><label>Кнопка 1 — ссылка (tel:...)</label><input type="text" name="articles_seo_section[cta_btn_link]" value="<?php echo esc_attr( $cta_btn_link ); ?>"></div>
        <div class="as-field"><label>Кнопка 2 — текст (открывает попап заявки)</label><input type="text" name="articles_seo_section[cta_btn2_text]" value="<?php echo esc_attr( $cta_btn2_text ); ?>"></div>
    </div>

    <!-- MODAL -->
    <div class="as-group">
        <p><strong>Попап «Оставить заявку» (#site-modal-booking)</strong></p>

        <div class="as-field"><label>Бейдж</label><input type="text" name="articles_seo_section[m_badge]" value="<?php echo esc_attr( $m_badge ); ?>"></div>
        <div class="as-field"><label>Заголовок попапа</label><input type="text" name="articles_seo_section[m_title]" value="<?php echo esc_attr( $m_title ); ?>"></div>
        <div class="as-field"><label>Подзаголовок</label><textarea name="articles_seo_section[m_subtitle]" rows="2"><?php echo esc_textarea( $m_subtitle ); ?></textarea></div>

        <h4 style="margin-top:12px">Поля формы</h4>
        <div style="display:flex;gap:12px;margin-bottom:8px">
            <div style="flex:1"><label style="font-size:12px">Лейбл «Имя»</label><input type="text" name="articles_seo_section[m_name_l]" value="<?php echo esc_attr( $m_name_l ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">Placeholder «Имя»</label><input type="text" name="articles_seo_section[m_name_ph]" value="<?php echo esc_attr( $m_name_ph ); ?>"></div>
        </div>
        <div style="display:flex;gap:12px;margin-bottom:8px">
            <div style="flex:1"><label style="font-size:12px">Лейбл «Телефон»</label><input type="text" name="articles_seo_section[m_phone_l]" value="<?php echo esc_attr( $m_phone_l ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">Placeholder «Телефон»</label><input type="text" name="articles_seo_section[m_phone_ph]" value="<?php echo esc_attr( $m_phone_ph ); ?>"></div>
        </div>
        <div style="display:flex;gap:12px;margin-bottom:8px">
            <div style="flex:1"><label style="font-size:12px">Лейбл «Email»</label><input type="text" name="articles_seo_section[m_email_l]" value="<?php echo esc_attr( $m_email_l ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">Placeholder «Email»</label><input type="text" name="articles_seo_section[m_email_ph]" value="<?php echo esc_attr( $m_email_ph ); ?>"></div>
        </div>
        <div style="display:flex;gap:12px;margin-bottom:8px">
            <div style="flex:1"><label style="font-size:12px">Лейбл «Даты»</label><input type="text" name="articles_seo_section[m_date_l]" value="<?php echo esc_attr( $m_date_l ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">Placeholder «Даты»</label><input type="text" name="articles_seo_section[m_date_ph]" value="<?php echo esc_attr( $m_date_ph ); ?>"></div>
        </div>
        <div style="display:flex;gap:12px;margin-bottom:8px">
            <div style="flex:1"><label style="font-size:12px">Лейбл «Гости»</label><input type="text" name="articles_seo_section[m_guests_l]" value="<?php echo esc_attr( $m_guests_l ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">Placeholder «Гости»</label><input type="text" name="articles_seo_section[m_guests_ph]" value="<?php echo esc_attr( $m_guests_ph ); ?>"></div>
        </div>
        <div style="display:flex;gap:12px;margin-bottom:8px">
            <div style="flex:1"><label style="font-size:12px">Лейбл «Комментарий»</label><input type="text" name="articles_seo_section[m_msg_l]" value="<?php echo esc_attr( $m_msg_l ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">Placeholder «Комментарий»</label><input type="text" name="articles_seo_section[m_msg_ph]" value="<?php echo esc_attr( $m_msg_ph ); ?>"></div>
        </div>

        <div class="as-field"><label>Примечание об обязательных полях</label><input type="text" name="articles_seo_section[m_req_note]" value="<?php echo esc_attr( $m_req_note ); ?>"></div>
        <div class="as-field"><label>Текст кнопки отправки</label><input type="text" name="articles_seo_section[m_submit]" value="<?php echo esc_attr( $m_submit ); ?>"></div>
        <div class="as-field"><label>Текст согласия (до ссылки)</label><input type="text" name="articles_seo_section[m_consent]" value="<?php echo esc_attr( $m_consent ); ?>"></div>
        <div class="as-field"><label>Текст ссылки согласия</label><input type="text" name="articles_seo_section[m_consent_lnk]" value="<?php echo esc_attr( $m_consent_lnk ); ?>"></div>
        <div class="as-field"><label>URL ссылки согласия</label><input type="text" name="articles_seo_section[m_consent_url]" value="<?php echo esc_attr( $m_consent_url ); ?>"></div>

        <h4 style="margin-top:12px">Success-блок</h4>
        <div style="display:flex;gap:12px">
            <div style="flex:1"><label style="font-size:12px">Заголовок</label><input type="text" name="articles_seo_section[m_ok_title]" value="<?php echo esc_attr( $m_ok_title ); ?>"></div>
            <div style="flex:1"><label style="font-size:12px">Текст</label><input type="text" name="articles_seo_section[m_ok_text]" value="<?php echo esc_attr( $m_ok_text ); ?>"></div>
        </div>
    </div>

    <?php
}

add_action( 'save_post', function ( $post_id ) {
    if ( 'page' !== get_post_type( $post_id ) ) return;
    if ( ! isset( $_POST['articles_seo_section_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['articles_seo_section_nonce_field'], 'articles_seo_section_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['articles_seo_section'] ?? [];

    $data = [
        'eyebrow'       => sanitize_text_field( $raw['eyebrow'] ?? '' ),
        'title'         => wp_kses_post( $raw['title'] ?? '' ),
        'section_id'    => sanitize_text_field( $raw['section_id'] ?? '' ),
        'bg_img'        => esc_url_raw( $raw['bg_img'] ?? '' ),
        'text'          => sanitize_textarea_field( $raw['text'] ?? '' ),
        'media_img'     => esc_url_raw( $raw['media_img'] ?? '' ),
        'cta_title'     => sanitize_text_field( $raw['cta_title'] ?? '' ),
        'cta_text'      => sanitize_textarea_field( $raw['cta_text'] ?? '' ),
        'cta_btn_text'  => sanitize_text_field( $raw['cta_btn_text'] ?? '' ),
        'cta_btn_link'  => esc_url_raw( $raw['cta_btn_link'] ?? '' ),
        'cta_btn2_text' => sanitize_text_field( $raw['cta_btn2_text'] ?? '' ),
        // Modal
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

    update_post_meta( $post_id, 'articles_seo_section', $data );
} );
