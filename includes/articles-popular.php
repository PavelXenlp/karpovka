<?php
$ids = get_post_meta(get_the_ID(), 'ap_article_ids', true);
if (! is_array($ids) && function_exists('karpovka_resolve_legacy_popular_article_ids')) {
    $ids = karpovka_resolve_legacy_popular_article_ids(get_the_ID());
}
$articles = karpovka_article_cards_from_ids(is_array($ids) ? $ids : array());
if (! $articles) return;
?>

<section class="c-articles-popular" id="popular">
    <div class="container">
        <div class="c-articles-popular__grid reveal">
            <?php foreach ($articles as $article) : ?>
                <article class="c-article-card c-article-card--featured" data-cat="<?php echo esc_attr($article['cat_slug']); ?>">
                    <div class="c-article-card__img">
                        <?php if ($article['img']) : ?><img src="<?php echo esc_url($article['img']); ?>" alt="" loading="lazy"><?php endif; ?>
                        <?php if ($article['tag']) : ?><span class="c-article-card__cat"><?php echo esc_html($article['tag']); ?></span><?php endif; ?>
                    </div>
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
