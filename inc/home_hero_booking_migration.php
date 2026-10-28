<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function karpovka_migrate_home_hero_booking_button( $post_id ) {
    if ( get_post_meta( $post_id, '_karpovka_home_hero_booking_migrated_20260901', true ) ) {
        return false;
    }

    $hero = get_post_meta( $post_id, 'hero_main', true );
    if ( ! is_array( $hero ) || empty( $hero['buttons'] ) || ! is_array( $hero['buttons'] ) ) {
        update_post_meta( $post_id, '_karpovka_home_hero_booking_migrated_20260901', '1' );
        return false;
    }

    $previous = [];
    $changed = false;

    foreach ( $hero['buttons'] as $index => $button ) {
        $text = trim( (string) ( $button['text'] ?? '' ) );
        if ( 'Забронировать проживание' !== $text ) {
            continue;
        }

        $previous[] = [
            'index' => $index,
            'link' => (string) ( $button['link'] ?? '' ),
        ];
        $hero['buttons'][ $index ]['link'] = '#booking-modal';
        $changed = true;
    }

    if ( $changed ) {
        update_post_meta( $post_id, '_karpovka_home_hero_booking_prev_links_20260901', $previous );
        update_post_meta( $post_id, 'hero_main', $hero );
    }

    update_post_meta( $post_id, '_karpovka_home_hero_booking_migrated_20260901', '1' );
    return $changed;
}

function karpovka_home_hero_booking_modal_defaults() {
    return [
        'm_badge' => 'Заявка',
        'm_title' => 'Забронировать проживание',
        'm_subtitle' => 'Оставьте контакты, даты и пожелания — администратор свяжется с вами для подтверждения бронирования',
        'm_name_l' => 'Имя *',
        'm_name_ph' => 'Ваше имя',
        'm_phone_l' => 'Телефон *',
        'm_phone_ph' => '+7 (___) ___-__-__',
        'm_email_l' => 'E-mail',
        'm_email_ph' => 'email@example.com',
        'm_date_l' => 'Желаемые даты',
        'm_date_ph' => 'Например: 15-18 августа',
        'm_guests_l' => 'Количество гостей',
        'm_guests_ph' => 'Например: 2 взрослых, 1 ребёнок',
        'm_msg_l' => 'Комментарий',
        'm_msg_ph' => 'Пожелания, вопросы, предпочтения...',
        'm_req_note' => '* Поля, отмеченные звёздочкой, обязательны для заполнения',
        'm_submit' => 'Отправить заявку',
        'm_consent' => 'Нажимая кнопку, вы соглашаетесь с',
        'm_consent_lnk' => 'политикой конфиденциальности',
        'm_consent_url' => '#',
        'm_ok_title' => 'Заявка отправлена',
        'm_ok_text' => 'Мы свяжемся с вами в ближайшее время для подтверждения бронирования',
    ];
}

function karpovka_migrate_home_hero_booking_modal( $post_id ) {
    if ( get_post_meta( $post_id, '_karpovka_home_hero_booking_modal_migrated_20260901', true ) ) {
        return false;
    }

    $hero = get_post_meta( $post_id, 'hero_main', true );
    if ( ! is_array( $hero ) ) {
        update_post_meta( $post_id, '_karpovka_home_hero_booking_modal_migrated_20260901', '1' );
        return false;
    }

    $changed = false;
    foreach ( karpovka_home_hero_booking_modal_defaults() as $key => $value ) {
        if ( empty( $hero[ $key ] ) ) {
            $hero[ $key ] = $value;
            $changed = true;
        }
    }

    if ( $changed ) {
        update_post_meta( $post_id, 'hero_main', $hero );
    }

    update_post_meta( $post_id, '_karpovka_home_hero_booking_modal_migrated_20260901', '1' );
    return $changed;
}

add_action( 'admin_init', function () {
    $pages = get_posts( [
        'post_type' => 'page',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_key' => '_wp_page_template',
        'meta_value' => 'index.php',
    ] );

    foreach ( $pages as $post_id ) {
        karpovka_migrate_home_hero_booking_button( (int) $post_id );
        karpovka_migrate_home_hero_booking_modal( (int) $post_id );
    }
} );
