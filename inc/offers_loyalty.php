<?php
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'offers_loyalty_section', 'Секция: Программа лояльности', 'offers_loyalty_section_box', 'page', 'normal', 'high' );
} );

function offers_loyalty_section_box( $post ) {
    wp_nonce_field( 'offers_loyalty_nonce', 'offers_loyalty_nonce_field' );
    $v = get_post_meta( $post->ID, 'offers_loyalty', true );

    $eyebrow    = $v['eyebrow'] ?? '';
    $title      = $v['title'] ?? '';
    $subtitle   = $v['subtitle'] ?? '';
    $section_id = $v['section_id'] ?? '';
    $lead       = $v['lead'] ?? '';
    $cash_num   = $v['cash_num'] ?? '';
    $cash_bold  = $v['cash_bold'] ?? '';
    $cash_small = $v['cash_small'] ?? '';
    $items      = $v['items'] ?? [];
    ?>
    <style>
        .ol-field{margin-bottom:10px}
        .ol-field label{display:block;font-weight:600;margin-bottom:3px;font-size:13px}
        .ol-field input[type="text"],.ol-field textarea{width:100%;max-width:600px}
        .ol-group{border:1px solid #c0c0c0;margin:14px 0;padding:14px;background:#fafafa;border-radius:4px}
        .ol-group>h4{margin-top:0}
        .ol-item{border:1px solid #ddd;margin:10px 0;padding:12px;background:#fff;border-radius:4px}
        .ol-item label{display:block;font-weight:600;margin-bottom:3px;font-size:12px}
        .ol-item input,.ol-item textarea{width:100%}
        .ol-btn{padding:4px 12px;cursor:pointer;margin:3px 2px}
        .ol-btn--del{color:#a00;border-color:#a00}
    </style>

    <div class="ol-field">
        <label>Надсекция (eyebrow)</label>
        <input type="text" name="offers_loyalty[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>">
    </div>
    <div class="ol-field">
        <label>Заголовок (HTML: &lt;span class="gold-header"&gt;&lt;em&gt;...&lt;/em&gt;&lt;/span&gt;)</label>
        <textarea name="offers_loyalty[title]" rows="2"><?php echo esc_textarea( $title ); ?></textarea>
    </div>
    <div class="ol-field">
        <label>Подзаголовок</label>
        <input type="text" name="offers_loyalty[subtitle]" value="<?php echo esc_attr( $subtitle ); ?>">
    </div>
    <div class="ol-field">
        <label>Section ID</label>
        <input type="text" name="offers_loyalty[section_id]" value="<?php echo esc_attr( $section_id ); ?>">
    </div>

    <div class="ol-group">
        <h4>Левая колонка (aside)</h4>
        <div class="ol-field">
            <label>Вступительный текст</label>
            <textarea name="offers_loyalty[lead]" rows="4"><?php echo esc_textarea( $lead ); ?></textarea>
        </div>
        <div style="display:flex;gap:12px;margin-bottom:8px">
            <div style="flex:0 0 80px">
                <label style="font-size:12px">Число (5%)</label>
                <input type="text" name="offers_loyalty[cash_num]" value="<?php echo esc_attr( $cash_num ); ?>">
            </div>
            <div style="flex:1">
                <label style="font-size:12px">Жирный текст (кэшбэк)</label>
                <input type="text" name="offers_loyalty[cash_bold]" value="<?php echo esc_attr( $cash_bold ); ?>">
            </div>
        </div>
        <div class="ol-field">
            <label>Подпись под жирным</label>
            <input type="text" name="offers_loyalty[cash_small]" value="<?php echo esc_attr( $cash_small ); ?>">
        </div>
    </div>

    <h3>Пункты программы</h3>
    <div id="ol-items">
        <?php if ( ! empty( $items ) ) : ?>
            <?php foreach ( $items as $ii => $item ) : ?>
                <div class="ol-item">
                    <div style="display:flex;gap:12px;margin-bottom:8px">
                        <div style="flex:0 0 80px">
                            <label>Номер</label>
                            <input type="text" name="offers_loyalty[items][<?php echo $ii; ?>][num]" value="<?php echo esc_attr( $item['num'] ?? '' ); ?>" placeholder="01">
                        </div>
                        <div style="flex:1">
                            <label>Заголовок</label>
                            <input type="text" name="offers_loyalty[items][<?php echo $ii; ?>][title]" value="<?php echo esc_attr( $item['title'] ?? '' ); ?>">
                        </div>
                    </div>
                    <div class="ol-field" style="margin-bottom:0">
                        <label>Описание</label>
                        <textarea name="offers_loyalty[items][<?php echo $ii; ?>][desc]" rows="3"><?php echo esc_textarea( $item['desc'] ?? '' ); ?></textarea>
                    </div>
                    <button type="button" class="ol-btn ol-btn--del" onclick="this.closest('.ol-item').remove()">Удалить</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button type="button" class="ol-btn" id="ol-add-item">+ Добавить пункт</button>

    <script>
    jQuery(function($){
        $('#ol-add-item').on('click',function(){
            var box = $('#ol-items');
            var i = box.find('.ol-item').length;
            var num = String(i+1).padStart(2,'0');
            box.append(
                '<div class="ol-item">' +
                '<div style="display:flex;gap:12px;margin-bottom:8px">' +
                '<div style="flex:0 0 80px"><label>Номер</label><input type="text" name="offers_loyalty[items]['+i+'][num]" value="'+num+'"></div>' +
                '<div style="flex:1"><label>Заголовок</label><input type="text" name="offers_loyalty[items]['+i+'][title]"></div>' +
                '</div>' +
                '<div class="ol-field" style="margin-bottom:0"><label>Описание</label><textarea name="offers_loyalty[items]['+i+'][desc]" rows="3"></textarea></div>' +
                '<button type="button" class="ol-btn ol-btn--del" onclick="this.closest(\'.ol-item\').remove()">Удалить</button>' +
                '</div>'
            );
        });
    });
    </script>
    <?php
}

add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['offers_loyalty_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['offers_loyalty_nonce_field'], 'offers_loyalty_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['offers_loyalty'] ?? [];

    $items = [];
    if ( ! empty( $raw['items'] ) && is_array( $raw['items'] ) ) {
        foreach ( $raw['items'] as $item ) {
            $t = sanitize_text_field( $item['title'] ?? '' );
            $d = sanitize_textarea_field( $item['desc'] ?? '' );
            if ( $t || $d ) {
                $items[] = [
                    'num'   => sanitize_text_field( $item['num'] ?? '' ),
                    'title' => $t,
                    'desc'  => $d,
                ];
            }
        }
    }

    $data = [
        'eyebrow'    => sanitize_text_field( $raw['eyebrow'] ?? '' ),
        'title'      => wp_kses_post( $raw['title'] ?? '' ),
        'subtitle'   => sanitize_text_field( $raw['subtitle'] ?? '' ),
        'section_id' => sanitize_text_field( $raw['section_id'] ?? '' ),
        'lead'       => sanitize_textarea_field( $raw['lead'] ?? '' ),
        'cash_num'   => sanitize_text_field( $raw['cash_num'] ?? '' ),
        'cash_bold'  => sanitize_text_field( $raw['cash_bold'] ?? '' ),
        'cash_small' => sanitize_text_field( $raw['cash_small'] ?? '' ),
        'items'      => $items,
    ];

    update_post_meta( $post_id, 'offers_loyalty', $data );
} );