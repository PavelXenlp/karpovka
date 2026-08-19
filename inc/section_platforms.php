<?php
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'platforms_section', 'Секция: Отзывы на площадках', 'platforms_section_box', 'page', 'normal', 'high' );
} );

function platforms_section_box( $post ) {
    wp_nonce_field( 'platforms_section_nonce', 'platforms_section_nonce_field' );
    $v = get_post_meta( $post->ID, 'platforms_section', true );

    $eyebrow    = $v['eyebrow'] ?? '';
    $title      = $v['title'] ?? '';
    $section_id = $v['section_id'] ?? '';
    $cards      = $v['cards'] ?? [];
    ?>
    <style>
        .pl-field{margin-bottom:10px}
        .pl-field label{display:block;font-weight:600;margin-bottom:3px;font-size:13px}
        .pl-field input[type="text"]{width:100%;max-width:600px}
        .pl-item{border:1px solid #ddd;margin:10px 0;padding:12px;background:#fff;border-radius:4px}
        .pl-item label{display:block;font-weight:600;margin-bottom:3px;font-size:12px}
        .pl-item input{width:100%}
        .pl-item .pl-row{display:flex;gap:12px;margin-bottom:8px}
        .pl-item .pl-row>div{flex:1}
        .pl-item .pl-row label{margin-bottom:2px}
        .pl-btn{padding:4px 12px;cursor:pointer;margin:3px 2px}
        .pl-btn--del{color:#a00;border-color:#a00}
    </style>

    <div class="pl-field">
        <label>Надсекция (eyebrow)</label>
        <input type="text" name="platforms_section[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>">
    </div>
    <div class="pl-field">
        <label>Заголовок (HTML: &lt;span class="gold-header"&gt;&lt;em&gt;...&lt;/em&gt;&lt;/span&gt;)</label>
        <textarea name="platforms_section[title]" rows="2"><?php echo esc_textarea( $title ); ?></textarea>
    </div>
    <div class="pl-field">
        <label>Section ID</label>
        <input type="text" name="platforms_section[section_id]" value="<?php echo esc_attr( $section_id ); ?>">
    </div>

    <h3>Карточки площадок</h3>
    <div id="pl-cards">
        <?php if ( ! empty( $cards ) ) : ?>
            <?php foreach ( $cards as $ci => $c ) : ?>
                <div class="pl-item">
                    <div class="pl-row">
                        <div>
                            <label>Название (Яндекс Карты, ВКонтакте...)</label>
                            <input type="text" name="platforms_section[cards][<?php echo $ci; ?>][name]" value="<?php echo esc_attr( $c['name'] ?? '' ); ?>">
                        </div>
                        <div>
                            <label>Рейтинг (например: 4.8)</label>
                            <input type="text" name="platforms_section[cards][<?php echo $ci; ?>][rating]" value="<?php echo esc_attr( $c['rating'] ?? '' ); ?>">
                        </div>
                    </div>
                    <div class="pl-row">
                        <div>
                            <label>Количество (например: 156 отзывов)</label>
                            <input type="text" name="platforms_section[cards][<?php echo $ci; ?>][count]" value="<?php echo esc_attr( $c['count'] ?? '' ); ?>">
                        </div>
                        <div>
                            <label>Ссылка</label>
                            <input type="text" name="platforms_section[cards][<?php echo $ci; ?>][link]" value="<?php echo esc_attr( $c['link'] ?? '' ); ?>">
                        </div>
                    </div>
                    <div class="pl-row">
                        <div>
                            <label>Иконка — FA класс (например: fab fa-yandex)</label>
                            <input type="text" name="platforms_section[cards][<?php echo $ci; ?>][icon]" value="<?php echo esc_attr( $c['icon'] ?? '' ); ?>">
                        </div>
                        <div>
                            <label>Цвет иконки (например: #FC3F1D)</label>
                            <input type="text" name="platforms_section[cards][<?php echo $ci; ?>][icon_color]" value="<?php echo esc_attr( $c['icon_color'] ?? '' ); ?>">
                        </div>
                    </div>
                    <button type="button" class="pl-btn pl-btn--del" onclick="this.closest('.pl-item').remove()">Удалить</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button type="button" class="pl-btn" id="pl-add-card">+ Добавить площадку</button>

    <script>
    jQuery(function($){
        $('#pl-add-card').on('click', function(){
            var box = $('#pl-cards');
            var i = box.find('.pl-item').length;
            box.append(
                '<div class="pl-item">' +
                '<div class="pl-row">' +
                '<div><label>Название</label><input type="text" name="platforms_section[cards]['+i+'][name]"></div>' +
                '<div><label>Рейтинг (например: 4.8)</label><input type="text" name="platforms_section[cards]['+i+'][rating]"></div>' +
                '</div>' +
                '<div class="pl-row">' +
                '<div><label>Количество (например: 156 отзывов)</label><input type="text" name="platforms_section[cards]['+i+'][count]"></div>' +
                '<div><label>Ссылка</label><input type="text" name="platforms_section[cards]['+i+'][link]"></div>' +
                '</div>' +
                '<div class="pl-row">' +
                '<div><label>Иконка — FA класс (например: fab fa-yandex)</label><input type="text" name="platforms_section[cards]['+i+'][icon]"></div>' +
                '<div><label>Цвет иконки (например: #FC3F1D)</label><input type="text" name="platforms_section[cards]['+i+'][icon_color]"></div>' +
                '</div>' +
                '<button type="button" class="pl-btn pl-btn--del" onclick="this.closest(\'.pl-item\').remove()">Удалить</button>' +
                '</div>'
            );
        });
    });
    </script>
    <?php
}

add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['platforms_section_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['platforms_section_nonce_field'], 'platforms_section_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['platforms_section'] ?? [];

    $cards = [];
    if ( ! empty( $raw['cards'] ) && is_array( $raw['cards'] ) ) {
        foreach ( $raw['cards'] as $c ) {
            $name = sanitize_text_field( $c['name'] ?? '' );
            $link = esc_url_raw( $c['link'] ?? '' );
            if ( $name || $link ) {
                $cards[] = [
                    'name'       => $name,
                    'rating'     => sanitize_text_field( $c['rating'] ?? '' ),
                    'count'      => sanitize_text_field( $c['count'] ?? '' ),
                    'link'       => $link,
                    'icon'       => sanitize_text_field( $c['icon'] ?? '' ),
                    'icon_color' => sanitize_text_field( $c['icon_color'] ?? '' ),
                ];
            }
        }
    }

    $data = [
        'eyebrow'    => sanitize_text_field( $raw['eyebrow'] ?? '' ),
        'title'      => wp_kses_post( $raw['title'] ?? '' ),
        'section_id' => sanitize_text_field( $raw['section_id'] ?? '' ),
        'cards'      => $cards,
    ];

    update_post_meta( $post_id, 'platforms_section', $data );
} );