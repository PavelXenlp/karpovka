<?php
/**
 * Шаблон «Тело статьи» — конструктор блоков
 * Подключить: include __DIR__ . '/includes/section-article-body.php';
 */

 $ab_lead   = get_post_meta(get_the_ID(), 'ab_lead', true);
 $ab_blocks = get_post_meta(get_the_ID(), 'ab_blocks', true);
 $ab_tags   = get_post_meta(get_the_ID(), 'ab_tags', true);
 $cta_t     = get_post_meta(get_the_ID(), 'ab_cta_title', true);
 $cta_txt   = get_post_meta(get_the_ID(), 'ab_cta_text', true);
 $cta_btn   = get_post_meta(get_the_ID(), 'ab_cta_btn', true);
 $cta_url   = get_post_meta(get_the_ID(), 'ab_cta_url', true);
 $cta_tel   = get_post_meta(get_the_ID(), 'ab_cta_tel', true);

if (!is_array($ab_blocks)) $ab_blocks = array();
if (!is_array($ab_tags))   $ab_tags   = array();

if (!$ab_lead && !$ab_blocks && !$ab_tags) return;

/* Хелпер: *текст* → золотой */
if (!function_exists('ab_g')) {
    function ab_g($t) {
        return preg_replace('/\*(.+?)\*/', '<span class="gold-header"><em>$1</em></span>', $t);
    }
}

/* Хелпер: двойной Enter → абзацы */
function ab_p($text) {
    $parts = preg_split('/\n\s*\n/', $text);
    $html = '';
    foreach ($parts as $p) {
        $p = trim($p);
        if ($p === '') continue;
        $html .= '<p>' . nl2br(ab_g($p)) . '</p>';
    }
    return $html;
}

/* Собираем оглавление из заголовков */
 $toc = array();
foreach ($ab_blocks as $b) {
    if ($b['type'] === 'heading' && !empty($b['heading_text'])) {
        $toc[] = array(
            'text' => $b['heading_text'],
            'id'   => !empty($b['heading_id']) ? $b['heading_id'] : ''
        );
    }
}
?>

<section class="c-article-body">
    <div class="container">
        <div class="c-article-layout reveal">

            <!-- Левая колонка -->
            <div class="c-article-content">

                <!-- Lead -->
                <?php if ($ab_lead) : ?>
                <div class="c-article-lead">
                    <?php echo ab_p($ab_lead); ?>
                </div>
                <?php endif; ?>

                <?php foreach ($ab_blocks as $b) :

                    /* --- Заголовок --- */
                    if ($b['type'] === 'heading' && !empty($b['heading_text'])) :
                        $hid = !empty($b['heading_id']) ? ' id="' . esc_attr($b['heading_id']) . '"' : '';
                ?>
                    <h2<?php echo $hid; ?>><?php echo ab_g(esc_html($b['heading_text'])); ?></h2>

                    <?php
                    /* --- Текст --- */
                    elseif ($b['type'] === 'text' && !empty($b['text'])) :
                        echo ab_p($b['text']);

                    /* --- Изображение --- */
                    elseif ($b['type'] === 'image' && !empty($b['image'])) :
                        $wide = !empty($b['image_wide']) ? ' c-article-img--wide' : '';
                    ?>
                    <img class="c-article-img<?php echo $wide; ?>" src="<?php echo esc_url($b['image']); ?>" alt="<?php echo esc_attr(!empty($b['image_alt'])?$b['image_alt']:''); ?>" loading="lazy">

                    <?php
                    /* --- Цитата --- */
                    elseif ($b['type'] === 'pullquote' && !empty($b['quote'])) :
                    ?>
                    <div class="c-article-pullquote">
                        <svg class="c-article-pullquote__icon" aria-hidden="true"><use href="#i-quote"></use></svg>
                        <blockquote><?php echo ab_g(nl2br(esc_html($b['quote']))); ?></blockquote>
                    </div>

                    <?php
                    /* --- Таблица --- */
                    elseif ($b['type'] === 'table' && !empty($b['table_headers'])) :
                        $hdrs = array_map('trim', explode('|', $b['table_headers']));
                        $rows = array();
                        if (!empty($b['table_rows'])) {
                            foreach (explode("\n", $b['table_rows']) as $line) {
                                $line = trim($line);
                                if ($line === '') continue;
                                $rows[] = array_map('trim', explode('|', $line));
                            }
                        }
                    ?>
                    <div class="c-article-table-wrap">
                        <table class="c-article-table">
                            <thead><tr>
                                <?php foreach ($hdrs as $h) : ?><th><?php echo esc_html($h); ?></th><?php endforeach; ?>
                            </tr></thead>
                            <tbody>
                                <?php foreach ($rows as $row) : ?>
                                <tr>
                                    <?php foreach ($row as $cell) : ?><td><?php echo esc_html($cell); ?></td><?php endforeach; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Мобильная карточная версия -->
                        <div class="c-article-table-wrap--cards">
                            <?php for ($col = 1; $col < count($hdrs); $col++) : ?>
                            <div class="c-table-card">
                                <div class="c-table-card__head"><?php echo esc_html($hdrs[$col]); ?></div>
                                <?php foreach ($rows as $row) : ?>
                                <div class="c-table-card__row">
                                    <span class="c-table-card__label"><?php echo esc_html($row[0]); ?></span>
                                    <span class="c-table-card__val"><?php echo isset($row[$col]) ? esc_html($row[$col]) : ''; ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <?php endif; ?>
                <?php endforeach; ?>

                <!-- Теги -->
                <?php if ($ab_tags) : ?>
                <div class="c-article-tags">
                    <span class="c-article-tags__label">Теги:</span>
                    <?php foreach ($ab_tags as $tag) : ?>
                        <a href="#" class="c-article-tags__tag"><?php echo esc_html($tag); ?></a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

            </div>

            <!-- Правая колонка — боковая панель -->
            <aside class="c-article-sidebar">

                <!-- Оглавление -->
                <?php if ($toc) : ?>
                <div class="c-sidebar-card">
                    <h4 class="c-sidebar-card__title">Содержание</h4>
                    <ul class="c-sidebar-toc">
                        <?php foreach ($toc as $item) : ?>
                        <li><a href="#<?php echo esc_attr($item['id']); ?>"><?php echo esc_html($item['text']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Забронировать -->
                <?php if ($cta_txt || $cta_btn || $cta_tel) : ?>
                <div class="c-sidebar-card c-sidebar-card--accent">
                    <?php if ($cta_t) : ?><h4 class="c-sidebar-card__title"><?php echo esc_html($cta_t); ?></h4><?php endif; ?>
                    <?php if ($cta_txt) : ?><p class="c-sidebar-card__text"><?php echo esc_html($cta_txt); ?></p><?php endif; ?>
                    <?php if ($cta_btn && $cta_url) : ?>
                        <a href="<?php echo esc_url($cta_url); ?>" class="btn btn--gold"><?php echo esc_html($cta_btn); ?></a>
                    <?php endif; ?>
                    <?php if ($cta_tel) : ?>
                        <a href="tel:<?php echo esc_attr($cta_tel); ?>" class="btn btn--ghost">Позвонить</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Поделиться -->
                <div class="c-sidebar-card">
                    <h4 class="c-sidebar-card__title">Поделиться</h4>
                    <div class="c-sidebar-share">
                        <a href="#" class="c-sidebar-share__link" aria-label="ВКонтакте"><svg class="social__icon" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 0C18.627 0 24 5.373 24 12C24 18.627 18.627 24 12 24C5.373 24 0 18.627 0 12C0 5.373 5.373 0 12 0ZM11.77 16.43h.94s.28-.03.43-.19c.14-.14.13-.4.13-.4s-.02-1.26.56-1.45c.57-.18 1.31 1.22 2.09 1.75.59.4 1.04.31 1.04.31l2.09-.03s1.09-.07.57-.93c-.04-.07-.3-.64-1.57-1.82-1.32-1.23-1.15-1.03.45-3.16.97-1.3 1.36-2.09 1.24-2.43-.11-.32-.83-.24-.83-.24l-2.35.02s-.17-.02-.3.06c-.13.08-.21.26-.21.26s-.38 1.01-.89 1.87c-1.07 1.82-1.5 1.91-1.67 1.8-.4-.26-.3-1.05-.3-1.61 0-1.75.27-2.48-.52-2.67-.26-.06-.45-.1-1.12-.11-.85-.01-1.58 0-1.99.2-.27.14-.48.44-.36.46.16.02.51.1.7.35.24.33.23 1.05.23 1.05s.14 2.01-.32 2.26c-.32.17-.75-.18-1.71-1.83-.49-.85-.86-1.78-.86-1.78s-.07-.17-.2-.27c-.15-.11-.37-.14-.37-.14l-2.23.02s-.34.01-.46.16c-.11.13-.01.4-.01.4s1.75 4.09 3.73 6.15c1.82 1.89 3.88 1.76 3.88 1.76z"/></svg></a>
                        <a href="#" class="c-sidebar-share__link" aria-label="Telegram"><svg class="social__icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.18.18 0 0 0-.05-.2c-.06-.06-.14-.04-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/></svg></a>
                        <a href="#" class="c-sidebar-share__link" aria-label="Скопировать ссылку"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg></a>
                    </div>
                </div>

            </aside>
        </div>
    </div>
</section>