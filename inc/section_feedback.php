<?php
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'feedback_form_section', 'Секция: Форма обратной связи', 'feedback_form_section_box', 'page', 'normal', 'high' );
} );

function feedback_form_section_box( $post ) {
    wp_nonce_field( 'feedback_form_nonce', 'feedback_form_nonce_field' );
    $v = get_post_meta( $post->ID, 'feedback_form', true );

    $label         = $v['label'] ?? '';
    $title         = $v['title'] ?? '';
    $desc          = $v['desc'] ?? '';
    $contacts      = $v['contacts'] ?? [];
    $name_label    = $v['name_label'] ?? '';
    $name_ph       = $v['name_ph'] ?? '';
    $phone_label   = $v['phone_label'] ?? '';
    $phone_ph      = $v['phone_ph'] ?? '';
    $email_label   = $v['email_label'] ?? '';
    $email_opt     = $v['email_opt'] ?? '';
    $email_ph      = $v['email_ph'] ?? '';
    $topic_label   = $v['topic_label'] ?? '';
    $topic_opts    = $v['topic_opts'] ?? [];
    $msg_label     = $v['msg_label'] ?? '';
    $msg_ph        = $v['msg_ph'] ?? '';
    $submit_text   = $v['submit_text'] ?? '';
    $priv_text     = $v['priv_text'] ?? '';
    $priv_link     = $v['priv_link'] ?? '';
    $priv_url      = $v['priv_url'] ?? '';
    $ok_title      = $v['ok_title'] ?? '';
    $ok_text       = $v['ok_text'] ?? '';
    $section_id    = $v['section_id'] ?? '';
    ?>
    <style>
        .fbf-field{margin-bottom:12px}
        .fbf-field label{display:block;font-weight:600;margin-bottom:4px}
        .fbf-field input[type="text"],.fbf-field textarea{width:100%;max-width:600px}
        .fbf-group{border:1px solid #ccc;margin:16px 0;padding:16px;background:#f9f9f9;border-radius:4px}
        .fbf-group h4{margin-top:0}
        .fbf-item{border:1px solid #e0e0e0;margin:8px 0;padding:12px;background:#fff;border-radius:4px}
        .fbf-item label{display:block;font-weight:600;margin-bottom:4px;font-size:13px}
        .fbf-item input,.fbf-item textarea{width:100%}
        .fbf-btn{margin:4px 2px;padding:4px 12px;cursor:pointer}
        .fbf-btn--del{color:#a00;border-color:#a00}
    </style>

    <p><strong>Левая колонка — текст</strong></p>

    <div class="fbf-field">
        <label>Метка (Над заголовком)</label>
        <input type="text" name="feedback_form[label]" value="<?php echo esc_attr( $label ); ?>">
    </div>
    <div class="fbf-field">
        <label>Заголовок (можно HTML: &lt;em&gt;)</label>
        <textarea name="feedback_form[title]" rows="2"><?php echo esc_textarea( $title ); ?></textarea>
    </div>
    <div class="fbf-field">
        <label>Описание</label>
        <textarea name="feedback_form[desc]" rows="3"><?php echo esc_textarea( $desc ); ?></textarea>
    </div>

    <h3>Контакты (телефоны и email)</h3>
    <div id="fbf-contacts">
        <?php if ( ! empty( $contacts ) ) : ?>
            <?php foreach ( $contacts as $ci => $c ) : ?>
                <div class="fbf-item">
                    <label>Ссылка (tel:... или mailto:...)</label>
                    <input type="text" name="feedback_form[contacts][<?php echo $ci; ?>][href]" value="<?php echo esc_attr( $c['href'] ?? '' ); ?>">
                    <label>Текст ссылки</label>
                    <input type="text" name="feedback_form[contacts][<?php echo $ci; ?>][text]" value="<?php echo esc_attr( $c['text'] ?? '' ); ?>">
                    <button type="button" class="fbf-btn fbf-btn--del" onclick="this.closest('.fbf-item').remove()">Удалить</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button type="button" class="fbf-btn" id="fbf-add-contact">+ Добавить контакт</button>

    <div class="fbf-group" style="margin-top:24px">
        <p><strong>Правая колонка — форма</strong></p>

        <div class="fbf-field">
            <label>Лейбл «Имя»</label>
            <input type="text" name="feedback_form[name_label]" value="<?php echo esc_attr( $name_label ); ?>">
        </div>
        <div class="fbf-field">
            <label>Placeholder «Имя»</label>
            <input type="text" name="feedback_form[name_ph]" value="<?php echo esc_attr( $name_ph ); ?>">
        </div>
        <div class="fbf-field">
            <label>Лейбл «Телефон»</label>
            <input type="text" name="feedback_form[phone_label]" value="<?php echo esc_attr( $phone_label ); ?>">
        </div>
        <div class="fbf-field">
            <label>Placeholder «Телефон»</label>
            <input type="text" name="feedback_form[phone_ph]" value="<?php echo esc_attr( $phone_ph ); ?>">
        </div>
        <div class="fbf-field">
            <label>Лейбл «Email»</label>
            <input type="text" name="feedback_form[email_label]" value="<?php echo esc_attr( $email_label ); ?>">
        </div>
        <div class="fbf-field">
            <label>Пометка «необязательно»</label>
            <input type="text" name="feedback_form[email_opt]" value="<?php echo esc_attr( $email_opt ); ?>">
        </div>
        <div class="fbf-field">
            <label>Placeholder «Email»</label>
            <input type="text" name="feedback_form[email_ph]" value="<?php echo esc_attr( $email_ph ); ?>">
        </div>
        <div class="fbf-field">
            <label>Лейбл «Тема обращения»</label>
            <input type="text" name="feedback_form[topic_label]" value="<?php echo esc_attr( $topic_label ); ?>">
        </div>

        <h4>Варианты темы (select options)</h4>
        <div id="fbf-topics">
            <?php if ( ! empty( $topic_opts ) ) : ?>
                <?php foreach ( $topic_opts as $ti => $opt ) : ?>
                    <div class="fbf-item">
                        <label>Value (латиница, без пробелов)</label>
                        <input type="text" name="feedback_form[topic_opts][<?php echo $ti; ?>][value]" value="<?php echo esc_attr( $opt['value'] ?? '' ); ?>">
                        <label>Текст option</label>
                        <input type="text" name="feedback_form[topic_opts][<?php echo $ti; ?>][text]" value="<?php echo esc_attr( $opt['text'] ?? '' ); ?>">
                        <button type="button" class="fbf-btn fbf-btn--del" onclick="this.closest('.fbf-item').remove()">Удалить</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" class="fbf-btn" id="fbf-add-topic">+ Добавить option</button>

        <div class="fbf-field" style="margin-top:16px">
            <label>Лейбл «Сообщение»</label>
            <input type="text" name="feedback_form[msg_label]" value="<?php echo esc_attr( $msg_label ); ?>">
        </div>
        <div class="fbf-field">
            <label>Placeholder «Сообщение»</label>
            <input type="text" name="feedback_form[msg_ph]" value="<?php echo esc_attr( $msg_ph ); ?>">
        </div>
        <div class="fbf-field">
            <label>Текст кнопки отправки</label>
            <input type="text" name="feedback_form[submit_text]" value="<?php echo esc_attr( $submit_text ); ?>">
        </div>
    </div>

    <div class="fbf-group">
        <p><strong>Подпись и success</strong></p>
        <div class="fbf-field">
            <label>Текст перед ссылкой</label>
            <input type="text" name="feedback_form[priv_text]" value="<?php echo esc_attr( $priv_text ); ?>">
        </div>
        <div class="fbf-field">
            <label>Текст ссылки (политика конфиденциальности)</label>
            <input type="text" name="feedback_form[priv_link]" value="<?php echo esc_attr( $priv_link ); ?>">
        </div>
        <div class="fbf-field">
            <label>URL ссылки политики</label>
            <input type="text" name="feedback_form[priv_url]" value="<?php echo esc_attr( $priv_url ); ?>">
        </div>
        <div class="fbf-field">
            <label>Success — заголовок</label>
            <input type="text" name="feedback_form[ok_title]" value="<?php echo esc_attr( $ok_title ); ?>">
        </div>
        <div class="fbf-field">
            <label>Success — текст</label>
            <input type="text" name="feedback_form[ok_text]" value="<?php echo esc_attr( $ok_text ); ?>">
        </div>
    </div>

    <div class="fbf-field">
        <label>Section ID</label>
        <input type="text" name="feedback_form[section_id]" value="<?php echo esc_attr( $section_id ); ?>">
    </div>

    <script>
    jQuery(function($){
        $('#fbf-add-contact').on('click', function(){
            var box = $('#fbf-contacts');
            var i = box.find('.fbf-item').length;
            box.append(
                '<div class="fbf-item">' +
                '<label>Ссылка (tel:... или mailto:...)</label>' +
                '<input type="text" name="feedback_form[contacts]['+i+'][href]">' +
                '<label>Текст ссылки</label>' +
                '<input type="text" name="feedback_form[contacts]['+i+'][text]">' +
                '<button type="button" class="fbf-btn fbf-btn--del" onclick="this.closest(\'.fbf-item\').remove()">Удалить</button>' +
                '</div>'
            );
        });

        $('#fbf-add-topic').on('click', function(){
            var box = $('#fbf-topics');
            var i = box.find('.fbf-item').length;
            box.append(
                '<div class="fbf-item">' +
                '<label>Value (латиница, без пробелов)</label>' +
                '<input type="text" name="feedback_form[topic_opts]['+i+'][value]">' +
                '<label>Текст option</label>' +
                '<input type="text" name="feedback_form[topic_opts]['+i+'][text]">' +
                '<button type="button" class="fbf-btn fbf-btn--del" onclick="this.closest(\'.fbf-item\').remove()">Удалить</button>' +
                '</div>'
            );
        });
    });
    </script>
    <?php
}

add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['feedback_form_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['feedback_form_nonce_field'], 'feedback_form_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['feedback_form'] ?? [];

    $contacts = [];
    if ( ! empty( $raw['contacts'] ) && is_array( $raw['contacts'] ) ) {
        foreach ( $raw['contacts'] as $c ) {
            $href = esc_url_raw( $c['href'] ?? '' );
            $text = sanitize_text_field( $c['text'] ?? '' );
            if ( $href || $text ) {
                $contacts[] = [ 'href' => $href, 'text' => $text ];
            }
        }
    }

    $topic_opts = [];
    if ( ! empty( $raw['topic_opts'] ) && is_array( $raw['topic_opts'] ) ) {
        foreach ( $raw['topic_opts'] as $o ) {
            $val = sanitize_text_field( $o['value'] ?? '' );
            $txt = sanitize_text_field( $o['text'] ?? '' );
            if ( $val || $txt ) {
                $topic_opts[] = [ 'value' => $val, 'text' => $txt ];
            }
        }
    }

    $data = [
        'label'       => sanitize_text_field( $raw['label'] ?? '' ),
        'title'       => wp_kses_post( $raw['title'] ?? '' ),
        'desc'        => sanitize_textarea_field( $raw['desc'] ?? '' ),
        'contacts'    => $contacts,
        'name_label'  => sanitize_text_field( $raw['name_label'] ?? '' ),
        'name_ph'     => sanitize_text_field( $raw['name_ph'] ?? '' ),
        'phone_label' => sanitize_text_field( $raw['phone_label'] ?? '' ),
        'phone_ph'    => sanitize_text_field( $raw['phone_ph'] ?? '' ),
        'email_label' => sanitize_text_field( $raw['email_label'] ?? '' ),
        'email_opt'   => sanitize_text_field( $raw['email_opt'] ?? '' ),
        'email_ph'    => sanitize_text_field( $raw['email_ph'] ?? '' ),
        'topic_label' => sanitize_text_field( $raw['topic_label'] ?? '' ),
        'topic_opts'  => $topic_opts,
        'msg_label'   => sanitize_text_field( $raw['msg_label'] ?? '' ),
        'msg_ph'      => sanitize_text_field( $raw['msg_ph'] ?? '' ),
        'submit_text' => sanitize_text_field( $raw['submit_text'] ?? '' ),
        'priv_text'   => sanitize_text_field( $raw['priv_text'] ?? '' ),
        'priv_link'   => sanitize_text_field( $raw['priv_link'] ?? '' ),
        'priv_url'    => esc_url_raw( $raw['priv_url'] ?? '' ),
        'ok_title'    => sanitize_text_field( $raw['ok_title'] ?? '' ),
        'ok_text'     => sanitize_text_field( $raw['ok_text'] ?? '' ),
        'section_id'  => sanitize_text_field( $raw['section_id'] ?? '' ),
    ];

    update_post_meta( $post_id, 'feedback_form', $data );
} );