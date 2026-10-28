<?php
$articles = karpovka_article_cards_from_ids(karpovka_published_article_ids());
if (! $articles) return;
?>

<section class="c-articles-all section" id="all-articles">
    <div class="container">
        <div class="section-head reveal">
            <p class="eyebrow">Полезное</p>
            <h2 class="section-title">Все статьи</h2>
        </div>
        <div class="c-articles-all__grid reveal">
            <?php foreach ($articles as $article) : ?>
                <article class="c-article-card c-article-card--compact" data-cat="<?php echo esc_attr($article['cat_slug']); ?>">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    var filters = document.getElementById('articleFilters');
    if (!filters) return;
    var buttons = filters.querySelectorAll('[data-filter]');
    var cards = document.querySelectorAll('#all-articles [data-cat]');
    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            buttons.forEach(function (item) { item.classList.remove('is-active'); });
            button.classList.add('is-active');
            cards.forEach(function (card) {
                card.classList.toggle('is-hidden', button.dataset.filter !== 'all' && card.dataset.cat !== button.dataset.filter);
            });
        });
    });
});
</script>
