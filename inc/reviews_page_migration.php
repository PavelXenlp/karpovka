<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function karpovka_reviews_asset_url( $filename ) {
    $base = 'map_no_wm.png' === $filename ? 'assets/img/' : 'assets/img/imgs/';
    return trailingslashit( get_template_directory_uri() ) . $base . ltrim( $filename, '/' );
}

function karpovka_reviews_page_migration_data() {
    $asset = static function ( $filename ) {
        return karpovka_reviews_asset_url( $filename );
    };

    return [
        'hero_eyebrow' => 'Отзывы гостей',
        'hero_title' => 'Отзывы',
        'hero_gold' => 'гостей',
        'hero_subtitle' => 'Реальные истории людей, которые уже побывали в Карповке. Честные впечатления о проживании, сервисе и атмосфере нашего парк-отеля',
        'hero_photo' => $asset( '1.jpg_1.jpeg' ),
        'hero_btn1_text' => 'Читать отзывы',
        'hero_btn1_link' => '#reviews-list',
        'hero_btn2_text' => 'Оставить свой',
        'hero_phone' => '+79127813933',
        'hero_email' => 'karpovka59ru@yandex.ru',
        'hero_vk' => 'https://vk.com/baza_otdyha_spa_perm',
        'hero_tg' => 'https://t.me/karpovka59',

        'reviews_list' => [
            'eyebrow' => 'Что говорят гости',
            'title' => 'Реальные <span class="gold-header"><em>отзывы</em></span>',
            'section_id' => 'reviews-list',
            'filters' => [
                [ 'slug' => 'all', 'text' => 'Все отзывы', 'is_active' => '1' ],
                [ 'slug' => 'stay', 'text' => 'Проживание', 'is_active' => '' ],
                [ 'slug' => 'spa', 'text' => 'СПА', 'is_active' => '' ],
                [ 'slug' => 'event', 'text' => 'Мероприятия', 'is_active' => '' ],
                [ 'slug' => 'family', 'text' => 'Семейный отдых', 'is_active' => '' ],
                [ 'slug' => 'fishing', 'text' => 'Рыбалка', 'is_active' => '' ],
            ],
            'reviews' => [
                [
                    'category' => 'stay',
                    'tag' => 'Проживание',
                    'star_count' => 5,
                    'has_partial' => '',
                    'partial_fill' => '',
                    'text' => 'Потрясающее место для перезагрузки! Ездили на выходных с мужем — остановились в эко-доме у пруда. Утром просыпаешься от пения птиц, выходишь на террасу с кофе — и вот он, этот пруд в тумане. Номер чистый, стильный, с большой кроватью и панорамными окнами. Вечером ужинали в кафе — местная кухня, всё очень вкусно. Обязательно вернёмся зимой!',
                    'name' => 'Анна Кузнецова',
                    'avatar' => 'АК',
                    'date' => 'Июль 2025',
                    'source' => 'Яндекс Карты',
                ],
                [
                    'category' => 'spa',
                    'tag' => 'СПА',
                    'star_count' => 5,
                    'has_partial' => '',
                    'partial_fill' => '',
                    'text' => 'СПА-центр — это что-то невероятное. Делали массаж для двоих, потом бассейны и травяной чай. Мастера — профессионалы с золотыми руками. Атмосфера полностью расслабляющая, музыка, ароматы, освещение. Чувствуешь, как напряжение уходит с каждой минутой. Брали ещё и баню на следующий день — тоже на высшем уровне.',
                    'name' => 'Мария Соколова',
                    'avatar' => 'МС',
                    'date' => 'Июнь 2025',
                    'source' => 'ВКонтакте',
                ],
                [
                    'category' => 'event',
                    'tag' => 'Мероприятия',
                    'star_count' => 5,
                    'has_partial' => '',
                    'partial_fill' => '',
                    'text' => 'Праздновали юбилей свекрови — приехали компанией из 18 человек. Организаторы всё продумали до мелочей: банкет в кафе с видом на пруд, аниматоры, интерактивы. Отдельное спасибо за роспись на кухне — было очень вкусно и красиво подано. Все гости были в восторге, многие спрашивали, где мы нашли такое место.',
                    'name' => 'Елена Волкова',
                    'avatar' => 'ЕВ',
                    'date' => 'Май 2025',
                    'source' => 'Яндекс Карты',
                ],
                [
                    'category' => 'family',
                    'tag' => 'Семейный отдых',
                    'star_count' => 5,
                    'has_partial' => '',
                    'partial_fill' => '',
                    'text' => 'Ездили с двумя детьми 5 и 9 лет. Детям — рай: площадка, пруд, кормили уток, катались на велосипедах. Нам — тишина, природа, вечером сидели на веранде. В номере был детский уголок, это очень удобно. Персонал относится к детям замечательно. Единственное — дорога с грейдера последние километры, но для нас это не стало проблемой.',
                    'name' => 'Дмитрий Петров',
                    'avatar' => 'ДП',
                    'date' => 'Апрель 2025',
                    'source' => 'TripAdvisor',
                ],
                [
                    'category' => 'fishing',
                    'tag' => 'Рыбалка',
                    'star_count' => 5,
                    'has_partial' => '',
                    'partial_fill' => '',
                    'text' => 'Приезжали с друзьями на рыбалку на три дня. Пруд оборудован отлично — есть dock, лодки, всё необходимое. Ловили карпа и щуку, улов был достойный. Вечером готовили уху на костре — незабываемые впечатления. Размещение в домиках комфортное, Wi-Fi работает. Рекомендую всем любителям рыбалки, отдалённость от города — только плюс.',
                    'name' => 'Игорь Морозов',
                    'avatar' => 'ИМ',
                    'date' => 'Март 2025',
                    'source' => 'Яндекс Карты',
                ],
                [
                    'category' => 'stay',
                    'tag' => 'Проживание',
                    'star_count' => 5,
                    'has_partial' => '',
                    'partial_fill' => '',
                    'text' => 'Бронировали глэмпинг на неделю — работали удалённо, а вечером отдыхали. Природа вокруг невероятная: сосны, пруд, закаты. В домике есть всё необходимое — кухня, санузел, кондиционер. Интернета хватает для видеозвонков. По выходным ездили в баню и на экскурсии. Идеальное место для тех, кто хочет совместить работу и отдых.',
                    'name' => 'Ольга Лебедева',
                    'avatar' => 'ОЛ',
                    'date' => 'Февраль 2025',
                    'source' => 'ВКонтакте',
                ],
                [
                    'category' => 'spa',
                    'tag' => 'СПА',
                    'star_count' => 4,
                    'has_partial' => '1',
                    'partial_fill' => '80%',
                    'text' => 'Брали пакет «Релакс-выходные»: два дня СПА, проживание и питание. Оставили все проблемы за воротами. Бассейн с подогревом, хаммам, сауна — пробовали всё. Особенно запомнилось обёртывание с мёдом и травами. Персонал незаметный, но всегда рядом, когда нужно. Единственный минус — не хотелось уезжать!',
                    'name' => 'Наталья Тихонова',
                    'avatar' => 'НТ',
                    'date' => 'Январь 2025',
                    'source' => 'Яндекс Карты',
                ],
                [
                    'category' => 'family',
                    'tag' => 'Семейный отдых',
                    'star_count' => 5,
                    'has_partial' => '',
                    'partial_fill' => '',
                    'text' => 'Третий раз приезжаем всей семьёй. Каждый раз открываем для себя что-то новое. В этот раз дети катались на лошадях, а мы с женой отправились на SPA. Вечером — барбекю на террасе. Это место стало нашей семейной традицией. Спасибо коллективу Карповки за тёплый приём и внимание к гостям!',
                    'name' => 'Александр Васильев',
                    'avatar' => 'АВ',
                    'date' => 'Декабрь 2024',
                    'source' => 'ВКонтакте',
                ],
                [
                    'category' => 'event',
                    'tag' => 'Мероприятия',
                    'star_count' => 5,
                    'has_partial' => '',
                    'partial_fill' => '',
                    'text' => 'Проводили корпоративную вечеринку на 30 человек. Организаторы помогли с программой: квест по территории, барбекю, баня, дискотека в кафе. Все коллеги были впечатлены. Место идеально для тимбилдинга — природа, свежий воздух, масса активностей. Отдельное спасибо менеджеру Анастасии за терпение и профессионализм!',
                    'name' => 'Виктор Козлов',
                    'avatar' => 'ВК',
                    'date' => 'Ноябрь 2024',
                    'source' => 'Яндекс Карты',
                ],
            ],
        ],

        'platforms_section' => [
            'eyebrow' => 'Нас рекомендуют',
            'title' => 'Отзывы на <span class="gold-header"><em>площадках</em></span>',
            'section_id' => 'platforms',
            'cards' => [
                [
                    'name' => 'Яндекс Карты',
                    'rating' => '4.8',
                    'count' => '156 отзывов',
                    'link' => 'https://yandex.ru/maps/org/karpovka/...',
                    'icon' => 'fab fa-yandex',
                    'icon_color' => '#FC3F1D',
                ],
                [
                    'name' => 'ВКонтакте',
                    'rating' => '4.9',
                    'count' => '63 отзыва',
                    'link' => 'https://vk.com/baza_otdyha_spa_perm',
                    'icon' => 'fab fa-vk',
                    'icon_color' => '#0077FF',
                ],
            ],
        ],

        'cta_review_section' => [
            'eyebrow' => 'Поделитесь впечатлениями',
            'title' => 'Оставьте свой отзыв',
            'section_id' => 'cta',
            'bg_img' => $asset( 'map_no_wm.png' ),
            'aside_text' => 'Ваше мнение помогает нам становиться лучше. Расскажите о своём опыте пребывания в Карповке — мы ценим каждую обратную связь.',
            'aside_img' => $asset( '1.jpg_1.jpeg' ),
            'form_legend' => 'Напишите нам напрямую',
            'name_label' => 'Ваше имя',
            'name_ph' => 'Как к вам обращаться',
            'phone_label' => 'Телефон',
            'phone_ph' => '+7 (___) ___-__-__',
            'review_label' => 'Отзыв',
            'review_ph' => 'Что понравилось, а что можно улучшить',
            'review_rows' => 4,
            'submit_text' => 'Отправить отзыв',
            'priv_text' => 'Нажимая кнопку, вы соглашаетесь с',
            'priv_link' => 'политикой конфиденциальности',
            'priv_url' => '#',
            'or_text' => 'или',
            'actions' => [
                [ 'text' => 'Написать на Яндекс Картах', 'link' => 'https://yandex.ru/maps/org/karpovka/...', 'style' => 'gold' ],
                [ 'text' => 'Написать в ВКонтакте', 'link' => 'https://vk.com/baza_otdyha_spa_perm', 'style' => 'ghost' ],
            ],
        ],
    ];
}

function karpovka_migrate_reviews_page_data( $post_id ) {
    if ( get_post_meta( $post_id, '_karpovka_reviews_page_migrated_20260901', true ) ) {
        return false;
    }

    foreach ( karpovka_reviews_page_migration_data() as $key => $value ) {
        update_post_meta( $post_id, $key, $value );
    }

    update_post_meta( $post_id, '_karpovka_section_order', [ 'hero', 'rating', 'list', 'platforms', 'review_cta' ] );
    update_post_meta( $post_id, '_karpovka_hidden_sections', [] );
    update_post_meta( $post_id, '_karpovka_reviews_page_migrated_20260901', '1' );

    return true;
}

add_action( 'admin_init', function () {
    $pages = get_posts( [
        'post_type' => 'page',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_key' => '_wp_page_template',
        'meta_value' => 'reviews.php',
    ] );

    foreach ( $pages as $post_id ) {
        karpovka_migrate_reviews_page_data( (int) $post_id );
    }
} );
