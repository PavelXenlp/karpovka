<?php
$excerpt = get_the_excerpt();
$content = get_the_content();
if ('' === trim($excerpt)) {
    $excerpt = (string) get_post_meta(get_the_ID(), 'ab_lead', true);
}
if ('' === trim($content) && function_exists('karpovka_article_legacy_blocks_to_content')) {
    $content = karpovka_article_legacy_blocks_to_content(get_post_meta(get_the_ID(), 'ab_blocks', true));
}
$tags    = get_the_tags();
$legacy_tags = $tags ? array() : get_post_meta(get_the_ID(), 'ab_tags', true);
$legacy_tags = is_array($legacy_tags) ? $legacy_tags : array();
$url     = get_permalink();
$title   = get_the_title();
?>

<section class="c-article-body">
    <div class="container">
        <div class="c-article-layout reveal">
            <article class="c-article-content">
                <?php if ($excerpt) : ?>
                    <div class="c-article-lead"><p><?php echo esc_html($excerpt); ?></p></div>
                <?php endif; ?>

                <?php echo apply_filters('the_content', $content); ?>

                <?php wp_link_pages(); ?>

                <?php if ($tags) : ?>
                    <div class="c-article-tags">
                        <span class="c-article-tags__label">Теги:</span>
                        <?php foreach ($tags as $tag) : ?>
                            <a href="<?php echo esc_url(get_tag_link($tag)); ?>" class="c-article-tags__tag"><?php echo esc_html($tag->name); ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php elseif ($legacy_tags) : ?>
                    <div class="c-article-tags">
                        <span class="c-article-tags__label">Теги:</span>
                        <?php foreach ($legacy_tags as $tag) : ?><span class="c-article-tags__tag"><?php echo esc_html($tag); ?></span><?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </article>

            <aside class="c-article-sidebar">
                <div class="c-sidebar-card c-sidebar-card--accent">
                    <h2 class="c-sidebar-card__title">Забронировать отдых</h2>
                    <p class="c-sidebar-card__text">Поможем подобрать проживание и программу отдыха в Карповке.</p>
                    <button type="button" class="btn btn--gold js-open-modal-booking">Оставить заявку</button>
                    <a href="tel:+79127813933" class="btn btn--ghost">Позвонить</a>
                </div>

                <div class="c-sidebar-card">
                    <h2 class="c-sidebar-card__title">Поделиться</h2>
                    <div class="c-sidebar-share">
                        <a href="https://vk.com/share.php?url=<?php echo rawurlencode($url); ?>&title=<?php echo rawurlencode($title); ?>" class="c-sidebar-share__link" target="_blank" rel="noopener" aria-label="Поделиться во ВКонтакте"><span aria-hidden="true">VK</span></a>
                        <a href="https://t.me/share/url?url=<?php echo rawurlencode($url); ?>&text=<?php echo rawurlencode($title); ?>" class="c-sidebar-share__link" target="_blank" rel="noopener" aria-label="Поделиться в Telegram"><span aria-hidden="true">TG</span></a>
                        <button type="button" class="c-sidebar-share__link" data-copy-link="<?php echo esc_url($url); ?>" aria-label="Скопировать ссылку">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        </button>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
