<?php
$post_id = get_the_ID();
$layout  = get_post_meta($post_id, 'spa_articles_layout', true) ?: 'feature_rows';
$eyebrow = get_post_meta($post_id, 'spa_articles_eyebrow', true) ?: 'Читателям';
$title   = get_post_meta($post_id, 'spa_articles_title', true) ?: 'Полезные статьи';
$gold    = get_post_meta($post_id, 'spa_articles_gold', true) ?: 'статьи';
$sec_id  = get_post_meta($post_id, 'spa_articles_id', true) ?: 'articles';
$ids     = get_post_meta($post_id, 'spa_articles_article_ids', true);
if (! is_array($ids) && function_exists('karpovka_resolve_legacy_section_article_ids')) {
    $ids = karpovka_resolve_legacy_section_article_ids($post_id);
}
$articles = karpovka_article_cards_from_ids(is_array($ids) ? $ids : array());
if (! $articles) return;
?>

<?php if ('spa_cards' === $layout) : ?>
<section class="c-articles-all" id="<?php echo esc_attr($sec_id); ?>">
    <div class="container">
        <div class="section-head reveal">
            <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
            <h2 class="section-title"><?php echo esc_html($title); ?> <?php if ($gold) : ?><em><?php echo esc_html($gold); ?></em><?php endif; ?></h2>
        </div>
        <div class="c-articles-all__grid reveal">
            <?php foreach ($articles as $article) : ?>
                <article class="c-article-card c-article-card--compact" data-cat="<?php echo esc_attr($article['cat_slug']); ?>">
                    <?php if ($article['img']) : ?><div class="c-article-card__img"><img src="<?php echo esc_url($article['img']); ?>" alt="" loading="lazy"><?php if ($article['tag']) : ?><span class="c-article-card__cat"><?php echo esc_html($article['tag']); ?></span><?php endif; ?></div><?php endif; ?>
                    <div class="c-article-card__body">
                        <h3 class="c-article-card__title"><?php echo esc_html($article['title']); ?></h3>
                        <?php if ($article['text']) : ?><p class="c-article-card__desc"><?php echo esc_html($article['text']); ?></p><?php endif; ?>
                        <div class="c-article-card__meta"><span class="c-article-card__read"><?php echo esc_html($article['time']); ?></span></div>
                        <a href="<?php echo esc_url($article['link']); ?>" class="c-article-card__link">Подробнее <span aria-hidden="true">→</span></a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php else : $feature = array_shift($articles); ?>
<section class="section acc-articles" id="<?php echo esc_attr($sec_id); ?>">
    <div class="section-head reveal">
        <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
        <h2 class="section-title"><?php echo esc_html($title); ?> <?php if ($gold) : ?><em><?php echo esc_html($gold); ?></em><?php endif; ?></h2>
    </div>
    <div class="acc-articles__layout reveal">
        <article class="acc-feature">
            <?php if ($feature['img']) : ?><img class="acc-feature__bg" src="<?php echo esc_url($feature['img']); ?>" alt="" loading="lazy"><?php endif; ?>
            <div class="acc-feature__overlay"></div>
            <div class="acc-feature__content">
                <?php if ($feature['tag']) : ?><p class="acc-feature__tag"><?php echo esc_html($feature['tag']); ?></p><?php endif; ?>
                <h3 class="acc-feature__title"><?php echo esc_html($feature['title']); ?></h3>
                <?php if ($feature['text']) : ?><p class="acc-feature__text"><?php echo esc_html($feature['text']); ?></p><?php endif; ?>
                <a class="acc-feature__link" href="<?php echo esc_url($feature['link']); ?>">Читать статью <span aria-hidden="true">→</span></a>
            </div>
        </article>
        <?php if ($articles) : ?>
            <div class="acc-articles__rows">
                <?php foreach ($articles as $index => $article) : ?>
                    <article class="acc-row">
                        <span class="acc-row__num" aria-hidden="true"><?php echo esc_html(str_pad((string) ($index + 2), 2, '0', STR_PAD_LEFT)); ?></span>
                        <div class="acc-row__body">
                            <h3 class="acc-row__title"><a href="<?php echo esc_url($article['link']); ?>"><?php echo esc_html($article['title']); ?></a></h3>
                            <?php if ($article['text']) : ?><p class="acc-row__text"><?php echo esc_html($article['text']); ?></p><?php endif; ?>
                            <div class="acc-row__meta"><?php if ($article['tag']) : ?><span><?php echo esc_html($article['tag']); ?></span><?php endif; ?><span><?php echo esc_html($article['time']); ?></span></div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>
