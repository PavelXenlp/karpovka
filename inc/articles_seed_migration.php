<?php

function karpovka_seed_articles_data()
{
    return array(
        array(
            'key'      => 'spa-day-20260918',
            'slug'     => 'kak-sostavit-idealnyy-spa-den',
            'title'    => 'Как составить идеальный SPA-день',
            'excerpt'  => 'Пошаговый план отдыха: как сочетать бассейн, сауну, массаж и спокойные паузы, чтобы восстановиться без спешки.',
            'category' => 'SPA и восстановление',
            'tags'     => array('SPA', 'массаж', 'восстановление'),
            'image'    => 'assets/img/imgs/IMG_0119-2.jpg.jpeg',
            'content'  => '<h2>Начните с воды и тишины</h2><p>Хороший SPA-день не требует плотного расписания. Оставьте время на спокойное знакомство с пространством, плавание и отдых между процедурами. Так организм успевает переключиться с повседневного ритма на восстановление.</p><h2>Чередуйте тепло и отдых</h2><p>После бассейна можно перейти в сауну или хаммам, а затем сделать паузу и восстановить водный баланс. Не стоит объединять все процедуры подряд: комфортный темп важнее количества посещённых зон.</p><h2>Выберите массаж под своё состояние</h2><p>Расслабляющий массаж подходит для снятия общего напряжения, а более интенсивные программы лучше выбирать после консультации со специалистом. Сообщите мастеру о самочувствии и желаемой интенсивности заранее.</p><blockquote><p>Главный ориентир SPA-дня — не расписание, а ваше самочувствие.</p></blockquote><h2>Завершите день без спешки</h2><p>После процедур запланируйте спокойный ужин, прогулку или отдых в номере. Такой финал помогает сохранить ощущение лёгкости и сделать перезагрузку полноценной.</p>',
        ),
        array(
            'key'      => 'pool-rest-20260918',
            'slug'     => 'otdyh-u-basseyna-chto-vzyat-s-soboy',
            'title'    => 'Отдых у бассейна: что взять с собой',
            'excerpt'  => 'Короткий список вещей и простые рекомендации для комфортного дня в бассейне и банном комплексе.',
            'category' => 'Отдых в Карповке',
            'tags'     => array('бассейн', 'баня', 'семейный отдых'),
            'image'    => 'assets/img/imgs/IMG_0632-2.jpg.jpeg',
            'content'  => '<h2>Соберите главное заранее</h2><p>Для посещения бассейна понадобятся купальный костюм, нескользящие сланцы и средства личной гигиены. Остальные принадлежности и условия посещения лучше уточнить при бронировании.</p><h2>Планируйте отдых с детьми</h2><p>Если вы приезжаете всей семьёй, возьмите привычные детские принадлежности и заранее обсудите с ребёнком правила поведения у воды. Взрослый должен оставаться рядом на протяжении всего посещения.</p><h2>Оставьте время на банный комплекс</h2><p>Бассейн удобно сочетать с мягким прогревом и спокойным отдыхом. Делайте перерывы, пейте воду и ориентируйтесь на самочувствие, особенно если банные процедуры для вас непривычны.</p><h2>Не торопитесь после воды</h2><p>Завершите посещение тёплым душем и небольшой паузой. Прогулка по территории или отдых в номере помогут мягко перейти к следующей части дня.</p>',
        ),
    );
}

function karpovka_seed_article_image($post_id, $relative_path, $alt)
{
    if (has_post_thumbnail($post_id)) return (int) get_post_thumbnail_id($post_id);

    $attachment_ids = get_posts(array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'meta_key'       => '_karpovka_seed_article_image',
        'meta_value'     => $relative_path,
    ));
    if ($attachment_ids) {
        set_post_thumbnail($post_id, $attachment_ids[0]);
        return (int) $attachment_ids[0];
    }

    $source = trailingslashit(get_template_directory()) . ltrim($relative_path, '/\\');
    if (! is_readable($source)) return 0;

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $temporary = wp_tempnam(basename($source));
    if (! $temporary || ! copy($source, $temporary)) return 0;
    $attachment_id = media_handle_sideload(array(
        'name'     => basename($source),
        'tmp_name' => $temporary,
    ), $post_id, $alt);
    if (is_wp_error($attachment_id)) {
        @unlink($temporary);
        return 0;
    }

    update_post_meta($attachment_id, '_karpovka_seed_article_image', $relative_path);
    update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt);
    set_post_thumbnail($post_id, $attachment_id);
    return (int) $attachment_id;
}

function karpovka_seed_article($item)
{
    $matches = get_posts(array(
        'post_type'      => 'articles',
        'post_status'    => array('publish', 'draft', 'pending', 'private'),
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'meta_key'       => '_karpovka_seed_article_key',
        'meta_value'     => $item['key'],
    ));
    $post_id = $matches ? (int) $matches[0] : 0;

    if (! $post_id) {
        $post_id = wp_insert_post(wp_slash(array(
            'post_type'    => 'articles',
            'post_status'  => 'publish',
            'post_name'    => $item['slug'],
            'post_title'   => $item['title'],
            'post_excerpt' => $item['excerpt'],
            'post_content' => $item['content'],
        )), true);
        if (is_wp_error($post_id)) return 0;
        update_post_meta($post_id, '_karpovka_seed_article_key', $item['key']);
        update_post_meta($post_id, 'hero_eyebrow', 'Полезная статья');
        update_post_meta($post_id, 'hero_title', $item['title']);
        update_post_meta($post_id, 'hero_subtitle', $item['excerpt']);
        wp_set_object_terms($post_id, array($item['category']), 'category', false);
        wp_set_object_terms($post_id, $item['tags'], 'post_tag', false);
    }

    $attachment_id = karpovka_seed_article_image($post_id, $item['image'], $item['title']);
    if ($attachment_id && ! get_post_meta($post_id, 'hero_photo', true)) {
        update_post_meta($post_id, 'hero_photo', wp_get_attachment_url($attachment_id));
    }
    return (int) $post_id;
}

function karpovka_run_articles_seed_migration()
{
    $ids = array();
    foreach (karpovka_seed_articles_data() as $item) {
        $post_id = karpovka_seed_article($item);
        if ($post_id) $ids[] = $post_id;
    }
    if (count($ids) !== 2) return false;

    update_post_meta($ids[0], 'article_related_ids', array($ids[1]));
    update_post_meta($ids[1], 'article_related_ids', array($ids[0]));

    $archive_pages = get_posts(array(
        'post_type'      => 'page',
        'post_status'    => array('publish', 'draft', 'private'),
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_key'       => '_wp_page_template',
        'meta_value'     => 'articles.php',
    ));
    foreach ($archive_pages as $page_id) {
        $manual = get_post_meta($page_id, '_karpovka_article_references_manual_20260918', true);
        $popular = get_post_meta($page_id, 'ap_article_ids', true);
        if ((! is_array($manual) || ! in_array('ap_article_ids', $manual, true)) && empty($popular)) {
            update_post_meta($page_id, 'ap_article_ids', $ids);
        }
    }

    update_option('karpovka_articles_seed_migration_20260918', $ids, false);
    return true;
}

add_action('admin_init', 'karpovka_run_articles_seed_migration', 30);
