<?php
/* ============================================================
 * Секция: Акции — многократные блоки
 * ============================================================ */


add_action('add_meta_boxes', function () {
    add_meta_box(
        'offers_sections',
        'Акции — Секции',
        'offers_sections_mb',
        'page',
        'normal',
        'high'
    );
});


function offers_sections_get_current_block_id()
{

    $globals = isset($GLOBALS['karpovka_current_builder_globals'])
        && is_array($GLOBALS['karpovka_current_builder_globals'])
        ? $GLOBALS['karpovka_current_builder_globals']
        : array();

    if (!empty($globals['offers_block_id'])) {
        return sanitize_key($globals['offers_block_id']);
    }

    return '';
}


function offers_sections_normalize_storage($stored)
{

    if (!is_array($stored) || empty($stored)) {
        return array();
    }

    // Проверяем, уже ли новый формат
    $has_named_blocks = false;

    foreach ($stored as $key => $value) {
        if (
            is_string($key)
            && !is_numeric($key)
            && is_array($value)
        ) {
            $has_named_blocks = true;
            break;
        }
    }

    if ($has_named_blocks) {
        return $stored;
    }

    // Старый формат - конвертируем
    $normalized = array();

    foreach ($stored as $section) {

        if (!is_array($section)) {
            continue;
        }

        $section_id = isset($section['section_id'])
            ? sanitize_key($section['section_id'])
            : '';

        if (!$section_id) {
            continue;
        }

        if (!isset($normalized[$section_id])) {
            $normalized[$section_id] = array();
        }

        $normalized[$section_id][] = $section;
    }

    return $normalized;
}

/**
 * Получаем секции конкретного блока
 */
function offers_sections_get_block_sections($stored, $block_id)
{

    if (!$block_id) {
        return array();
    }

    $normalized = offers_sections_normalize_storage($stored);

    if (
        isset($normalized[$block_id])
        && is_array($normalized[$block_id])
    ) {
        return $normalized[$block_id];
    }

    return array();
}

/**
 * Meta box
 */
function offers_sections_mb($post)
{

    wp_nonce_field(
        'offers_sections_nonce',
        'offers_sections_nonce_f'
    );

    $block_id = offers_sections_get_current_block_id();

    if (!$block_id) {
        $block_id = 'default';
    }

    $stored = get_post_meta(
        $post->ID,
        'offers_sections',
        true
    );

    $sections = offers_sections_get_block_sections(
        $stored,
        $block_id
    );

?>

    <input
        type="hidden"
        name="offers_sections_present[<?php echo esc_attr($block_id); ?>]"
        value="1">

    <style>
        .ofs-sec {
            margin-bottom: 16px;
            padding: 16px;
            border: 2px solid #c3c4c7;
            background: #fff;
        }

        .ofs-sec>h4 {
            margin: 0 0 10px;
            padding: 6px 10px;
            background: #f0f0f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .ofs-card {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #dcdcde;
            background: #fafafa;
        }

        .ofs-card>h5 {
            margin: 0 0 6px;
            color: #2271b1;
        }

        .ofs-cond-row {
            display: flex;
            gap: 6px;
            margin-bottom: 4px;
            align-items: center;
        }

        .ofs-cond-row input {
            flex: 1;
        }

        .ofs-block-info {
            padding: 10px 12px;
            margin: 0 0 15px;
            background: #f0f6fc;
            border-left: 4px solid #2271b1;
        }

        .ofs-card-img-preview {
            display: block;
            max-width: 180px;
            max-height: 120px;
            object-fit: contain;
            margin-top: 8px;
        }
    </style>

    <div class="ofs-block-info">
        <strong>Блок акций:</strong>
        <?php echo esc_html($block_id); ?>
        <br>
        <small>
            Данные этого блока сохраняются отдельно от остальных блоков акций.
        </small>
    </div>

    <p>
        <strong>Секции акций:</strong>
        каждая секция = один блок на странице.
    </p>

    <div
        id="ofs-wrap-<?php echo esc_attr($block_id); ?>"
        class="ofs-wrap"
        data-ofs-block="<?php echo esc_attr($block_id); ?>">

        <?php foreach ($sections as $si => $sec) : ?>

            <div class="ofs-sec">

                <h4>
                    <span class="ofs-sec-label">
                        <?php
                        echo esc_html(
                            $sec['section_id']
                                ?? 'sec_' . $si
                        );
                        ?>
                    </span>

                    <button
                        type="button"
                        class="button ofs-sec-del">
                        Удалить секцию
                    </button>
                </h4>

                <p>
                    <label>
                        <strong>ID секции (для вызова в шаблоне):</strong>
                    </label>
                    <br>

                    <input
                        type="text"
                        name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][section_id]"
                        value="<?php echo esc_attr($sec['section_id'] ?? ''); ?>"
                        class="widefat"
                        placeholder="current">
                </p>

                <p>
                    <label>
                        <strong>Класс секции (CSS):</strong>
                    </label>
                    <br>

                    <input
                        type="text"
                        name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][section_class]"
                        value="<?php echo esc_attr($sec['section_class'] ?? ''); ?>"
                        class="widefat"
                        placeholder="c-offers-current">
                </p>

                <p>
                    <label>Надзаголовок:</label>
                    <br>

                    <input
                        type="text"
                        name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][eyebrow]"
                        value="<?php echo esc_attr($sec['eyebrow'] ?? ''); ?>"
                        class="widefat">
                </p>

                <p>
                    <label>Заголовок:</label>
                    <br>

                    <input
                        type="text"
                        name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][title_before]"
                        value="<?php echo esc_attr($sec['title_before'] ?? ''); ?>"
                        class="widefat">
                </p>

                <p>
                    <label>Золотой текст:</label>
                    <br>

                    <input
                        type="text"
                        name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][title_em]"
                        value="<?php echo esc_attr($sec['title_em'] ?? ''); ?>"
                        class="widefat">
                </p>

                <p>
                    <label>Подзаголовок:</label>
                    <br>

                    <textarea
                        name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][sub]"
                        rows="2"
                        class="widefat"><?php echo esc_textarea($sec['sub'] ?? ''); ?></textarea>
                </p>

                <hr>

                <p>
                    <strong>Карточки:</strong>
                </p>

                <div class="ofs-cards-wrap">

                    <?php
                    if (
                        !empty($sec['cards'])
                        && is_array($sec['cards'])
                    ) :

                        foreach ($sec['cards'] as $ci => $c) :
                    ?>

                            <div class="ofs-card">

                                <h5>
                                    Карточка <?php echo (int) $ci + 1; ?>
                                </h5>

                                <p>
                                    <label>Изображение:</label>
                                    <br>

                                    <input
                                        type="hidden"
                                        name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][cards][<?php echo esc_attr($ci); ?>][image]"
                                        value="<?php echo esc_url($c['image'] ?? ''); ?>"
                                        class="ofs-card-img"
                                        data-kp-media>

                                    <?php if (!empty($c['image'])) : ?>

                                        <img
                                            src="<?php echo esc_url($c['image']); ?>"
                                            class="ofs-card-img-preview"
                                            alt="">

                                    <?php endif; ?>

                                </p>

                                <p>
                                    <label>Текст бейджа:</label>
                                    <br>

                                    <input
                                        type="text"
                                        name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][cards][<?php echo esc_attr($ci); ?>][badge_text]"
                                        value="<?php echo esc_attr($c['badge_text'] ?? ''); ?>"
                                        class="widefat"
                                        placeholder="Скидка 20%">
                                </p>

                                <p>
                                    <label>

                                        <input
                                            type="checkbox"
                                            name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][cards][<?php echo esc_attr($ci); ?>][badge_hot]"
                                            value="1"
                                            <?php checked($c['badge_hot'] ?? '', '1'); ?>>

                                        Бейдж «Хит» (красный)

                                    </label>
                                </p>

                                <p>
                                    <label>Название:</label>
                                    <br>

                                    <input
                                        type="text"
                                        name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][cards][<?php echo esc_attr($ci); ?>][title]"
                                        value="<?php echo esc_attr($c['title'] ?? ''); ?>"
                                        class="widefat">
                                </p>

                                <p>
                                    <label>Описание:</label>
                                    <br>

                                    <textarea
                                        name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][cards][<?php echo esc_attr($ci); ?>][desc]"
                                        rows="3"
                                        class="widefat"><?php echo esc_textarea($c['desc'] ?? ''); ?></textarea>
                                </p>

                                <p>
                                    <label>
                                        Подробное описание в попапе:
                                    </label>
                                    <br>

                                    <textarea
                                        name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][cards][<?php echo esc_attr($ci); ?>][modal_text]"
                                        rows="5"
                                        class="widefat"
                                        placeholder="Если пусто, используется описание карточки"><?php echo esc_textarea($c['modal_text'] ?? ''); ?></textarea>
                                </p>

                                <p>
                                    <label>
                                        Мета-строка (формат · время · что включено):
                                    </label>
                                    <br>

                                    <input
                                        type="text"
                                        name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][cards][<?php echo esc_attr($ci); ?>][meta]"
                                        value="<?php echo esc_attr($c['meta'] ?? ''); ?>"
                                        class="widefat"
                                        placeholder="Whole-day формат · 08:00 – 20:00 · Обед включён">
                                </p>

                                <p>
                                    <label>
                                        Цена (например, «от 2 500 ₽»):
                                    </label>
                                    <br>

                                    <input
                                        type="text"
                                        name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][cards][<?php echo esc_attr($ci); ?>][price]"
                                        value="<?php echo esc_attr($c['price'] ?? ''); ?>"
                                        class="widefat"
                                        placeholder="от 2 500 ₽">
                                </p>

                                <p>
                                    <label>
                                        Подпись цены (например, «за человека / день»):
                                    </label>
                                    <br>

                                    <input
                                        type="text"
                                        name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][cards][<?php echo esc_attr($ci); ?>][price_unit]"
                                        value="<?php echo esc_attr($c['price_unit'] ?? ''); ?>"
                                        class="widefat"
                                        placeholder="за человека / день">
                                </p>

                                <p>
                                    <label>
                                        <strong>Что входит</strong>
                                        (одна строка = один пункт списка):
                                    </label>
                                    <br>

                                    <textarea
                                        name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][cards][<?php echo esc_attr($ci); ?>][includes]"
                                        rows="5"
                                        class="widefat"
                                        placeholder="Полный день на пруду (08:00 – 20:00)&#10;Аренда удочки или спиннинга&#10;Рыболовное кресло и аксессуары&#10;Обед в кафе (по меню дня)"><?php echo esc_textarea($c['includes'] ?? ''); ?></textarea>
                                </p>

                                <p>
                                    <label>
                                        Ссылка кнопки в попапе:
                                    </label>
                                    <br>

                                    <input
                                        type="text"
                                        name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][cards][<?php echo esc_attr($ci); ?>][link]"
                                        value="<?php echo esc_attr($c['link'] ?? ''); ?>"
                                        class="widefat"
                                        placeholder="/offers/weekday">
                                </p>

                                <p>
                                    <strong>
                                        Условия (иконка + текст):
                                    </strong>
                                </p>

                                <div class="ofs-conds-wrap">

                                    <?php
                                    if (
                                        !empty($c['conditions'])
                                        && is_array($c['conditions'])
                                    ) :

                                        foreach (
                                            $c['conditions']
                                            as $kni => $cn
                                        ) :
                                    ?>

                                            <div class="ofs-cond-row">

                                                <input
                                                    type="text"
                                                    name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][cards][<?php echo esc_attr($ci); ?>][conditions][<?php echo esc_attr($kni); ?>][icon]"
                                                    value="<?php echo esc_attr($cn['icon'] ?? ''); ?>"
                                                    placeholder="fa-regular fa-calendar">

                                                <input
                                                    type="text"
                                                    name="offers_sections[<?php echo esc_attr($block_id); ?>][<?php echo esc_attr($si); ?>][cards][<?php echo esc_attr($ci); ?>][conditions][<?php echo esc_attr($kni); ?>][text]"
                                                    value="<?php echo esc_attr($cn['text'] ?? ''); ?>"
                                                    placeholder="Пн — Чт">

                                                <button
                                                    type="button"
                                                    class="button ofs-cond-del">
                                                    −
                                                </button>

                                            </div>

                                    <?php
                                        endforeach;
                                    endif;
                                    ?>

                                </div>

                                <p>
                                    <button
                                        type="button"
                                        class="button ofs-cond-add">
                                        + Условие
                                    </button>
                                </p>

                                <p>
                                    <button
                                        type="button"
                                        class="button ofs-card-del">
                                        Удалить карточку
                                    </button>
                                </p>

                            </div>

                    <?php
                        endforeach;
                    endif;
                    ?>

                </div>

                <p>
                    <button
                        type="button"
                        class="button ofs-card-add">
                        + Добавить карточку
                    </button>
                </p>

            </div>

        <?php endforeach; ?>

    </div>

    <p>
        <button
            type="button"
            class="button button-primary ofs-sec-add">
            + Добавить секцию
        </button>
    </p>

    <?php
    // JavaScript оставьте без изменений (из вашего исходного кода)
    ?>
    <script>
(function($) {
    var $wrap = $('#ofs-wrap-<?php echo esc_js($block_id); ?>');

    if (!$wrap.length) {
        return;
    }

    var blockId = <?php echo wp_json_encode($block_id); ?>;
    var sn = $wrap.find('.ofs-sec').length;

    function fieldName(si, suffix) {
        return 'offers_sections[' +
            blockId +
            '][' +
            si +
            ']' +
            suffix;
    }

    function getSectionIndex($section) {
        return $wrap.find('.ofs-sec').index($section);
    }

    function getCardIndex($card) {
        var $section = $card.closest('.ofs-sec');

        return $section.find('.ofs-card').index($card);
    }

    function getConditionIndex($condition) {
        var $card = $condition.closest('.ofs-card');

        return $card.find('.ofs-cond-row').index($condition);
    }

    function mkCond(si, ci, ni) {
        return '<div class="ofs-cond-row">' +
            '<input ' +
                'type="text" ' +
                'name="' +
                    fieldName(
                        si,
                        '[cards][' +
                        ci +
                        '][conditions][' +
                        ni +
                        '][icon]'
                    ) +
                '" ' +
                'placeholder="fa-regular fa-calendar">' +
            '<input ' +
                'type="text" ' +
                'name="' +
                    fieldName(
                        si,
                        '[cards][' +
                        ci +
                        '][conditions][' +
                        ni +
                        '][text]'
                    ) +
                '" ' +
                'placeholder="Пн — Чт">' +
            '<button ' +
                'type="button" ' +
                'class="button ofs-cond-del">' +
                '−' +
            '</button>' +
        '</div>';
    }

    function mkCard(si, ci) {
        return '<div class="ofs-card">' +

            '<h5>Карточка ' + (ci + 1) + '</h5>' +

            '<p>' +
                '<label>Изображение:</label><br>' +
                '<input ' +
                    'type="hidden" ' +
                    'name="' +
                        fieldName(
                            si,
                            '[cards][' +
                            ci +
                            '][image]'
                        ) +
                    '" ' +
                    'class="ofs-card-img" ' +
                    'data-kp-media>' +
            '</p>' +

            '<p>' +
                '<label>Текст бейджа:</label><br>' +
                '<input ' +
                    'type="text" ' +
                    'name="' +
                        fieldName(
                            si,
                            '[cards][' +
                            ci +
                            '][badge_text]'
                        ) +
                    '" ' +
                    'class="widefat" ' +
                    'placeholder="Скидка 20%">' +
            '</p>' +

            '<p>' +
                '<label>' +
                    '<input ' +
                        'type="checkbox" ' +
                        'name="' +
                            fieldName(
                                si,
                                '[cards][' +
                                ci +
                                '][badge_hot]'
                            ) +
                        '" ' +
                        'value="1">' +
                    ' Бейдж «Хит» (красный)' +
                '</label>' +
            '</p>' +

            '<p>' +
                '<label>Название:</label><br>' +
                '<input ' +
                    'type="text" ' +
                    'name="' +
                        fieldName(
                            si,
                            '[cards][' +
                            ci +
                            '][title]'
                        ) +
                    '" ' +
                    'class="widefat">' +
            '</p>' +

            '<p>' +
                '<label>Описание:</label><br>' +
                '<textarea ' +
                    'name="' +
                        fieldName(
                            si,
                            '[cards][' +
                            ci +
                            '][desc]'
                        ) +
                    '" ' +
                    'rows="3" ' +
                    'class="widefat">' +
                '</textarea>' +
            '</p>' +

            '<p>' +
                '<label>Подробное описание в попапе:</label><br>' +
                '<textarea ' +
                    'name="' +
                        fieldName(
                            si,
                            '[cards][' +
                            ci +
                            '][modal_text]'
                        ) +
                    '" ' +
                    'rows="5" ' +
                    'class="widefat" ' +
                    'placeholder="Если пусто, используется описание карточки">' +
                '</textarea>' +
            '</p>' +

            '<p>' +
                '<label>Мета-строка (формат · время · что включено):</label><br>' +
                '<input ' +
                    'type="text" ' +
                    'name="' +
                        fieldName(
                            si,
                            '[cards][' +
                            ci +
                            '][meta]'
                        ) +
                    '" ' +
                    'class="widefat" ' +
                    'placeholder="Whole-day формат · 08:00 – 20:00 · Обед включён">' +
            '</p>' +

            '<p>' +
                '<label>Цена:</label><br>' +
                '<input ' +
                    'type="text" ' +
                    'name="' +
                        fieldName(
                            si,
                            '[cards][' +
                            ci +
                            '][price]'
                        ) +
                    '" ' +
                    'class="widefat" ' +
                    'placeholder="от 2 500 ₽">' +
            '</p>' +

            '<p>' +
                '<label>Подпись цены:</label><br>' +
                '<input ' +
                    'type="text" ' +
                    'name="' +
                        fieldName(
                            si,
                            '[cards][' +
                            ci +
                            '][price_unit]'
                        ) +
                    '" ' +
                    'class="widefat" ' +
                    'placeholder="за человека / день">' +
            '</p>' +

            '<p>' +
                '<label>' +
                    '<strong>Что входит</strong> ' +
                    '(одна строка = один пункт):' +
                '</label><br>' +
                '<textarea ' +
                    'name="' +
                        fieldName(
                            si,
                            '[cards][' +
                            ci +
                            '][includes]'
                        ) +
                    '" ' +
                    'rows="5" ' +
                    'class="widefat" ' +
                    'placeholder="Полный день на пруду (08:00 – 20:00)&#10;Аренда удочки или спиннинга">' +
                '</textarea>' +
            '</p>' +

            '<p>' +
                '<label>Ссылка кнопки в попапе:</label><br>' +
                '<input ' +
                    'type="text" ' +
                    'name="' +
                        fieldName(
                            si,
                            '[cards][' +
                            ci +
                            '][link]'
                        ) +
                    '" ' +
                    'class="widefat" ' +
                    'placeholder="/offers/weekday">' +
            '</p>' +

            '<p><strong>Условия:</strong></p>' +

            '<div class="ofs-conds-wrap"></div>' +

            '<p>' +
                '<button ' +
                    'type="button" ' +
                    'class="button ofs-cond-add">' +
                    '+ Условие' +
                '</button>' +
            '</p>' +

            '<p>' +
                '<button ' +
                    'type="button" ' +
                    'class="button ofs-card-del">' +
                    'Удалить карточку' +
                '</button>' +
            '</p>' +

        '</div>';
    }

    function mkSection() {
        var si = sn;

        var h =
            '<div class="ofs-sec">' +

                '<h4>' +
                    '<span class="ofs-sec-label">' +
                        'sec_' + si +
                    '</span>' +

                    '<button ' +
                        'type="button" ' +
                        'class="button ofs-sec-del">' +
                        'Удалить секцию' +
                    '</button>' +
                '</h4>' +

                '<p>' +
                    '<label>ID секции:</label><br>' +

                    '<input ' +
                        'type="text" ' +
                        'name="' +
                            fieldName(si, '[section_id]') +
                        '" ' +
                        'class="widefat" ' +
                        'placeholder="' +
                            blockId +
                        '">' +
                '</p>' +

                '<p>' +
                    '<label>Класс секции:</label><br>' +

                    '<input ' +
                        'type="text" ' +
                        'name="' +
                            fieldName(si, '[section_class]') +
                        '" ' +
                        'class="widefat" ' +
                        'placeholder="c-offers-current">' +
                '</p>' +

                '<p>' +
                    '<label>Надзаголовок:</label><br>' +

                    '<input ' +
                        'type="text" ' +
                        'name="' +
                            fieldName(si, '[eyebrow]') +
                        '" ' +
                        'class="widefat">' +
                '</p>' +

                '<p>' +
                    '<label>Заголовок:</label><br>' +

                    '<input ' +
                        'type="text" ' +
                        'name="' +
                            fieldName(si, '[title_before]') +
                        '" ' +
                        'class="widefat">' +
                '</p>' +

                '<p>' +
                    '<label>Золотой текст:</label><br>' +

                    '<input ' +
                        'type="text" ' +
                        'name="' +
                            fieldName(si, '[title_em]') +
                        '" ' +
                        'class="widefat">' +
                '</p>' +

                '<p>' +
                    '<label>Подзаголовок:</label><br>' +

                    '<textarea ' +
                        'name="' +
                            fieldName(si, '[sub]') +
                        '" ' +
                        'rows="2" ' +
                        'class="widefat">' +
                    '</textarea>' +
                '</p>' +

                '<hr>' +

                '<p><strong>Карточки:</strong></p>' +

                '<div class="ofs-cards-wrap"></div>' +

                '<p>' +
                    '<button ' +
                        'type="button" ' +
                        'class="button ofs-card-add">' +
                        '+ Добавить карточку' +
                    '</button>' +
                '</p>' +

            '</div>';

        $wrap.append(h);
        sn++;
    }

    $wrap.on(
        'click',
        '.ofs-sec-add',
        function() {
            mkSection();
        }
    );

    $wrap.on(
        'click',
        '.ofs-sec-del',
        function() {
            $(this)
                .closest('.ofs-sec')
                .remove();
        }
    );

    $wrap.on(
        'click',
        '.ofs-card-add',
        function() {
            var $section = $(this).closest('.ofs-sec');

            var $wrapCards = $section
                .find('.ofs-cards-wrap')
                .first();

            var si = getSectionIndex($section);

            var ci = $wrapCards
                .find('.ofs-card')
                .length;

            $wrapCards.append(
                mkCard(si, ci)
            );
        }
    );

    $wrap.on(
        'click',
        '.ofs-card-del',
        function() {
            $(this)
                .closest('.ofs-card')
                .remove();
        }
    );

    $wrap.on(
        'click',
        '.ofs-cond-add',
        function() {
            var $card = $(this).closest('.ofs-card');

            var $conditions = $card
                .find('.ofs-conds-wrap')
                .first();

            var si = getSectionIndex(
                $card.closest('.ofs-sec')
            );

            var ci = getCardIndex($card);

            var ni = $conditions
                .find('.ofs-cond-row')
                .length;

            $conditions.append(
                mkCond(si, ci, ni)
            );
        }
    );

    $wrap.on(
        'click',
        '.ofs-cond-del',
        function() {
            $(this)
                .closest('.ofs-cond-row')
                .remove();
        }
    );

    $wrap.on(
        'click',
        '[data-kp-media]',
        function(e) {
            e.preventDefault();

            var $input = $(this);

            if (
                typeof wp === 'undefined' ||
                !wp.media
            ) {
                return;
            }

            var frame = wp.media({
                title: 'Выберите изображение',

                button: {
                    text: 'Использовать изображение'
                },

                multiple: false
            });

            frame.on(
                'select',
                function() {
                    var attachment = frame
                        .state()
                        .get('selection')
                        .first()
                        .toJSON();

                    $input.val(
                        attachment.url
                    );

                    var $preview = $input.siblings(
                        '.ofs-card-img-preview'
                    );

                    if (!$preview.length) {
                        $preview = $('<img>')
                            .addClass(
                                'ofs-card-img-preview'
                            );

                        $input.after(
                            $preview
                        );
                    }

                    $preview.attr(
                        'src',
                        attachment.url
                    );
                }
            );

            frame.open();
        }
    );

    $('form#post').on('submit', function() {
    $wrap.find('.ofs-sec').each(function(si) {
        var $section = $(this);

        $section.find('[name]').each(function() {
            var name = $(this).attr('name');

            name = name.replace(
                /offers_sections\[[^\]]+\]\[[^\]]+\]/,
                'offers_sections[' +
                blockId +
                '][' +
                si +
                ']'
            );

            $(this).attr('name', name);
        });

        $section.find('.ofs-card').each(function(ci) {
            var $card = $(this);

            $card.find('[name]').each(function() {
                var name = $(this).attr('name');

                name = name.replace(
                    /\[cards\]\[\d+\]/,
                    '[cards][' + ci + ']'
                );

                $(this).attr('name', name);
            });

            $card.find('.ofs-cond-row').each(function(ni) {
                var $condition = $(this);

                $condition.find('[name]').each(function() {
                    var name = $(this).attr('name');

                    name = name.replace(
                        /\[conditions\]\[\d+\]/,
                        '[conditions][' + ni + ']'
                    );

                    $(this).attr('name', name);
                });
            });
        });
    });
});
})(jQuery);
</script>

<?php
}



/**
 * ============================================================
 * SAVE
 * ============================================================
 */

add_action('save_post', function ($pid) {

    // Только страницы
    if (
        isset($_POST['post_type'])
        && $_POST['post_type'] !== 'page'
    ) {
        return;
    }

    // Nonce
    if (
        !isset(
            $_POST['offers_sections_nonce_f']
        )
    ) {
        return;
    }

    if (
        !wp_verify_nonce(
            $_POST['offers_sections_nonce_f'],
            'offers_sections_nonce'
        )
    ) {
        return;
    }

    // Autosave
    if (
        defined('DOING_AUTOSAVE')
        && DOING_AUTOSAVE
    ) {
        return;
    }

    // Revision
    if (
        wp_is_post_revision($pid)
    ) {
        return;
    }

    // Permissions
    if (
        !current_user_can(
            'edit_post',
            $pid
        )
    ) {
        return;
    }

    // Получаем уже существующие данные
    $existing = get_post_meta(
        $pid,
        'offers_sections',
        true
    );

    // Преобразуем старый формат в новый
    $existing = offers_sections_normalize_storage(
        $existing
    );

    if (!is_array($existing)) {
        $existing = array();
    }

    // Получаем отправленные блоки
    $posted_blocks = (
        !empty($_POST['offers_sections'])
        && is_array($_POST['offers_sections'])
    )
        ? $_POST['offers_sections']
        : array();

    // Маркеры блоков
    $present_blocks = (
        !empty($_POST['offers_sections_present'])
        && is_array($_POST['offers_sections_present'])
    )
        ? $_POST['offers_sections_present']
        : array();

    // Обрабатываем каждый блок отдельно
    foreach ($present_blocks as $block_id => $present) {

        $block_id = sanitize_key($block_id);

        if (!$block_id) {
            continue;
        }

        // Если пользователь удалил все секции блока
        if (
            empty($posted_blocks[$block_id])
            || !is_array($posted_blocks[$block_id])
        ) {

            $existing[$block_id] = array();

            continue;
        }

        $clean_sections = array();

        foreach (
            $posted_blocks[$block_id]
            as $sec
        ) {

            if (!is_array($sec)) {
                continue;
            }

            $cards = array();

            // Карточки
            if (
                !empty($sec['cards'])
                && is_array($sec['cards'])
            ) {

                foreach (
                    $sec['cards']
                    as $c
                ) {

                    if (!is_array($c)) {
                        continue;
                    }

                    $conditions = array();

                    // Условия
                    if (
                        !empty($c['conditions'])
                        && is_array($c['conditions'])
                    ) {

                        foreach (
                            $c['conditions']
                            as $cn
                        ) {

                            if (!is_array($cn)) {
                                continue;
                            }

                            $conditions[] = array(

                                'icon' => sanitize_text_field(
                                    $cn['icon'] ?? ''
                                ),

                                'text' => sanitize_text_field(
                                    $cn['text'] ?? ''
                                ),

                            );
                        }
                    }

                    // ВАЖНО: Сохраняем ВСЕ поля карточки
                    $cards[] = array(
                        'image' => esc_url_raw(
                            $c['image'] ?? ''
                        ),
                        'badge_text' => sanitize_text_field(
                            $c['badge_text'] ?? ''
                        ),
                        'badge_hot' => !empty(
                            $c['badge_hot']
                        )
                            ? '1'
                            : '',
                        'title' => sanitize_text_field(
                            $c['title'] ?? ''
                        ),
                        'desc' => sanitize_textarea_field(
                            $c['desc'] ?? ''
                        ),
                        'modal_text' => sanitize_textarea_field(
                            $c['modal_text'] ?? ''
                        ),

                        // ===== НОВЫЕ ПОЛЯ ДЛЯ МОДАЛКИ =====
                        'meta'       => sanitize_text_field($c['meta'] ?? ''),
    'price'      => sanitize_text_field($c['price'] ?? ''),
    'price_unit' => sanitize_text_field($c['price_unit'] ?? ''),
    'includes'   => sanitize_textarea_field($c['includes'] ?? ''),
                        // ===== КОНЕЦ НОВЫХ ПОЛЕЙ =====

                        'link' => esc_url_raw(
                            $c['link'] ?? ''
                        ),

                        'conditions' => $conditions,

                    );
                }
            }

            // Секция
            $clean_sections[] = array(

                'section_id' => sanitize_text_field(
                    $sec['section_id'] ?? ''
                ),

                'section_class' => sanitize_text_field(
                    $sec['section_class'] ?? ''
                ),

                'eyebrow' => sanitize_text_field(
                    $sec['eyebrow'] ?? ''
                ),

                'title_before' => sanitize_text_field(
                    $sec['title_before'] ?? ''
                ),

                'title_em' => sanitize_text_field(
                    $sec['title_em'] ?? ''
                ),

                'sub' => sanitize_textarea_field(
                    $sec['sub'] ?? ''
                ),

                'cards' => $cards,

            );
        }

        // Записываем ТОЛЬКО этот блок
        $existing[$block_id] = $clean_sections;
    }

    // Сохраняем всю структуру
    update_post_meta(
        $pid,
        'offers_sections',
        $existing
    );

});
