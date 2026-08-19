<?php
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'reviews_list_section', 'Секция: Список отзывов', 'reviews_list_section_box', 'page', 'normal', 'high' );
} );

function reviews_list_section_box( $post ) {
    wp_nonce_field( 'reviews_list_nonce', 'reviews_list_nonce_field' );
    $v = get_post_meta( $post->ID, 'reviews_list', true );

    $eyebrow    = $v['eyebrow'] ?? '';
    $title      = $v['title'] ?? '';
    $section_id = $v['section_id'] ?? '';
    $filters    = $v['filters'] ?? [];
    $reviews    = $v['reviews'] ?? [];
    ?>
    <style>
        .rl-field{margin-bottom:10px}
        .rl-field label{display:block;font-weight:600;margin-bottom:3px;font-size:13px}
        .rl-field input[type="text"],.rl-field textarea,.rl-field input[type="number"],.rl-field select{width:100%;max-width:600px}
        .rl-group{border:1px solid #c0c0c0;margin:14px 0;padding:14px;background:#fafafa;border-radius:4px}
        .rl-group>h4{margin-top:0}
        .rl-item{border:1px solid #ddd;margin:10px 0;padding:12px;background:#fff;border-radius:4px;position:relative}
        .rl-item label{display:block;font-weight:600;margin-bottom:3px;font-size:12px}
        .rl-item input,.rl-item textarea,.rl-item select{width:100%}
        .rl-item .rl-row{display:flex;gap:12px;margin-bottom:8px}
        .rl-item .rl-row>div{flex:1}
        .rl-item .rl-row label{margin-bottom:2px}
        .rl-btn{padding:4px 12px;cursor:pointer;margin:3px 2px}
        .rl-btn--del{color:#a00;border-color:#a00}
        .rl-stars{display:flex;gap:4px;align-items:center;margin:6px 0}
        .rl-stars label{margin:0;font-weight:normal}
        .rl-stars input{width:60px;max-width:60px}
    </style>

    <div class="rl-field">
        <label>Надсекция (eyebrow)</label>
        <input type="text" name="reviews_list[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>">
    </div>
    <div class="rl-field">
        <label>Заголовок (HTML: &lt;span class="gold-header"&gt;&lt;em&gt;...&lt;/em&gt;&lt;/span&gt;)</label>
        <textarea name="reviews_list[title]" rows="2"><?php echo esc_textarea( $title ); ?></textarea>
    </div>
    <div class="rl-field">
        <label>Section ID</label>
        <input type="text" name="reviews_list[section_id]" value="<?php echo esc_attr( $section_id ); ?>">
    </div>

    <!-- ФИЛЬТРЫ -->
    <h3>Фильтры (кнопки категорий)</h3>
    <p style="font-size:12px;color:#666">Первый фильтр обычно с slug <code>all</code> и галочкой «Активный»</p>
    <div id="rl-filters">
        <?php if ( ! empty( $filters ) ) : ?>
            <?php foreach ( $filters as $fi => $f ) : ?>
                <div class="rl-item">
                    <div class="rl-row">
                        <div>
                            <label>Slug (латиница, data-filter)</label>
                            <input type="text" name="reviews_list[filters][<?php echo $fi; ?>][slug]" value="<?php echo esc_attr( $f['slug'] ?? '' ); ?>">
                        </div>
                        <div>
                            <label>Текст кнопки</label>
                            <input type="text" name="reviews_list[filters][<?php echo $fi; ?>][text]" value="<?php echo esc_attr( $f['text'] ?? '' ); ?>">
                        </div>
                        <div style="display:flex;align-items:flex-end;padding-bottom:2px">
                            <label style="display:flex;align-items:center;gap:4px;font-weight:normal">
                                <input type="checkbox" name="reviews_list[filters][<?php echo $fi; ?>][is_active]" value="1" <?php checked( $f['is_active'] ?? 0, 1 ); ?>> Активный
                            </label>
                        </div>
                    </div>
                    <button type="button" class="rl-btn rl-btn--del" onclick="this.closest('.rl-item').remove()">Удалить фильтр</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button type="button" class="rl-btn" id="rl-add-filter">+ Добавить фильтр</button>

    <!-- ОТЗЫВЫ -->
    <h3 style="margin-top:24px">Отзывы</h3>
    <div id="rl-reviews">
        <?php if ( ! empty( $reviews ) ) : ?>
            <?php foreach ( $reviews as $ri => $r ) : ?>
                <div class="rl-item">
                    <div class="rl-row">
                        <div>
                            <label>Категория (slug из фильтров, без "all")</label>
                            <input type="text" name="reviews_list[reviews][<?php echo $ri; ?>][category]" value="<?php echo esc_attr( $r['category'] ?? '' ); ?>">
                        </div>
                        <div>
                            <label>Тег (текст в карточке)</label>
                            <input type="text" name="reviews_list[reviews][<?php echo $ri; ?>][tag]" value="<?php echo esc_attr( $r['tag'] ?? '' ); ?>">
                        </div>
                    </div>
                    <div class="rl-stars">
                        <label>Звёзды:</label>
                        <input type="number" name="reviews_list[reviews][<?php echo $ri; ?>][star_count]" min="1" max="5" value="<?php echo esc_attr( $r['star_count'] ?? 5 ); ?>">
                        <label style="margin-left:12px;font-size:12px">
                            <input type="checkbox" name="reviews_list[reviews][<?php echo $ri; ?>][has_partial]" value="1" <?php checked( $r['has_partial'] ?? 0, 1 ); ?>> Частичная последняя
                        </label>
                        <input type="text" name="reviews_list[reviews][<?php echo $ri; ?>][partial_fill]" value="<?php echo esc_attr( $r['partial_fill'] ?? '80%' ); ?>" style="width:60px;max-width:60px" placeholder="80%">
                    </div>
                    <div class="rl-field">
                        <label>Текст отзыва</label>
                        <textarea name="reviews_list[reviews][<?php echo $ri; ?>][text]" rows="4"><?php echo esc_textarea( $r['text'] ?? '' ); ?></textarea>
                    </div>
                    <div class="rl-row">
                        <div>
                            <label>Инициалы (аватар). Оставьте пустым — сгенерируются из имени</label>
                            <input type="text" name="reviews_list[reviews][<?php echo $ri; ?>][avatar]" value="<?php echo esc_attr( $r['avatar'] ?? '' ); ?>">
                        </div>
                        <div>
                            <label>Имя автора</label>
                            <input type="text" name="reviews_list[reviews][<?php echo $ri; ?>][name]" value="<?php echo esc_attr( $r['name'] ?? '' ); ?>">
                        </div>
                    </div>
                    <div class="rl-row">
                        <div>
                            <label>Дата</label>
                            <input type="text" name="reviews_list[reviews][<?php echo $ri; ?>][date]" value="<?php echo esc_attr( $r['date'] ?? '' ); ?>" placeholder="Июль 2025">
                        </div>
                        <div>
                            <label>Источник</label>
                            <input type="text" name="reviews_list[reviews][<?php echo $ri; ?>][source]" value="<?php echo esc_attr( $r['source'] ?? '' ); ?>" placeholder="Яндекс Карты">
                        </div>
                    </div>
                    <button type="button" class="rl-btn rl-btn--del" onclick="this.closest('.rl-item').remove()">Удалить отзыв</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button type="button" class="rl-btn" id="rl-add-review">+ Добавить отзыв</button>

    <script>
    jQuery(function($){

        /* Фильтры */
        $('#rl-add-filter').on('click', function(){
            var box = $('#rl-filters');
            var i = box.find('.rl-item').length;
            box.append(
                '<div class="rl-item">' +
                '<div class="rl-row">' +
                '<div><label>Slug (латиница, data-filter)</label><input type="text" name="reviews_list[filters]['+i+'][slug]"></div>' +
                '<div><label>Текст кнопки</label><input type="text" name="reviews_list[filters]['+i+'][text]"></div>' +
                '<div style="display:flex;align-items:flex-end;padding-bottom:2px"><label style="display:flex;align-items:center;gap:4px;font-weight:normal"><input type="checkbox" name="reviews_list[filters]['+i+'][is_active]" value="1"> Активный</label></div>' +
                '</div>' +
                '<button type="button" class="rl-btn rl-btn--del" onclick="this.closest(\'.rl-item\').remove()">Удалить фильтр</button>' +
                '</div>'
            );
        });

        /* Отзывы */
        $('#rl-add-review').on('click', function(){
            var box = $('#rl-reviews');
            var i = box.find('.rl-item').length;
            box.append(
                '<div class="rl-item">' +
                '<div class="rl-row">' +
                '<div><label>Категория (slug из фильтров, без "all")</label><input type="text" name="reviews_list[reviews]['+i+'][category]"></div>' +
                '<div><label>Тег (текст в карточке)</label><input type="text" name="reviews_list[reviews]['+i+'][tag]"></div>' +
                '</div>' +
                '<div class="rl-stars">' +
                '<label>Звёзды:</label>' +
                '<input type="number" name="reviews_list[reviews]['+i+'][star_count]" min="1" max="5" value="5">' +
                '<label style="margin-left:12px;font-size:12px"><input type="checkbox" name="reviews_list[reviews]['+i+'][has_partial]" value="1"> Частичная последняя</label>' +
                '<input type="text" name="reviews_list[reviews]['+i+'][partial_fill]" value="80%" style="width:60px;max-width:60px" placeholder="80%">' +
                '</div>' +
                '<div class="rl-field"><label>Текст отзыва</label><textarea name="reviews_list[reviews]['+i+'][text]" rows="4"></textarea></div>' +
                '<div class="rl-row">' +
                '<div><label>Инициалы (аватар). Оставьте пустым — сгенерируются из имени</label><input type="text" name="reviews_list[reviews]['+i+'][avatar]"></div>' +
                '<div><label>Имя автора</label><input type="text" name="reviews_list[reviews]['+i+'][name]"></div>' +
                '</div>' +
                '<div class="rl-row">' +
                '<div><label>Дата</label><input type="text" name="reviews_list[reviews]['+i+'][date]" placeholder="Июль 2025"></div>' +
                '<div><label>Источник</label><input type="text" name="reviews_list[reviews]['+i+'][source]" placeholder="Яндекс Карты"></div>' +
                '</div>' +
                '<button type="button" class="rl-btn rl-btn--del" onclick="this.closest(\'.rl-item\').remove()">Удалить отзыв</button>' +
                '</div>'
            );
        });

    });
    </script>
    <?php
}

add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['reviews_list_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['reviews_list_nonce_field'], 'reviews_list_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['reviews_list'] ?? [];

    $filters = [];
    if ( ! empty( $raw['filters'] ) && is_array( $raw['filters'] ) ) {
        foreach ( $raw['filters'] as $f ) {
            $slug = sanitize_text_field( $f['slug'] ?? '' );
            $text = sanitize_text_field( $f['text'] ?? '' );
            if ( $slug || $text ) {
                $filters[] = [
                    'slug'     => $slug,
                    'text'     => $text,
                    'is_active' => ! empty( $f['is_active'] ) ? 1 : 0,
                ];
            }
        }
    }

    $reviews = [];
    if ( ! empty( $raw['reviews'] ) && is_array( $raw['reviews'] ) ) {
        foreach ( $raw['reviews'] as $r ) {
            $text = sanitize_textarea_field( $r['text'] ?? '' );
            $name = sanitize_text_field( $r['name'] ?? '' );
            if ( $text || $name ) {
                $star_count = intval( $r['star_count'] ?? 5 );
                if ( $star_count < 1 ) $star_count = 1;
                if ( $star_count > 5 ) $star_count = 5;

                $reviews[] = [
                    'category'     => sanitize_text_field( $r['category'] ?? '' ),
                    'tag'          => sanitize_text_field( $r['tag'] ?? '' ),
                    'star_count'   => $star_count,
                    'has_partial'  => ! empty( $r['has_partial'] ) ? 1 : 0,
                    'partial_fill' => sanitize_text_field( $r['partial_fill'] ?? '80%' ),
                    'text'         => $text,
                    'avatar'       => sanitize_text_field( $r['avatar'] ?? '' ),
                    'name'         => $name,
                    'date'         => sanitize_text_field( $r['date'] ?? '' ),
                    'source'       => sanitize_text_field( $r['source'] ?? '' ),
                ];
            }
        }
    }

    $data = [
        'eyebrow'    => sanitize_text_field( $raw['eyebrow'] ?? '' ),
        'title'      => wp_kses_post( $raw['title'] ?? '' ),
        'section_id' => sanitize_text_field( $raw['section_id'] ?? '' ),
        'filters'    => $filters,
        'reviews'    => $reviews,
    ];

    update_post_meta( $post_id, 'reviews_list', $data );
} );