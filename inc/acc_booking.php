<?php
// Meta box: Бронирование (подбор проживания)
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'acc_booking_section', 'Бронирование (подбор проживания)', 'acc_booking_section_cb', 'page', 'normal', 'high' );
} );

function acc_booking_section_cb( $post ) {
    wp_nonce_field( 'acc_booking_section_nonce', 'acc_booking_section_nonce_field' );
    $v = get_post_meta( $post->ID, 'acc_booking', true );

    $eyebrow      = $v['eyebrow'] ?? '';
    $title        = $v['title'] ?? '';
    $lead         = $v['lead'] ?? '';
    $points       = $v['points'] ?? [];
    $phones       = $v['phones'] ?? [];
    $fields       = $v['fields'] ?? [];
    $format_opts  = $v['format_opts'] ?? [];
    $submit_text  = $v['submit_text'] ?? 'Отправить заявку';
    $privacy_text = $v['privacy_text'] ?? '';
    $privacy_link = $v['privacy_link'] ?? '';
    ?>

    <p>
        <label><b>Бровь</b></label><br>
        <input type="text" name="acc_booking[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>" style="width:100%;max-width:500px;">
    </p>
    <p>
        <label><b>Заголовок</b> (<code>&lt;em&gt;</code>)</label><br>
        <input type="text" name="acc_booking[title]" value="<?php echo esc_attr( $title ); ?>" style="width:100%;">
    </p>
    <p>
        <label><b>Подзаголовок</b></label><br>
        <textarea name="acc_booking[lead]" rows="3" style="width:100%;max-width:700px;"><?php echo esc_textarea( $lead ); ?></textarea>
    </p>

    <hr>
    <h4>Пункты списка (с галочками)</h4>
    <div id="ab-points-wrap">
        <?php if ( ! empty( $points ) ) : foreach ( $points as $pi => $pt ) : ?>
        <div class="ab-point-row" style="display:flex;gap:6px;align-items:center;margin-bottom:4px;">
            <input type="text" name="acc_booking[points][<?php echo $pi; ?>][text]" value="<?php echo esc_attr( $pt['text'] ?? '' ); ?>" style="flex:1;" placeholder="Текст пункта">
            <button type="button" class="button ab-point-rm">✕</button>
        </div>
        <?php endforeach; endif; ?>
    </div>
    <button type="button" class="button" id="ab-point-add">+ Добавить пункт</button>

    <hr>
    <h4>Телефоны</h4>
    <div id="ab-phones-wrap">
        <?php if ( ! empty( $phones ) ) : foreach ( $phones as $phi => $ph ) : ?>
        <div class="ab-phone-row" style="display:flex;gap:6px;align-items:center;margin-bottom:4px;">
            <input type="text" name="acc_booking[phones][<?php echo $phi; ?>][number]" value="<?php echo esc_attr( $ph['number'] ?? '' ); ?>" style="width:200px;" placeholder="+79850200203">
            <input type="text" name="acc_booking[phones][<?php echo $phi; ?>][display]" value="<?php echo esc_attr( $ph['display'] ?? '' ); ?>" style="flex:1;" placeholder="+7 (985) 020-02-03">
            <button type="button" class="button ab-phone-rm">✕</button>
        </div>
        <?php endforeach; endif; ?>
    </div>
    <button type="button" class="button" id="ab-phone-add">+ Добавить телефон</button>

    <hr>
    <h4>Поля формы</h4>
    <p class="description">HTML формы захардкожен (id, name, type для JS). Здесь редактируются только подписи (label) и плейсхолдеры.</p>

    <?php
    $default_fields = [
        [ 'key' => 'name',     'label' => 'Ваше имя',              'placeholder' => 'Как к вам обращаться' ],
        [ 'key' => 'phone',    'label' => 'Телефон',              'placeholder' => '+7 (___) ___-__-__' ],
        [ 'key' => 'checkin',  'label' => 'Заезд',                'placeholder' => '' ],
        [ 'key' => 'checkout', 'label' => 'Выезд',                'placeholder' => '' ],
        [ 'key' => 'guests',   'label' => 'Гостей',               'placeholder' => '2' ],
        [ 'key' => 'format',   'label' => 'Формат',               'placeholder' => '' ],
        [ 'key' => 'comment',  'label' => 'Комментарий',          'placeholder' => 'Питомец, детская кроватка, баня или СПА...' ],
    ];
    $fields = $v['fields'] ?? $default_fields;
    ?>
    <table class="widefat" style="max-width:700px;">
        <thead><tr><th>Поле</th><th>Label</th><th>Placeholder</th></tr></thead>
        <tbody>
        <?php foreach ( $default_fields as $df ) :
            $k = $df['key'];
            $saved = null;
            foreach ( $fields as $f ) { if ( ($f['key'] ?? '') === $k ) { $saved = $f; break; } }
            $label = $saved['label'] ?? $df['label'];
            $ph    = $saved['placeholder'] ?? $df['placeholder'];
        ?>
        <tr>
            <td style="width:100px;font-weight:600;"><?php echo esc_html( $k ); ?></td>
            <td><input type="text" name="acc_booking[fields][<?php echo $k; ?>][label]" value="<?php echo esc_attr( $label ); ?>" style="width:100%;"></td>
            <td><input type="text" name="acc_booking[fields][<?php echo $k; ?>][placeholder]" value="<?php echo esc_attr( $ph ); ?>" style="width:100%;"></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <hr>
    <h4>Опции «Формат» (select)</h4>
    <div id="ab-fopts-wrap">
        <?php
        $default_opts = [
            [ 'value' => 'any',   'text' => 'Подберите на ваш выбор' ],
            [ 'value' => 'eco',   'text' => 'Эко-дом' ],
            [ 'value' => 'hotel', 'text' => 'Номер в гостинице' ],
            [ 'value' => 'guest', 'text' => 'Гостевой дом' ],
            [ 'value' => 'vip',   'text' => 'VIP-дом' ],
        ];
        $fopts = ! empty( $format_opts ) ? $format_opts : $default_opts;
        foreach ( $fopts as $oi => $opt ) :
        ?>
        <div class="ab-fopt-row" style="display:flex;gap:6px;align-items:center;margin-bottom:4px;">
            <input type="text" name="acc_booking[format_opts][<?php echo $oi; ?>][value]" value="<?php echo esc_attr( $opt['value'] ?? '' ); ?>" style="width:200px;" placeholder="value (eco)">
            <input type="text" name="acc_booking[format_opts][<?php echo $oi; ?>][text]" value="<?php echo esc_attr( $opt['text'] ?? '' ); ?>" style="flex:1;" placeholder="Текст опции">
            <button type="button" class="button ab-fopt-rm">✕</button>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button" id="ab-fopt-add">+ Добавить опцию</button>

    <hr>
    <p>
        <label><b>Текст кнопки отправки</b></label><br>
        <input type="text" name="acc_booking[submit_text]" value="<?php echo esc_attr( $submit_text ); ?>" style="width:400px;">
    </p>
    <p>
        <label><b>Текст ссылки на политику</b></label><br>
        <input type="text" name="acc_booking[privacy_text]" value="<?php echo esc_attr( $privacy_text ); ?>" style="width:100%;max-width:700px;" placeholder="Нажимая кнопку, вы соглашаетесь с ">
    </p>
    <p>
        <label><b>Ссылка на политику конфиденциальности</b></label><br>
        <input type="text" name="acc_booking[privacy_link]" value="<?php echo esc_attr( $privacy_link ); ?>" style="width:100%;max-width:500px;" placeholder="/privacy">
    </p>

    <script>
    (function($){
        // Points
        var pIdx = $('#ab-points-wrap .ab-point-row').length;
        $('#ab-point-add').on('click', function(){
            $('#ab-points-wrap').append(
                '<div class="ab-point-row" style="display:flex;gap:6px;align-items:center;margin-bottom:4px;">'
                + '<input type="text" name="acc_booking[points][' + pIdx + '][text]" style="flex:1;" placeholder="Текст пункта">'
                + '<button type="button" class="button ab-point-rm">✕</button></div>'
            );
            pIdx++;
        });
        $(document).on('click', '.ab-point-rm', function(){ $(this).closest('.ab-point-row').remove(); });

        // Phones
        var phIdx = $('#ab-phones-wrap .ab-phone-row').length;
        $('#ab-phone-add').on('click', function(){
            $('#ab-phones-wrap').append(
                '<div class="ab-phone-row" style="display:flex;gap:6px;align-items:center;margin-bottom:4px;">'
                + '<input type="text" name="acc_booking[phones][' + phIdx + '][number]" style="width:200px;" placeholder="+79850200203">'
                + '<input type="text" name="acc_booking[phones][' + phIdx + '][display]" style="flex:1;" placeholder="+7 (985) 020-02-03">'
                + '<button type="button" class="button ab-phone-rm">✕</button></div>'
            );
            phIdx++;
        });
        $(document).on('click', '.ab-phone-rm', function(){ $(this).closest('.ab-phone-row').remove(); });

        // Format options
        var fIdx = $('#ab-fopts-wrap .ab-fopt-row').length;
        $('#ab-fopt-add').on('click', function(){
            $('#ab-fopts-wrap').append(
                '<div class="ab-fopt-row" style="display:flex;gap:6px;align-items:center;margin-bottom:4px;">'
                + '<input type="text" name="acc_booking[format_opts][' + fIdx + '][value]" style="width:200px;" placeholder="value">'
                + '<input type="text" name="acc_booking[format_opts][' + fIdx + '][text]" style="flex:1;" placeholder="Текст">'
                + '<button type="button" class="button ab-fopt-rm">✕</button></div>'
            );
            fIdx++;
        });
        $(document).on('click', '.ab-fopt-rm', function(){ $(this).closest('.ab-fopt-row').remove(); });
    })(jQuery);
    </script>
    <?php
}

// Сохранение
add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['acc_booking_section_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['acc_booking_section_nonce_field'], 'acc_booking_section_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['acc_booking'] ?? [];

    // Points
    $points = [];
    if ( ! empty( $raw['points'] ) && is_array( $raw['points'] ) ) {
        foreach ( $raw['points'] as $pt ) {
            $t = sanitize_text_field( $pt['text'] ?? '' );
            if ( $t !== '' ) $points[] = [ 'text' => $t ];
        }
    }

    // Phones
    $phones = [];
    if ( ! empty( $raw['phones'] ) && is_array( $raw['phones'] ) ) {
        foreach ( $raw['phones'] as $ph ) {
            $num = preg_replace( '/[^0-9+]/', '', sanitize_text_field( $ph['number'] ?? '' ) );
            $disp = sanitize_text_field( $ph['display'] ?? '' );
            if ( $num !== '' ) $phones[] = [ 'number' => $num, 'display' => $disp ];
        }
    }

    // Fields (label + placeholder)
    $fields = [];
    if ( ! empty( $raw['fields'] ) && is_array( $raw['fields'] ) ) {
        foreach ( $raw['fields'] as $key => $f ) {
            $fields[] = [
                'key'         => sanitize_text_field( $key ),
                'label'       => sanitize_text_field( $f['label'] ?? '' ),
                'placeholder' => sanitize_text_field( $f['placeholder'] ?? '' ),
            ];
        }
    }

    // Format options
    $format_opts = [];
    if ( ! empty( $raw['format_opts'] ) && is_array( $raw['format_opts'] ) ) {
        foreach ( $raw['format_opts'] as $opt ) {
            $val = sanitize_text_field( $opt['value'] ?? '' );
            $txt = sanitize_text_field( $opt['text'] ?? '' );
            if ( $val !== '' && $txt !== '' ) $format_opts[] = [ 'value' => $val, 'text' => $txt ];
        }
    }

    $data = [
        'eyebrow'      => sanitize_text_field( $raw['eyebrow'] ?? '' ),
        'title'        => wp_kses_post( $raw['title'] ?? '' ),
        'lead'         => sanitize_textarea_field( $raw['lead'] ?? '' ),
        'points'       => $points,
        'phones'       => $phones,
        'fields'       => $fields,
        'format_opts'  => $format_opts,
        'submit_text'  => sanitize_text_field( $raw['submit_text'] ?? 'Отправить заявку' ),
        'privacy_text' => sanitize_text_field( $raw['privacy_text'] ?? '' ),
        'privacy_link' => esc_url_raw( $raw['privacy_link'] ?? '' ),
    ];

    update_post_meta( $post_id, 'acc_booking', $data );
} );