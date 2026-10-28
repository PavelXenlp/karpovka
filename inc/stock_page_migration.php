<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function karpovka_stock_asset_url( $filename ) {
    return trailingslashit( get_template_directory_uri() ) . 'assets/img/imgs/' . ltrim( $filename, '/' );
}

function karpovka_stock_card( $image, $badge, $title, $desc, $conditions, $hot = '', $link = '#cta' ) {
    return [
        'image' => karpovka_stock_asset_url( $image ),
        'badge_text' => $badge,
        'badge_hot' => $hot,
        'title' => $title,
        'desc' => $desc,
        'link' => $link,
        'conditions' => $conditions,
    ];
}

function karpovka_stock_page_migration_data() {
    return [
        'hero_eyebrow' => 'Выгодные предложения',
        'hero_title' => 'Акции и',
        'hero_gold' => 'спецпредложения',
        'hero_subtitle' => 'Сезонные скидки, пакеты и программа лояльности — всё в одном месте для максимально выгодного отдыха',
        'hero_photo' => karpovka_stock_asset_url( '1.jpg_1.jpeg' ),
        'hero_btn1_text' => 'Смотреть предложения',
        'hero_btn1_link' => '#current',
        'hero_btn2_text' => 'Задать вопрос',
        'hero_phone' => '+79850200203',
        'hero_email' => 'karpovka59ru@yandex.ru',
        'hero_vk' => 'https://vk.com/baza_otdyha_spa_perm',
        'hero_tg' => 'https://t.me/karpovka59',

        'offers_sections' => [
            [
                'section_id' => 'current',
                'section_class' => 'c-offers-current',
                'eyebrow' => 'Сезонное предложение',
                'title_before' => 'Актуальные',
                'title_em' => 'акции',
                'sub' => 'Карточки со сроком действия и условиями — выберите подходящее предложение',
                'cards' => [
                    karpovka_stock_card(
                        '1.jpg_1.jpeg',
                        'Скидка 20%',
                        'Будни в тишине',
                        'Скидка 20% на проживание в эко-домах с понедельника по четверг. Тишина соснового леса, отсутствие толп и полный релакс на территории отеля.',
                        [
                            [ 'icon' => 'fa-regular fa-calendar', 'text' => 'Пн — Чт' ],
                            [ 'icon' => 'fa-regular fa-clock', 'text' => '2+ ночи' ],
                            [ 'icon' => 'fa-solid fa-users', 'text' => 'Эко-дома' ],
                        ]
                    ),
                    karpovka_stock_card(
                        'tempImagelyuBrS.jpg.jpeg',
                        'Пакет',
                        'SPA-день для двоих',
                        'Полный день восстановления: массаж для двоих, доступ к бассейну и хаммаму, обед в кафе. Комплексная цена ниже на 15% стоимости отдельных услуг.',
                        [
                            [ 'icon' => 'fa-regular fa-calendar', 'text' => 'Круглый год' ],
                            [ 'icon' => 'fa-regular fa-clock', 'text' => '6 часов' ],
                            [ 'icon' => 'fa-solid fa-heart', 'text' => 'Для двоих' ],
                        ]
                    ),
                    karpovka_stock_card(
                        'IMG_0119-2.jpg.jpeg',
                        'Хит сезона',
                        'Семейные каникулы',
                        'Проживание, питание и детские активности — всё включено. Дети до 6 лет бесплатно. Аниматор, квесты и мастер-классы каждый день.',
                        [
                            [ 'icon' => 'fa-regular fa-calendar', 'text' => 'Июнь — Август' ],
                            [ 'icon' => 'fa-solid fa-moon', 'text' => '3+ ночи' ],
                            [ 'icon' => 'fa-solid fa-child', 'text' => 'Семьям' ],
                        ],
                        '1'
                    ),
                ],
            ],
            [
                'section_id' => 'spa',
                'section_class' => 'c-offers-spa',
                'eyebrow' => 'Процедуры и пакеты',
                'title_before' => 'SPA-',
                'title_em' => 'предложения',
                'sub' => 'Сезонные программы и комбинированные пакеты для полного восстановления',
                'cards' => [
                    karpovka_stock_card(
                        'tempImagewZbK0r.jpg.jpeg',
                        '−15%',
                        'Для двоих',
                        'Парный массаж, доступ к хаммам и бассейну, фруктовый чай и шоколад. Идеальный подарок для пары на любой повод.',
                        [
                            [ 'icon' => 'fa-regular fa-calendar', 'text' => 'Круглый год' ],
                            [ 'icon' => 'fa-regular fa-clock', 'text' => '3 часа' ],
                            [ 'icon' => 'fa-solid fa-heart', 'text' => 'Для двоих' ],
                        ]
                    ),
                    karpovka_stock_card(
                        'matthew-mcbrayer-mO-.jpg.jpeg',
                        'Пакет дня',
                        'День восстановления',
                        'Полная программа: лимфодренажный массаж, обёртывание, соляная комната, бассейн и лёгкий обед. Фокус на детокс и расслабление.',
                        [
                            [ 'icon' => 'fa-regular fa-calendar', 'text' => 'Пн — Пт' ],
                            [ 'icon' => 'fa-regular fa-clock', 'text' => '6 часов' ],
                            [ 'icon' => 'fa-solid fa-user', 'text' => '1 гость' ],
                        ]
                    ),
                    karpovka_stock_card(
                        'IMG_9846.JPG.jpeg',
                        'Новое',
                        'Комплексный уход',
                        'Стоун-массаж + фитообёртывание + уход за лицом. Полный цикл заботы о теле в один визит с хорошим дисконтом от суммарной стоимости.',
                        [
                            [ 'icon' => 'fa-regular fa-calendar', 'text' => 'Круглый год' ],
                            [ 'icon' => 'fa-regular fa-clock', 'text' => '4 часа' ],
                            [ 'icon' => 'fa-solid fa-user', 'text' => '1 гость' ],
                        ]
                    ),
                ],
            ],
        ],

        'offers_loyalty' => [
            'eyebrow' => 'Бонусы для гостей',
            'title' => 'Программа <span class="gold-header"><em>лояльности</em></span>',
            'subtitle' => 'Как начисляются и используются бонусы за каждый визит в Карповку',
            'section_id' => 'loyalty',
            'lead' => 'Каждый визит приносит бонусы, которые можно обменять на бесплатную ночь, SPA-процедуру или скидку на размещение. Чем чаще приезжаете — тем больше выгод.',
            'cash_num' => '5%',
            'cash_bold' => 'кэшбэк',
            'cash_small' => 'от каждого оплаченного проживания',
            'items' => [
                [ 'num' => '01', 'title' => 'Начисление бонусов', 'desc' => '5% от суммы каждого проживания зачисляется на ваш бонусный счёт. Бонусы действуют 12 месяцев с момента начисления.' ],
                [ 'num' => '02', 'title' => 'Накопительные уровни', 'desc' => 'От 3 визитов — уровень «Знакомый» (+2% кэшбэк). От 10 визитов — «Свой» (+5%). От 20 визитов — «Ветеран» (+8% и персональные предложения).' ],
                [ 'num' => '03', 'title' => 'Как потратить бонусы', 'desc' => 'Оплатить до 30% стоимости проживания, получить бесплатную SPA-процедуру или обед в кафе. Минимальный остаток для оплаты — 500 бонусов.' ],
                [ 'num' => '04', 'title' => 'Совместимость с акциями', 'desc' => 'Бонусы начисляются даже при использовании акций и спецпредложений. Две скидки не суммируются — действует максимальная из них.' ],
            ],
        ],

        'acc_offers' => [
            'eyebrow' => 'Пакеты размещения',
            'title_before' => 'Предложения для',
            'title_em' => 'проживания',
            'sub' => 'Выгодные условия для длинных выходных, раннего бронирования и семейного отдыха',
            'cards' => [
                karpovka_stock_card(
                    'photo.jpg_2.jpeg',
                    '−10%',
                    'Длинные выходные',
                    'Скидка 10% при бронировании от 3 ночей подряд. Действует на все типы размещения: эко-дома, гостиничные номера и VIP-дома.',
                    [
                        [ 'icon' => 'fa-solid fa-moon', 'text' => '3+ ночи' ],
                        [ 'icon' => 'fa-regular fa-calendar', 'text' => 'Круглый год' ],
                        [ 'icon' => 'fa-solid fa-house', 'text' => 'Все типы' ],
                    ]
                ),
                karpovka_stock_card(
                    '1.jpg_1.jpeg',
                    '−15%',
                    'Раннее бронирование',
                    'Забронируйте за 30 и более дней до заезда и получите скидку 15%. Идеально для планирования летнего отпуска и новогодних праздников.',
                    [
                        [ 'icon' => 'fa-solid fa-clock', 'text' => 'За 30 дней' ],
                        [ 'icon' => 'fa-regular fa-calendar', 'text' => 'Любой сезон' ],
                        [ 'icon' => 'fa-solid fa-house', 'text' => 'Все типы' ],
                    ]
                ),
                karpovka_stock_card(
                    'IMG_0119-2.jpg.jpeg',
                    'Хит',
                    'Семейный отдых',
                    'Дети до 6 лет — бесплатно. При бронировании от 2 ночей — комплиментарный завтрак для всей семьи и бесплатная аниматорская программа.',
                    [
                        [ 'icon' => 'fa-solid fa-child', 'text' => 'Дети ≤6 лет' ],
                        [ 'icon' => 'fa-solid fa-moon', 'text' => '2+ ночи' ],
                        [ 'icon' => 'fa-solid fa-utensils', 'text' => '+ Завтрак' ],
                    ],
                    '1'
                ),
            ],
        ],

        'spa_reviews_layout' => 'spa_featured',
        'spa_reviews_eyebrow' => 'Отзывы',
        'spa_reviews_title' => 'Отзывы об',
        'spa_reviews_gold' => 'акциях и предложениях',
        'spa_reviews_subtitle' => '',
        'spa_reviews_items' => [
            [ 'stars' => 5, 'text' => 'Потрясающий SPA-день на двоих! Массаж был просто невероятным, а хаммам после бассейна — лучшее завершение. Обязательно вернёмся снова и снова. Отдельное спасибо мастерам за внимание к деталям и индивидуальный подход.', 'name' => 'Анна К.', 'date' => 'Март 2025' ],
            [ 'stars' => 5, 'text' => 'Приезжаем каждый месяц за лимфодренажным массажем. Результат виден после трёх сеансов.', 'name' => 'Елена М.', 'date' => 'Февраль 2025' ],
            [ 'stars' => 5, 'text' => 'Программа «День восстановления» — лучший подарок для мамы. Весь день — одно удовольствие.', 'name' => 'Ольга П.', 'date' => 'Январь 2025' ],
        ],

        'spa_articles_layout' => 'spa_cards',
        'spa_articles_eyebrow' => 'Полезное',
        'spa_articles_title' => 'Сезонные',
        'spa_articles_gold' => 'идеи',
        'spa_articles_id' => 'articles',
        'spa_articles_feat_img' => karpovka_stock_asset_url( 'IMG_0119-2.jpg.jpeg' ),
        'spa_articles_feat_tag' => 'Советы',
        'spa_articles_feat_title' => 'Как сэкономить на загородном отдыхе',
        'spa_articles_feat_text' => '5 проверенных способов: будние, раннее бронирование, лояльность, семейные тарифы и сезонные акции',
        'spa_articles_feat_link' => '#',
        'spa_articles_feat_time' => '6 мин',
        'spa_articles_rows' => [
            [ 'img' => karpovka_stock_asset_url( 'IMG_9846.JPG.jpeg' ), 'tag' => 'Календарь', 'title' => 'Когда выгоднее всего ехать в Карповку', 'text' => 'Разбираем цены по сезонам: когда дешевле, когда тише, а когда — самые интересные мероприятия', 'link' => '#', 'time' => '5 мин' ],
            [ 'img' => karpovka_stock_asset_url( 'tempImagewZbK0r.jpg.jpeg' ), 'tag' => 'Подарки', 'title' => 'Подарочный сертификат: полный гид', 'text' => 'Как оформить, какие форматы есть и как получателю активировать сертификат на опыте Карповки', 'link' => '#', 'time' => '4 мин' ],
        ],

        'spa_faq' => [
            'eyebrow' => 'Вопросы и ответы',
            'title' => 'Часто задаваемые',
            'gold_text' => 'вопросы',
            'subtitle' => 'Сроки, ограничения и совместимость акций',
            'bg_image' => karpovka_stock_asset_url( 'photo.jpg_2.jpeg' ),
            'section_id' => 'faq',
            'btn_text' => 'Не нашли ответ? Свяжитесь с нами',
            'btn_link' => '/contacts/',
            'items' => [
                [ 'question' => 'Можно ли комбинировать несколько акций?', 'answer' => 'Нет, акции не суммируются. При бронировании действует одна акция — та, которая даёт максимальную выгоду. Программа лояльности начисляется поверх любой акции.' ],
                [ 'question' => 'Как долго действуют акции?', 'answer' => 'Срок действия указан на каждой карточке. Акции с пометкой «Сезонное предложение» обновляются ежемесячно. Подписывайтесь на Telegram, чтобы не пропустить новые.' ],
                [ 'question' => 'Бонусы программы лояльности сгорают?', 'answer' => 'Бонусы действуют 12 месяцев с момента начисления. За 30 дней до сгорания мы отправляем напоминание на указанный email или в Telegram.' ],
                [ 'question' => 'Как забронировать акцию?', 'answer' => 'Позвоните нам или напишите в мессенджер — укажите название акции и желаемые даты. Бронирование подтверждается после предоплаты 30% от стоимости.' ],
                [ 'question' => 'Распространяются ли акции на дни высокого спроса?', 'answer' => 'Скидки на проживание не распространяются на период с 31 декабря по 8 января и на майские праздники. SPA-пакеты действуют круглогодично без исключений.' ],
            ],
        ],

        'cta_review' => [
            'eyebrow' => 'Подобрать предложение',
            'title' => 'Оставьте заявку на',
            'gold_text' => 'акцию',
            'text' => 'Наш администратор свяжется с вами в течение дня — расскажет об актуальных предложениях и подберёт оптимальный вариант',
            'phone' => 'tel:+79850200203',
            'btn_text' => 'Связаться с нами',
            'btn2_text' => 'Оставить заявку',
            'section_id' => 'cta',
        ],
    ];
}

function karpovka_migrate_stock_page_data( $post_id ) {
    if ( get_post_meta( $post_id, '_karpovka_stock_page_migrated_20260901', true ) ) {
        return false;
    }

    foreach ( karpovka_stock_page_migration_data() as $key => $value ) {
        update_post_meta( $post_id, $key, $value );
    }

    update_post_meta( $post_id, '_karpovka_section_order', [ 'hero', 'current', 'loyalty', 'spa_offers', 'accommodation_offers', 'reviews', 'articles', 'faq', 'cta' ] );
    update_post_meta( $post_id, '_karpovka_hidden_sections', [] );
    update_post_meta( $post_id, '_karpovka_stock_page_migrated_20260901', '1' );

    return true;
}

add_action( 'admin_init', function () {
    $pages = get_posts( [
        'post_type' => 'page',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_key' => '_wp_page_template',
        'meta_value' => 'stock.php',
    ] );

    foreach ( $pages as $post_id ) {
        karpovka_migrate_stock_page_data( (int) $post_id );
    }
} );
