<?php
/**
 * Шаблон «Мета статьи»
 * Подключить: include __DIR__ . '/includes/section-article-meta.php';
 */

 $am_parent_text = get_post_meta(get_the_ID(), 'am_parent_text', true);
 $am_parent_url  = get_post_meta(get_the_ID(), 'am_parent_url', true);
 $am_category    = get_post_meta(get_the_ID(), 'am_category', true);
 $am_date        = get_post_meta(get_the_ID(), 'am_date', true);
 $am_read_time   = get_post_meta(get_the_ID(), 'am_read_time', true);

if (!$am_category && !$am_date && !$am_read_time) return;
?>

<section class="c-article-meta">
    <div class="container">
        <?php if ($am_parent_text) : ?>
        <nav class="c-article-breadcrumb reveal">
            <a href="<?php echo $am_parent_url ? esc_url($am_parent_url) : '#'; ?>"><?php echo esc_html($am_parent_text); ?></a>
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            <?php if ($am_category) : ?>
                <span><?php echo esc_html($am_category); ?></span>
            <?php endif; ?>
        </nav>
        <?php endif; ?>

        <div class="c-article-info reveal">
            <div class="c-article-info__left">
                <?php if ($am_category) : ?>
                    <span class="c-article-info__cat"><?php echo esc_html($am_category); ?></span>
                <?php endif; ?>
                <?php if ($am_category && $am_date) : ?>
                    <span class="c-article-info__sep">·</span>
                <?php endif; ?>
                <?php if ($am_date) : ?>
                    <span class="c-article-info__date"><?php echo esc_html($am_date); ?></span>
                <?php endif; ?>
            </div>
            <?php if ($am_read_time) : ?>
            <div class="c-article-info__right">
                <span class="c-article-info__read">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <?php echo esc_html($am_read_time); ?>
                </span>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>