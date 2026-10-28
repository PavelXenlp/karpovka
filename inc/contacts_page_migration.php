<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function karpovka_contacts_asset_url( $filename ) {
    return trailingslashit( get_template_directory_uri() ) . 'assets/img/imgs/' . ltrim( $filename, '/' );
}

function karpovka_contacts_page_migration_data() {
    $asset = static function ( $filename ) {
        return karpovka_contacts_asset_url( $filename );
    };

    return [
        'hero_eyebrow' => 'Свяжитесь с нами',
        'hero_title' => 'Как нас найти',
        'hero_gold' => '',
        'hero_subtitle' => 'Мы всегда на связи — звоните, пишите, приезжайте. Парк-отель «Карповка» расположен в Карагайском районе на берегу пруда',
        'hero_photo' => $asset( '1.jpg_1.jpeg' ),
        'hero_btn1_text' => 'Контактные данные',
        'hero_btn1_link' => '#contacts',
        'hero_btn2_text' => 'Как добраться',
        'hero_phone' => '+79127813933',
        'hero_email' => 'karpovka59ru@yandex.ru',
        'hero_vk' => 'https://vk.com/baza_otdyha_spa_perm',
        'hero_tg' => 'https://t.me/karpovka59',

        'contacts_section' => [
            'eyebrow' => 'Свяжитесь с нами',
            'title' => 'Контакты',
            'section_id' => 'contacts',
            'address' => 'Пермский край, Карагайский район, деревня Карповка',
            'phones' => [
                [ 'label' => '+7 (985) 020-02-03', 'href' => 'tel:+79850200203' ],
                [ 'label' => '+7 (912) 781-39-33', 'href' => 'tel:+79127813933' ],
            ],
            'email' => 'karpovka59@yandex.ru',
            'form_title' => 'Остались вопросы?',
            'form_sub' => 'Оставьте контакты — перезвоним и поможем с бронированием.',
            'form_btn' => 'Перезвоните мне',
        ],

        'directions_section' => [
            'eyebrow' => 'Маршрут',
            'title' => 'Как добраться',
            'subtitle' => 'До Карповки — чуть больше часа по живописной трассе',
            'section_id' => 'directions',
            'side_image' => $asset( '1.jpg_1.jpeg' ),
            'primary' => [
                'icon' => '#i-car',
                'title' => 'На автомобиле',
                'subtitle' => 'Основной и самый удобный способ',
                'lead' => 'Ехать по трассе Р-342 в направлении Ильинского / Кунгура. Дорога асфальтирована на всём протяжении до поворота на Карповку.',
                'tag' => 'Навигатор находит парк-отель по названию',
                'list' => [
                    [ 'text' => 'Выехать на трассу <strong>Р-342</strong> в Кунгурском направлении' ],
                    [ 'text' => 'Проехать через посёлок Ильинский' ],
                    [ 'text' => 'Далее двигаться по указателю на Карагай' ],
                    [ 'text' => 'В деревне Карповка повернуть по указателю «Парк-отель Карповка»' ],
                ],
            ],
            'steps' => [
                [
                    'num' => '02',
                    'icon' => '#i-signpost',
                    'title' => 'Ориентиры',
                    'subtitle' => 'Навигатор найдёт нас',
                    'list' => [
                        [ 'text' => 'Въезд в д. Карповка — придорожный указатель' ],
                        [ 'text' => 'После въезда повернуть на грунтовую дорогу <strong>вправо</strong>' ],
                        [ 'text' => 'Ещё немного по лесной дороге' ],
                        [ 'text' => 'У въезда — информационный щит и шлагбаум' ],
                    ],
                ],
                [
                    'num' => '03',
                    'icon' => '#i-parking',
                    'title' => 'Парковка',
                    'subtitle' => 'Бесплатно и безопасно',
                    'list' => [
                        [ 'text' => 'Бесплатная парковка для всех гостей' ],
                        [ 'text' => 'Охраняемая территория <strong>кругосуточно</strong>' ],
                        [ 'text' => 'Парковка у главного корпуса и у эко-домов' ],
                        [ 'text' => 'Крупногабаритный транспорт по согласованию' ],
                    ],
                ],
            ],
        ],

        'requisites_section' => [
            'eyebrow' => 'Информация',
            'title' => 'Реквизиты',
            'section_id' => 'requisites',
            'groups' => [
                [
                    'group_title' => 'Организация',
                    'rows' => [
                        [ 'key' => 'Название', 'value' => 'Парк-отель «Карповка»', 'is_accent' => 0, 'is_sub' => 0 ],
                        [ 'key' => 'Адрес', 'value' => 'Пермский край, Карагайский район, д. Карповка', 'is_accent' => 0, 'is_sub' => 0 ],
                        [ 'key' => 'Телефон', 'value' => '<a href="tel:+79850200203">+7 (985) 020-02-03</a><br><a href="tel:+79127813933">+7 (912) 781-39-33</a>', 'is_accent' => 0, 'is_sub' => 0 ],
                        [ 'key' => 'Email', 'value' => '<a href="mailto:karpovka59@yandex.ru">karpovka59@yandex.ru</a>', 'is_accent' => 0, 'is_sub' => 0 ],
                    ],
                ],
                [
                    'group_title' => 'Режим работы',
                    'rows' => [
                        [ 'key' => 'Администратор', 'value' => 'Кругосуточно', 'is_accent' => 1, 'is_sub' => 0 ],
                        [ 'key' => 'SPA-центр', 'value' => 'Ежедневно 09:00 – 22:00', 'is_accent' => 0, 'is_sub' => 0 ],
                        [ 'key' => 'Кафе', 'value' => 'ПН–ЧТ 08:00 – 20:30', 'is_accent' => 0, 'is_sub' => 0 ],
                        [ 'key' => '', 'value' => 'ПТ–СБ 08:00 – 21:30', 'is_accent' => 0, 'is_sub' => 1 ],
                    ],
                ],
            ],
        ],

        'feedback_form' => [
            'label' => 'Напишите нам',
            'title' => 'Остались <em>вопросы</em>?',
            'desc' => 'Оставьте заявку — мы перезвоним и поможем с бронированием, выбором номера или организацией мероприятия',
            'contacts' => [
                [ 'href' => 'tel:+79850200203', 'text' => '+7 (985) 020-02-03' ],
                [ 'href' => 'tel:+79127813933', 'text' => '+7 (912) 781-39-33' ],
                [ 'href' => 'mailto:karpovka59@yandex.ru', 'text' => 'karpovka59@yandex.ru' ],
            ],
            'name_label' => 'Ваше имя',
            'name_ph' => 'Как к вам обращаться',
            'phone_label' => 'Телефон',
            'phone_ph' => '+7 (___) ___-__-__',
            'email_label' => 'E-mail',
            'email_opt' => 'необязательно',
            'email_ph' => 'your@email.com',
            'topic_label' => 'Тема обращения',
            'topic_opts' => [
                [ 'value' => 'booking', 'text' => 'Бронирование проживания' ],
                [ 'value' => 'spa', 'text' => 'SPA-услуги и баня' ],
                [ 'value' => 'event', 'text' => 'Организация мероприятия' ],
                [ 'value' => 'cert', 'text' => 'Подарочный сертификат' ],
                [ 'value' => 'other', 'text' => 'Другой вопрос' ],
            ],
            'msg_label' => 'Сообщение',
            'msg_ph' => 'Расскажите, чем можем помочь...',
            'submit_text' => 'Отправить заявку',
            'priv_text' => 'Нажимая кнопку, вы соглашаетесь с',
            'priv_link' => 'политикой конфиденциальности',
            'priv_url' => '/privacy-policy/',
            'ok_title' => 'Заявка отправлена',
            'ok_text' => 'Мы свяжемся с вами в ближайшее время',
            'section_id' => 'feedback',
        ],
    ];
}

function karpovka_migrate_contacts_page_data( $post_id ) {
    if ( get_post_meta( $post_id, '_karpovka_contacts_page_migrated_20260901', true ) ) return false;

    foreach ( karpovka_contacts_page_migration_data() as $key => $value ) {
        update_post_meta( $post_id, $key, $value );
    }

    update_post_meta( $post_id, '_karpovka_section_order', [ 'hero', 'contacts', 'directions', 'requisites', 'feedback' ] );
    update_post_meta( $post_id, '_karpovka_hidden_sections', [] );
    update_post_meta( $post_id, '_karpovka_contacts_page_migrated_20260901', '1' );
    return true;
}

add_action( 'admin_init', function () {
    $pages = get_posts( [
        'post_type' => 'page',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'meta_key' => '_wp_page_template',
        'meta_value' => 'contacts.php',
        'fields' => 'ids',
    ] );
    foreach ( $pages as $page_id ) karpovka_migrate_contacts_page_data( (int) $page_id );
} );
