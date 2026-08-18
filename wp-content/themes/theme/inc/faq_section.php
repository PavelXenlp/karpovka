<?php
/**
 * Meta box: FAQ Section (spa_faq)
 */

/* ── Register ─────────────────────────────────────── */
add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'spa_faq_mb',
        'Секция: FAQ — Вопросы и ответы',
        'spa_faq_mb_callback',
        'page',
        'normal',
        'high'
    );
} );

/* ── Callback ────────────────────────────────────── */
function spa_faq_mb_callback( $post ) {

    wp_nonce_field( 'spa_faq_save', 'spa_faq_nonce' );

    $values = get_post_meta( $post->ID, 'spa_faq', true ) ?: [];

    $eyebrow       = $values['eyebrow']       ?? '';
    $title         = $values['title']         ?? '';
    $gold_text     = $values['gold_text']     ?? '';
    $subtitle      = $values['subtitle']       ?? '';
    $bg_image      = $values['bg_image']      ?? '';
    $section_id    = $values['section_id']    ?? 'faq';
    $btn_text      = $values['btn_text']      ?? '';
    $btn_link      = $values['btn_link']      ?? '';
    $items         = $values['items']         ?? [];

    ?>
    <style>
        #spa_faq_mb .field-group { margin-bottom: 14px; }
        #spa_faq_mb label { display: block; font-weight: 600; margin-bottom: 4px; }
        #spa_faq_mb input[type="text"],
        #spa_faq_mb input[type="url"],
        #spa_faq_mb textarea { width: 100%; }
        #spa_faq_mb .repeater-item { background: #fff; border: 1px solid #dcdcde; padding: 14px; margin-bottom: 10px; }
        #spa_faq_mb .faq-item-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        #spa_faq_mb .faq-item-head strong { font-size: 13px; color: #555; }
        #spa_faq_mb .remove-row { color: #b00; border: none; cursor: pointer; }
        #spa_faq_mb .add-row { margin-top: 8px; }
    </style>

    <div class="field-group">
        <label>Аннотация (eyebrow)</label>
        <input type="text" name="spa_faq[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>" />
    </div>

    <div class="field-group">
        <label>Заголовок (до gold-текста)</label>
        <input type="text" name="spa_faq[title]" value="<?php echo esc_attr( $title ); ?>" />
    </div>

    <div class="field-group">
        <label>Gold-текст (обёрнется в &lt;em&gt;)</label>
        <input type="text" name="spa_faq[gold_text]" value="<?php echo esc_attr( $gold_text ); ?>" />
    </div>

    <div class="field-group">
        <label>Подзаголовок</label>
        <textarea name="spa_faq[subtitle]" rows="2"><?php echo esc_textarea( $subtitle ); ?></textarea>
    </div>

    <div class="field-group">
        <label>Фоновое изображение</label>
        <input type="text" name="spa_faq[bg_image]" id="spa_faq_bg_image" value="<?php echo esc_attr( $bg_image ); ?>" />
        <button type="button" class="button" id="spa_faq_bg_image_btn">Выбрать</button>
    </div>

    <div class="field-group">
        <label>ID секции (anchor)</label>
        <input type="text" name="spa_faq[section_id]" value="<?php echo esc_attr( $section_id ); ?>" />
    </div>

    <hr />

    <h4>Вопросы и ответы</h4>
    <div id="spa_faq_items_wrap">
    <?php if ( ! empty( $items ) && is_array( $items ) ) :
        foreach ( $items as $i => $item ) : ?>
        <div class="repeater-item">
            <div class="faq-item-head">
                <strong><?php echo esc_html( mb_substr( $item['question'] ?? '', 0, 40 ) ); ?>…</strong>
                <button type="button" class="remove-row button button-small">Удалить</button>
            </div>
            <div class="field-group">
                <label>Вопрос</label>
                <input type="text" name="spa_faq[items][<?php echo $i; ?>][question]" value="<?php echo esc_attr( $item['question'] ?? '' ); ?>" />
            </div>
            <div class="field-group">
                <label>Ответ</label>
                <textarea name="spa_faq[items][<?php echo $i; ?>][answer]" rows="4"><?php echo esc_textarea( $item['answer'] ?? '' ); ?></textarea>
            </div>
        </div>
    <?php endforeach;
    endif; ?>
    </div>

    <button type="button" class="add-row button" id="add_faq_item">+ Вопрос</button>

    <hr />

    <div class="field-group">
        <label>Текст кнопки</label>
        <input type="text" name="spa_faq[btn_text]" value="<?php echo esc_attr( $btn_text ); ?>" />
    </div>

    <div class="field-group">
        <label>Ссылка кнопки</label>
        <input type="text" name="spa_faq[btn_link]" value="<?php echo esc_attr( $btn_link ); ?>" />
    </div>

    <script>
    jQuery(function($){
        $('#spa_faq_bg_image_btn').on('click', function(){
            var frame = wp.media({ title:'Выберите фоновое изображение', multiple:false, library:{type:'image'} });
            frame.on('select', function(){
                var url = frame.state().get('selection').first().toJSON().url;
                $('#spa_faq_bg_image').val(url);
            });
            frame.open();
        });
        $('#add_faq_item').on('click', function(){
            var wrap = $('#spa_faq_items_wrap');
            var idx  = wrap.find('.repeater-item').length;
            var html =
                '<div class="repeater-item">' +
                    '<div class="faq-item-head">' +
                        '<strong>Новый вопрос</strong>' +
                        '<button type="button" class="remove-row button button-small">Удалить</button>' +
                    '</div>' +
                    '<div class="field-group">' +
                        '<label>Вопрос</label>' +
                        '<input type="text" name="spa_faq[items][' + idx + '][question]" value="" />' +
                    '</div>' +
                    '<div class="field-group">' +
                        '<label>Ответ</label>' +
                        '<textarea name="spa_faq[items][' + idx + '][answer]" rows="4"></textarea>' +
                    '</div>' +
                '</div>';
            wrap.append(html);
        });
        $('#spa_faq_items_wrap').on('click', '.remove-row', function(){
            if( confirm('Удалить этот вопрос?') ) jQuery(this).closest('.repeater-item').remove();
        });
    });
    </script>
    <?php
}

/* ── Save ────────────────────────────────────────── */
add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['spa_faq_nonce'] ) || ! wp_verify_nonce( $_POST['spa_faq_nonce'], 'spa_faq_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['spa_faq'] ?? [];

    $clean = [
        'eyebrow'    => sanitize_text_field( $raw['eyebrow']    ?? '' ),
        'title'      => sanitize_text_field( $raw['title']      ?? '' ),
        'gold_text'  => sanitize_text_field( $raw['gold_text']  ?? '' ),
        'subtitle'   => sanitize_textarea_field( $raw['subtitle'] ?? '' ),
        'bg_image'   => esc_url_raw( $raw['bg_image'] ?? '' ),
        'section_id' => sanitize_text_field( $raw['section_id'] ?? '' ),
        'btn_text'   => sanitize_text_field( $raw['btn_text']   ?? '' ),
        'btn_link'   => esc_url_raw( $raw['btn_link'] ?? '' ),
        'items'      => [],
    ];

    if ( ! empty( $raw['items'] ) && is_array( $raw['items'] ) ) {
        foreach ( $raw['items'] as $item ) {
            $q = sanitize_text_field( $item['question'] ?? '' );
            $a = sanitize_textarea_field( $item['answer'] ?? '' );
            if ( $q !== '' ) {
                $clean['items'][] = [ 'question' => $q, 'answer' => $a ];
            }
        }
    }

    update_post_meta( $post_id, 'spa_faq', $clean );
} );