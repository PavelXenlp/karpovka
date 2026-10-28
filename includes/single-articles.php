<?php
get_header();

 $inc = get_template_directory() . '/includes/';
 $inc2 = get_template_directory() . '/inc/';

/* ОТЛАДКА — покажет какие файлы найдены */
echo '<div style="background:yellow;padding:16px;font-family:monospace;font-size:13px;">';
echo '<strong>ОТЛАДКА single-articles.php</strong><br><br>';
echo 'includes/ существует: ' . (is_dir($inc) ? 'ДА' : 'НЕТ') . '<br>';
echo 'inc/ существует: ' . (is_dir($inc2) ? 'ДА' : 'НЕТ') . '<br><br>';
 $all_files = [
    'section-hero.php', 'hero.php', 'section_hero.php',
    'section-article-meta.php', 'article-meta.php',
    'section-article-body.php', 'article-body.php',
    'articles-about.php', 'section-articles-seo.php', 'articles_seo.php',
];
foreach ($all_files as $f) {
    $in_inc = file_exists($inc . $f) ? '✅' : '❌';
    $in_inc2 = file_exists($inc2 . $f) ? '✅' : '❌';
    echo "$f → includes/: $in_inc | inc/: $in_inc2<br>";
}
echo '</div>';

while (have_posts()) : the_post();

    $hero_files = ['section-hero.php', 'hero.php', 'section_hero.php'];
    foreach ($hero_files as $f) {
        if (file_exists($inc . $f)) { include $inc . $f; break; }
        if (file_exists($inc2 . $f)) { include $inc2 . $f; break; }
    }

    $meta_files = ['section-article-meta.php', 'article-meta.php'];
    foreach ($meta_files as $f) {
        if (file_exists($inc . $f)) { include $inc . $f; break; }
        if (file_exists($inc2 . $f)) { include $inc2 . $f; break; }
    }

    $body_files = ['section-article-body.php', 'article-body.php'];
    foreach ($body_files as $f) {
        if (file_exists($inc . $f)) { include $inc . $f; break; }
        if (file_exists($inc2 . $f)) { include $inc2 . $f; break; }
    }

    $seo_files = ['articles-about.php', 'section-articles-seo.php', 'articles_seo.php'];
    foreach ($seo_files as $f) {
        if (file_exists($inc . $f)) { include $inc . $f; break; }
        if (file_exists($inc2 . $f)) { include $inc2 . $f; break; }
    }

endwhile;

get_footer();