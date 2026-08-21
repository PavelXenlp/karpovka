<?php
/**
 * Шаблон «Статьи: категории + популярные»
 * Подключить: include __DIR__ . '/includes/section-articles-popular.php';
 */

 $ap_cats  = get_post_meta(get_the_ID(), 'ap_cats', true);
 $ap_cards = get_post_meta(get_the_ID(), 'ap_cards', true);
if (!is_array($ap_cats))  $ap_cats  = array();
if (!is_array($ap_cards)) $ap_cards = array();

if (!$ap_cats && !$ap_cards) return;
?>

<?php if ($ap_cats) : ?>
<!-- Фильтры категорий -->
<section class="c-articles-cats section" id="categories">
    <div class="container">
        <div class="section-head section-head--center reveal">
            <p class="eyebrow">Разделы</p>
            <h2 class="section-title"><span class="gold-header"><em>Темы</em></span> статей</h2>
            <p class="section-sub">Выберите интересующую категорию</p>
        </div>
        <div class="c-articles-cats__filters reveal" id="articleFilters">
            <button class="c-articles-cats__filter is-active" data-filter="all">Все статьи</button>
            <?php foreach ($ap_cats as $cat) : ?>
                <button class="c-articles-cats__filter" data-filter="<?php echo esc_attr($cat['slug']); ?>">
                    <?php echo esc_html($cat['label']); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($ap_cards) : ?>
<!-- Популярные статьи -->
<section class="c-articles-popular" id="popular">
    <div class="container">
        <div class="c-articles-popular__grid reveal">
            <?php foreach ($ap_cards as $card) :
                $link = !empty($card['link']) ? esc_url($card['link']) : '';
            ?>
            <article class="c-article-card c-article-card--featured" data-cat="<?php echo esc_attr(!empty($card['cat_slug']) ? $card['cat_slug'] : ''); ?>">
                <div class="c-article-card__img">
                    <?php if (!empty($card['image'])) : ?>
                        <img src="<?php echo esc_url($card['image']); ?>" alt="<?php echo esc_attr($card['title']); ?>" loading="lazy">
                    <?php endif; ?>
                    <?php
                    /* Название категории — берём из cats по slug */
                    $cat_label = '';
                    if (!empty($card['cat_slug'])) {
                        foreach ($ap_cats as $cat) {
                            if ($cat['slug'] === $card['cat_slug']) {
                                $cat_label = $cat['label'];
                                break;
                            }
                        }
                    }
                    ?>
                    <?php if ($cat_label) : ?>
                        <span class="c-article-card__cat"><?php echo esc_html($cat_label); ?></span>
                    <?php endif; ?>
                </div>
                <div class="c-article-card__body">
                    <?php if (!empty($card['title'])) : ?>
                        <h3 class="c-article-card__title"><?php echo esc_html($card['title']); ?></h3>
                    <?php endif; ?>

                    <?php if (!empty($card['desc'])) : ?>
                        <p class="c-article-card__desc"><?php echo esc_html($card['desc']); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($card['read'])) : ?>
                    <div class="c-article-card__meta">
                        <span class="c-article-card__read">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <?php echo esc_html($card['read']); ?>
                        </span>
                    </div>
                    <?php endif; ?>

                    <?php if ($link) : ?>
                    <a href="<?php echo $link; ?>" class="c-article-card__link">
                        Подробнее
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($ap_cats && $ap_cards) : ?>
<script>
(function () {
    var filtersWrap = document.getElementById('articleFilters');
    if (!filtersWrap) return;

    var buttons = filtersWrap.querySelectorAll('.c-articles-cats__filter');
    var cards = document.querySelectorAll('.c-article-card[data-cat]');

    buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            buttons.forEach(function (b) { b.classList.remove('is-active'); });
            btn.classList.add('is-active');

            var filter = btn.dataset.filter;

            cards.forEach(function (card) {
                if (filter === 'all' || card.dataset.cat === filter) {
                    card.classList.remove('is-hidden');
                } else {
                    card.classList.add('is-hidden');
                }
            });
        });
    });
})();
</script>
<?php endif; ?>