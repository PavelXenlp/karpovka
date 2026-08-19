<?php
/**
 * Meta box: CTA Review Section (cta_review)
 * + hardcoded modals (callback & booking)
 */

/* ── Register ─────────────────────────────────────── */
add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'cta_review_mb',
        'Секция: CTA — Забронируйте SPA-день',
        'cta_review_mb_callback',
        'page',
        'normal',
        'high'
    );
} );

/* ── Callback ────────────────────────────────────── */
function cta_review_mb_callback( $post ) {

    wp_nonce_field( 'cta_review_save', 'cta_review_nonce' );

    $v = get_post_meta( $post->ID, 'cta_review', true ) ?: [];

    $eyebrow    = $v['eyebrow']    ?? '';
    $title      = $v['title']      ?? '';
    $gold_text  = $v['gold_text']  ?? '';
    $text       = $v['text']       ?? '';
    $phone      = $v['phone']      ?? '';
    $btn_text   = $v['btn_text']   ?? '';
    $btn2_text  = $v['btn2_text']  ?? '';
    $section_id = $v['section_id'] ?? 'cta';

    ?>
    <style>
        #cta_review_mb .field-group { margin-bottom: 14px; }
        #cta_review_mb label { display: block; font-weight: 600; margin-bottom: 4px; }
        #cta_review_mb input[type="text"],
        #cta_review_mb textarea { width: 100%; }
        #cta_review_mb .note { color: #666; font-style: italic; margin-bottom: 14px; }
    </style>

    <p class="note">Попапы «Обратный звонок» и «Оставить заявку» — встроены в шаблон и не редактируются (фиксированные формы).</p>

    <div class="field-group">
        <label>Аннотация (eyebrow)</label>
        <input type="text" name="cta_review[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>" />
    </div>

    <div class="field-group">
        <label>Заголовок (до gold-текста)</label>
        <input type="text" name="cta_review[title]" value="<?php echo esc_attr( $title ); ?>" />
    </div>

    <div class="field-group">
        <label>Gold-текст (обёрнется в &lt;em&gt;)</label>
        <input type="text" name="cta_review[gold_text]" value="<?php echo esc_attr( $gold_text ); ?>" />
    </div>

    <div class="field-group">
        <label>Описание</label>
        <textarea name="cta_review[text]" rows="3"><?php echo esc_textarea( $text ); ?></textarea>
    </div>

    <hr />

    <div class="field-group">
        <label>Телефон для кнопки «Позвонить» (tel:)</label>
        <input type="text" name="cta_review[phone]" value="<?php echo esc_attr( $phone ); ?>" placeholder="tel:+79850200203" />
    </div>

    <div class="field-group">
        <label>Текст кнопки «Позвонить»</label>
        <input type="text" name="cta_review[btn_text]" value="<?php echo esc_attr( $btn_text ); ?>" />
    </div>

    <div class="field-group">
        <label>Текст кнопки «Оставить заявку»</label>
        <input type="text" name="cta_review[btn2_text]" value="<?php echo esc_attr( $btn2_text ); ?>" />
    </div>

    <hr />

    <div class="field-group">
        <label>ID секции (anchor)</label>
        <input type="text" name="cta_review[section_id]" value="<?php echo esc_attr( $section_id ); ?>" />
    </div>
    <?php
}

/* ── Save ────────────────────────────────────────── */
add_action( 'save_post', function ( $post_id ) {

    if ( ! isset( $_POST['cta_review_nonce'] ) || ! wp_verify_nonce( $_POST['cta_review_nonce'], 'cta_review_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['cta_review'] ?? [];

    $clean = [
        'eyebrow'    => sanitize_text_field( $raw['eyebrow']    ?? '' ),
        'title'      => sanitize_text_field( $raw['title']      ?? '' ),
        'gold_text'  => sanitize_text_field( $raw['gold_text']  ?? '' ),
        'text'       => sanitize_textarea_field( $raw['text']    ?? '' ),
        'phone'      => sanitize_text_field( $raw['phone']      ?? '' ),
        'btn_text'   => sanitize_text_field( $raw['btn_text']   ?? '' ),
        'btn2_text'  => sanitize_text_field( $raw['btn2_text']  ?? '' ),
        'section_id' => sanitize_text_field( $raw['section_id'] ?? '' ),
    ];

    update_post_meta( $post_id, 'cta_review', $clean );
} );