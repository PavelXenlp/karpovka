<?php
 $wanted = $GLOBALS['offers_block_id'] ?? '';
if (!$wanted) return;

 $sections = get_post_meta(get_the_ID(), 'offers_sections', true);
if (!is_array($sections) || empty($sections)) return;

 $sec = null;
foreach ($sections as $s) {
    if (isset($s['section_id']) && $s['section_id'] === $wanted) {
        $sec = $s;
        break;
    }
}
if (!$sec) return;

 $cls = !empty($sec['section_class']) ? $sec['section_class'] : 'c-offers-current';
 $sid = !empty($sec['section_id']) ? $sec['section_id'] : 'offers';
?>
<section class="<?php echo esc_attr($cls); ?>" id="<?php echo esc_attr($sid); ?>">
    <div class="container">
        <div class="section-head section-head--center reveal">
            <?php if (!empty($sec['eyebrow'])) : ?>
                <p class="eyebrow"><?php echo esc_html($sec['eyebrow']); ?></p>
            <?php endif; ?>
            <?php if (!empty($sec['title_before']) || !empty($sec['title_em'])) : ?>
                <h2 class="section-title">
                    <?php echo esc_html($sec['title_before']); ?>
                    <?php if (!empty($sec['title_em'])) : ?>
                        <span class="gold-header"><em><?php echo esc_html($sec['title_em']); ?></em></span>
                    <?php endif; ?>
                </h2>
            <?php endif; ?>
            <?php if (!empty($sec['sub'])) : ?>
                <p class="section-sub"><?php echo esc_html($sec['sub']); ?></p>
            <?php endif; ?>
        </div>
        <div class="c-offers-grid reveal">
            <?php if (!empty($sec['cards']) && is_array($sec['cards'])) : foreach ($sec['cards'] as $c) :
                $badge_cls = 'c-offers-card__badge' . (($c['badge_hot'] ?? '') === '1' ? ' c-offers-card__badge--hot' : '');
            ?>
            <article class="c-offers-card">
                <div class="c-offers-card__img">
                    <?php if (!empty($c['image'])) : ?>
                    <img src="<?php echo esc_url($c['image']); ?>" alt="<?php echo esc_attr($c['title'] ?? ''); ?>" loading="lazy">
                    <?php endif; ?>
                    <?php if (!empty($c['badge_text'])) : ?>
                    <span class="<?php echo $badge_cls; ?>"><?php echo esc_html($c['badge_text']); ?></span>
                    <?php endif; ?>
                </div>
                <div class="c-offers-card__body">
                    <h3 class="c-offers-card__title"><?php echo esc_html($c['title'] ?? ''); ?></h3>
                    <p class="c-offers-card__desc"><?php echo esc_html($c['desc'] ?? ''); ?></p>
                    <?php if (!empty($c['conditions']) && is_array($c['conditions'])) : ?>
                    <div class="c-offers-card__conditions">
                        <?php foreach ($c['conditions'] as $cn) : ?>
                        <span class="c-offers-card__cond">
                            <?php if (!empty($cn['icon'])) : ?><i class="<?php echo esc_attr($cn['icon']); ?>"></i> <?php endif; ?>
                            <?php echo esc_html($cn['text'] ?? ''); ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($c['link'])) : ?>
                    <a href="<?php echo esc_url($c['link']); ?>" class="c-offers-card__link">Подробнее <i class="fa-solid fa-arrow-right"></i></a>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>