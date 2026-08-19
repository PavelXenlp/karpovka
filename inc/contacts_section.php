<?php
/**
 * Meta box: Contacts Section (contacts_section)
 */

add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'contacts_section_mb',
        'Секция: Контакты',
        'contacts_section_mb_callback',
        'page',
        'normal',
        'high'
    );
} );

function contacts_section_mb_callback( $post ) {

    wp_nonce_field( 'contacts_section_save', 'contacts_section_nonce' );

    $v = get_post_meta( $post->ID, 'contacts_section', true ) ?: [];

    ?>
    <style>
        #contacts_section_mb .field-group { margin-bottom: 14px; }
        #contacts_section_mb label { display: block; font-weight: 600; margin-bottom: 4px; }
        #contacts_section_mb input[type="text"],
        #contacts_section_mb textarea { width: 100%; }
        #contacts_section_mb .phone-row { display: flex; gap: 8px; align-items: flex-end; margin-bottom: 8px; }
        #contacts_section_mb .phone-row input { flex: 1; }
        #contacts_section_mb .note { color: #666; font-style: italic; margin-bottom: 14px; }
    </style>

    <p class="note">Форма обратного звонка и карта — встроены в шаблон и не редактируются.</p>

    <div class="field-group">
        <label>Аннотация (eyebrow)</label>
        <input type="text" name="contacts_section[eyebrow]" value="<?php echo esc_attr( $v['eyebrow'] ?? '' ); ?>" />
    </div>

    <div class="field-group">
        <label>Заголовок</label>
        <input type="text" name="contacts_section[title]" value="<?php echo esc_attr( $v['title'] ?? '' ); ?>" />
    </div>

    <div class="field-group">
        <label>ID секции (anchor)</label>
        <input type="text" name="contacts_section[section_id]" value="<?php echo esc_attr( $v['section_id'] ?? '' ); ?>" />
    </div>

    <hr />

    <div class="field-group">
        <label>Адрес</label>
        <input type="text" name="contacts_section[address]" value="<?php echo esc_attr( $v['address'] ?? '' ); ?>" />
    </div>

    <h4>Телефоны</h4>
    <div id="cs_phones_wrap">
    <?php
    $phones = $v['phones'] ?? [];
    if ( ! empty( $phones ) && is_array( $phones ) ) :
        foreach ( $phones as $i => $ph ) : ?>
        <div class="phone-row">
            <input type="text" name="contacts_section[phones][<?php echo $i; ?>][label]" value="<?php echo esc_attr( $ph['label'] ?? '' ); ?>" placeholder="Отображаемый номер" />
            <input type="text" name="contacts_section[phones][<?php echo $i; ?>][href]" value="<?php echo esc_attr( $ph['href'] ?? '' ); ?>" placeholder="tel:+79850200203" />
            <button type="button" class="button button-small remove-phone">✕</button>
        </div>
        <?php endforeach;
    endif; ?>
    </div>
    <button type="button" class="button" id="add_cs_phone" style="margin-bottom:14px;">+ Телефон</button>

    <div class="field-group">
        <label>Email</label>
        <input type="text" name="contacts_section[email]" value="<?php echo esc_attr( $v['email'] ?? '' ); ?>" />
    </div>

    <div class="field-group">
        <label>Заголовок формы</label>
        <input type="text" name="contacts_section[form_title]" value="<?php echo esc_attr( $v['form_title'] ?? '' ); ?>" />
    </div>

    <div class="field-group">
        <label>Подзаголовок формы</label>
        <textarea name="contacts_section[form_sub]" rows="2"><?php echo esc_textarea( $v['form_sub'] ?? '' ); ?></textarea>
    </div>

    <div class="field-group">
        <label>Текст кнопки формы</label>
        <input type="text" name="contacts_section[form_btn]" value="<?php echo esc_attr( $v['form_btn'] ?? '' ); ?>" />
    </div>

    <script>
    jQuery(function($){

        var phCount = 0;
        $('#cs_phones_wrap .phone-row').each(function(){
            var name = $(this).find('input').first().attr('name');
            if(name){
                var m = name.match(/\[phones\]\[(\d+)\]/);
                if(m) var idx = parseInt(m[1]);
                if(idx >= phCount) phCount = idx + 1;
            }
        });

        $('#add_cs_phone').on('click', function(){
            var i = phCount; phCount++;
            var h = '<div class="phone-row">' +
                '<input type="text" name="contacts_section[phones][' + i + '][label]" value="" placeholder="Отображаемый номер" />' +
                '<input type="text" name="contacts_section[phones][' + i + '][href]" value="" placeholder="tel:+79850200203" />' +
                '<button type="button" class="button button-small remove-phone">✕</button>' +
                '</div>';
            $('#cs_phones_wrap').append(h);
        });

        $('#cs_phones_wrap').on('click', '.remove-phone', function(){
            $(this).closest('.phone-row').remove();
        });

    });
    </script>
    <?php
}

/* ── Save ────────────────────────────────────────── */
add_action( 'save_post', function ( $post_id ) {

    if ( ! isset( $_POST['contacts_section_nonce'] ) || ! wp_verify_nonce( $_POST['contacts_section_nonce'], 'contacts_section_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['contacts_section'] ?? [];

    $phones = [];
    if ( ! empty( $raw['phones'] ) && is_array( $raw['phones'] ) ) {
        foreach ( $raw['phones'] as $ph ) {
            $l = sanitize_text_field( $ph['label'] ?? '' );
            $h = sanitize_text_field( $ph['href'] ?? '' );
            if ( $l !== '' ) $phones[] = [ 'label' => $l, 'href' => $h ];
        }
    }

    $clean = [
        'eyebrow'    => sanitize_text_field( $raw['eyebrow']    ?? '' ),
        'title'      => sanitize_text_field( $raw['title']      ?? '' ),
        'section_id' => sanitize_text_field( $raw['section_id'] ?? '' ),
        'address'    => sanitize_text_field( $raw['address']    ?? '' ),
        'phones'     => $phones,
        'email'      => sanitize_text_field( $raw['email']      ?? '' ),
        'form_title' => sanitize_text_field( $raw['form_title'] ?? '' ),
        'form_sub'   => sanitize_textarea_field( $raw['form_sub'] ?? '' ),
        'form_btn'   => sanitize_text_field( $raw['form_btn']   ?? '' ),
    ];

    update_post_meta( $post_id, 'contacts_section', $clean );
} );