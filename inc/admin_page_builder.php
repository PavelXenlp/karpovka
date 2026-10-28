<?php

/**
 * Plugin-free section builder for Classic Editor page templates.
 *
 * Existing meta-box callbacks and post-meta keys remain unchanged. The builder
 * only adds section order and visibility settings for each page.
 */

if (! defined('ABSPATH')) {
    exit;
}

function karpovka_section($title, $include, $callback = '', $description = '')
{
    return array(
        'title'       => $title,
        'description' => $description,
        'include'     => 'includes/' . $include . '.php',
        'callback'    => $callback,
    );
}

function karpovka_page_builder_registries()
{
    $common = array(
        'hero'     => karpovka_section('Главный экран', 'section-hero', 'hero_section_render_box', 'Заголовок, фон и кнопки страницы.'),
        'reviews'  => karpovka_section('Отзывы гостей', 'reviews', 'spa_reviews_render_box', 'Заголовок и карточки отзывов.'),
        'articles' => karpovka_section('Полезные статьи', 'section-articles', 'spa_articles_render_box', 'Подборка материалов по теме страницы.'),
        'faq'      => karpovka_section('Вопросы и ответы', 'faq', 'spa_faq_mb_callback', 'Список частых вопросов.'),
        'cta'      => karpovka_section('Финальный призыв', 'cta', 'cta_review_mb_callback', 'Контактный блок и формы.'),
        'gallery'  => karpovka_section('Галерея', 'section-gallery', 'spa_gallery_render_box', 'Фотографии объекта или услуги.'),
        'price'    => karpovka_section('Цены', 'section-price', 'spa_price_render_box', 'Таблица цен и примечания.'),
        'booking'  => karpovka_section('Условия бронирования', 'booking', 'dining_booking_mb_callback', 'Правила и форма бронирования.'),
    );

    $home = array(
        'hero'          => karpovka_section('Главный экран', 'hero', 'hero_main_section_box', 'Заголовок, фон, кнопки и форма бронирования.'),
        'formats'       => karpovka_section('Форматы отдыха', 'stats', 'stories_section_cb', 'Слайдер сценариев и форматов отдыха.'),
        'accommodation' => karpovka_section('Проживание и активный отдых', 'accommodation', 'combined_section_box', 'Карточки проживания и связанный промо-блок.'),
        'spa'           => karpovka_section('SPA-центр', 'spa', 'spa_interactive_section_box', 'Интерактивные вкладки услуг SPA.'),
        'services'      => karpovka_section('Основные услуги', 'services', 'karpovka_home_services_box', 'Карусель направлений отдыха.'),
        'map'           => karpovka_section('Интерактивная карта', 'map', 'karpovka_home_map_box', 'Карта территории с маркерами объектов.'),
        'advantages'    => karpovka_section('Преимущества', 'advantages', 'advantages_section_cb', 'Ключевые преимущества парк-отеля.'),
        'promo'         => karpovka_section('Лояльность и сертификаты', 'promo', 'promo_section_cb', 'Промо-панели основных предложений.'),
        'reviews'       => karpovka_section('Отзывы гостей', 'reviews', 'spa_reviews_render_box', 'Заголовок и карточки отзывов.'),
        'faq'           => karpovka_section('Вопросы и ответы', 'faq', 'spa_faq_mb_callback', 'Список частых вопросов.'),
        'contacts'      => karpovka_section('Контакты', 'contacts-section', 'contacts_section_mb_callback', 'Адрес, телефоны, форма обратного звонка и карта.'),
        'cta'           => karpovka_section('Финальный призыв', 'cta', 'cta_review_mb_callback', 'Контактные данные и формы.'),
        'awards'        => karpovka_section('Награды и премии', 'awards', 'awards_section_cb', 'Логотипы, названия и ссылки на награды.'),
    );

    return array(
        'index.php' => array('title' => 'Структура главной страницы', 'sections' => $home, 'legacy_home_meta' => true),
        'accommodation.php' => array(
            'title' => 'Структура страницы проживания',
            'sections' => array(
                'hero' => $common['hero'],
                'objects' => karpovka_section('Варианты проживания', 'acc-objects', 'acc_objects_section_cb', 'Эко-дома, гостиница и гостевые дома.'),
                'compare' => karpovka_section('Сравнение форматов', 'acc-compare', 'acc_compare_section_cb', 'Таблица характеристик вариантов проживания.'),
                'loyalty' => karpovka_section('Программа лояльности', 'offers-loyalty', 'offers_loyalty_section_box', 'Условия и преимущества программы.'),
                'extras' => karpovka_section('Дополнительные услуги', 'acc-extras', '', 'Дополнительные возможности для гостей.'),
                'reviews' => $common['reviews'],
                'articles' => $common['articles'],
                'faq' => $common['faq'],
                'acc_booking' => karpovka_section('Подбор проживания', 'acc-booking', 'acc_booking_section_cb', 'Форма подбора и бронирования.'),
            ),
        ),
        'spa-page.php' => array(
            'title' => 'Структура страницы SPA',
            'sections' => array(
                'hero' => $common['hero'],
                'formats' => karpovka_section('Кратко о SPA', 'stats', 'stories_section_cb', 'Основные факты и направления.'),
                'pools' => karpovka_section('Бассейны и водная зона', 'spa-detail', 'spa_detail_render_box', 'Описание бассейнов и водной зоны.'),
                'programs' => karpovka_section('SPA-программы', 'spa-programs', 'spa_programs_section_cb', 'Программы и ритуалы.'),
                'massage' => karpovka_section('Массаж', 'spa-massage', 'spa_massage_render_box', 'Ручной и аппаратный массаж.'),
                'salt' => karpovka_section('Соляная комната', 'spa-detail-solt', 'spa_salt_render_box', 'Галотерапия и условия посещения.'),
                'price' => $common['price'],
                'gallery' => $common['gallery'],
                'reviews' => $common['reviews'],
                'articles' => $common['articles'],
                'faq' => $common['faq'],
                'cta' => $common['cta'],
            ),
        ),
        'bath-page.php' => array(
            'title' => 'Структура страницы банного комплекса',
            'sections' => array(
                'hero' => $common['hero'],
                'types' => karpovka_section('Виды бань', 'bath-types', 'bath_types_mb_callback', 'Направления банного комплекса.'),
                'details' => karpovka_section('Описание бань', 'bath-detail', 'bath_details_mb_callback', 'Подробности по каждому виду.'),
                'price' => $common['price'],
                'gallery' => $common['gallery'],
                'reviews' => $common['reviews'],
                'articles' => $common['articles'],
                'faq' => $common['faq'],
                'cta' => $common['cta'],
            ),
        ),
        'dining.php' => array(
            'title' => 'Структура страницы кафе',
            'sections' => array(
                'hero' => $common['hero'],
                'details' => karpovka_section('О кафе и банкетном зале', 'dining-detail', 'bath_details_mb_callback', 'Описание залов и кухни.'),
                'menu' => karpovka_section('Меню', 'dining-menu', 'dining_menu_mb_callback', 'Категории и примеры блюд.'),
                'formats' => karpovka_section( 'Форматы обслуживания', 'dining-formats', 'dining_formats_mb_callback', 'Кафе, банкеты и мероприятия.' ),
                'booking' => $common['booking'],
                'gallery' => $common['gallery'],
                'reviews' => $common['reviews'],
                'articles' => $common['articles'],
                'faq' => $common['faq'],
                'cta' => $common['cta'],
            ),
        ),
        'recreation.php' => array(
            'title' => 'Структура страницы активного отдыха',
            'sections' => array(
                'hero' => $common['hero'],
                'seasons' => karpovka_section('Сезоны', 'recreation-seasons', 'recreation_seasons_mb', 'Лето, зима и круглогодичные активности.'),
                'activities' => karpovka_section('Активности', 'recreation-spa', 'recreation_groups_mb', 'Сезонные группы активностей.'),
                'price' => $common['price'],
                'booking' => $common['booking'],
                'gallery' => $common['gallery'],
                'reviews' => $common['reviews'],
                'articles' => $common['articles'],
                'faq' => $common['faq'],
                'cta' => $common['cta'],
            ),
        ),
        'fishing.php' => array(
            'title' => 'Структура страницы рыбалки',
            'sections' => array(
                'hero' => $common['hero'],
                'details' => karpovka_section('Рыбалка летом и осенью', 'spa-detail', 'spa_detail_render_box', 'Сезон, условия и описание.'),
                'included' => karpovka_section('Что включено', 'events-includes', 'events_includes_mb_callback', 'Инвентарь и услуги.'),
                'offers' => array_merge(karpovka_section('Предложения', 'offers-current', 'offers_sections_mb', 'Актуальные предложения для гостей.'), array('global' => array('offers_block_id' => 'fishing'))),
                'gallery' => $common['gallery'],
                'reviews' => $common['reviews'],
                'articles' => $common['articles'],
                'faq' => $common['faq'],
                'cta' => $common['cta'],
            ),
        ),
        'events.php' => array(
            'title' => 'Структура страницы мероприятий',
            'sections' => array(
                'hero' => $common['hero'],
                'formats' => karpovka_section('Форматы мероприятий', 'bath-types', 'bath_types_mb_callback', 'Доступные форматы и площадки.'),
                'details' => karpovka_section('Описание площадок', 'bath-detail', 'bath_details_mb_callback', 'Подробности по залам и площадкам.'),
                'weekend' => karpovka_section('Программы уикенда', 'events-weekend', 'events_weekend_mb_callback', 'Готовые сценарии мероприятий.'),
                'included' => karpovka_section('Что включено', 'events-includes', 'events_includes_mb_callback', 'Состав услуг и оснащение.'),
                'price' => $common['price'],
                'gallery' => $common['gallery'],
                'reviews' => $common['reviews'],
                'articles' => $common['articles'],
                'faq' => $common['faq'],
                'cta' => karpovka_section('Форма заявки', 'feedback', 'feedback_form_section_box', 'Форма заявки на мероприятие.'),
            ),
        ),
        'wedding.php' => array(
            'title' => 'Структура страницы события',
            'sections' => array(
                'hero' => $common['hero'],
                'content' => karpovka_section('Описание и галерея', 'wedding-page', 'wedding_gallery_render_box', 'Основная программа и фотографии.'),
                'reviews' => $common['reviews'],
                'articles' => $common['articles'],
                'faq' => $common['faq'],
                'cta' => $common['cta'],
            ),
        ),
        'contacts.php' => array(
            'title' => 'Структура страницы контактов',
            'sections' => array(
                'hero' => $common['hero'],
                'contacts' => karpovka_section('Контакты', 'contacts-section', 'contacts_section_mb_callback', 'Телефон, почта, адрес и режим работы.'),
                'directions' => karpovka_section('Как добраться', 'directions', 'directions_section_mb_callback', 'Маршрут и карта с адресом.'),
                'requisites' => karpovka_section('Реквизиты', 'requisites', 'requisites_section_box', 'Юридические данные.'),
                'feedback' => karpovka_section('Обратная связь', 'feedback', 'feedback_form_section_box', 'Форма обращения.'),
            ),
        ),
        'reviews.php' => array(
            'title' => 'Структура страницы отзывов',
            'sections' => array(
                'hero' => $common['hero'],
                'rating' => karpovka_section('Рейтинг', 'rating', '', 'Сводный рейтинг гостей.'),
                'list' => karpovka_section('Список отзывов', 'reviews-list', 'reviews_list_section_box', 'Фильтры и карточки отзывов.'),
                'platforms' => karpovka_section('Отзывы на площадках', 'platforms', 'platforms_section_box', 'Ссылки на внешние площадки.'),
                'review_cta' => karpovka_section('Оставить отзыв', 'cta-review', 'cta_review_section_box', 'Форма и призыв оставить отзыв.'),
            ),
        ),
        'stock.php' => array(
            'title' => 'Структура страницы акций',
            'sections' => array(
                'hero' => $common['hero'],
                'current' => array_merge(karpovka_section('Текущие акции', 'offers-current', 'offers_sections_mb', 'Основные актуальные предложения.'), array('global' => array('offers_block_id' => 'current'))),
                'loyalty' => karpovka_section('Программа лояльности', 'offers-loyalty', 'offers_loyalty_section_box', 'Условия программы лояльности.'),
                'spa_offers' => array_merge(karpovka_section('SPA-предложения', 'offers-current', 'offers_sections_mb', 'Акции SPA-центра.'), array('global' => array('offers_block_id' => 'spa'))),
                'accommodation_offers' => karpovka_section('Пакеты размещения', 'offers-acc', 'acc_offers_mb', 'Предложения по проживанию.'),
                'reviews' => $common['reviews'],
                'articles' => $common['articles'],
                'faq' => $common['faq'],
                'cta' => $common['cta'],
            ),
        ),
        'articles.php' => array(
            'title' => 'Структура страницы статей',
            'sections' => array(
                'hero' => $common['hero'],
                'categories' => karpovka_section('Категории', 'categories', 'karpovka_article_categories_note', 'Фильтры формируются из рубрик статей.'),
                'popular' => karpovka_section('Популярные статьи', 'articles-popular', 'ap_box_cb', 'Выбор существующих статей.'),
                'all' => karpovka_section('Все статьи', 'articles-all', 'karpovka_articles_all_note', 'Автоматический список опубликованных статей.'),
                'about' => karpovka_section('О загородном отдыхе', 'articles-about', 'articles_seo_section_box', 'SEO-текст и форма заявки.'),
                'cta' => $common['cta'],
            ),
        ),
        'house.php' => array(
            'title' => 'Структура страницы дома',
            'sections' => array(
                'hero' => $common['hero'],
                'info' => karpovka_section('Информация о доме', 'inf-house', 'house_info_mb', 'Оснащение, вместимость и особенности.'),
                'reviews' => $common['reviews'],
                'articles' => $common['articles'],
                'faq' => $common['faq'],
                'cta' => $common['cta'],
            ),
        ),
        'discount.php' => array(
            'title' => 'Структура страницы акции',
            'sections' => array(
                'hero' => $common['hero'],
                'offer' => karpovka_section('Условия акции', 'discount', 'promo_page_box_cb', 'Описание, условия и срок действия.'),
                'faq' => $common['faq'],
                'cta' => $common['cta'],
            ),
        ),


        'faq-page.php' => array(
            'title' => 'Структура страницы Вопросы и Ответы',
            'sections' => array(
                'hero' => $common['hero'],
                'faq'  => karpovka_section('Вопросы и ответы',           'questions',             'kp_faq_box_cb',        'Категории и список частых вопросов.'),
                'help' => karpovka_section('Не нашли ответ?',            'help',          'kp_faq_help_box_cb',   'Контактные способы (телефон, Telegram, почта) и кнопки.'),
                'form' => karpovka_section('Форма обратной связи',       'feedback-faq',  'kp_faq_form_box_cb',   'Поля формы и темы вопроса.'),
            ),
        ),

        'loyalty-program.php' => array(
            'title' => 'Структура страницы программы лояльности',
            'sections' => array(
                'hero'          => $common['hero'],
                'loyalty_one'   => karpovka_section('Введение (кешбэк + карта)', 'loyalty-one',   'kp_loyalty_one_box_cb',   'Изображение слева, заголовок и текст справа.'),
                'loyalty_two'   => karpovka_section('Система статусов',         'loyalty-two',   'kp_loyalty_two_box_cb',   'Заголовок секции и 6 карточек статусов.'),
                'loyalty_three' => karpovka_section('Правила и условия',        'loyalty-three', 'kp_loyalty_three_box_cb', 'Карточки с правилами начисления/списания.'),
                'faq'          => $common['faq'],
                'cta'          => $common['cta'],
            ),
        ),

        'gift-page.php' => array(
            'title' => 'Структура страницы Подарочные сертификаты',
            'sections' => array(
                'hero'          => $common['hero'],
                'gift_one'   => karpovka_section( 'Сертификаты — слайдер',        'gift-one',  'kp_gift_one_box_cb',  'Заголовок, слайдер сертификатов, CTA.' ),
                'gift_two'   => karpovka_section( 'Бонусная карта UDS',         'gift-two',  'kp_gift_two_box_cb',  'Заголовок, текст, шаги, QR-код.' ),
                'faq'          => $common['faq'],
                'feedback'     => karpovka_section( 'Обратная связь', 'feedback', 'feedback_form_section_box', 'Форма обращения.' ),
            ),
        ),
    );
}

function karpovka_get_builder_template($post)
{
    if (! $post) {
        return '';
    }

    if ('page' !== $post->post_type) {
        return '';
    }

    $template = get_page_template_slug($post->ID);
    if ('default' === $template || ! $template) {
        $front_id = (int) get_option('page_on_front');
        if ($front_id > 0 && $front_id === (int) $post->ID) {
            return 'index.php';
        }
    }

    return $template;
}

function karpovka_get_builder_registry($post)
{
    $registries = karpovka_page_builder_registries();
    $template   = karpovka_get_builder_template($post);
    return isset($registries[$template]) ? $registries[$template] : null;
}

function karpovka_builder_meta_keys($registry)
{
    if (! empty($registry['legacy_home_meta'])) {
        return array('_karpovka_home_section_order', '_karpovka_home_hidden_sections');
    }
    return array('_karpovka_section_order', '_karpovka_hidden_sections');
}

function karpovka_get_page_layout($post_id, $registry)
{
    list($order_key) = karpovka_builder_meta_keys($registry);
    $default = array_keys($registry['sections']);
    $saved   = get_post_meta($post_id, $order_key, true);
    $saved   = is_array($saved) ? array_values(array_intersect($saved, $default)) : array();
    return array_merge($saved, array_values(array_diff($default, $saved)));
}

function karpovka_get_hidden_page_sections($post_id, $registry)
{
    list(, $hidden_key) = karpovka_builder_meta_keys($registry);
    $saved = get_post_meta($post_id, $hidden_key, true);
    return is_array($saved) ? array_values(array_intersect($saved, array_keys($registry['sections']))) : array();
}

function karpovka_render_page_sections()
{
    global $post;

    $registry = karpovka_get_builder_registry($post);
    if (! $registry) {
        return;
    }

    $post_id = get_the_ID();
    $hidden  = karpovka_get_hidden_page_sections($post_id, $registry);
    $render  = static function ($include_file, $globals) {
        foreach ($globals as $name => $value) {
            $GLOBALS[$name] = $value;
        }
        include $include_file;
    };

    foreach (karpovka_get_page_layout($post_id, $registry) as $section_key) {
        if (in_array($section_key, $hidden, true) || ! isset($registry['sections'][$section_key])) {
            continue;
        }
        $section = $registry['sections'][$section_key];
        $include = trailingslashit(get_template_directory()) . $section['include'];
        if (is_readable($include)) {
            $render($include, isset($section['global']) ? $section['global'] : array());
        }
    }
}

function karpovka_home_sections()
{
    $registries = karpovka_page_builder_registries();
    return $registries['index.php']['sections'];
}

function karpovka_is_home_builder_page($post)
{
    return 'index.php' === karpovka_get_builder_template($post);
}

function karpovka_get_home_layout($post_id)
{
    $registries = karpovka_page_builder_registries();
    return karpovka_get_page_layout($post_id, $registries['index.php']);
}

function karpovka_get_hidden_home_sections($post_id)
{
    $registries = karpovka_page_builder_registries();
    return karpovka_get_hidden_page_sections($post_id, $registries['index.php']);
}

function karpovka_render_home_sections()
{
    karpovka_render_page_sections();
}

function karpovka_is_theme_meta_box_callback($callback)
{
    try {
        if (is_string($callback) && function_exists($callback)) {
            $reflection = new ReflectionFunction($callback);
        } elseif (is_array($callback) && 2 === count($callback)) {
            $reflection = new ReflectionMethod($callback[0], $callback[1]);
        } else {
            return false;
        }
    } catch (ReflectionException $exception) {
        return false;
    }

    $filename = wp_normalize_path((string) $reflection->getFileName());
    $inc_dir  = trailingslashit(wp_normalize_path(get_template_directory())) . 'inc/';
    return 0 === strpos($filename, $inc_dir);
}

function karpovka_collect_theme_meta_box_ids($screen = 'page')
{
    global $wp_meta_boxes;
    $ids = array();
    if (empty($wp_meta_boxes[$screen]) || ! is_array($wp_meta_boxes[$screen])) {
        return $ids;
    }
    foreach ($wp_meta_boxes[$screen] as $context => $priorities) {
        foreach ($priorities as $boxes) {
            if (! is_array($boxes)) {
                continue;
            }
            foreach ($boxes as $box_id => $box) {
                if (! empty($box['callback']) && karpovka_is_theme_meta_box_callback($box['callback'])) {
                    $ids[$box_id] = $context;
                }
            }
        }
    }
    return $ids;
}

function karpovka_register_page_builder_meta_box($post)
{
    $registry = karpovka_get_builder_registry($post);
    if (! $registry) {
        return;
    }
    $screen = $post->post_type;
    if (karpovka_is_home_builder_page($post)) {
        remove_meta_box('postcustom', 'page', 'normal');
    }
    foreach (karpovka_collect_theme_meta_box_ids($screen) as $box_id => $context) {
        remove_meta_box($box_id, $screen, $context);
    }
    add_meta_box('karpovka_page_builder', $registry['title'], 'karpovka_page_builder_meta_box', $screen, 'normal', 'high');
}
add_action('add_meta_boxes_page', 'karpovka_register_page_builder_meta_box', 100);

function karpovka_page_builder_meta_box($post)
{
    $registry = karpovka_get_builder_registry($post);

    if (!$registry) {
        return;
    }


    $sections = $registry['sections'];

    $order = karpovka_get_page_layout(
        $post->ID,
        $registry
    );

    $hidden = karpovka_get_hidden_page_sections(
        $post->ID,
        $registry
    );


    wp_nonce_field(
        'karpovka_save_page_builder',
        'karpovka_page_builder_nonce'
    );
    ?>

    <div
        class="karpovka-builder"
        data-karpovka-builder
        data-kp-builder-id="<?php echo esc_attr($post->ID); ?>"
    >

        <div class="karpovka-builder__intro">

            <div class="karpovka-builder__summary">

                <strong>
                    Структура страницы
                </strong>

                <span
                    data-kp-section-summary
                ></span>

            </div>


            <div class="karpovka-builder__toolbar">

                <label class="karpovka-builder__search">

                    <span
                        class="dashicons dashicons-search"
                        aria-hidden="true"
                    ></span>

                    <span class="screen-reader-text">
                        Найти секцию
                    </span>

                    <input
                        type="search"
                        placeholder="Найти секцию"
                        data-kp-section-search
                    >

                </label>


                <button
                    type="button"
                    class="button"
                    data-kp-expand-all
                >

                    <span
                        class="dashicons dashicons-editor-expand"
                        aria-hidden="true"
                    ></span>

                    Развернуть все

                </button>


                <button
                    type="button"
                    class="button"
                    data-kp-collapse-all
                >

                    <span
                        class="dashicons dashicons-editor-contract"
                        aria-hidden="true"
                    ></span>

                    Свернуть все

                </button>

            </div>

        </div>


        <input
            type="hidden"
            name="karpovka_section_order"
            value="<?php echo esc_attr(implode(',', $order)); ?>"
            data-kp-order
        >


        <input
            type="hidden"
            name="karpovka_builder_template"
            value="<?php echo esc_attr(karpovka_get_builder_template($post)); ?>"
        >


        <div
            class="karpovka-builder__sections"
            data-kp-sections
        >

        <?php

        foreach (
            $order as $index => $section_key
        ) :

            if (
                !isset(
                    $sections[$section_key]
                )
            ) {
                continue;
            }


            $section =
                $sections[$section_key];


            $is_enabled =
                !in_array(
                    $section_key,
                    $hidden,
                    true
                );


            $callback =
                isset($section['callback'])
                    ? $section['callback']
                    : '';

            ?>

            <section
                class="karpovka-builder__section<?php echo $is_enabled ? '' : ' is-disabled'; ?>"
                data-kp-section="<?php echo esc_attr($section_key); ?>"
            >

                <header
                    class="karpovka-builder__section-head"
                >

                    <span
                        class="karpovka-builder__handle"
                        draggable="true"
                        title="Перетащить секцию"
                        aria-label="Перетащить секцию"
                        role="button"
                        tabindex="0"
                    >
                        ⋮⋮
                    </span>


                    <span
                        class="karpovka-builder__number"
                        data-kp-number
                    >
                        <?php
                        echo esc_html(
                            $index + 1
                        );
                        ?>
                    </span>


                    <span
                        class="karpovka-builder__section-copy"
                    >

                        <strong>
                            <?php
                            echo esc_html(
                                $section['title']
                            );
                            ?>
                        </strong>

                        <small>
                            <?php
                            echo esc_html(
                                $section['description']
                            );
                            ?>
                        </small>

                        <em
                            data-kp-content-state
                        ></em>

                    </span>


                    <label
                        class="karpovka-builder__switch"
                    >

                        <input
                            type="checkbox"
                            name="karpovka_enabled_sections[]"
                            value="<?php echo esc_attr($section_key); ?>"
                            <?php checked($is_enabled); ?>
                            data-kp-enabled
                        >

                        <span
                            aria-hidden="true"
                        ></span>

                        <b data-kp-status>
                            <?php
                            echo $is_enabled
                                ? 'Показывается'
                                : 'Скрыто';
                            ?>
                        </b>

                    </label>


                    <span
                        class="karpovka-builder__move-actions"
                    >

                        <button
                            type="button"
                            title="Переместить выше"
                            aria-label="Переместить секцию выше"
                            data-kp-move-up
                        >
                            ↑
                        </button>

                        <button
                            type="button"
                            title="Переместить ниже"
                            aria-label="Переместить секцию ниже"
                            data-kp-move-down
                        >
                            ↓
                        </button>

                    </span>


                    <button
                        type="button"
                        class="karpovka-builder__expand"
                        aria-expanded="false"
                        data-kp-expand
                    >

                        <span>
                            Настроить
                        </span>

                        <i aria-hidden="true"></i>

                    </button>

                </header>


                <div
                    class="karpovka-builder__section-body"
                    hidden
                    data-kp-body
                >

                <?php if (
                    $callback
                    && is_callable($callback)
                ) : ?>

                    <?php

                    /*
                     * ====================================================
                     * ВАЖНОЕ ИЗМЕНЕНИЕ
                     *
                     * Передаём текущей секции:
                     *
                     * 1. ключ builder:
                     *    current
                     *    spa_offers
                     *
                     * 2. global:
                     *    offers_block_id = current
                     *    offers_block_id = spa
                     *
                     * Благодаря этому offers_sections_mb()
                     * понимает, какой блок сейчас редактируется.
                     * ====================================================
                     */

                    $GLOBALS[
                        'karpovka_current_builder_section'
                    ] = $section_key;


                    $GLOBALS[
                        'karpovka_current_builder_globals'
                    ] = (
                        isset($section['global'])
                        && is_array($section['global'])
                    )
                        ? $section['global']
                        : array();


                    try {

                        call_user_func(
                            $callback,
                            $post
                        );

                    } catch (Throwable $exception) {

                        error_log(
                            sprintf(
                                'Раздел построителя Карповки "%s": %s',
                                $section_key,
                                $exception->getMessage()
                            )
                        );

                        echo '<div class="notice notice-error inline"><p>';

                        echo esc_html(
                            'Не удалось загрузить настройки этого блока. Остальные разделы страницы продолжают работать.'
                        );

                        echo '</p></div>';

                    }


                    /*
                     * Обязательно очищаем globals.
                     *
                     * Иначе следующая секция может получить
                     * данные предыдущей.
                     */

                    unset(
                        $GLOBALS[
                            'karpovka_current_builder_section'
                        ]
                    );


                    unset(
                        $GLOBALS[
                            'karpovka_current_builder_globals'
                        ]
                    );

                    ?>

                <?php else : ?>

                    <div
                        class="karpovka-builder__static-note"
                    >

                        <strong>
                            Блок участвует в структуре страницы.
                        </strong>

                        <p>
                            Сейчас его можно перемещать и скрывать.
                            Настройки содержимого появятся
                            на следующей итерации.
                        </p>

                    </div>

                <?php endif; ?>

                </div>

            </section>

        <?php endforeach; ?>

        </div>

    </div>

    <?php
}

function karpovka_save_page_builder($post_id)
{
    if (! isset($_POST['karpovka_page_builder_nonce'], $_POST['karpovka_builder_template'])) {
        return;
    }
    if (! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['karpovka_page_builder_nonce'])), 'karpovka_save_page_builder')) {
        return;
    }
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ! current_user_can('edit_post', $post_id)) {
        return;
    }

    $post     = get_post($post_id);
    $registry = karpovka_get_builder_registry($post);
    $template = sanitize_file_name(wp_unslash($_POST['karpovka_builder_template']));
    if (! $registry || $template !== karpovka_get_builder_template($post)) {
        return;
    }

    $valid = array_keys($registry['sections']);
    $raw_order = isset($_POST['karpovka_section_order'])
        ? explode(',', sanitize_text_field(wp_unslash($_POST['karpovka_section_order'])))
        : array();
    $order = array_values(array_intersect($raw_order, $valid));
    $order = array_merge($order, array_values(array_diff($valid, $order)));
    $enabled = isset($_POST['karpovka_enabled_sections']) && is_array($_POST['karpovka_enabled_sections'])
        ? array_map('sanitize_key', wp_unslash($_POST['karpovka_enabled_sections']))
        : array();
    $enabled = array_values(array_intersect($enabled, $valid));
    $hidden  = array_values(array_diff($valid, $enabled));
    list($order_key, $hidden_key) = karpovka_builder_meta_keys($registry);
    update_post_meta($post_id, $order_key, $order);
    update_post_meta($post_id, $hidden_key, $hidden);
}
add_action('save_post_page', 'karpovka_save_page_builder');

add_action('admin_enqueue_scripts', function ($hook_suffix) {

    if (! in_array($hook_suffix, array('post.php', 'post-new.php'), true)) {
        return;
    }

    $post_id = isset($_GET['post'])
        ? absint($_GET['post'])
        : 0;

    if (! $post_id && isset($GLOBALS['post']) && $GLOBALS['post'] instanceof WP_Post) {
        $post_id = $GLOBALS['post']->ID;
    }

    $post = $post_id ? get_post($post_id) : null;

    $is_article = $post && 'articles' === $post->post_type;
    if (! $post || (! $is_article && ! karpovka_get_builder_registry($post))) {
        return;
    }

    /*
     * Единственный правильный способ подключить
     * классическую Media Library WordPress.
     */
    wp_enqueue_media(
        array(
            'post' => $post_id,
        )
    );

    wp_enqueue_style(
        'karpovka-page-builder',
        get_template_directory_uri() . '/assets/admin/page-builder.css',
        array(),
        '1.3.0'
    );

    wp_enqueue_script(
        'karpovka-page-builder',
        get_template_directory_uri() . '/assets/admin/page-builder.js',
        array('jquery'),
        '1.1.0',
        true
    );

    wp_enqueue_script(
        'karpovka-content-controls',
        get_template_directory_uri() . '/assets/admin/content-controls.js',
        array(
            'jquery',
            'karpovka-page-builder',
            'media-editor',
        ),
        '1.2.1',
        true
    );
}, 99);
