<?php
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'cta_review_section', 'Секция: Оставьте отзыв (CTA)', 'cta_review_section_box', 'page', 'normal', 'high' );
} );

function cta_review_section_box( $post ) {
    wp_nonce_field( 'cta_review_section_nonce', 'cta_review_section_nonce_field' );
    $v = get_post_meta( $post->ID, 'cta_review_section', true );

    $eyebrow      = $v['eyebrow'] ?? '';
    $title        = $v['title'] ?? '';
    $section_id   = $v['section_id'] ?? '';
    $bg_img       = $v['bg_img'] ?? '';
    $aside_text   = $v['aside_text'] ?? '';
    $aside_img    = $v['aside_img'] ?? '';
    $form_legend  = $v['form_legend'] ?? '';
    $name_label   = $v['name_label'] ?? '';
    $name_ph      = $v['name_ph'] ?? '';
    $phone_label  = $v['phone_label'] ?? '';
    $phone_ph     = $v['phone_ph'] ?? '';
    $review_label = $v['review_label'] ?? '';
    $review_ph    = $v['review_ph'] ?? '';
    $review_rows  = $v['review_rows'] ?? '';
    $submit_text  = $v['submit_text'] ?? '';
    $priv_text    = $v['priv_text'] ?? '';
    $priv_link    = $v['priv_link'] ?? '';
    $priv_url     = $v['priv_url'] ?? '';
    $or_text      = $v['or_text'] ?? '';
    $actions      = $v['actions'] ?? [];
    ?>
    <style>
        .ctr-field{margin-bottom:10px}
        .ctr-field label{display:block;font-weight:600;margin-bottom:3px;font-size:13px}
        .ctr-field input[type="text"],.ctr-field textarea,.ctr-field input[type="number"]{width:100%;max-width:600px}
        .ctr-group{border:1px solid #c0c0c0;margin:14px 0;padding:14px;background:#fafafa;border-radius:4px}
        .ctr-group>h4{margin-top:0}
        .ctr-item{border:1px solid #ddd;margin:10px 0;padding:12px;background:#fff;border-radius:4px}
        .ctr-item label{display:block;font-weight:600;margin-bottom:3px;font-size:12px}
        .ctr-item input{width:100%}
        .ctr-item .ctr-row{display:flex;gap:12px;margin-bottom:8px}
        .ctr-item .ctr-row>div{flex:1}
        .ctr-item .ctr-row label{margin-bottom:2px}
        .ctr-btn{padding:4px 12px;cursor:pointer;margin:3px 2px}
        .ctr-btn--del{color:#a00;border-color:#a00}
    </style>

    <p><strong>Шапка секции</strong></p>
    <div class="ctr-field">
        <label>Надсекция (eyebrow)</label>
        <input type="text" name="cta_review_section[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>">
    </div>
    <div class="ctr-field">
        <label>Заголовок</label>
        <input type="text" name="cta_review_section[title]" value="<?php echo esc_attr( $title ); ?>">
    </div>
    <div class="ctr-field">
        <label>Section ID</label>
        <input type="text" name="cta_review_section[section_id]" value="<?php echo esc_attr( $section_id ); ?>">
    </div>

    <div class="ctr-field">
        <label>Фон (большое фото)</label>
        <input type="hidden" name="cta_review_section[bg_img]" value="<?php echo esc_attr( $bg_img ); ?>" data-kp-media>
    </div>

    <div class="ctr-group">
        <p><strong>Левая колонка</strong></p>
        <div class="ctr-field">
            <label>Текст</label>
            <textarea name="cta_review_section[aside_text]" rows="3"><?php echo esc_textarea( $aside_text ); ?></textarea>
        </div>
        <div class="ctr-field">
            <label>Фото</label>
            <input type="hidden" name="cta_review_section[aside_img]" value="<?php echo esc_attr( $aside_img ); ?>" data-kp-media>
        </div>
    </div>

    <div class="ctr-group">
        <p><strong>Форма отзыва</strong></p>
        <div class="ctr-field">
            <label>Подзаголовок формы (legend)</label>
            <input type="text" name="cta_review_section[form_legend]" value="<?php echo esc_attr( $form_legend ); ?>">
        </div>
        <div class="ctr-field">
            <label>Лейбл «Имя»</label>
            <input type="text" name="cta_review_section[name_label]" value="<?php echo esc_attr( $name_label ); ?>">
        </div>
        <div class="ctr-field">
            <label>Placeholder «Имя»</label>
            <input type="text" name="cta_review_section[name_ph]" value="<?php echo esc_attr( $name_ph ); ?>">
        </div>
        <div class="ctr-field">
            <label>Лейбл «Телефон»</label>
            <input type="text" name="cta_review_section[phone_label]" value="<?php echo esc_attr( $phone_label ); ?>">
        </div>
        <div class="ctr-field">
            <label>Placeholder «Телефон»</label>
            <input type="text" name="cta_review_section[phone_ph]" value="<?php echo esc_attr( $phone_ph ); ?>">
        </div>
        <div class="ctr-field">
            <label>Лейбл «Отзыв»</label>
            <input type="text" name="cta_review_section[review_label]" value="<?php echo esc_attr( $review_label ); ?>">
        </div>
        <div class="ctr-field">
            <label>Placeholder «Отзыв»</label>
            <input type="text" name="cta_review_section[review_ph]" value="<?php echo esc_attr( $review_ph ); ?>">
        </div>
        <div class="ctr-field">
            <label>Количество строк textarea</label>
            <input type="number" name="cta_review_section[review_rows]" value="<?php echo esc_attr( $review_rows ); ?>" min="2" max="20">
        </div>
        <div class="ctr-field">
            <label>Текст кнопки</label>
            <input type="text" name="cta_review_section[submit_text]" value="<?php echo esc_attr( $submit_text ); ?>">
        </div>
        <div class="ctr-field">
            <label>Текст перед ссылкой политики</label>
            <input type="text" name="cta_review_section[priv_text]" value="<?php echo esc_attr( $priv_text ); ?>">
        </div>
        <div class="ctr-field">
            <label>Текст ссылки</label>
            <input type="text" name="cta_review_section[priv_link]" value="<?php echo esc_attr( $priv_link ); ?>">
        </div>
        <div class="ctr-field">
            <label>URL ссылки политики</label>
            <input type="text" name="cta_review_section[priv_url]" value="<?php echo esc_attr( $priv_url ); ?>">
        </div>
    </div>

    <div class="ctr-group">
        <p><strong>Кнопки «или»</strong></p>
        <div class="ctr-field">
            <label>Текст разделителя (например: «или»)</label>
            <input type="text" name="cta_review_section[or_text]" value="<?php echo esc_attr( $or_text ); ?>">
        </div>

        <h4>Кнопки действий</h4>
        <div id="ctr-actions">
            <?php if ( ! empty( $actions ) ) : ?>
                <?php foreach ( $actions as $ai => $a ) : ?>
                    <div class="ctr-item">
                        <div class="ctr-row">
                            <div>
                                <label>Текст кнопки</label>
                                <input type="text" name="cta_review_section[actions][<?php echo $ai; ?>][text]" value="<?php echo esc_attr( $a['text'] ?? '' ); ?>">
                            </div>
                            <div>
                                <label>Ссылка</label>
                                <input type="text" name="cta_review_section[actions][<?php echo $ai; ?>][link]" value="<?php echo esc_attr( $a['link'] ?? '' ); ?>">
                            </div>
                            <div>
                                <label>Оформление</label>
                                <select name="cta_review_section[actions][<?php echo $ai; ?>][style]">
                                    <option value="gold" <?php selected( $a['style'] ?? 'gold', 'gold' ); ?>>Акцентная кнопка</option>
                                    <option value="ghost" <?php selected( $a['style'] ?? '', 'ghost' ); ?>>Контурная кнопка</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="ctr-btn ctr-btn--del" onclick="this.closest('.ctr-item').remove()">Удалить</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" class="ctr-btn" id="ctr-add-action">+ Добавить кнопку</button>
    </div>

    <script>
    jQuery(function($){

        /* Кнопки действий */
        $('#ctr-add-action').on('click',function(){
            var box = $('#ctr-actions');
            var i = box.find('.ctr-item').length;
            box.append(
                '<div class="ctr-item">' +
                '<div class="ctr-row">' +
                '<div><label>Текст кнопки</label><input type="text" name="cta_review_section[actions]['+i+'][text]"></div>' +
                '<div><label>Ссылка</label><input type="text" name="cta_review_section[actions]['+i+'][link]"></div>' +
                '<div><label>Оформление</label><select name="cta_review_section[actions]['+i+'][style]"><option value="gold">Акцентная кнопка</option><option value="ghost">Контурная кнопка</option></select></div>' +
                '</div>' +
                '<button type="button" class="ctr-btn ctr-btn--del" onclick="this.closest(\'.ctr-item\').remove()">Удалить</button>' +
                '</div>'
            );
        });

    });
    </script>
    <?php
}

add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['cta_review_section_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['cta_review_section_nonce_field'], 'cta_review_section_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['cta_review_section'] ?? [];

    $actions = [];
    if ( ! empty( $raw['actions'] ) && is_array( $raw['actions'] ) ) {
        foreach ( $raw['actions'] as $a ) {
            $text = sanitize_text_field( $a['text'] ?? '' );
            $link = esc_url_raw( $a['link'] ?? '' );
            if ( $text || $link ) {
                $actions[] = [
                    'text'  => $text,
                    'link'  => $link,
                    'style' => ( $a['style'] === 'ghost' ) ? 'ghost' : 'gold',
                ];
            }
        }
    }

    $data = [
        'eyebrow'      => sanitize_text_field( $raw['eyebrow'] ?? '' ),
        'title'        => sanitize_text_field( $raw['title'] ?? '' ),
        'section_id'   => sanitize_text_field( $raw['section_id'] ?? '' ),
        'bg_img'       => esc_url_raw( $raw['bg_img'] ?? '' ),
        'aside_text'   => sanitize_textarea_field( $raw['aside_text'] ?? '' ),
        'aside_img'    => esc_url_raw( $raw['aside_img'] ?? '' ),
        'form_legend'  => sanitize_text_field( $raw['form_legend'] ?? '' ),
        'name_label'   => sanitize_text_field( $raw['name_label'] ?? '' ),
        'name_ph'      => sanitize_text_field( $raw['name_ph'] ?? '' ),
        'phone_label'  => sanitize_text_field( $raw['phone_label'] ?? '' ),
        'phone_ph'     => sanitize_text_field( $raw['phone_ph'] ?? '' ),
        'review_label' => sanitize_text_field( $raw['review_label'] ?? '' ),
        'review_ph'    => sanitize_text_field( $raw['review_ph'] ?? '' ),
        'review_rows'  => max( 2, intval( $raw['review_rows'] ?? 4 ) ),
        'submit_text'  => sanitize_text_field( $raw['submit_text'] ?? '' ),
        'priv_text'    => sanitize_text_field( $raw['priv_text'] ?? '' ),
        'priv_link'    => sanitize_text_field( $raw['priv_link'] ?? '' ),
        'priv_url'     => esc_url_raw( $raw['priv_url'] ?? '' ),
        'or_text'      => sanitize_text_field( $raw['or_text'] ?? '' ),
        'actions'      => $actions,
    ];

    update_post_meta( $post_id, 'cta_review_section', $data );
} );
