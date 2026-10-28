<?php
/**
 * Meta box: Награды и премии.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'add_meta_boxes', function () {
    add_meta_box( 'awards_section', 'Награды и премии', 'awards_section_cb', 'page', 'normal', 'high' );
} );

function karpovka_award_years_for_editor( $years ) {
    if ( is_array( $years ) ) {
        return implode( "\n", array_filter( array_map( 'strval', $years ) ) );
    }

    return is_scalar( $years ) ? (string) $years : '';
}

function karpovka_award_item_fields( $item, $index ) {
    $base       = 'awards[items][' . $index . ']';
    $badge_type = isset( $item['badge_type'] ) && 'iframe' === $item['badge_type'] ? 'iframe' : 'image';
    $title      = isset( $item['award_title'] ) ? $item['award_title'] : '';
    ?>
    <article class="kp-repeater__item" data-kp-repeater-item>
        <header class="kp-repeater__item-head">
            <strong data-kp-item-summary><?php echo esc_html( $title ? $title : 'Новая награда' ); ?></strong>
            <span class="kp-repeater__actions">
                <button type="button" class="button" data-kp-item-up aria-label="Переместить выше">↑</button>
                <button type="button" class="button" data-kp-item-down aria-label="Переместить ниже">↓</button>
                <button type="button" class="button" data-kp-item-duplicate>Дублировать</button>
                <button type="button" class="button button-link-delete" data-kp-item-delete>Удалить</button>
            </span>
        </header>

        <div class="kp-field-grid">
            <p>
                <label><strong>Название награды</strong></label>
                <input class="widefat" type="text" name="<?php echo esc_attr( $base ); ?>[award_title]" value="<?php echo esc_attr( $title ); ?>" data-kp-summary-source data-kp-required="Название награды">
            </p>
            <p>
                <label><strong>Организатор или источник</strong></label>
                <input class="widefat" type="text" name="<?php echo esc_attr( $base ); ?>[source]" value="<?php echo esc_attr( $item['source'] ?? '' ); ?>" placeholder="Например, Яндекс Карты">
            </p>
            <p class="kp-field-grid__wide">
                <label><strong>Описание</strong></label>
                <textarea class="widefat" rows="3" name="<?php echo esc_attr( $base ); ?>[desc]"><?php echo esc_textarea( $item['desc'] ?? '' ); ?></textarea>
            </p>
            <p>
                <label><strong>Годы получения</strong></label>
                <textarea class="widefat" rows="3" name="<?php echo esc_attr( $base ); ?>[years]" placeholder="2026&#10;2025&#10;2024"><?php echo esc_textarea( karpovka_award_years_for_editor( $item['years'] ?? '' ) ); ?></textarea>
                <span class="description">Каждый год укажите с новой строки.</span>
            </p>
            <p>
                <label><strong>Способ отображения</strong></label>
                <select class="widefat" name="<?php echo esc_attr( $base ); ?>[badge_type]" data-kp-award-badge-type>
                    <option value="image" <?php selected( $badge_type, 'image' ); ?>>Изображение или логотип</option>
                    <option value="iframe" <?php selected( $badge_type, 'iframe' ); ?>>Внешний виджет</option>
                </select>
            </p>
            <p class="kp-field-grid__wide" data-kp-award-image<?php echo 'image' === $badge_type ? '' : ' hidden'; ?>>
                <label><strong>Изображение награды</strong></label>
                <input type="hidden" name="<?php echo esc_attr( $base ); ?>[logo]" value="<?php echo esc_attr( $item['logo'] ?? '' ); ?>" data-kp-media>
            </p>
            <p class="kp-field-grid__wide" data-kp-award-iframe<?php echo 'iframe' === $badge_type ? '' : ' hidden'; ?>>
                <label><strong>Ссылка на внешний виджет</strong></label>
                <input class="widefat" type="url" name="<?php echo esc_attr( $base ); ?>[iframe_src]" value="<?php echo esc_attr( $item['iframe_src'] ?? '' ); ?>" placeholder="https://yandex.ru/sprav/widget/...">
            </p>
            <p data-kp-technical>
                <label><strong>Ширина виджета</strong></label>
                <input class="widefat" type="number" min="1" name="<?php echo esc_attr( $base ); ?>[iframe_w]" value="<?php echo esc_attr( $item['iframe_w'] ?? 150 ); ?>">
            </p>
            <p data-kp-technical>
                <label><strong>Высота виджета</strong></label>
                <input class="widefat" type="number" min="1" name="<?php echo esc_attr( $base ); ?>[iframe_h]" value="<?php echo esc_attr( $item['iframe_h'] ?? 50 ); ?>">
            </p>
            <p class="kp-field-grid__wide" data-kp-technical>
                <label><strong>Описание виджета для доступности</strong></label>
                <input class="widefat" type="text" name="<?php echo esc_attr( $base ); ?>[iframe_title]" value="<?php echo esc_attr( $item['iframe_title'] ?? '' ); ?>">
            </p>
        </div>
    </article>
    <?php
}

function awards_section_cb( $post ) {
    wp_nonce_field( 'awards_section_nonce', 'awards_section_nonce_field' );
    $value = get_post_meta( $post->ID, 'awards', true );
    $value = is_array( $value ) ? $value : array();
    $items = isset( $value['items'] ) && is_array( $value['items'] ) ? array_values( $value['items'] ) : array();
    ?>
    <div class="kp-fields">
        <div class="kp-field-grid">
            <p>
                <label><strong>Надзаголовок</strong></label>
                <input class="widefat" type="text" name="awards[eyebrow]" value="<?php echo esc_attr( $value['eyebrow'] ?? '' ); ?>">
            </p>
            <p>
                <label><strong>Заголовок</strong></label>
                <input class="widefat" type="text" name="awards[title]" value="<?php echo esc_attr( $value['title'] ?? '' ); ?>">
                <span class="description">Для выделения части заголовка можно использовать тег &lt;em&gt;.</span>
            </p>
        </div>

        <div data-kp-repeater data-kp-repeater-name="awards[items]" data-kp-next="<?php echo esc_attr( count( $items ) ); ?>">
            <div class="kp-repeater__head">
                <strong>Награды</strong>
                <button type="button" class="button" data-kp-repeater-add>Добавить награду</button>
            </div>
            <div data-kp-repeater-list>
                <?php foreach ( $items as $index => $item ) : ?>
                    <?php karpovka_award_item_fields( is_array( $item ) ? $item : array(), $index ); ?>
                <?php endforeach; ?>
            </div>
            <template data-kp-repeater-template><?php karpovka_award_item_fields( array(), '__INDEX__' ); ?></template>
        </div>
    </div>
    <?php
}

add_action( 'save_post_page', function ( $post_id ) {
    if ( ! isset( $_POST['awards_section_nonce_field'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['awards_section_nonce_field'] ) ), 'awards_section_nonce' ) ) {
        return;
    }
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

    $raw   = isset( $_POST['awards'] ) && is_array( $_POST['awards'] ) ? wp_unslash( $_POST['awards'] ) : array();
    $items = array();

    foreach ( isset( $raw['items'] ) && is_array( $raw['items'] ) ? $raw['items'] : array() as $item ) {
        if ( ! is_array( $item ) ) {
            continue;
        }

        $award_title = sanitize_text_field( $item['award_title'] ?? '' );
        $logo        = esc_url_raw( $item['logo'] ?? '' );
        $iframe_src  = esc_url_raw( $item['iframe_src'] ?? '' );
        if ( '' === $award_title && '' === $logo && '' === $iframe_src ) {
            continue;
        }

        $years = array();
        foreach ( preg_split( '/\R/', (string) ( $item['years'] ?? '' ) ) as $year ) {
            $year = sanitize_text_field( trim( $year ) );
            if ( '' !== $year ) {
                $years[] = $year;
            }
        }

        $items[] = array(
            'badge_type'   => isset( $item['badge_type'] ) && 'iframe' === $item['badge_type'] ? 'iframe' : 'image',
            'logo'         => $logo,
            'iframe_src'   => $iframe_src,
            'iframe_w'     => max( 1, absint( $item['iframe_w'] ?? 150 ) ),
            'iframe_h'     => max( 1, absint( $item['iframe_h'] ?? 50 ) ),
            'iframe_title' => sanitize_text_field( $item['iframe_title'] ?? '' ),
            'source'       => sanitize_text_field( $item['source'] ?? '' ),
            'award_title'  => $award_title,
            'desc'         => sanitize_textarea_field( $item['desc'] ?? '' ),
            'years'        => $years,
        );
    }

    update_post_meta(
        $post_id,
        'awards',
        array(
            'eyebrow' => sanitize_text_field( $raw['eyebrow'] ?? '' ),
            'title'   => wp_kses_post( $raw['title'] ?? '' ),
            'items'   => $items,
        )
    );
} );
