<?php
/* ===== Шаблон: Активности — Сезонные группы ===== */
/* Использует модалки act-modal (data-modal / data-modal-close) */
 $pid = get_the_ID();
 $groups = get_post_meta($pid, 'rec_groups', true);
if (!is_array($groups) || empty($groups)) return;

 $map = [
    'summer'  => ['modifier' => 'c-spa-programs',                    'bg_class' => 'c-spa-programs__bg'],
    'winter'  => ['modifier' => 'c-act-grid--3col c-spa-massage',    'bg_class' => 'c-spa-massage__bg'],
    'allyear' => ['modifier' => 'c-act-grid--3col c-spa-programs',   'bg_class' => 'c-spa-programs__bg'],
];

/* SVG-иконка закрытия (как в act-modal на сайте) */
 $close_svg = '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><line x1="6" y1="6" x2="18" y2="18"></line><line x1="18" y1="6" x2="6" y2="18"></line></svg>';
?>

<?php foreach ($groups as $g) :
    $key    = $g['group_key'] ?: 'summer';
    $m      = $map[$key] ?? $map['summer'];
    $mod    = ' ' . $m['modifier'];
    $bg_cls = $m['bg_class'];
    $sid    = esc_attr($g['section_id'] ?: $key);
?>
<section class="c-act-grid<?php echo $mod; ?>" id="<?php echo $sid; ?>" data-season-group="<?php echo esc_attr($key); ?>">
    <div class="<?php echo esc_attr($bg_cls); ?>">
        <?php if (!empty($g['bg_image'])) : ?>
        <img src="<?php echo esc_url($g['bg_image']); ?>" alt="" aria-hidden="true">
        <?php endif; ?>
    </div>
    <div class="container">
        <div class="section-head section-head--center reveal">
            <?php if (!empty($g['eyebrow'])) : ?>
                <p class="eyebrow"><?php echo esc_html($g['eyebrow']); ?></p>
            <?php endif; ?>
            <?php if (!empty($g['title_before']) || !empty($g['title_em'])) : ?>
                <h2 class="section-title">
                    <?php echo esc_html($g['title_before']); ?>
                    <?php if (!empty($g['title_em'])) : ?>
                        <span class="gold-header"><em><?php echo esc_html($g['title_em']); ?></em></span>
                    <?php endif; ?>
                </h2>
            <?php endif; ?>
            <?php if (!empty($g['sub'])) : ?>
                <p class="section-sub"><?php echo esc_html($g['sub']); ?></p>
            <?php endif; ?>
        </div>
        <div class="c-act-grid__list reveal">
            <?php if (!empty($g['cards']) && is_array($g['cards'])) : foreach ($g['cards'] as $ci => $c) :
                $modal_id = sanitize_html_class($c['link'] ?: 'modal-act-' . $sid . '-' . $ci);
            ?>
            <article class="c-act-card" data-modal="<?php echo esc_attr($modal_id); ?>" role="button" tabindex="0">
                <div class="c-act-card__img">
                    <?php if (!empty($c['image'])) : ?>
                    <img src="<?php echo esc_url($c['image']); ?>" alt="<?php echo esc_attr($c['title']); ?>" loading="lazy">
                    <?php endif; ?>
                </div>
                <div class="c-act-card__body">
                    <?php if (!empty($c['tag_text'])) : ?>
                    <span class="c-act-card__tag">
                        <?php if (!empty($c['tag_icon'])) : ?><i class="<?php echo esc_attr($c['tag_icon']); ?>"></i> <?php endif; ?>
                        <?php echo esc_html($c['tag_text']); ?>
                    </span>
                    <?php endif; ?>
                    <h3 class="c-act-card__title"><?php echo esc_html($c['title']); ?></h3>
                    <p class="c-act-card__desc"><?php echo esc_html($c['desc']); ?></p>
                    <div class="c-act-card__meta">
                        <?php if (!empty($c['meta1_text'])) : ?>
                        <span>
                            <?php if (!empty($c['meta1_icon'])) : ?><i class="<?php echo esc_attr($c['meta1_icon']); ?>"></i> <?php endif; ?>
                            <?php echo esc_html($c['meta1_text']); ?>
                        </span>
                        <?php endif; ?>
                        <?php if (!empty($c['meta2_text'])) : ?>
                        <span>
                            <?php if (!empty($c['meta2_icon'])) : ?><i class="<?php echo esc_attr($c['meta2_icon']); ?>"></i> <?php endif; ?>
                            <?php echo esc_html($c['meta2_text']); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="c-act-card__link">Подробнее <i class="fa-solid fa-arrow-right"></i></button>
                </div>
            </article>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<!-- ═══ Модалки (вне секции — act-modal система) ═══ -->
<?php if (!empty($g['cards']) && is_array($g['cards'])) : foreach ($g['cards'] as $ci => $c) :
    $modal_id = sanitize_html_class($c['link'] ?: 'modal-act-' . $sid . '-' . $ci);

    /* Поля модалки с fallback на поля карточки */
    $modal_image = $c['modal_image'] ?? ($c['image'] ?? '');
    $modal_badge = !empty($c['modal_badge']) ? $c['modal_badge'] : ($c['tag_text'] ?? '');
    $modal_title = !empty($c['modal_title']) ? $c['modal_title'] : ($c['title'] ?? '');
    $modal_meta  = $c['modal_meta'] ?? '';
    if (empty($modal_meta)) {
        $modal_meta = implode(' · ', array_filter([$c['meta1_text'] ?? '', $c['meta2_text'] ?? '']));
    }
    $modal_desc  = $c['modal_desc'] ?? '';
    $modal_items = is_array($c['modal_items'] ?? null) ? $c['modal_items'] : [];
    $cta_text    = $c['cta_text'] ?? 'Забронировать';
    $cta_link    = $c['cta_link'] ?: '#cta';
    $price       = $c['price'] ?? '';
    $price_small = $c['price_small'] ?? '';
?>
<div class="act-modal" id="<?php echo esc_attr($modal_id); ?>" hidden>
    <div class="act-modal__overlay" data-modal-close=""></div>
    <div class="act-modal__dialog">
        <button class="act-modal__close" type="button" data-modal-close="" aria-label="Закрыть">
            <?php echo $close_svg; ?>
        </button>

        <?php if ($modal_image) : ?>
        <img class="act-modal__photo" src="<?php echo esc_url($modal_image); ?>" alt="<?php echo esc_attr($modal_title); ?>" loading="lazy">
        <?php endif; ?>

        <div class="act-modal__body">
            <?php if ($modal_badge) : ?>
            <p class="act-modal__badge"><?php echo esc_html($modal_badge); ?></p>
            <?php endif; ?>

            <?php if ($modal_title) : ?>
            <h3 class="act-modal__title"><?php echo esc_html($modal_title); ?></h3>
            <?php endif; ?>

            <?php if ($modal_meta) : ?>
            <p class="act-modal__meta"><?php echo esc_html($modal_meta); ?></p>
            <?php endif; ?>

            <?php if ($modal_desc || !empty($modal_items)) : ?>
            <div class="act-modal__details">
                <?php if ($modal_desc) : ?>
                <?php echo wp_kses_post($modal_desc); ?>
                <?php endif; ?>

                <?php if (!empty($modal_items)) : ?>
                <ul>
                    <?php foreach ($modal_items as $it) :
                        $t = is_array($it) ? ($it['text'] ?? '') : $it;
                        if (!$t) continue;

                        /* Если в строке есть "Заголовок — Описание" (em/en/regular dash с пробелами),
                           обернём заголовок в <strong> */
                        if (preg_match('/^(.+?)\s+[—–-]\s+(.+)$/u', $t, $matches)) :
                    ?>
                    <li><strong><?php echo esc_html($matches[1]); ?></strong> — <?php echo esc_html($matches[2]); ?></li>
                    <?php else : ?>
                    <li><?php echo esc_html($t); ?></li>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <?php if ($price || $cta_text) : ?>
        <div class="act-modal__body" style="padding-top: 0;">
            <div class="act-modal__foot">
                <?php if ($price) : ?>
                <p class="act-modal__price"><?php echo esc_html($price); ?>
                    <?php if ($price_small) : ?> <small><?php echo esc_html($price_small); ?></small><?php endif; ?>
                </p>
                <?php endif; ?>

                <a href="<?php echo esc_url($link); ?>" class="btn btn--gold js-open-modal-callback">Узнать подробности</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; endif; ?>
<?php endforeach; ?>