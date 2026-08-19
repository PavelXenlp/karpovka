<?php
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'spa_interactive_section', 'Секция: СПА-центр (интерактивные табы)', 'spa_interactive_section_box', 'page', 'normal', 'high' );
} );

function spa_interactive_section_box( $post ) {
    wp_nonce_field( 'spa_interactive_nonce', 'spa_interactive_nonce_field' );
    $v = get_post_meta( $post->ID, 'spa_interactive', true );

    $eyebrow    = $v['eyebrow'] ?? '';
    $section_id = $v['section_id'] ?? '';
    $slides     = $v['slides'] ?? [];
    ?>
    <style>
        .si-field{margin-bottom:10px}
        .si-field label{display:block;font-weight:600;margin-bottom:3px;font-size:13px}
        .si-field input[type="text"],.si-field textarea{width:100%;max-width:700px}
        .si-item{border:1px solid #ddd;margin:12px 0;padding:14px;background:#fff;border-radius:4px}
        .si-item label{display:block;font-weight:600;margin-bottom:3px;font-size:12px}
        .si-item input,.si-item textarea{width:100%}
        .si-item .si-row{display:flex;gap:12px;margin-bottom:8px}
        .si-item .si-row>div{flex:1}
        .si-item .si-row label{margin-bottom:2px}
        .si-btn{padding:4px 12px;cursor:pointer;margin:3px 2px}
        .si-btn--del{color:#a00;border-color:#a00}
        .si-upload{display:flex;align-items:center;gap:8px;margin-bottom:8px}
        .si-upload input[type="text"]{flex:1}
        .si-upload button{padding:3px 8px;cursor:pointer;font-size:12px}
        .si-upload img.prev{max-width:100px;height:auto;border:1px solid #ccc;border-radius:3px;display:none}
    </style>

    <div class="si-field">
        <label>Eyebrow</label>
        <input type="text" name="spa_interactive[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>">
    </div>
    <div class="si-field">
        <label>Section ID</label>
        <input type="text" name="spa_interactive[section_id]" value="<?php echo esc_attr( $section_id ); ?>">
    </div>

    <h3>Слайды / Табы</h3>
    <div id="si-slides">
        <?php if ( ! empty( $slides ) ) : ?>
            <?php foreach ( $slides as $si => $s ) : ?>
                <div class="si-item">
                    <div class="si-row">
                        <div style="flex:0 0 200px"><label>Текст таба (коротко)</label><input type="text" name="spa_interactive[slides][<?php echo $si; ?>][tab]" value="<?php echo esc_attr( $s['tab'] ?? '' ); ?>"></div>
                        <div><label>Заголовок (H2)</label><input type="text" name="spa_interactive[slides][<?php echo $si; ?>][title]" value="<?php echo esc_attr( $s['title'] ?? '' ); ?>"></div>
                    </div>
                    <div class="si-field">
                        <label>Описание</label>
                        <textarea name="spa_interactive[slides][<?php echo $si; ?>][desc]" rows="3"><?php echo esc_textarea( $s['desc'] ?? '' ); ?></textarea>
                    </div>
                    <div class="si-field">
                        <label>Детали (HTML: списки, абзацы)</label>
                        <textarea name="spa_interactive[slides][<?php echo $si; ?>][details]" rows="5"><?php echo esc_textarea( $s['details'] ?? '' ); ?></textarea>
                    </div>
                    <div class="si-upload">
                        <button type="button" class="si-btn si-upload-slide">📁 Фото</button>
                        <input type="text" name="spa_interactive[slides][<?php echo $si; ?>][img]" value="<?php echo esc_attr( $s['img'] ?? '' ); ?>">
                        <img class="prev" src="<?php echo esc_url( $s['img'] ?? '' ); ?>" alt="">
                    </div>
                    <button type="button" class="si-btn si-btn--del" onclick="this.closest('.si-item').remove()">Удалить слайд</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button type="button" class="si-btn" id="si-add-slide">+ Добавить слайд</button>

    <script>
    jQuery(function($){

        /* Загрузка картинок */
        $(document).on('click','.si-upload-slide',function(e){
            e.preventDefault();
            var btn = this;
            var frame = wp.media({title:'Выберите изображение',multiple:false,library:{type:'image'}});
            frame.on('select',function(){
                var url = frame.state().get('selection').first().toJSON().url;
                $(btn).closest('.si-upload').find('input[type="text"]').val(url);
                $(btn).closest('.si-upload').find('img.prev').attr('src',url).show();
            });
            frame.open();
        });

        $('.si-upload img.prev').each(function(){
            if($(this).attr('src')) $(this).show();
        });

        /* Добавить слайд */
        $('#si-add-slide').on('click',function(){
            var box=$('#si-slides'), i=box.find('.si-item').length;
            box.append(
                '<div class="si-item">' +
                '<div class="si-row"><div style="flex:0 0 200px"><label>Текст таба</label><input type="text" name="spa_interactive[slides]['+i+'][tab]"></div><div><label>Заголовок (H2)</label><input type="text" name="spa_interactive[slides]['+i+'][title]"></div></div>' +
                '<div class="si-field"><label>Описание</label><textarea name="spa_interactive[slides]['+i+'][desc]" rows="3"></textarea></div>' +
                '<div class="si-field"><label>Детали (HTML)</label><textarea name="spa_interactive[slides]['+i+'][details]" rows="5"></textarea></div>' +
                '<div class="si-upload"><button type="button" class="si-btn si-upload-slide">\uD83D\uDCC1 \u0424\u043E\u0442\u043E</button><input type="text" name="spa_interactive[slides]['+i+'][img]"><img class="prev" src="" alt=""></div>' +
                '<button type="button" class="si-btn si-btn--del" onclick="this.closest(\'.si-item\').remove()">\u0423\u0434\u0430\u043B\u0438\u0442\u044C \u0441\u043B\u0430\u0439\u0434</button>' +
                '</div>'
            );
        });

    });
    </script>
    <?php
}

add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['spa_interactive_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['spa_interactive_nonce_field'], 'spa_interactive_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['spa_interactive'] ?? [];

    $slides = [];
    if ( ! empty( $raw['slides'] ) && is_array( $raw['slides'] ) ) {
        foreach ( $raw['slides'] as $s ) {
            $tab = sanitize_text_field( $s['tab'] ?? '' );
            if ( $tab ) {
                $slides[] = [
                    'tab'     => $tab,
                    'title'   => sanitize_text_field( $s['title'] ?? '' ),
                    'desc'    => sanitize_textarea_field( $s['desc'] ?? '' ),
                    'details' => wp_kses_post( $s['details'] ?? '' ),
                    'img'     => esc_url_raw( $s['img'] ?? '' ),
                ];
            }
        }
    }

    $data = [
        'eyebrow'    => sanitize_text_field( $raw['eyebrow'] ?? '' ),
        'section_id' => sanitize_text_field( $raw['section_id'] ?? '' ),
        'slides'     => $slides,
    ];

    update_post_meta( $post_id, 'spa_interactive', $data );
} );