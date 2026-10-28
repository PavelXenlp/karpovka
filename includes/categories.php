<?php $article_categories = karpovka_article_categories(); ?>
<?php if ($article_categories) : ?>
<section class="c-articles-cats section" id="categories">
    <div class="container">
        <div class="section-head section-head--center reveal">
            <p class="eyebrow">Разделы</p>
            <h2 class="section-title"><span class="gold-header"><em>Темы</em></span> статей</h2>
            <p class="section-sub">Выберите интересующую категорию</p>
        </div>
        <div class="c-articles-cats__filters reveal" id="articleFilters">
            <button type="button" class="c-articles-cats__filter is-active" data-filter="all">Все статьи</button>
            <?php foreach ($article_categories as $category) : ?>
                <button type="button" class="c-articles-cats__filter" data-filter="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></button>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
