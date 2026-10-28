<?php
/**
 * Editable content for the previously static home services and map sections.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function karpovka_home_services_defaults() {
    $assets = trailingslashit( get_template_directory_uri() ) . 'assets/img/imgs/';
    return array(
        'title'    => 'Всё для насыщенного',
        'title_em' => 'отдыха',
        'cards'    => array(
            array( 'anchor' => 'bath', 'title' => 'Банный комплекс', 'description' => 'Русская баня на дровах, парные с системой «второе дыхание».', 'image' => $assets . 'banya.png', 'url' => '#contacts' ),
            array( 'anchor' => 'dining', 'title' => 'Кафе и банкетный зал', 'description' => 'Домашняя кухня, сезонные блюда и уютная атмосфера для событий.', 'image' => $assets . 'photo.png', 'url' => '/cafe' ),
            array( 'anchor' => 'events', 'title' => 'Мероприятия', 'description' => 'Свадьбы, банкеты, выпускные, дни рождения, ретриты и корпоративы.', 'image' => $assets . 'events.JPG', 'url' => '#contacts' ),
            array( 'anchor' => '', 'title' => 'Доставка еды и напитков', 'description' => 'Закажите любимые блюда и напитки — доставим в любое место на территории парк-отеля.', 'image' => $assets . 'food_delivery.jpg', 'url' => 'https://shop.hotbot.ai/karpovka/eda-i-napitki-v-nomer/' ),
            array( 'anchor' => '', 'title' => 'Беседки', 'description' => 'Открытая беседка на берегу пруда и тёплый банкетный зал для мероприятий в любое время года.', 'image' => $assets . 'besedka_outdoor.jpg', 'secondary_image' => $assets . 'besedka_warm.jpg', 'url' => '#contacts' ),
        ),
    );
}

function karpovka_home_map_defaults() {
    $theme  = trailingslashit( get_template_directory_uri() );
    $assets = $theme . 'assets/img/imgs/';
    return array(
        'title'    => 'Интерактивная карта',
        'title_em' => 'Карповки',
        'image'    => $theme . 'assets/img/map_no_wm.png',
        'markers'  => array(
            array( 'title' => 'Эко-дома', 'image' => $assets . '1.jpg_1.jpeg', 'description' => 'Отдельный дом, терраса, кухня, мангальная зона. Для пар, семей и спокойных выходных на природе.', 'price' => 'от 10 500 ₽ / ночь', 'x' => '0.4', 'y' => '0.20' ),
            array( 'title' => 'Гостиничный комплекс', 'image' => $assets . 'IMG_9846.jpg.jpeg', 'description' => 'Номера для короткого отдыха, СПА-визита или события. Мангальная зона, Wi-Fi, удобный доступ к услугам комплекса.', 'price' => 'от 6 000 ₽ / ночь', 'x' => '0.32', 'y' => '0.60' ),
            array( 'title' => 'Гостевые дома', 'image' => $assets . '1.jpg.jpeg', 'description' => '4 спальни, большая гостиная, кухня, вид на лес.', 'price' => 'от 14 500 ₽ / ночь', 'x' => '0.5', 'y' => '0.4' ),
        ),
    );
}

function karpovka_home_services_data( $post_id ) {
    $defaults = karpovka_home_services_defaults();
    $saved    = get_post_meta( $post_id, 'karpovka_home_services', true );
    $data     = is_array( $saved ) ? wp_parse_args( $saved, $defaults ) : $defaults;

    if ( ! isset( $data['cards'] ) || ! is_array( $data['cards'] ) ) {
        $data['cards'] = $defaults['cards'];
    } else {
        $data['cards'] = array_values( array_filter( $data['cards'], 'is_array' ) );
    }

    return $data;
}

function karpovka_home_map_data( $post_id ) {
    $defaults = karpovka_home_map_defaults();
    $saved    = get_post_meta( $post_id, 'karpovka_home_map', true );
    $data     = is_array( $saved ) ? wp_parse_args( $saved, $defaults ) : $defaults;

    if ( ! isset( $data['markers'] ) || ! is_array( $data['markers'] ) ) {
        $data['markers'] = $defaults['markers'];
    } else {
        $data['markers'] = array_values( array_filter( $data['markers'], 'is_array' ) );
    }

    return $data;
}

function karpovka_home_services_box( $post ) {
    $data = karpovka_home_services_data( $post->ID );
    wp_nonce_field( 'karpovka_home_services_save', 'karpovka_home_services_nonce' );
    ?>
    <div class="kp-fields">
        <div class="kp-field-grid">
            <p><label><strong>Заголовок</strong></label><input class="widefat" type="text" name="karpovka_home_services[title]" value="<?php echo esc_attr( $data['title'] ); ?>" data-kp-required="Заголовок блока услуг"></p>
            <p><label><strong>Выделенная часть</strong></label><input class="widefat" type="text" name="karpovka_home_services[title_em]" value="<?php echo esc_attr( $data['title_em'] ); ?>"></p>
        </div>
        <div data-kp-repeater data-kp-repeater-name="karpovka_home_services[cards]" data-kp-next="<?php echo esc_attr( count( $data['cards'] ) ); ?>">
            <div class="kp-repeater__head"><strong>Карточки услуг</strong><button type="button" class="button" data-kp-repeater-add>Добавить карточку</button></div>
            <div data-kp-repeater-list>
                <?php foreach ( $data['cards'] as $index => $card ) : ?>
                    <?php karpovka_home_service_card_fields( $card, $index ); ?>
                <?php endforeach; ?>
            </div>
            <template data-kp-repeater-template><?php karpovka_home_service_card_fields( array(), '__INDEX__' ); ?></template>
        </div>
    </div>
    <?php
}

function karpovka_home_service_card_fields( $card, $index ) {
    $base = 'karpovka_home_services[cards][' . $index . ']';
    ?>
    <article class="kp-repeater__item" data-kp-repeater-item>
        <header class="kp-repeater__item-head">
            <strong data-kp-item-summary><?php echo esc_html( ! empty( $card['title'] ) ? $card['title'] : 'Новая карточка' ); ?></strong>
            <span class="kp-repeater__actions">
                <button type="button" class="button" data-kp-item-up aria-label="Переместить выше">↑</button>
                <button type="button" class="button" data-kp-item-down aria-label="Переместить ниже">↓</button>
                <button type="button" class="button" data-kp-item-duplicate>Дублировать</button>
                <button type="button" class="button button-link-delete" data-kp-item-delete>Удалить</button>
            </span>
        </header>
        <div class="kp-field-grid">
            <p><label><strong>Название</strong></label><input class="widefat" type="text" name="<?php echo esc_attr( $base ); ?>[title]" value="<?php echo esc_attr( $card['title'] ?? '' ); ?>" data-kp-summary-source data-kp-required="Название карточки"></p>
            <p><label><strong>Ссылка</strong></label><input class="widefat" type="text" name="<?php echo esc_attr( $base ); ?>[url]" value="<?php echo esc_attr( $card['url'] ?? '' ); ?>" placeholder="https://, /страница или #якорь"></p>
            <p class="kp-field-grid__wide"><label><strong>Описание</strong></label><textarea class="widefat" rows="3" name="<?php echo esc_attr( $base ); ?>[description]"><?php echo esc_textarea( $card['description'] ?? '' ); ?></textarea></p>
            <p><label><strong>Изображение</strong></label><input class="widefat" type="text" name="<?php echo esc_attr( $base ); ?>[image]" value="<?php echo esc_attr( $card['image'] ?? '' ); ?>" data-kp-media></p>
            <p><label><strong>Второе изображение</strong></label><input class="widefat" type="text" name="<?php echo esc_attr( $base ); ?>[secondary_image]" value="<?php echo esc_attr( $card['secondary_image'] ?? '' ); ?>" data-kp-media><span class="description">Необязательно. В карточке появится переключение фотографий.</span></p>
            <p data-kp-technical><label><strong>Якорь карточки</strong></label><input class="widefat" type="text" name="<?php echo esc_attr( $base ); ?>[anchor]" value="<?php echo esc_attr( $card['anchor'] ?? '' ); ?>"></p>
        </div>
    </article>
    <?php
}

function karpovka_home_map_box( $post ) {
    $data = karpovka_home_map_data( $post->ID );
    wp_nonce_field( 'karpovka_home_map_save', 'karpovka_home_map_nonce' );
    ?>
    <div class="kp-fields">
        <div class="kp-field-grid">
            <p><label><strong>Заголовок</strong></label><input class="widefat" type="text" name="karpovka_home_map[title]" value="<?php echo esc_attr( $data['title'] ); ?>" data-kp-required="Заголовок карты"></p>
            <p><label><strong>Выделенная часть</strong></label><input class="widefat" type="text" name="karpovka_home_map[title_em]" value="<?php echo esc_attr( $data['title_em'] ); ?>"></p>
            <p class="kp-field-grid__wide"><label><strong>Изображение карты</strong></label><input class="widefat" type="text" name="karpovka_home_map[image]" value="<?php echo esc_attr( $data['image'] ); ?>" data-kp-media></p>
        </div>
        <div data-kp-repeater data-kp-repeater-name="karpovka_home_map[markers]" data-kp-next="<?php echo esc_attr( count( $data['markers'] ) ); ?>">
            <div class="kp-repeater__head"><strong>Объекты на карте</strong><button type="button" class="button" data-kp-repeater-add>Добавить объект</button></div>
            <div data-kp-repeater-list>
                <?php foreach ( $data['markers'] as $index => $marker ) : ?>
                    <?php karpovka_home_map_marker_fields( $marker, $index, $data['image'] ); ?>
                <?php endforeach; ?>
            </div>
            <template data-kp-repeater-template><?php karpovka_home_map_marker_fields( array(), '__INDEX__', $data['image'] ); ?></template>
        </div>
    </div>
    <style>
        .kp-map-picker{position:relative;overflow:hidden;width:100%;max-width:760px;aspect-ratio:16/9;border:1px solid #dcdcde;border-radius:8px;background:#f0f0f1;cursor:crosshair}
        .kp-map-picker img{display:block;width:100%;height:100%;object-fit:cover;user-select:none;pointer-events:none}
        .kp-map-picker__pin{position:absolute;left:50%;top:50%;width:24px;height:24px;border:3px solid #fff;border-radius:50%;background:#1f7a4d;box-shadow:0 3px 12px rgba(0,0,0,.35);transform:translate(-50%,-50%);cursor:grab}
        .kp-map-picker__pin:after{content:"";position:absolute;left:50%;top:100%;border:6px solid transparent;border-top-color:#1f7a4d;transform:translateX(-50%)}
        .kp-map-picker__hint{margin:6px 0 0;color:#646970;font-size:12px}
        .kp-map-picker__coords{display:inline-block;margin-left:8px;color:#2271b1}
    </style>
    <script>
    (function(){
        function clamp(value) { return Math.max(0, Math.min(1, value)); }
        function updatePicker(picker, x, y) {
            x = clamp(Number(x) || 0.5);
            y = clamp(Number(y) || 0.5);
            var item = picker.closest('[data-kp-repeater-item]');
            var inputX = item && item.querySelector('[data-kp-map-x]');
            var inputY = item && item.querySelector('[data-kp-map-y]');
            var pin = picker.querySelector('[data-kp-map-pin]');
            var coords = item && item.querySelector('[data-kp-map-coords]');
            if (inputX) inputX.value = x.toFixed(2);
            if (inputY) inputY.value = y.toFixed(2);
            if (pin) {
                pin.style.left = (x * 100) + '%';
                pin.style.top = (y * 100) + '%';
            }
            if (coords) coords.textContent = 'x: ' + x.toFixed(2) + ', y: ' + y.toFixed(2);
        }
        function setFromEvent(picker, event) {
            var rect = picker.getBoundingClientRect();
            updatePicker(picker, (event.clientX - rect.left) / rect.width, (event.clientY - rect.top) / rect.height);
        }
        function initPicker(picker) {
            if (picker.dataset.kpMapReady) return;
            picker.dataset.kpMapReady = '1';
            var item = picker.closest('[data-kp-repeater-item]');
            var inputX = item && item.querySelector('[data-kp-map-x]');
            var inputY = item && item.querySelector('[data-kp-map-y]');
            updatePicker(picker, inputX ? inputX.value : 0.5, inputY ? inputY.value : 0.5);
        }
        document.addEventListener('pointerdown', function(event) {
            var picker = event.target.closest('[data-kp-map-picker]');
            if (!picker) return;
            event.preventDefault();
            picker.setPointerCapture(event.pointerId);
            setFromEvent(picker, event);
            picker.dataset.kpDragging = '1';
        });
        document.addEventListener('pointermove', function(event) {
            var picker = document.querySelector('[data-kp-map-picker][data-kp-dragging="1"]');
            if (picker) setFromEvent(picker, event);
        });
        document.addEventListener('pointerup', function() {
            document.querySelectorAll('[data-kp-map-picker][data-kp-dragging="1"]').forEach(function(picker) {
                delete picker.dataset.kpDragging;
            });
        });
        document.addEventListener('input', function(event) {
            if (!event.target.matches('[data-kp-map-x], [data-kp-map-y]')) return;
            var item = event.target.closest('[data-kp-repeater-item]');
            var picker = item && item.querySelector('[data-kp-map-picker]');
            var inputX = item && item.querySelector('[data-kp-map-x]');
            var inputY = item && item.querySelector('[data-kp-map-y]');
            if (picker) updatePicker(picker, inputX ? inputX.value : 0.5, inputY ? inputY.value : 0.5);
        });
        function initAll() {
            document.querySelectorAll('[data-kp-map-picker]').forEach(initPicker);
        }
        document.addEventListener('DOMContentLoaded', initAll);
        new MutationObserver(initAll).observe(document.documentElement, { childList: true, subtree: true });
    })();
    </script>
    <?php
}

function karpovka_home_map_marker_fields( $marker, $index, $map_image = '' ) {
    $base = 'karpovka_home_map[markers][' . $index . ']';
    $x = $marker['x'] ?? '0.5';
    $y = $marker['y'] ?? '0.5';
    ?>
    <article class="kp-repeater__item" data-kp-repeater-item>
        <header class="kp-repeater__item-head">
            <strong data-kp-item-summary><?php echo esc_html( ! empty( $marker['title'] ) ? $marker['title'] : 'Новый объект' ); ?></strong>
            <span class="kp-repeater__actions">
                <button type="button" class="button" data-kp-item-up aria-label="Переместить выше">↑</button>
                <button type="button" class="button" data-kp-item-down aria-label="Переместить ниже">↓</button>
                <button type="button" class="button" data-kp-item-duplicate>Дублировать</button>
                <button type="button" class="button button-link-delete" data-kp-item-delete>Удалить</button>
            </span>
        </header>
        <div class="kp-field-grid">
            <p><label><strong>Название объекта</strong></label><input class="widefat" type="text" name="<?php echo esc_attr( $base ); ?>[title]" value="<?php echo esc_attr( $marker['title'] ?? '' ); ?>" data-kp-summary-source data-kp-required="Название объекта на карте"></p>
            <p><label><strong>Цена или подпись</strong></label><input class="widefat" type="text" name="<?php echo esc_attr( $base ); ?>[price]" value="<?php echo esc_attr( $marker['price'] ?? '' ); ?>"></p>
            <p class="kp-field-grid__wide"><label><strong>Описание</strong></label><textarea class="widefat" rows="3" name="<?php echo esc_attr( $base ); ?>[description]"><?php echo esc_textarea( $marker['description'] ?? '' ); ?></textarea></p>
            <p class="kp-field-grid__wide"><label><strong>Фотография объекта</strong></label><input class="widefat" type="text" name="<?php echo esc_attr( $base ); ?>[image]" value="<?php echo esc_attr( $marker['image'] ?? '' ); ?>" data-kp-media></p>
            <div class="kp-field-grid__wide">
                <label><strong>Положение маркера на карте</strong></label>
                <div class="kp-map-picker" data-kp-map-picker>
                    <?php if ( $map_image ) : ?><img src="<?php echo esc_url( $map_image ); ?>" alt="Карта территории"><?php endif; ?>
                    <span class="kp-map-picker__pin" data-kp-map-pin aria-hidden="true"></span>
                </div>
                <p class="kp-map-picker__hint">Кликните по карте или перетащите точку. <span class="kp-map-picker__coords" data-kp-map-coords></span></p>
                <input type="hidden" name="<?php echo esc_attr( $base ); ?>[x]" value="<?php echo esc_attr( $x ); ?>" data-kp-map-x>
                <input type="hidden" name="<?php echo esc_attr( $base ); ?>[y]" value="<?php echo esc_attr( $y ); ?>" data-kp-map-y>
            </div>
        </div>
    </article>
    <?php
}

add_action( 'save_post_page', function ( $post_id ) {
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['karpovka_home_services_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['karpovka_home_services_nonce'] ) ), 'karpovka_home_services_save' ) ) {
        $raw   = isset( $_POST['karpovka_home_services'] ) && is_array( $_POST['karpovka_home_services'] ) ? wp_unslash( $_POST['karpovka_home_services'] ) : array();
        $cards = array();
        foreach ( isset( $raw['cards'] ) && is_array( $raw['cards'] ) ? $raw['cards'] : array() as $card ) {
            $clean = array(
                'anchor' => sanitize_key( $card['anchor'] ?? '' ), 'title' => sanitize_text_field( $card['title'] ?? '' ),
                'description' => sanitize_textarea_field( $card['description'] ?? '' ), 'image' => esc_url_raw( $card['image'] ?? '' ),
                'secondary_image' => esc_url_raw( $card['secondary_image'] ?? '' ),
                'url' => esc_url_raw( $card['url'] ?? '' ),
            );
            if ( $clean['title'] || $clean['image'] ) { $cards[] = $clean; }
        }
        update_post_meta( $post_id, 'karpovka_home_services', array( 'title' => sanitize_text_field( $raw['title'] ?? '' ), 'title_em' => sanitize_text_field( $raw['title_em'] ?? '' ), 'cards' => $cards ) );
    }

    if ( isset( $_POST['karpovka_home_map_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['karpovka_home_map_nonce'] ) ), 'karpovka_home_map_save' ) ) {
        $raw     = isset( $_POST['karpovka_home_map'] ) && is_array( $_POST['karpovka_home_map'] ) ? wp_unslash( $_POST['karpovka_home_map'] ) : array();
        $markers = array();
        foreach ( isset( $raw['markers'] ) && is_array( $raw['markers'] ) ? $raw['markers'] : array() as $marker ) {
            $clean = array(
                'title' => sanitize_text_field( $marker['title'] ?? '' ), 'description' => sanitize_textarea_field( $marker['description'] ?? '' ),
                'price' => sanitize_text_field( $marker['price'] ?? '' ), 'image' => esc_url_raw( $marker['image'] ?? '' ),
                'x' => (string) max( 0, min( 1, (float) ( $marker['x'] ?? 0.5 ) ) ), 'y' => (string) max( 0, min( 1, (float) ( $marker['y'] ?? 0.5 ) ) ),
            );
            if ( $clean['title'] ) { $markers[] = $clean; }
        }
        update_post_meta( $post_id, 'karpovka_home_map', array( 'title' => sanitize_text_field( $raw['title'] ?? '' ), 'title_em' => sanitize_text_field( $raw['title_em'] ?? '' ), 'image' => esc_url_raw( $raw['image'] ?? '' ), 'markers' => $markers ) );
    }
}, 20 );
