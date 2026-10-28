<?php

add_action( 'init', function () {
    register_post_type( 'karpovka_house', [
        'labels' => [
            'name'          => 'Дома',
            'singular_name' => 'Дом',
            'add_new_item'  => 'Добавить дом',
            'edit_item'     => 'Редактировать дом',
            'menu_name'     => 'Дома',
        ],
        'public'             => true,
        'publicly_queryable' => true,
        'exclude_from_search'=> false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => false,
        'menu_icon'          => 'dashicons-admin-home',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'page-attributes' ],
        'has_archive'        => false,
        'rewrite'            => [ 'slug'=>'dom', 'with_front'=>false ],
        'capability_type'    => 'post',
        'map_meta_cap'       => true,
    ] );
} );

function karpovka_house_card_defaults() {
    return [
        'type'              => 'eco',
        'audience'          => [],
        'img'               => '',
        'img_alt'           => '',
        'gallery'           => [],
        'source_url'        => '',
        'badge'             => '',
        'meta'              => '',
        'features'          => [],
        'price_prefix'      => 'от',
        'price'             => '',
        'price_suffix'      => '/ ночь',
        'more_text'         => 'Подробнее',
        'more_link'         => '',
        'book_text'         => 'Забронировать',
        'book_link'         => '#booking',
        'details_intro'     => '',
        'details_items'     => [],
        'details_pricenote' => '',
    ];
}

function karpovka_get_house_card( $post_id ) {
    $card = get_post_meta( $post_id, '_karpovka_house_card', true );
    $card = is_array( $card ) ? $card : [];
    $card = array_merge( karpovka_house_card_defaults(), $card );
    $card['title'] = get_the_title( $post_id );
    if ( empty( $card['more_link'] ) && 'karpovka_house' === get_post_type( $post_id ) ) {
        $card['more_link'] = get_permalink( $post_id );
    }
    return $card;
}

add_action( 'add_meta_boxes_karpovka_house', function () {
    add_meta_box( 'karpovka_house_card', 'Карточка дома', 'karpovka_house_card_box', 'karpovka_house', 'normal', 'high' );
} );

function karpovka_house_card_box( $post ) {
    wp_nonce_field( 'karpovka_house_card_save', 'karpovka_house_card_nonce' );
    $card = karpovka_get_house_card( $post->ID );
    $audience = is_array( $card['audience'] ) ? $card['audience'] : [];
    $features = implode( "\n", array_filter( array_map( static function ( $item ) {
        return $item['text'] ?? '';
    }, $card['features'] ) ) );
    $details = implode( "\n", array_filter( array_map( static function ( $item ) {
        return $item['text'] ?? '';
    }, $card['details_items'] ) ) );
    ?>
    <div class="kp-house-fields" data-karpovka-builder>
        <div class="kp-house-grid">
            <p><label><b>Формат</b></label><select name="karpovka_house_card[type]">
                <option value="eco" <?php selected( $card['type'], 'eco' ); ?>>Эко-дом</option>
                <option value="hotel" <?php selected( $card['type'], 'hotel' ); ?>>Гостиница</option>
                <option value="guest" <?php selected( $card['type'], 'guest' ); ?>>Гостевой дом</option>
            </select></p>
            <p><label><b>Бейдж</b></label><input type="text" name="karpovka_house_card[badge]" value="<?php echo esc_attr( $card['badge'] ); ?>"></p>
        </div>
        <p><label><b>Для кого</b></label>
            <label><input type="checkbox" name="karpovka_house_card[audience][]" value="family" <?php checked( in_array( 'family', $audience, true ) ); ?>> Семья</label>
            <label><input type="checkbox" name="karpovka_house_card[audience][]" value="couple" <?php checked( in_array( 'couple', $audience, true ) ); ?>> Пара</label>
            <label><input type="checkbox" name="karpovka_house_card[audience][]" value="company" <?php checked( in_array( 'company', $audience, true ) ); ?>> Компания</label>
        </p>
        <p><label><b>Изображение</b></label><input type="hidden" name="karpovka_house_card[img]" value="<?php echo esc_attr( $card['img'] ); ?>" data-kp-media></p>
        <p><label><b>Alt изображения</b></label><input type="text" name="karpovka_house_card[img_alt]" value="<?php echo esc_attr( $card['img_alt'] ); ?>"></p>
        <div class="kp-house-gallery">
            <label><b>Галерея</b></label>
            <div data-house-gallery>
                <?php foreach ( (array) $card['gallery'] as $gallery_index => $gallery_url ) : ?>
                    <div class="kp-house-gallery__item"><input type="hidden" name="karpovka_house_card[gallery][<?php echo (int) $gallery_index; ?>]" value="<?php echo esc_attr( $gallery_url ); ?>" data-kp-media><button type="button" class="button-link-delete" data-remove-house-image>Удалить изображение</button></div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button" data-add-house-image>Добавить изображение</button>
        </div>
        <p><label><b>Краткие характеристики</b></label><input type="text" name="karpovka_house_card[meta]" value="<?php echo esc_attr( $card['meta'] ); ?>" placeholder="До 6 гостей · 2 спальни · 2 санузла"></p>
        <p><label><b>Преимущества</b></label><textarea name="karpovka_house_card[features]" rows="5" placeholder="Каждое преимущество с новой строки"><?php echo esc_textarea( $features ); ?></textarea></p>
        <div class="kp-house-grid kp-house-grid--price">
            <p><label><b>Префикс цены</b></label><input type="text" name="karpovka_house_card[price_prefix]" value="<?php echo esc_attr( $card['price_prefix'] ); ?>"></p>
            <p><label><b>Цена</b></label><input type="text" name="karpovka_house_card[price]" value="<?php echo esc_attr( $card['price'] ); ?>"></p>
            <p><label><b>Суффикс</b></label><input type="text" name="karpovka_house_card[price_suffix]" value="<?php echo esc_attr( $card['price_suffix'] ); ?>"></p>
        </div>
        <div class="kp-house-grid">
            <p><label><b>Текст «Подробнее»</b></label><input type="text" name="karpovka_house_card[more_text]" value="<?php echo esc_attr( $card['more_text'] ); ?>"></p>
            <p><label><b>Ссылка «Подробнее»</b></label><input type="text" name="karpovka_house_card[more_link]" value="<?php echo esc_attr( $card['more_link'] ); ?>"></p>
            <p><label><b>Текст бронирования</b></label><input type="text" name="karpovka_house_card[book_text]" value="<?php echo esc_attr( $card['book_text'] ); ?>"></p>
            <p><label><b>Ссылка бронирования</b></label><input type="text" name="karpovka_house_card[book_link]" value="<?php echo esc_attr( $card['book_link'] ); ?>"></p>
        </div>
        <details>
            <summary><b>Дополнительная информация карточки</b></summary>
            <p><label><b>Вступительный текст</b></label><textarea name="karpovka_house_card[details_intro]" rows="4"><?php echo esc_textarea( $card['details_intro'] ); ?></textarea></p>
            <p><label><b>Подробности</b></label><textarea name="karpovka_house_card[details_items]" rows="6" placeholder="Каждый пункт с новой строки"><?php echo esc_textarea( $details ); ?></textarea></p>
            <p><label><b>Примечание к цене</b></label><input type="text" name="karpovka_house_card[details_pricenote]" value="<?php echo esc_attr( $card['details_pricenote'] ); ?>"></p>
        </details>
        <?php if ( $card['source_url'] ) : ?><p class="description">Источник данных: <a href="<?php echo esc_url( $card['source_url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $card['source_url'] ); ?></a></p><?php endif; ?>
    </div>
    <style>
        .kp-house-fields label { display:block; margin-bottom:5px; }
        .kp-house-fields input[type="text"], .kp-house-fields select, .kp-house-fields textarea { width:100%; }
        .kp-house-fields p > label + label { display:inline-block; margin-right:18px; }
        .kp-house-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px; }
        .kp-house-grid--price { grid-template-columns:140px minmax(180px,1fr) 160px; }
        .kp-house-gallery { margin:16px 0; }.kp-house-gallery__item{padding:10px;border:1px solid #dcdcde;margin-bottom:10px}.kp-house-gallery__item>.button-link-delete{margin-top:6px}
        @media(max-width:782px){ .kp-house-grid,.kp-house-grid--price{grid-template-columns:1fr;} }
    </style>
    <script>
    jQuery(function($){
        var $gallery=$('[data-house-gallery]');
        $('[data-add-house-image]').on('click',function(){
            var index=Date.now();
            $gallery.append('<div class="kp-house-gallery__item"><input type="hidden" name="karpovka_house_card[gallery]['+index+']" value="" data-kp-media><button type="button" class="button-link-delete" data-remove-house-image>Удалить изображение</button></div>');
        });
        $gallery.on('click','[data-remove-house-image]',function(){ $(this).closest('.kp-house-gallery__item').remove(); });
    });
    </script>
    <?php
}

function karpovka_house_lines( $value, $allow_html = false ) {
    $lines = preg_split( '/\r\n|\r|\n/', (string) $value );
    $items = [];
    foreach ( $lines as $line ) {
        $line = trim( $line );
        if ( '' === $line ) continue;
        $items[] = [ 'text' => $allow_html ? wp_kses( $line, [ 'strong' => [], 'b' => [], 'em' => [], 'br' => [] ] ) : sanitize_text_field( $line ) ];
    }
    return $items;
}

add_action( 'save_post_karpovka_house', function ( $post_id ) {
    if ( ! isset( $_POST['karpovka_house_card_nonce'] ) || ! wp_verify_nonce( $_POST['karpovka_house_card_nonce'], 'karpovka_house_card_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['karpovka_house_card'] ?? [];
    $type = in_array( $raw['type'] ?? '', [ 'eco', 'hotel', 'guest' ], true ) ? $raw['type'] : 'eco';
    $audience = array_values( array_intersect( (array) ( $raw['audience'] ?? [] ), [ 'family', 'couple', 'company' ] ) );
    $card = [
        'type'              => $type,
        'audience'          => $audience,
        'img'               => esc_url_raw( $raw['img'] ?? '' ),
        'img_alt'           => sanitize_text_field( $raw['img_alt'] ?? '' ),
        'gallery'           => array_values( array_filter( array_map( 'esc_url_raw', (array) ( $raw['gallery'] ?? [] ) ) ) ),
        'source_url'        => esc_url_raw( $raw['source_url'] ?? get_post_meta( $post_id, '_karpovka_house_source_url', true ) ),
        'badge'             => sanitize_text_field( $raw['badge'] ?? '' ),
        'meta'              => sanitize_text_field( $raw['meta'] ?? '' ),
        'features'          => karpovka_house_lines( $raw['features'] ?? '' ),
        'price_prefix'      => sanitize_text_field( $raw['price_prefix'] ?? '' ),
        'price'             => sanitize_text_field( $raw['price'] ?? '' ),
        'price_suffix'      => sanitize_text_field( $raw['price_suffix'] ?? '' ),
        'more_text'         => sanitize_text_field( $raw['more_text'] ?? '' ),
        'more_link'         => esc_url_raw( $raw['more_link'] ?? '' ),
        'book_text'         => sanitize_text_field( $raw['book_text'] ?? '' ),
        'book_link'         => esc_url_raw( $raw['book_link'] ?? '' ),
        'details_intro'     => wp_kses_post( $raw['details_intro'] ?? '' ),
        'details_items'     => karpovka_house_lines( $raw['details_items'] ?? '', true ),
        'details_pricenote' => sanitize_text_field( $raw['details_pricenote'] ?? '' ),
    ];
    update_post_meta( $post_id, '_karpovka_house_card', $card );
} );

add_action( 'admin_enqueue_scripts', function ( $hook_suffix ) {
    if ( ! in_array( $hook_suffix, [ 'post.php', 'post-new.php' ], true ) ) return;
    $screen = get_current_screen();
    if ( ! $screen || 'karpovka_house' !== $screen->post_type ) return;

    $post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
    wp_enqueue_media( $post_id ? [ 'post' => $post_id ] : [] );
    wp_enqueue_style(
        'karpovka-page-builder',
        get_template_directory_uri() . '/assets/admin/page-builder.css',
        [],
        '1.3.0'
    );
    wp_enqueue_script(
        'karpovka-content-controls',
        get_template_directory_uri() . '/assets/admin/content-controls.js',
        [ 'jquery', 'media-editor' ],
        '1.2.1',
        true
    );
}, 99 );

function karpovka_migrate_acc_objects_to_houses( $page_id ) {
    if ( get_post_meta( $page_id, '_karpovka_houses_migrated_20260824', true ) ) return false;
    $section = get_post_meta( $page_id, 'acc_objects', true );
    if ( ! is_array( $section ) || empty( $section['cards'] ) || ! is_array( $section['cards'] ) ) return false;

    $house_ids = [];
    foreach ( $section['cards'] as $index => $card ) {
        $title = sanitize_text_field( $card['title'] ?? '' );
        if ( '' === $title ) continue;
        $hash = md5( wp_json_encode( $card ) );
        $existing = get_posts( [
            'post_type'      => 'karpovka_house',
            'post_status'    => 'any',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'meta_key'       => '_karpovka_legacy_card_hash',
            'meta_value'     => $hash,
        ] );
        $house_id = $existing ? (int) $existing[0] : wp_insert_post( [
            'post_type'   => 'karpovka_house',
            'post_status' => 'publish',
            'post_title'  => $title,
            'menu_order'  => (int) $index,
        ] );
        if ( ! $house_id || is_wp_error( $house_id ) ) continue;
        if ( ! $existing ) {
            unset( $card['title'] );
            update_post_meta( $house_id, '_karpovka_house_card', array_merge( karpovka_house_card_defaults(), $card ) );
            update_post_meta( $house_id, '_karpovka_legacy_card_hash', $hash );
        }
        $house_ids[] = (int) $house_id;
    }

    if ( $house_ids ) {
        $section['selection_mode'] = 'selected';
        $section['house_ids'] = $house_ids;
        unset( $section['cards'] );
        update_post_meta( $page_id, 'acc_objects', $section );
        update_post_meta( $page_id, '_karpovka_houses_migrated_20260824', '1' );
        return true;
    }
    return false;
}

add_action( 'admin_init', function () {
    if ( ! isset( $_GET['post'] ) ) return;
    $post_id = absint( $_GET['post'] );
    if ( ! $post_id || ! current_user_can( 'edit_page', $post_id ) ) return;
    karpovka_migrate_acc_objects_to_houses( $post_id );
} );

function karpovka_old_house_node_text( $xpath, $context, $query ) {
    $node = $xpath->query( $query, $context )->item( 0 );
    return $node ? trim( preg_replace( '/\s+/u', ' ', $node->textContent ) ) : '';
}

function karpovka_old_house_node_html( $node ) {
    $html = '';
    foreach ( $node->childNodes as $child ) {
        $html .= $node->ownerDocument->saveHTML( $child );
    }
    return trim( preg_replace( '/\s+/u', ' ', $html ) );
}

function karpovka_old_house_absolute_url( $url, $source_url ) {
    if ( preg_match( '~^https?://~i', $url ) ) return $url;
    $base = preg_replace( '~/[^/]*$~', '/', $source_url );
    return $base . ltrim( preg_replace( '~^\./~', '', $url ), '/' );
}

function karpovka_parse_old_accommodation_html( $html, $source_url ) {
    if ( ! class_exists( 'DOMDocument' ) || ! is_string( $html ) || '' === trim( $html ) ) return [];
    $previous = libxml_use_internal_errors( true );
    $document = new DOMDocument();
    $loaded = $document->loadHTML( $html, LIBXML_NOERROR | LIBXML_NOWARNING );
    libxml_clear_errors();
    libxml_use_internal_errors( $previous );
    if ( ! $loaded ) return [];

    $xpath = new DOMXPath( $document );
    $cards = [];
    $card_nodes = $xpath->query( '//article[contains(concat(" ",normalize-space(@class)," ")," acc-card ")]' );
    foreach ( $card_nodes as $card ) {
        $title = karpovka_old_house_node_text( $xpath, $card, './/*[contains(concat(" ",normalize-space(@class)," ")," acc-card__title ")]' );
        if ( '' === $title ) continue;
        $image = $xpath->query( './/div[contains(@class,"acc-card__media")]//img', $card )->item( 0 );
        $price_node = $xpath->query( './/*[contains(concat(" ",normalize-space(@class)," ")," acc-card__price ")]', $card )->item( 0 );
        $prefix = $price_node ? karpovka_old_house_node_text( $xpath, $price_node, './span' ) : '';
        $suffix = $price_node ? karpovka_old_house_node_text( $xpath, $price_node, './small' ) : '';
        $price = $price_node ? trim( preg_replace( '/\s+/u', ' ', $price_node->textContent ) ) : '';
        if ( $prefix ) $price = trim( preg_replace( '/^' . preg_quote( $prefix, '/' ) . '\s*/u', '', $price ) );
        if ( $suffix ) $price = trim( preg_replace( '/' . preg_quote( $suffix, '/' ) . '$/u', '', $price ) );

        $features = [];
        foreach ( $xpath->query( './/ul[contains(@class,"acc-card__features")]/li', $card ) as $item ) {
            $features[] = [ 'text' => trim( preg_replace( '/\s+/u', ' ', $item->textContent ) ) ];
        }
        $details = [];
        foreach ( $xpath->query( './/*[@data-acc-details]//ul/li', $card ) as $item ) {
            $details[] = [ 'text' => karpovka_old_house_node_html( $item ) ];
        }
        $intro = karpovka_old_house_node_text( $xpath, $card, './/*[@data-acc-details]/p[not(contains(@class,"pricenote"))]' );
        $more = $xpath->query( './/*[@data-acc-more]', $card )->item( 0 );
        $book = $xpath->query( './/a[contains(concat(" ",normalize-space(@class)," ")," btn--gold ")]', $card )->item( 0 );
        $cards[] = [
            'type'              => $card->getAttribute( 'data-type' ) ?: 'eco',
            'audience'          => preg_split( '/\s+/', trim( $card->getAttribute( 'data-audience' ) ), -1, PREG_SPLIT_NO_EMPTY ),
            'img'               => $image ? karpovka_old_house_absolute_url( $image->getAttribute( 'src' ), $source_url ) : '',
            'img_alt'           => $image ? $image->getAttribute( 'alt' ) : '',
            'badge'             => karpovka_old_house_node_text( $xpath, $card, './/*[contains(concat(" ",normalize-space(@class)," ")," acc-card__badge ")]' ),
            'title'             => $title,
            'meta'              => karpovka_old_house_node_text( $xpath, $card, './/*[contains(concat(" ",normalize-space(@class)," ")," acc-card__meta ")]' ),
            'features'          => $features,
            'price_prefix'      => $prefix,
            'price'             => $price,
            'price_suffix'      => $suffix,
            'more_text'         => $more ? trim( $more->textContent ) : 'Подробнее',
            'more_link'         => '',
            'book_text'         => $book ? trim( $book->textContent ) : 'Забронировать',
            'book_link'         => $book ? $book->getAttribute( 'href' ) : '#booking',
            'details_intro'     => $intro,
            'details_items'     => $details,
            'details_pricenote' => karpovka_old_house_node_text( $xpath, $card, './/*[contains(concat(" ",normalize-space(@class)," ")," acc-card__pricenote ")]' ),
        ];
    }
    return $cards;
}

function karpovka_official_house_catalog() {
    return [
        [ 'url'=>'https://karpovka59.ru/ekonomer', 'title'=>'Эконом-номер', 'legacy_title'=>'Эконом-номер', 'type'=>'hotel', 'audience'=>['couple'], 'badge'=>'Номер', 'meta'=>'до 2 гостей · санузел на этаже', 'price_prefix'=>'', 'price'=>'3 300 ₽', 'price_note'=>'Пн–вс — 3 300 ₽ за ночь.' ],
        [ 'url'=>'https://karpovka59.ru/nomer_v_dome', 'title'=>'Номер в доме', 'legacy_title'=>'Номер в доме', 'type'=>'hotel', 'audience'=>['couple','family'], 'badge'=>'Номер', 'meta'=>'до 3 гостей · отдельная комната в гостевом доме', 'price_prefix'=>'от', 'price'=>'8 000 ₽', 'price_note'=>'Пн–чт — 8 000 ₽, пт–вс — 9 250 ₽. Одноместное размещение — 7 000 ₽ и 8 250 ₽.' ],
        [ 'url'=>'https://karpovka59.ru/2h', 'title'=>'Двухкомнатный номер', 'legacy_title'=>'Двухкомнатный номер', 'type'=>'hotel', 'audience'=>['family','couple'], 'badge'=>'Номер', 'meta'=>'до 4 гостей · спальня и гостиная', 'price_prefix'=>'от', 'price'=>'9 000 ₽', 'price_note'=>'Пн–чт — 9 000 ₽, пт–вс — 10 000 ₽ за ночь.' ],
        [ 'url'=>'https://karpovka59.ru/3h', 'title'=>'Трёхкомнатный номер', 'legacy_title'=>'Трёхкомнатный номер', 'type'=>'hotel', 'audience'=>['family','company'], 'badge'=>'Номер', 'meta'=>'до 6 гостей · 2 спальни и гостиная', 'price_prefix'=>'от', 'price'=>'11 000 ₽', 'price_note'=>'Пн–чт — 11 000 ₽, пт–вс — 12 000 ₽ за ночь.' ],
        [ 'url'=>'https://karpovka59.ru/vip', 'title'=>'VIP-дом', 'legacy_title'=>'VIP-дом', 'type'=>'guest', 'audience'=>['company','family'], 'badge'=>'VIP-дом', 'meta'=>'до 15 гостей · 13 спальных мест · 6 спален', 'price_prefix'=>'от', 'price'=>'48 000 ₽', 'price_note'=>'Пн–чт — 48 000 ₽, пт–вс — 55 000 ₽ за ночь.' ],
        [ 'url'=>'https://karpovka59.ru/gostdom', 'title'=>'Гостевой дом', 'legacy_title'=>'Гостевые дома № 1 и № 2', 'type'=>'guest', 'audience'=>['company','family'], 'badge'=>'Гостевой дом', 'meta'=>'до 12 гостей · 4 комнаты · 4 ванные комнаты', 'price_prefix'=>'от', 'price'=>'32 000 ₽', 'price_note'=>'Пн–чт — 32 000 ₽, пт–вс — 37 000 ₽ за ночь.' ],
        [ 'url'=>'https://karpovka59.ru/gostdom2', 'title'=>'Гостевой дом 2', 'legacy_title'=>'Гостевые дома № 1 и № 2', 'type'=>'guest', 'audience'=>['company','family'], 'badge'=>'Гостевой дом', 'meta'=>'до 12 гостей · 4 комнаты · 4 ванные комнаты', 'price_prefix'=>'от', 'price'=>'32 000 ₽', 'price_note'=>'Пн–чт — 32 000 ₽, пт–вс — 37 000 ₽ за ночь.' ],
        [ 'url'=>'https://karpovka59.ru/ekobolshoy', 'title'=>'Эко-дом «Скандинавский»', 'legacy_title'=>'Эко-дома «Скандинавский» и «Фьюжн»', 'type'=>'eco', 'audience'=>['family','company'], 'badge'=>'Эко-дом', 'meta'=>'до 8 гостей · 3 спальни и гостиная', 'price_prefix'=>'от', 'price'=>'32 500 ₽', 'price_note'=>'Пн–чт — 32 500 ₽, пт–вс — 35 000 ₽ за ночь.' ],
        [ 'url'=>'https://karpovka59.ru/ekobolshoy2', 'title'=>'Эко-дом «Фьюжн»', 'legacy_title'=>'Эко-дома «Скандинавский» и «Фьюжн»', 'type'=>'eco', 'audience'=>['family','company'], 'badge'=>'Эко-дом', 'meta'=>'до 8 гостей · 3 спальни и гостиная', 'price_prefix'=>'от', 'price'=>'32 500 ₽', 'price_note'=>'Пн–чт — 32 500 ₽, пт–вс — 35 000 ₽ за ночь.' ],
        [ 'url'=>'https://karpovka59.ru/ekomal2', 'title'=>'Эко-дом «Морской»', 'legacy_title'=>'Эко-дома «Морской» и «Рустик»', 'type'=>'eco', 'audience'=>['family','couple'], 'badge'=>'Эко-дом', 'meta'=>'до 4 гостей · спальня и гостиная', 'price_prefix'=>'от', 'price'=>'21 000 ₽', 'price_note'=>'Пн–чт — 21 000 ₽, пт–вс — 23 000 ₽ за ночь.' ],
        [ 'url'=>'https://karpovka59.ru/ekomal', 'title'=>'Эко-дом «Рустик»', 'legacy_title'=>'Эко-дома «Морской» и «Рустик»', 'type'=>'eco', 'audience'=>['family','couple'], 'badge'=>'Эко-дом', 'meta'=>'до 4 гостей · спальня и гостиная', 'price_prefix'=>'от', 'price'=>'21 000 ₽', 'price_note'=>'Пн–чт — 21 000 ₽, пт–вс — 23 000 ₽ за ночь.' ],
        [ 'url'=>'https://karpovka59.ru/loft', 'title'=>'Эко-дом «Лофт»', 'legacy_title'=>'Эко-дом «Лофт»', 'type'=>'eco', 'audience'=>['couple'], 'badge'=>'Эко-дом', 'meta'=>'до 2 гостей · отдельный дом для пары', 'price_prefix'=>'от', 'price'=>'13 500 ₽', 'price_note'=>'Пн–чт — 13 500 ₽, пт–вс — 15 500 ₽ за ночь.' ],
    ];
}

function karpovka_remote_html( $url ) {
    $response = wp_remote_get( $url, [ 'timeout'=>30, 'redirection'=>3, 'user-agent'=>'WordPress/Karpovka content migration' ] );
    if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) return '';
    return (string) wp_remote_retrieve_body( $response );
}

function karpovka_parse_official_house_html( $html ) {
    if ( ! class_exists( 'DOMDocument' ) || '' === trim( (string) $html ) ) return [];
    $previous = libxml_use_internal_errors( true );
    $document = new DOMDocument();
    $loaded = $document->loadHTML( $html, LIBXML_NOERROR | LIBXML_NOWARNING );
    libxml_clear_errors(); libxml_use_internal_errors( $previous );
    if ( ! $loaded ) return [];
    $xpath = new DOMXPath( $document );
    $meta = static function ( $property ) use ( $xpath ) {
        $node = $xpath->query( '//meta[@property="' . $property . '"]/@content' )->item( 0 );
        return $node ? trim( $node->nodeValue ) : '';
    };
    $primary_images = [];
    $fallback_images = [];
    foreach ( [ '//*[@data-img-zoom-url]/@data-img-zoom-url', '//*[contains(concat(" ",normalize-space(@class)," ")," js-product-img ")]/@data-original' ] as $query ) {
        foreach ( $xpath->query( $query ) as $node ) {
            $url = trim( $node->nodeValue );
            $filename = rawurldecode( basename( parse_url( $url, PHP_URL_PATH ) ) );
            if ( preg_match( '/(?:mask|logo|icon|noroot)/i', $filename ) ) continue;
            if ( preg_match( '~^https://(?:static|thb)\.tildacdn\.com/.+\.(?:jpe?g|png|webp)(?:\?.*)?$~i', $url ) ) $primary_images[$url] = $url;
        }
    }
    if ( count( $primary_images ) < 3 ) {
        foreach ( $xpath->query( '//*[@data-original]/@data-original' ) as $node ) {
            $url = trim( $node->nodeValue );
            $filename = rawurldecode( basename( parse_url( $url, PHP_URL_PATH ) ) );
            if ( preg_match( '/(?:mask|logo|icon|noroot)/i', $filename ) ) continue;
            if ( preg_match( '~^https://(?:static|thb)\.tildacdn\.com/.+\.(?:jpe?g|png|webp)(?:\?.*)?$~i', $url ) ) $fallback_images[$url] = $url;
        }
    }
    $images = count( $primary_images ) >= 3
        ? $primary_images
        : array_slice( $primary_images + $fallback_images, 0, 12, true );
    if ( ! $images && $meta( 'og:image' ) ) $images[$meta( 'og:image' )] = $meta( 'og:image' );

    $items = [];
    foreach ( $xpath->query( '//*[@field="descr" or @field="text"]' ) as $node ) {
        $fragment = $document->saveHTML( $node );
        $fragment = preg_replace( '~<br\s*/?>~i', "\n", $fragment );
        $text = html_entity_decode( strip_tags( $fragment ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
        foreach ( preg_split( '/(?:\r\n|\r|\n|•)+/u', $text ) as $line ) {
            $line = trim( preg_replace( '/\s+/u', ' ', $line ), " \t\n\r\0\x0B-" );
            if ( mb_strlen( $line ) < 12 || mb_strlen( $line ) > 420 ) continue;
            if ( preg_match( '/Вопросы по размещению|Предложения по сотрудничеству|Политик|Вернуться к просмотру|Забронировать/u', $line ) ) continue;
            $items[md5($line)] = [ 'text'=>$line ];
        }
    }
    return [ 'intro'=>$meta( 'og:description' ), 'gallery'=>array_values( $images ), 'details_items'=>array_slice( array_values( $items ), 0, 30 ) ];
}

function karpovka_find_legacy_house_card( $legacy_title, $old_cards ) {
    foreach ( $old_cards as $card ) if ( ( $card['title'] ?? '' ) === $legacy_title ) return $card;
    return [];
}

function karpovka_import_official_houses() {
    if ( get_option( 'karpovka_official_houses_20260824_v2' ) ) return false;
    $old_html = karpovka_remote_html( 'https://dev.miram-prod.ru/clients/karpovka/old/accommodation.html' );
    $old_cards = karpovka_parse_old_accommodation_html( $old_html, 'https://dev.miram-prod.ru/clients/karpovka/old/accommodation.html' );
    $imported = []; $replacements = []; $catalog = karpovka_official_house_catalog();

    foreach ( $catalog as $index=>$item ) {
        $detail_html = karpovka_remote_html( $item['url'] );
        $official = karpovka_parse_official_house_html( $detail_html );
        if ( empty( $official['gallery'] ) ) continue;
        $source_key = md5( $item['url'] );
        $matches = get_posts( [ 'post_type'=>'karpovka_house', 'post_status'=>'any', 'posts_per_page'=>1, 'fields'=>'ids', 'meta_key'=>'_karpovka_official_source_key', 'meta_value'=>$source_key ] );
        if ( ! $matches ) $matches = get_posts( [ 'post_type'=>'karpovka_house', 'post_status'=>'any', 'posts_per_page'=>1, 'fields'=>'ids', 'title'=>$item['title'] ] );
        $house_id = $matches ? (int) $matches[0] : wp_insert_post( [ 'post_type'=>'karpovka_house', 'post_status'=>'publish', 'post_title'=>$item['title'], 'menu_order'=>$index ] );
        if ( ! $house_id || is_wp_error( $house_id ) ) continue;

        $legacy = karpovka_find_legacy_house_card( $item['legacy_title'], $old_cards );
        $card = array_merge( karpovka_house_card_defaults(), $legacy, [
            'type'=>$item['type'], 'audience'=>$item['audience'], 'badge'=>$item['badge'], 'meta'=>$item['meta'],
            'price_prefix'=>$item['price_prefix'], 'price'=>$item['price'], 'price_suffix'=>'/ ночь',
            'details_intro'=>$official['intro'] ?: ( $legacy['details_intro'] ?? '' ),
            'details_items'=>$official['details_items'] ?: ( $legacy['details_items'] ?? [] ),
            'details_pricenote'=>$item['price_note'], 'gallery'=>$official['gallery'], 'img'=>$official['gallery'][0],
            'img_alt'=>$item['title'], 'source_url'=>$item['url'], 'more_text'=>'Подробнее', 'more_link'=>'',
            'book_text'=>'Забронировать', 'book_link'=>'#booking',
        ] );
        $content = '<p>' . esc_html( $card['details_intro'] ) . '</p><ul>';
        foreach ( $card['details_items'] as $detail ) $content .= '<li>' . esc_html( $detail['text'] ?? '' ) . '</li>';
        $content .= '</ul>';
        wp_update_post( [ 'ID'=>$house_id, 'post_title'=>$item['title'], 'post_status'=>'publish', 'menu_order'=>$index, 'post_content'=>$content ] );
        update_post_meta( $house_id, '_karpovka_official_source_key', $source_key );
        update_post_meta( $house_id, '_karpovka_house_source_url', $item['url'] );
        update_post_meta( $house_id, '_karpovka_house_gallery_sources', $official['gallery'] );
        update_post_meta( $house_id, '_karpovka_house_card', $card );
        $imported[] = $house_id;
        $replacements[$item['legacy_title']][] = $house_id;
    }
    if ( count( array_unique( $imported ) ) !== count( $catalog ) ) return false;

    foreach ( $replacements as $legacy_title=>$replacement_ids ) {
        if ( 1 === count( $replacement_ids ) && $legacy_title === get_the_title( $replacement_ids[0] ) ) continue;
        $legacy_ids = get_posts( [ 'post_type'=>'karpovka_house', 'post_status'=>'any', 'posts_per_page'=>-1, 'fields'=>'ids', 'title'=>$legacy_title ] );
        foreach ( $legacy_ids as $legacy_id ) {
            if ( in_array( (int) $legacy_id, $replacement_ids, true ) ) continue;
            wp_update_post( [ 'ID'=>(int)$legacy_id, 'post_status'=>'draft' ] );
            $pages = get_posts( [ 'post_type'=>'page', 'post_status'=>'any', 'posts_per_page'=>-1, 'fields'=>'ids', 'meta_key'=>'acc_objects' ] );
            foreach ( $pages as $page_id ) {
                $section = get_post_meta( $page_id, 'acc_objects', true );
                if ( ! is_array( $section ) || 'selected' !== ( $section['selection_mode'] ?? '' ) || ! in_array( (int)$legacy_id, array_map( 'intval', (array)( $section['house_ids'] ?? [] ) ), true ) ) continue;
                $section['house_ids'] = array_values( array_unique( array_merge( array_diff( array_map( 'intval', $section['house_ids'] ), [ (int)$legacy_id ] ), $replacement_ids ) ) );
                update_post_meta( $page_id, 'acc_objects', $section );
            }
        }
    }
    update_option( 'karpovka_official_houses_20260824_v2', [ 'count'=>count($catalog), 'ids'=>array_values(array_unique($imported)), 'time'=>current_time('mysql') ], false );
    if ( ! wp_next_scheduled( 'karpovka_import_house_media_batch' ) ) wp_schedule_single_event( time()+5, 'karpovka_import_house_media_batch' );
    return true;
}

function karpovka_sideload_house_media( $house_id, $source_url, $alt ) {
    $existing = get_posts( [ 'post_type'=>'attachment', 'post_status'=>'inherit', 'posts_per_page'=>1, 'fields'=>'ids', 'meta_key'=>'_karpovka_house_media_source', 'meta_value'=>$source_url ] );
    if ( $existing ) {
        $existing_url = wp_get_attachment_url( (int) $existing[0] );
        if ( $existing_url ) return $existing_url;
    }
    require_once ABSPATH.'wp-admin/includes/file.php'; require_once ABSPATH.'wp-admin/includes/media.php'; require_once ABSPATH.'wp-admin/includes/image.php';
    $attachment_id = media_sideload_image( $source_url, $house_id, $alt, 'id' );
    if ( is_wp_error( $attachment_id ) ) return '';
    update_post_meta( $attachment_id, '_wp_attachment_image_alt', sanitize_text_field( $alt ) );
    update_post_meta( $attachment_id, '_karpovka_house_media_source', esc_url_raw( $source_url ) );
    return wp_get_attachment_url( $attachment_id ) ?: '';
}

add_action( 'karpovka_import_house_media_batch', function () {
    $state = get_option( 'karpovka_official_houses_20260824_v2' ); if ( empty( $state['ids'] ) ) return;
    $processed = 0; $pending = false;
    foreach ( $state['ids'] as $house_id ) {
        $sources = (array) get_post_meta( $house_id, '_karpovka_house_gallery_sources', true );
        $map = (array) get_post_meta( $house_id, '_karpovka_house_gallery_map', true );
        $failures = (array) get_post_meta( $house_id, '_karpovka_house_gallery_failures', true );
        foreach ( $sources as $source ) {
            if ( ! empty( $map[$source] ) ) continue;
            $local = karpovka_sideload_house_media( $house_id, $source, get_the_title( $house_id ) );
            if ( $local ) {
                $map[$source] = $local;
            } else {
                $failures[$source] = (int) ( $failures[$source] ?? 0 ) + 1;
                if ( $failures[$source] >= 3 ) $map[$source] = $source;
            }
            update_post_meta( $house_id, '_karpovka_house_gallery_map', $map );
            update_post_meta( $house_id, '_karpovka_house_gallery_failures', $failures );
            $processed++; if ( $processed >= 3 ) break 2;
        }
    }
    foreach ( $state['ids'] as $house_id ) {
        $sources=(array)get_post_meta($house_id,'_karpovka_house_gallery_sources',true); $map=(array)get_post_meta($house_id,'_karpovka_house_gallery_map',true);
        $gallery=[]; foreach($sources as $source){ $gallery[]=$map[$source]??$source; if(empty($map[$source]))$pending=true; }
        $card=karpovka_get_house_card($house_id); $card['gallery']=$gallery; if($gallery)$card['img']=$gallery[0]; update_post_meta($house_id,'_karpovka_house_card',$card);
        if($gallery && !has_post_thumbnail($house_id) && !empty($map[$sources[0]])){ $attachment_id=attachment_url_to_postid($map[$sources[0]]); if($attachment_id)set_post_thumbnail($house_id,$attachment_id); }
    }
    if($pending)wp_schedule_single_event(time()+10,'karpovka_import_house_media_batch'); else update_option('karpovka_official_house_media_complete_20260824','1',false);
} );

add_action( 'admin_init', function () { if(current_user_can('manage_options'))karpovka_import_official_houses(); }, 20 );

add_action( 'admin_init', function () {
    if ( get_option( 'karpovka_house_rewrite_rules_v1' ) ) return;
    flush_rewrite_rules( false );
    update_option( 'karpovka_house_rewrite_rules_v1', '1', false );
}, 30 );

add_action( 'admin_notices', function () {
    if(!current_user_can('manage_options'))return; $state=get_option('karpovka_official_houses_20260824_v2'); if(!$state)return;
    if(get_option('karpovka_official_house_media_complete_20260824'))echo '<div class="notice notice-success is-dismissible"><p>Карповка: импортировано 12 домов и номеров, медиагалереи загружены.</p></div>';
    else echo '<div class="notice notice-info"><p>Карповка: 12 домов и номеров импортированы. Медиагалереи загружаются в фоне.</p></div>';
} );
