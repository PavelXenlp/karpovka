<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function karpovka_recreation_asset_url( $filename ) {
    return trailingslashit( get_template_directory_uri() ) . 'assets/img/imgs/' . ltrim( $filename, '/' );
}

function karpovka_recreation_page_migration_data() {
    $asset = static function ( $filename ) {
        return karpovka_recreation_asset_url( $filename );
    };

    return [
        'hero_eyebrow' => 'Природа в каждом сезоне',
        'hero_title' => 'Активный',
        'hero_gold' => 'отдых',
        'hero_subtitle' => 'Рыбалка на рассвете, велопрогулки по лесным тропам, лыжи зимой и катамараны летом — более 10 видов досуга на территории отеля в сосновом лесу',
        'hero_photo' => $asset( '1.jpg_1.jpeg' ),
        'hero_btn1_text' => 'Выбрать активность',
        'hero_btn1_link' => '#seasons',
        'hero_btn2_text' => 'Задать вопрос',
        'hero_phone' => '+79127813933',
        'hero_email' => 'karpovka59ru@yandex.ru',
        'hero_vk' => 'https://vk.com/baza_otdyha_spa_perm',
        'hero_tg' => 'https://t.me/karpovka59',

        'rec_seasons_eyebrow' => 'Когда гостите',
        'rec_seasons_title_before' => 'Выберите',
        'rec_seasons_title_em' => 'сезон',
        'rec_seasons_sub' => 'Разные активности — для тёплых дней, зимних вечеров и любого времени года',
        'rec_seasons_chips' => [
            [ 'text' => 'Все активности', 'value' => 'all', 'active' => '1' ],
            [ 'text' => 'Лето и осень', 'value' => 'summer', 'active' => '' ],
            [ 'text' => 'Зима', 'value' => 'winter', 'active' => '' ],
            [ 'text' => 'Круглый год', 'value' => 'allyear', 'active' => '' ],
        ],

        'rec_groups' => [
            [
                'group_key' => 'summer',
                'section_id' => 'summer',
                'bg_image' => $asset( '1.jpg_1.jpeg' ),
                'eyebrow' => 'Тёплые дни',
                'title_before' => 'Лето и',
                'title_em' => 'осень',
                'sub' => 'Лучшее время для воды, леса и открытого воздуха',
                'cards' => [
                    [
                        'image' => $asset( '1.jpg_1.jpeg' ),
                        'tag_icon' => 'fa-solid fa-sun',
                        'tag_text' => 'Май — Октябрь',
                        'title' => 'Рыбалка',
                        'desc' => 'Карпов пруд богат карасём, щукой и окунём. Удочки и снасти выдаём на месте, для опытных рыбаков — лодка и эхолот.',
                        'meta1_icon' => 'fa-regular fa-clock',
                        'meta1_text' => 'от 2 часов',
                        'meta2_icon' => 'fa-solid fa-child',
                        'meta2_text' => 'от 6 лет',
                        'link' => 'modal-act-fishing',
                    ],
                    [
                        'image' => $asset( 'tempImagelyuBrS.jpg.jpeg' ),
                        'tag_icon' => 'fa-solid fa-sun',
                        'tag_text' => 'Апрель — Октябрь',
                        'title' => 'Велопрогулки',
                        'desc' => 'Прокат велосипедов для взрослых и детей. Маршруты от лёгких вокруг пруда до лесных через сосновый бор.',
                        'meta1_icon' => 'fa-regular fa-clock',
                        'meta1_text' => 'от 1 часа',
                        'meta2_icon' => 'fa-solid fa-child',
                        'meta2_text' => 'от 8 лет',
                        'link' => 'modal-act-bikes',
                    ],
                    [
                        'image' => $asset( 'IMG_0119-2.jpg.jpeg' ),
                        'tag_icon' => 'fa-solid fa-sun',
                        'tag_text' => 'Июнь — Сентябрь',
                        'title' => 'Катамараны',
                        'desc' => 'Двух- и четырёхместные катамараны для прогулок по Карпову пруду. Спокойная вода и живописные берега.',
                        'meta1_icon' => 'fa-regular fa-clock',
                        'meta1_text' => 'от 30 минут',
                        'meta2_icon' => 'fa-solid fa-child',
                        'meta2_text' => 'от 5 лет',
                        'link' => 'modal-act-catamarans',
                    ],
                    [
                        'image' => $asset( 'matthew-mcbrayer-mO-.jpg.jpeg' ),
                        'tag_icon' => 'fa-solid fa-sun',
                        'tag_text' => 'Май — Октябрь',
                        'title' => 'Спортивные игры',
                        'desc' => 'Площадки для волейбола, бадминтона и настольного тенниса. Мячи и ракетки выдаются на ресепшене бесплатно.',
                        'meta1_icon' => 'fa-solid fa-check',
                        'meta1_text' => 'Бесплатно',
                        'meta2_icon' => 'fa-solid fa-child',
                        'meta2_text' => 'Для всех',
                        'link' => 'modal-act-sport',
                    ],
                ],
            ],
            [
                'group_key' => 'winter',
                'section_id' => 'winter',
                'bg_image' => $asset( 'IMG_0119-2.jpg.jpeg' ),
                'eyebrow' => 'Снежные дни',
                'title_before' => '',
                'title_em' => 'Зима',
                'sub' => 'Лыжи, тюбинг и снежные прогулки в заснеженном сосновом лесу',
                'cards' => [
                    [
                        'image' => $asset( '1.jpg_1.jpeg' ),
                        'tag_icon' => 'fa-solid fa-snowflake',
                        'tag_text' => 'Декабрь — Март',
                        'title' => 'Лыжи',
                        'desc' => 'Прокат лыж и палок для взрослых и детей. Лыжная трасса протяжённостью 3 км проходит через сосновый бор вокруг пруда.',
                        'meta1_icon' => 'fa-regular fa-clock',
                        'meta1_text' => 'от 1 часа',
                        'meta2_icon' => 'fa-solid fa-child',
                        'meta2_text' => 'от 6 лет',
                        'link' => 'modal-act-ski',
                    ],
                    [
                        'image' => $asset( 'tempImagelyuBrS.jpg.jpeg' ),
                        'tag_icon' => 'fa-solid fa-snowflake',
                        'tag_text' => 'Декабрь — Март',
                        'title' => 'Тюбинг',
                        'desc' => 'Зимняя горка для тюбинга рядом с главным корпусом. Воздушные ватрушки выдаются на месте.',
                        'meta1_icon' => 'fa-regular fa-clock',
                        'meta1_text' => 'без ограничений',
                        'meta2_icon' => 'fa-solid fa-child',
                        'meta2_text' => 'от 3 лет',
                        'link' => 'modal-act-tubing',
                    ],
                    [
                        'image' => $asset( 'IMG_0119-2.jpg.jpeg' ),
                        'tag_icon' => 'fa-solid fa-snowflake',
                        'tag_text' => 'Круглый год',
                        'title' => 'Зимние прогулки',
                        'desc' => 'Протоптанные тропы через зимний лес к обзорным точкам. Прокат снегоступов для нетронутого снега за территорией отеля.',
                        'meta1_icon' => 'fa-solid fa-check',
                        'meta1_text' => 'Бесплатно',
                        'meta2_icon' => 'fa-solid fa-child',
                        'meta2_text' => 'Для всех',
                        'link' => 'modal-act-winter-walks',
                    ],
                ],
            ],
            [
                'group_key' => 'allyear',
                'section_id' => 'allyear',
                'bg_image' => $asset( '1.jpg_1.jpeg' ),
                'eyebrow' => 'Без привязки к сезону',
                'title_before' => 'Круглый',
                'title_em' => 'год',
                'sub' => 'Развлечения, которые доступны в любое время года',
                'cards' => [
                    [
                        'image' => $asset( 'photo.jpg_2.jpeg' ),
                        'tag_icon' => 'fa-solid fa-calendar',
                        'tag_text' => 'Всегда',
                        'title' => 'Прогулки по территории',
                        'desc' => 'Обустроенные тропы, берёзовая аллея, сосновый бор, набережная Карпова пруда и обзорная площадка.',
                        'meta1_icon' => 'fa-solid fa-check',
                        'meta1_text' => 'Бесплатно',
                        'meta2_icon' => 'fa-solid fa-child',
                        'meta2_text' => 'Для всех',
                        'link' => 'modal-act-walks',
                    ],
                    [
                        'image' => $asset( 'matthew-mcbrayer-mO-.jpg.jpeg' ),
                        'tag_icon' => 'fa-solid fa-calendar',
                        'tag_text' => 'Всегда',
                        'title' => 'Настольные игры',
                        'desc' => 'Библиотека из 30+ настольных игр в зоне отдыха: от классических шахмат до современных стратегий для компаний.',
                        'meta1_icon' => 'fa-solid fa-check',
                        'meta1_text' => 'Бесплатно',
                        'meta2_icon' => 'fa-solid fa-child',
                        'meta2_text' => 'от 5 лет',
                        'link' => 'modal-act-board-games',
                    ],
                    [
                        'image' => $asset( '1.jpg_1.jpeg' ),
                        'tag_icon' => 'fa-solid fa-calendar',
                        'tag_text' => 'По запросу',
                        'title' => 'Караоке',
                        'desc' => 'Караоке-система в банкетном зале. Песни на русском и английском, микрофоны и экран — отлично для вечера с друзьями.',
                        'meta1_icon' => 'fa-solid fa-check',
                        'meta1_text' => 'Бесплатно',
                        'meta2_icon' => 'fa-solid fa-child',
                        'meta2_text' => 'Для всех',
                        'link' => 'modal-act-karaoke',
                    ],
                ],
            ],
        ],

        'spa_price_eyebrow' => 'Сводная информация',
        'spa_price_title' => 'Карточки',
        'spa_price_gold' => 'активностей',
        'spa_price_subtitle' => 'Продолжительность, возраст, сезонность и стоимость',
        'spa_price_id' => 'activities-table',
        'spa_price_rows' => [
            [ 'name' => 'Рыбалка', 'desc' => 'Семьи, пары, рыбаки', 'dur' => 'Май — Октябрь', 'price' => 'Бесплатно / лодка — 500 ₽/ч' ],
            [ 'name' => 'Велопрогулки', 'desc' => 'Взрослые, дети от 8 лет', 'dur' => 'Апрель — Октябрь', 'price' => '300 ₽/ч' ],
            [ 'name' => 'Катамараны', 'desc' => 'Семьи с детьми', 'dur' => 'Июнь — Сентябрь', 'price' => '400 ₽/30 мин' ],
            [ 'name' => 'Лыжи', 'desc' => 'Взрослые, дети от 6 лет', 'dur' => 'Декабрь — Март', 'price' => '500 ₽/ч' ],
            [ 'name' => 'Тюбинг', 'desc' => 'Дети от 3 лет, взрослые', 'dur' => 'Декабрь — Март', 'price' => 'Бесплатно' ],
            [ 'name' => 'Спортивные игры', 'desc' => 'Для всех гостей', 'dur' => 'Круглый год', 'price' => 'Бесплатно' ],
            [ 'name' => 'Настольные игры', 'desc' => 'Компании, семьи', 'dur' => 'Круглый год', 'price' => 'Бесплатно' ],
        ],
        'spa_price_cards' => [],

        'dining_booking' => [
            'eyebrow' => 'Важно знать',
            'title' => 'Правила и',
            'gold_text' => 'безопасность',
            'subtitle' => 'Подготовка, ограничения и экипировка для комфортного отдыха',
            'section_id' => 'rules',
            'items' => [
                [ 'num' => '01', 'question' => 'Нужна ли своя экипировка?', 'answer' => 'Нет, экипировка выдаётся на месте: удочки для рыбалки, велосипеды и шлемы, лыжи с палками, снегоступы. Рекомендуем удобную обувь и одежду по сезону. Для водных активностей есть спасательные жилеты.' ],
                [ 'num' => '02', 'question' => 'С какого возраста можно заниматься?', 'answer' => 'Для каждой активности есть свои возрастные ограничения: рыбалка и лыжи — от 6 лет, велопрогулки — от 8 лет, тюбинг — от 3 лет. Дети до 12 лет должны быть в сопровождении взрослого на водных и зимних активностях.' ],
                [ 'num' => '03', 'question' => 'Что делать при плохой погоде?', 'answer' => 'В случае непогоды наружные активности могут быть временно приостановлены. На этот случай есть настольные игры, караоке, SPA-центр и баня. Водные активности не проводятся при грозе и сильном ветре.' ],
                [ 'num' => '04', 'question' => 'Можно ли бронировать активности заранее?', 'answer' => 'Рыбалку с лодкой и групповые мероприятия рекомендуем бронировать за сутки. Велопрокат, тюбинг и спортивный инвентарь — по запросу на ресепшене в день занятия. Лыжи в высокий сезон лучше бронировать заранее.' ],
            ],
        ],

        'spa_gallery_eyebrow' => 'Галерея',
        'spa_gallery_title' => 'Галерея',
        'spa_gallery_gold' => 'активного отдыха',
        'spa_gallery_subtitle' => 'Лесные тропы, вода, спорт и сезонные развлечения',
        'spa_gallery_id' => 'gallery',
        'spa_gallery_items' => [
            [ 'img' => $asset( '1.jpg_1.jpeg' ), 'alt' => 'Активный отдых у воды', 'tall' => '1' ],
            [ 'img' => $asset( 'tempImagelyuBrS.jpg.jpeg' ), 'alt' => 'Велопрогулки', 'tall' => '' ],
            [ 'img' => $asset( 'IMG_0119-2.jpg.jpeg' ), 'alt' => 'Катамараны и прогулки', 'tall' => '' ],
            [ 'img' => $asset( 'matthew-mcbrayer-mO-.jpg.jpeg' ), 'alt' => 'Спортивные игры', 'tall' => '' ],
            [ 'img' => $asset( 'IMG_9846.JPG.jpeg' ), 'alt' => 'Прогулки по территории', 'tall' => '1' ],
            [ 'img' => $asset( 'photo.jpg_2.jpeg' ), 'alt' => 'Лес и пруд Карповки', 'tall' => '' ],
        ],

        'spa_reviews_layout' => 'spa_featured',
        'spa_reviews_eyebrow' => 'Отзывы',
        'spa_reviews_title' => 'Отзывы об',
        'spa_reviews_gold' => 'активном отдыхе',
        'spa_reviews_subtitle' => '',
        'spa_reviews_items' => [
            [ 'stars' => 5, 'text' => 'Отлично провели выходные: утром рыбалка, днём велосипеды, вечером баня. Всё рядом, инвентарь дали на месте, детям особенно понравился тюбинг.', 'name' => 'Марина С.', 'date' => 'Июль 2025' ],
            [ 'stars' => 5, 'text' => 'Брали катамаран и гуляли вокруг пруда. Очень спокойно, красиво, без городского шума. Хорошее место, чтобы переключиться.', 'name' => 'Алексей П.', 'date' => 'Июнь 2025' ],
            [ 'stars' => 5, 'text' => 'Зимой приезжали с детьми на лыжи и прогулки. Трасса простая, безопасная, после активности удобно зайти в кафе.', 'name' => 'Ольга Н.', 'date' => 'Февраль 2025' ],
        ],

        'spa_articles_layout' => 'spa_cards',
        'spa_articles_eyebrow' => 'Полезное',
        'spa_articles_title' => 'Идеи',
        'spa_articles_gold' => 'по сезонам',
        'spa_articles_id' => 'articles',
        'spa_articles_feat_tag' => 'Летний досуг',
        'spa_articles_feat_title' => 'Что делать в Карповке летом',
        'spa_articles_feat_text' => 'Полный гид по летним активностям: от утренней рыбалки до вечерних катамаранов',
        'spa_articles_feat_link' => '',
        'spa_articles_feat_img' => $asset( 'IMG_0119-2.jpg.jpeg' ),
        'spa_articles_feat_time' => '7 мин',
        'spa_articles_rows' => [
            [ 'num' => '02', 'title' => 'Зимний отдых с детьми: чек-лист', 'text' => 'Как собрать детей на зимний уикенд, что брать и чем заняться на территории', 'link' => '', 'img' => $asset( 'IMG_9846.JPG.jpeg' ), 'tag' => 'Зима с детьми', 'time' => '5 мин' ],
            [ 'num' => '03', 'title' => '10 идей для семейного уикенда', 'text' => 'Программа на 2 дня: активности, еда, релакс — всё распланировано за вас', 'link' => '', 'img' => $asset( 'tempImagewZbK0r.jpg.jpeg' ), 'tag' => 'Семейный отдых', 'time' => '4 мин' ],
        ],

        'spa_faq' => [
            'eyebrow' => 'Вопросы и ответы',
            'title' => 'Часто задаваемые',
            'gold_text' => 'вопросы',
            'subtitle' => 'Об активностях, экипировке и безопасности',
            'bg_image' => $asset( 'photo.jpg_2.jpeg' ),
            'section_id' => 'faq',
            'btn_text' => 'Все вопросы и ответы',
            'btn_link' => '/faq/',
            'items' => [
                [ 'question' => 'Входит ли активный отдых в стоимость проживания?', 'answer' => 'Часть активностей входит бесплатно: спортивные площадки, настольные игры, прогулки по территории, тюбинг. Прокат велосипедов, лодки и лыж оплачивается отдельно. Актуальные цены указаны в таблице активностей на странице.' ],
                [ 'question' => 'Можно ли заниматься активностями, если не проживаю в отеле?', 'answer' => 'Да, территории открыты для приезжих гостей. Прогулки, кафе и SPA доступны по предварительной записи. Прокат инвентаря и водные активности доступны в часы работы отеля.' ],
                [ 'question' => 'Есть ли аниматор для детей?', 'answer' => 'По предварительному запросу мы организуем детского аниматора для групп от 5 детей. Аниматор проводит подвижные игры, квесты по территории и творческие мастер-классы.' ],
                [ 'question' => 'Работают ли активности в новогодние праздники?', 'answer' => 'Да, зимние активности работают в новогодние и каникулярные дни. Рекомендуем бронировать лыжи и лодку заранее, так как в этот период спрос повышен.' ],
                [ 'question' => 'Есть ли на территории медпункт?', 'answer' => 'На территории есть аптечка первой помощи. Сотрудники проходят инструктаж по оказанию первой помощи. Для водных активностей обязательно ношение спасательных жилетов.' ],
            ],
        ],

        'cta_review' => [
            'eyebrow' => 'Готовы к активному отдыху?',
            'title' => 'Спланируйте',
            'gold_text' => 'активный день',
            'text' => 'Оставьте заявку — поможем подобрать активности под ваш состав компании и время года',
            'phone' => 'tel:+79850200203',
            'btn_text' => 'Позвонить',
            'btn2_text' => 'Оставить заявку',
            'section_id' => 'cta',
        ],
    ];
}

function karpovka_migrate_recreation_page_data( $post_id ) {
    if ( get_post_meta( $post_id, '_karpovka_recreation_page_migrated_20260901', true ) ) return false;

    foreach ( karpovka_recreation_page_migration_data() as $key => $value ) {
        update_post_meta( $post_id, $key, $value );
    }

    update_post_meta( $post_id, '_karpovka_section_order', [ 'hero', 'seasons', 'activities', 'price', 'booking', 'gallery', 'reviews', 'articles', 'faq', 'cta' ] );
    update_post_meta( $post_id, '_karpovka_hidden_sections', [] );
    update_post_meta( $post_id, '_karpovka_recreation_page_migrated_20260901', '1' );
    return true;
}

add_action( 'admin_init', function () {
    $pages = get_posts( [
        'post_type' => 'page',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'meta_key' => '_wp_page_template',
        'meta_value' => 'recreation.php',
        'fields' => 'ids',
    ] );
    foreach ( $pages as $page_id ) karpovka_migrate_recreation_page_data( (int) $page_id );
} );
