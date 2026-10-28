<?php
$categories = get_the_terms(get_the_ID(), 'category');
$categories = is_array($categories) ? $categories : array();
$category   = $categories ? $categories[0] : null;
$legacy_category = $category ? '' : trim((string) get_post_meta(get_the_ID(), 'am_category', true));
$date       = get_the_date('j F Y');
$read_time  = karpovka_article_reading_time();
?>

<section class="c-article-meta">
    <div class="container">
        <nav class="c-article-breadcrumb reveal" aria-label="Хлебные крошки">
            <a href="<?php echo esc_url(karpovka_articles_page_url()); ?>">Статьи</a>
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
            <span><?php echo esc_html(get_the_title()); ?></span>
        </nav>

        <div class="c-article-info reveal">
            <div class="c-article-info__left">
                <?php if ($category) : ?>
                    <a class="c-article-info__cat" href="<?php echo esc_url(get_term_link($category)); ?>"><?php echo esc_html($category->name); ?></a>
                    <span class="c-article-info__sep">·</span>
                <?php elseif ($legacy_category) : ?>
                    <span class="c-article-info__cat"><?php echo esc_html($legacy_category); ?></span>
                    <span class="c-article-info__sep">·</span>
                <?php endif; ?>
                <time class="c-article-info__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html($date); ?></time>
            </div>
            <div class="c-article-info__right">
                <span class="c-article-info__read">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <?php echo esc_html($read_time . ' мин чтения'); ?>
                </span>
            </div>
        </div>
    </div>
</section>
