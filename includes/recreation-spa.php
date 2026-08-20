<?php
/* ===== Шаблон: Активности — Сезонные группы ===== */
 $pid = get_the_ID();
 $groups = get_post_meta($pid, 'rec_groups', true);
if (!is_array($groups) || empty($groups)) return;

 $map = [
    'summer'  => ['modifier' => 'c-spa-programs',              'bg_class' => 'c-spa-programs__bg'],
    'winter'  => ['modifier' => 'c-act-grid--3col c-spa-massage', 'bg_class' => 'c-spa-massage__bg'],
    'allyear' => ['modifier' => 'c-act-grid--3col c-spa-programs', 'bg_class' => 'c-spa-programs__bg'],
];
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
            <?php if (!empty($g['cards']) && is_array($g['cards'])) : foreach ($g['cards'] as $c) : ?>
            <article class="c-act-card">
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
                    <?php if (!empty($c['link'])) : ?>
                    <a href="<?php echo esc_url($c['link']); ?>" class="c-act-card__link">Подробнее <i class="fa-solid fa-arrow-right"></i></a>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>
<?php endforeach; ?>