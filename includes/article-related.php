<?php
$related_ids = get_post_meta(get_the_ID(), 'article_related_ids', true);
$related_ids = is_array($related_ids) ? array_values(array_filter(array_map('absint', $related_ids))) : array();
if (! $related_ids && ! metadata_exists('post', get_the_ID(), 'article_related_ids')) {
    $legacy_cards = get_post_meta(get_the_ID(), 'ap_cards', true);
    if (is_array($legacy_cards)) {
        foreach ($legacy_cards as $legacy_card) {
            if (! empty($legacy_card['article_id'])) $related_ids[] = absint($legacy_card['article_id']);
        }
        $related_ids = array_values(array_unique(array_filter($related_ids)));
    }
}
if (! $related_ids) return;

$related = get_posts(array(
    'post_type'      => 'articles',
    'post_status'    => 'publish',
    'posts_per_page' => count($related_ids),
    'post__in'       => $related_ids,
    'orderby'        => 'post__in',
));
if (! $related) return;
?>

<section class="c-article-related" id="related">
    <img class="c-article-related__bg" src="<?php echo esc_url(get_theme_file_uri('/assets/img/imgs/1.jpg_1.jpeg')); ?>" alt="" aria-hidden="true">
    <div class="container">
        <div class="section-head reveal">
            <p class="eyebrow">Читайте также</p>
            <h2 class="section-title">Похожие статьи</h2>
        </div>
        <div class="c-article-related__grid reveal">
            <?php foreach ($related as $article) :
                $categories = get_the_terms($article->ID, 'category');
                $category   = is_array($categories) && $categories ? $categories[0] : null;
                $image      = get_the_post_thumbnail_url($article->ID, 'large');
                $excerpt    = get_the_excerpt($article);
            ?>
                <article class="c-article-card c-article-card--featured">
                    <div class="c-article-card__img">
                        <?php if ($image) : ?><img src="<?php echo esc_url($image); ?>" alt="" loading="lazy"><?php endif; ?>
                        <?php if ($category) : ?><span class="c-article-card__cat"><?php echo esc_html($category->name); ?></span><?php endif; ?>
                    </div>
                    <div class="c-article-card__body">
                        <h3 class="c-article-card__title"><?php echo esc_html(get_the_title($article)); ?></h3>
                        <?php if ($excerpt) : ?><p class="c-article-card__desc"><?php echo esc_html($excerpt); ?></p><?php endif; ?>
                        <div class="c-article-card__meta">
                            <span class="c-article-card__read"><?php echo esc_html(karpovka_article_reading_time($article->ID) . ' мин чтения'); ?></span>
                        </div>
                        <a href="<?php echo esc_url(get_permalink($article)); ?>" class="c-article-card__link">Подробнее <span aria-hidden="true">→</span></a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
