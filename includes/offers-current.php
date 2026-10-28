<?php

$wanted = isset($GLOBALS['offers_block_id']) ? $GLOBALS['offers_block_id'] : '';

if (!$wanted) {
    return;
}

$stored = get_post_meta(get_the_ID(), 'offers_sections', true);

if (!is_array($stored) || empty($stored)) {
    return;
}

$sections = array();

if (isset($stored[$wanted]) && is_array($stored[$wanted])) {
    $sections = $stored[$wanted];
} elseif (isset($stored[0]) || isset($stored[1])) {
    foreach ($stored as $section) {
        if (!is_array($section) || empty($section['section_id'])) {
            continue;
        }

        if (sanitize_key($section['section_id']) === sanitize_key($wanted)) {
            $sections[] = $section;
        }
    }
}

if (!is_array($sections) || empty($sections)) {
    return;
}

$sec = null;

foreach ($sections as $section) {
    if (
        isset($section['section_id']) &&
        sanitize_key($section['section_id']) === sanitize_key($wanted)
    ) {
        $sec = $section;
        break;
    }
}

if (!$sec && count($sections) === 1) {
    $sec = reset($sections);
}

if (!$sec) {
    return;
}

$cards = isset($sec['cards']) && is_array($sec['cards'])
    ? $sec['cards']
    : array();

$section_key = sanitize_key($wanted);
$page_id = get_the_ID();

?>

<section
    class="c-offers-current c-fishing-formats"
    id="offers-<?php echo esc_attr($section_key); ?>">
    <div class="container">

        <div class="section-head section-head--center reveal">
            <p class="eyebrow">Форматы</p>

            <h2 class="section-title">
                Выберите свой
                <span class="gold-header">
                    <em>формат</em>
                </span>
            </h2>

            <p class="section-sub">
                Три варианта организации рыбалки для разных целей
            </p>
        </div>

        <?php if (!empty($cards)) : ?>

            <div class="c-offers-grid reveal">

                <?php foreach ($cards as $index => $card) : ?>

                    <?php
                    if (!is_array($card)) {
                        continue;
                    }

                    $title = !empty($card['title']) ? $card['title'] : '';
                    $badge = !empty($card['badge_text']) ? $card['badge_text'] : '';
                    $desc = !empty($card['desc']) ? $card['desc'] : '';
                    $image = !empty($card['image']) ? $card['image'] : '';

                    $card_id = 'fishing-format-' . $page_id . '-' . $section_key . '-' . $index;

                    $conditions = !empty($card['conditions']) && is_array($card['conditions'])
                        ? $card['conditions']
                        : array();
                    ?>

                    <article
                        class="c-offers-card"
                        data-fishing-format-modal="<?php echo esc_attr($card_id); ?>">

                        <div class="c-offers-card__img">

                            <?php if ($image) : ?>
                                <img
                                    src="<?php echo esc_url($image); ?>"
                                    alt="<?php echo esc_attr($title); ?>"
                                    loading="lazy">
                            <?php endif; ?>

                            <?php if ($badge) : ?>
                                <span class="c-offers-card__badge">
                                    <?php echo esc_html($badge); ?>
                                </span>
                            <?php endif; ?>

                        </div>

                        <div class="c-offers-card__body">

                            <?php if ($title) : ?>
                                <h3 class="c-offers-card__title">
                                    <?php echo esc_html($title); ?>
                                </h3>
                            <?php endif; ?>

                            <?php if ($desc) : ?>
                                <p class="c-offers-card__desc">
                                    <?php echo esc_html($desc); ?>
                                </p>
                            <?php endif; ?>

                            <?php if (!empty($conditions)) : ?>

                                <div class="c-offers-card__conditions">

                                    <?php foreach ($conditions as $cond) : ?>

                                        <?php
                                        if (!is_array($cond)) {
                                            continue;
                                        }

                                        $cond_icon = !empty($cond['icon'])
                                            ? $cond['icon']
                                            : 'fa-regular fa-circle';

                                        $cond_text = !empty($cond['text'])
                                            ? $cond['text']
                                            : '';

                                        if (empty($cond_text)) {
                                            continue;
                                        }
                                        ?>

                                        <span class="c-offers-card__cond">
                                            <i class="<?php echo esc_attr($cond_icon); ?>"></i>
                                            <?php echo esc_html($cond_text); ?>
                                        </span>

                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>

                            <a
                                href="#cta"
                                class="c-offers-card__link"
                                data-fishing-format-open="<?php echo esc_attr($card_id); ?>">
                                Подробнее
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>
</section>

<?php if (!empty($cards)) : ?>

    <?php foreach ($cards as $index => $card) : ?>

        <?php
        if (!is_array($card)) {
            continue;
        }

        $title = !empty($card['title']) ? $card['title'] : '';
        $badge = !empty($card['badge_text']) ? $card['badge_text'] : '';
        $modal_text = !empty($card['modal_text']) ? $card['modal_text'] : '';
        $meta = !empty($card['meta']) ? $card['meta'] : '';
        $price = !empty($card['price']) ? $card['price'] : '';
        $price_unit = !empty($card['price_unit']) ? $card['price_unit'] : '';
        $link = !empty($card['link']) ? $card['link'] : '';
        $image = !empty($card['image']) ? $card['image'] : '';

        $modal_id = 'fishing-format-' . $page_id . '-' . $section_key . '-' . $index;

        $modal_conditions = !empty($card['conditions']) && is_array($card['conditions'])
            ? $card['conditions']
            : array();

        $includes = array();

        if (!empty($card['includes'])) {

            $lines = preg_split(
                '/\r\n|\r|\n/',
                $card['includes']
            );

            foreach ($lines as $line) {

                $line = trim($line);

                if ($line !== '') {
                    $includes[] = $line;
                }
            }
        }
        ?>

        <div
            class="fishing-modal"
            id="<?php echo esc_attr($modal_id); ?>"
            hidden
            aria-hidden="true">

            <div
                class="fishing-modal__overlay"
                data-fishing-modal-close></div>

            <div
                class="fishing-modal__dialog"
                role="dialog"
                aria-modal="true"
                aria-label="<?php echo esc_attr($title); ?>">

                <button
                    class="fishing-modal__close"
                    type="button"
                    data-fishing-modal-close
                    aria-label="Закрыть">
                    <svg class="icon" aria-hidden="true">
                        <use href="#icon-close"></use>
                    </svg>
                </button>

                <?php if ($image) : ?>

                    <img
                        class="fishing-modal__photo"
                        src="<?php echo esc_url($image); ?>"
                        alt="<?php echo esc_attr($title); ?>">

                <?php endif; ?>

                <div class="fishing-modal__body">

                    <?php if ($badge) : ?>

                        <span class="fishing-modal__badge">
                            <?php echo esc_html($badge); ?>
                        </span>

                    <?php endif; ?>

                    <?php if ($title) : ?>

                        <h2 class="fishing-modal__title">
                            <?php echo esc_html($title); ?>
                        </h2>

                    <?php endif; ?>

                    <?php if ($meta) : ?>

                        <p class="fishing-modal__meta">
                            <?php echo esc_html($meta); ?>
                        </p>

                    <?php endif; ?>

                    <?php if ($modal_text) : ?>

                        <div class="fishing-modal__desc">
                            <?php echo nl2br(esc_html($modal_text)); ?>
                        </div>

                    <?php endif; ?>

                    <?php if (!empty($modal_conditions)) : ?>

                        <div class="fishing-modal__conditions c-offers-card__conditions">

                            <?php foreach ($modal_conditions as $cond) : ?>

                                <?php
                                if (!is_array($cond)) {
                                    continue;
                                }

                                $cond_icon = !empty($cond['icon'])
                                    ? $cond['icon']
                                    : 'fa-regular fa-circle';

                                $cond_text = !empty($cond['text'])
                                    ? $cond['text']
                                    : '';

                                if (empty($cond_text)) {
                                    continue;
                                }
                                ?>

                                <span class="c-offers-card__cond">
                                    <i class="<?php echo esc_attr($cond_icon); ?>"></i>
                                    <?php echo esc_html($cond_text); ?>
                                </span>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                    <?php if (!empty($includes)) : ?>

                        <div class="fishing-modal__includes">

                            <h3 class="fishing-modal__includes-title">
                                Что входит
                            </h3>

                            <ul class="fishing-modal__includes-list">

                                <?php foreach ($includes as $include) : ?>

                                    <li>
                                        <svg class="icon" aria-hidden="true">
                                            <use href="#icon-check"></use>
                                        </svg>

                                        <span>
                                            <?php echo esc_html($include); ?>
                                        </span>
                                    </li>

                                <?php endforeach; ?>

                            </ul>

                        </div>

                    <?php endif; ?>

                    <?php if ($price || $price_unit || $link) : ?>

                        <div class="fishing-modal__foot">

                            <?php if ($price || $price_unit) : ?>

                                <div class="fishing-modal__price-wrap">

                                    <?php if ($price) : ?>

                                        <p class="fishing-modal__price">
                                            <?php echo esc_html($price); ?>
                                        </p>

                                    <?php endif; ?>

                                    <?php if ($price_unit) : ?>

                                        <small class="fishing-modal__price-unit">
                                            <?php echo esc_html($price_unit); ?>
                                        </small>

                                    <?php endif; ?>

                                </div>

                            <?php endif; ?>

                            <?php if ($link) : ?>

                                <a
                                    href="<?php echo esc_url($link); ?>"
                                    class="btn btn--gold js-open-modal-callback">
                                    Узнать подробности
                                </a>

                            <?php else : ?>

                                <button
                                    type="button"
                                    class="btn btn--gold js-open-modal-callback">
                                    Узнать подробности
                                </button>

                            <?php endif; ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    <?php endforeach; ?>

<?php endif; ?>