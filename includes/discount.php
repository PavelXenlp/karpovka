<?php
/**
 * Шаблон секции «Акция / Промо»
 * Подключить в шаблоне страницы: include __DIR__ . '/includes/section-promo.php';
 */

 $promo_over  = get_post_meta(get_the_ID(), 'promo_over', true);
 $promo_title = get_post_meta(get_the_ID(), 'promo_title', true);
 $promo_gold  = get_post_meta(get_the_ID(), 'promo_gold', true);
 $promo_sub   = get_post_meta(get_the_ID(), 'promo_sub', true);
 $promo_intro = get_post_meta(get_the_ID(), 'promo_intro', true);
 $promo_image = get_post_meta(get_the_ID(), 'promo_image', true);
 $promo_badge = get_post_meta(get_the_ID(), 'promo_badge', true);
 $promo_desc  = get_post_meta(get_the_ID(), 'promo_desc', true);
 $promo_advs  = get_post_meta(get_the_ID(), 'promo_advantages', true);
 $promo_feats = get_post_meta(get_the_ID(), 'promo_features', true);

if (!is_array($promo_advs))  $promo_advs  = array();
if (!is_array($promo_feats)) $promo_feats = array();

 $has_content = $promo_desc || $promo_advs || $promo_feats;

if (!$promo_title && !$promo_image && !$has_content) return;
?>

<section class="section promo-section" id="promo">

    <?php if ($promo_over || $promo_title || $promo_sub || $promo_intro) : ?>
    <div class="container">
        <div class="section-head promo-head reveal">
            <?php if ($promo_over) : ?>
                <span class="section-over"><?php echo esc_html($promo_over); ?></span>
            <?php endif; ?>

            <?php if ($promo_title || $promo_gold) : ?>
                <h2 class="section-title">
                    <?php echo esc_html($promo_title); ?>
                    <?php if ($promo_gold) : ?>
                        <em><?php echo esc_html($promo_gold); ?></em>
                    <?php endif; ?>
                </h2>
            <?php endif; ?>

            <?php if ($promo_sub) : ?>
                <p class="section-sub"><?php echo esc_html($promo_sub); ?></p>
            <?php endif; ?>

            <?php if ($promo_intro) : ?>
                <div class="promo-intro"><?php echo wp_kses_post($promo_intro); ?></div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($promo_image) : ?>
    <div class="promo-image-wrap reveal">
        <?php if ($promo_badge) : ?>
            <span class="promo-badge"><?php echo esc_html($promo_badge); ?></span>
        <?php endif; ?>
        <div class="promo-image-overlay"></div>
        <img src="<?php echo esc_url($promo_image); ?>" alt="<?php echo esc_attr($promo_title); ?>" loading="lazy">
    </div>
    <?php endif; ?>

    <?php if ($has_content) : ?>
    <div class="container">
        <div class="promo-body reveal">

                        <?php if ($promo_desc) : ?>
                <div class="promo-desc">
                    <?php
                    // Двойной перенос = новый абзац, одиночный = <br>
                    $paragraphs = preg_split('/\n\s*\n/', $promo_desc);
                    foreach ($paragraphs as $p) :
                        $p = trim($p);
                        if ($p === '') continue;
                        // жирный *текст*
                        $p = preg_replace('/\*(.+?)\*/', '<strong>$1</strong>', $p);
                        // ссылки [текст](url)
                        $p = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2">$1</a>', $p);
                    ?>
                        <p><?php echo nl2br(esc_html($p)); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($promo_advs || $promo_feats) : ?>
            <div class="promo-details<?php echo ($promo_advs && $promo_feats) ? ' promo-details--cols' : ''; ?>">

                <?php if ($promo_advs) : ?>
                <div class="promo-details__col">
                    <div class="promo-advantages">
                        <?php foreach ($promo_advs as $adv) : ?>
                            <?php if (!empty($adv)) : ?>
                            <span class="promo-adv"><?php echo esc_html($adv); ?></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($promo_feats) : ?>
                <div class="promo-details__col">
                    <div class="promo-features">
                        <?php foreach ($promo_feats as $feat) : ?>
                            <?php if (!empty($feat)) : ?>
                            <div class="promo-feature"><?php echo wp_kses_post($feat); ?></div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
            <?php endif; ?>

        </div>
    </div>
    <?php endif; ?>

</section>