<?php
/**
 * Шаблон «Все статьи»
 * Подключить: include __DIR__ . '/includes/section-articles-all.php';
 */

 $aa_cards = get_post_meta(get_the_ID(), 'aa_cards', true);
if (!is_array($aa_cards)) $aa_cards = array();

/* Берём категории из общего метабокса (ap_cats) */
 $ap_cats = get_post_meta(get_the_ID(), 'ap_cats', true);
if (!is_array($ap_cats)) $ap_cats = array();

if (!$aa_cards) return;
?>

<section class="c-articles-all section" id="all-articles">
    <div class="container">
        <div class="section-head reveal">
            <p class="eyebrow">Полезное</p>
            <h2 class="section-title">Все статьи</h2>
        </div>
        <div class="c-articles-all__grid reveal">
            <?php foreach ($aa_cards as $card) :
                $link = !empty($card['link']) ? esc_url($card['link']) : '';

                /* Название категории по slug */
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
            <article class="c-article-card c-article-card--compact" data-cat="<?php echo esc_attr(!empty($card['cat_slug']) ? $card['cat_slug'] : ''); ?>">
                <div class="c-article-card__img">
                    <?php if (!empty($card['image'])) : ?>
                        <img src="<?php echo esc_url($card['image']); ?>" alt="<?php echo esc_attr($card['title']); ?>" loading="lazy">
                    <?php endif; ?>
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
    <?php if ($ap_cats && $ap_cards) : ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>
<?php endif; ?>
</section>
