<?php
// Meta box: Stories (слайдер форматов отдыха)
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'stories_section', 'Stories — форматы отдыха (слайдер)', 'stories_section_cb', 'page', 'normal', 'high' );
} );

function stories_section_cb( $post ) {
    wp_nonce_field( 'stories_section_nonce', 'stories_section_nonce_field' );
    $v = get_post_meta( $post->ID, 'stories', true );

    $title = $v['title'] ?? '';
    $slides = $v['slides'] ?? [];
    ?>

    <p>
        <label><b>Заголовок</b></label><br>
        <input type="text" name="stories[title]" value="<?php echo esc_attr( $title ); ?>" style="width:100%;max-width:600px;">
    </p>

    <hr>
    <h4>Слайды</h4>
    <div id="stories-slides-wrap">
        <?php if ( ! empty( $slides ) ) : foreach ( $slides as $i => $slide ) : ?>
        <div class="story-slide-block" style="border:1px solid #ccc;padding:12px;margin-bottom:12px;background:#fafafa;">
            <p>
                <label><b>Изображение</b></label><br>
                <input type="hidden" name="stories[slides][<?php echo $i; ?>][img]" value="<?php echo esc_attr( $slide['img'] ?? '' ); ?>" data-kp-media>
                <input type="text" name="stories[slides][<?php echo $i; ?>][img_alt]" value="<?php echo esc_attr( $slide['img_alt'] ?? '' ); ?>" placeholder="Alt текст" style="width:400px;margin-left:8px;">
            </p>
            <p>
                <label><b>Название</b></label><br>
                <input type="text" name="stories[slides][<?php echo $i; ?>][title]" value="<?php echo esc_attr( $slide['title'] ?? '' ); ?>" style="width:100%;max-width:400px;">
            </p>
            <p>
                <label><b>Ссылка на карточке</b> (href)</label><br>
                <input type="text" name="stories[slides][<?php echo $i; ?>][link]" value="<?php echo esc_attr( $slide['link'] ?? '' ); ?>" style="width:100%;max-width:400px;" placeholder="#accommodation">
            </p>
            <p>
                <label><b>Текст кнопки «Подробнее»</b></label><br>
                <input type="text" name="stories[slides][<?php echo $i; ?>][more_text]" value="<?php echo esc_attr( $slide['more_text'] ?? 'Подробнее' ); ?>" style="width:300px;">
            </p>
            <hr style="margin:10px 0;">
            <button type="button" class="button story-rm-slide" style="color:#a00;">Удалить слайд</button>
        </div>
        <?php endforeach; endif; ?>
    </div>
    <button type="button" class="button" id="story-add-slide">+ Добавить слайд</button>

    <script>
    (function($){
        var idx = $('#stories-slides-wrap .story-slide-block').length;

        $('#story-add-slide').on('click', function(){
            var h = '<div class="story-slide-block" style="border:1px solid #ccc;padding:12px;margin-bottom:12px;background:#fafafa;">'
                + '<p><label><b>Изображение</b></label><br>'
                + '<input type="hidden" name="stories[slides][' + idx + '][img]" value="" data-kp-media>'
                + '<input type="text" name="stories[slides][' + idx + '][img_alt]" placeholder="Alt текст" style="width:400px;margin-left:8px;">'
                + '</p>'
                + '<p><label><b>Название</b></label><br>'
                + '<input type="text" name="stories[slides][' + idx + '][title]" style="width:100%;max-width:400px;"></p>'
                + '<p><label><b>Ссылка на карточке</b></label><br>'
                + '<input type="text" name="stories[slides][' + idx + '][link]" style="width:100%;max-width:400px;" placeholder="#accommodation"></p>'
                + '<p><label><b>Текст кнопки «Подробнее»</b></label><br>'
                + '<input type="text" name="stories[slides][' + idx + '][more_text]" value="Подробнее" style="width:300px;"></p>'
                + '<hr style="margin:10px 0;">'
                + '<button type="button" class="button story-rm-slide" style="color:#a00;">Удалить слайд</button>'
                + '</div>';
            $('#stories-slides-wrap').append(h);
            idx++;
        });

        $(document).on('click', '.story-rm-slide', function(){
            if( confirm('Удалить слайд?') ) $(this).closest('.story-slide-block').remove();
        });

    })(jQuery);
    </script>
    <?php
}

// Сохранение
add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['stories_section_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['stories_section_nonce_field'], 'stories_section_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['stories'] ?? [];

    $slides = [];
    if ( ! empty( $raw['slides'] ) && is_array( $raw['slides'] ) ) {
        foreach ( $raw['slides'] as $slide ) {
            $t = sanitize_text_field( $slide['title'] ?? '' );
            if ( $t === '' ) continue;
            $slides[] = [
                'img'       => esc_url_raw( $slide['img'] ?? '' ),
                'img_alt'   => sanitize_text_field( $slide['img_alt'] ?? '' ),
                'title'     => $t,
                'link'      => esc_url_raw( $slide['link'] ?? '#' ),
                'more_text' => sanitize_text_field( $slide['more_text'] ?? 'Подробнее' ),
            ];
        }
    }

    $data = [
        'title'  => sanitize_text_field( $raw['title'] ?? '' ),
        'slides' => $slides,
    ];

    update_post_meta( $post_id, 'stories', $data );
} );
