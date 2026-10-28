<?php
/**
 * Custom Post Type: Статьи
 * Подключить: require __DIR__ . '/inc/articles_post_type.php';
 * Файл уже подключён в functions.php
 *
 * URL статей: /article/SLUG/ (ед.ч., совпадает с существующим форматом ссылок)
 */
if (!function_exists('kp_register_articles_cpt')) {
    function kp_register_articles_cpt() {
        register_post_type('articles', array(
            'labels' => array(
                'name'                  => 'Статьи',
                'singular_name'         => 'Статья',
                'add_new'               => 'Добавить статью',
                'add_new_item'          => 'Добавить новую статью',
                'edit_item'             => 'Редактировать статью',
                'new_item'              => 'Новая статья',
                'view_item'             => 'Просмотреть статью',
                'search_items'          => 'Искать статьи',
                'not_found'             => 'Статьи не найдены',
                'all_items'             => 'Все статьи',
                'menu_name'             => 'Статьи',
                'name_admin_bar'        => 'Статья',
            ),
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_rest'        => true,
            'has_archive'         => false,
            'rewrite'             => array('slug' => 'article', 'with_front' => false),
            'menu_icon'           => 'dashicons-media-document',
            'menu_position'       => 5,
            'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
            'taxonomies'          => array('category', 'post_tag'),
            'hierarchical'        => false,
            'capability_type'     => 'post',
            'map_meta_cap'        => true,
        ));
    }
    add_action('init', 'kp_register_articles_cpt');
}

add_action('init', function () {
    register_taxonomy_for_object_type('category', 'articles');
    register_taxonomy_for_object_type('post_tag', 'articles');
}, 11);

add_action('after_switch_theme', function () {
    kp_register_articles_cpt();
    flush_rewrite_rules();
});

function kp_articles_multiselect($selected_ids, $field_name)
{
    $selected_ids = is_array($selected_ids) ? array_values(array_unique(array_filter(array_map('absint', $selected_ids)))) : array();
    $articles = get_posts(array(
        'post_type'      => 'articles',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => array('publish', 'draft'),
    ));
    $by_id = array();
    foreach ($articles as $article) $by_id[(int) $article->ID] = $article;
    $ordered = array();
    foreach ($selected_ids as $id) if (isset($by_id[$id])) $ordered[$id] = $by_id[$id];
    foreach ($by_id as $id => $article) if (! isset($ordered[$id])) $ordered[$id] = $article;
    ?>
    <select name="<?php echo esc_attr($field_name); ?>[]" multiple size="10" style="width:100%;max-width:760px">
        <?php foreach ($ordered as $id => $article) : ?>
            <option value="<?php echo esc_attr($id); ?>" <?php selected(in_array($id, $selected_ids, true)); ?>><?php echo esc_html($article->post_title); ?></option>
        <?php endforeach; ?>
    </select>
    <p class="description">Ctrl/Command — выбрать несколько. Уже выбранные статьи расположены сверху; первая используется как ведущая.</p>
    <?php
}

function karpovka_article_card_data($article_id)
{
    $article = get_post($article_id);
    if (! $article || 'articles' !== $article->post_type || 'publish' !== $article->post_status) return null;
    $categories = get_the_terms($article->ID, 'category');
    $category = is_array($categories) && $categories ? $categories[0] : null;
    return array(
        'id'       => (int) $article->ID,
        'title'    => get_the_title($article),
        'text'     => get_the_excerpt($article),
        'img'      => get_the_post_thumbnail_url($article->ID, 'large'),
        'link'     => get_permalink($article),
        'tag'      => $category ? $category->name : '',
        'cat_slug' => $category ? $category->slug : '',
        'time'     => karpovka_article_reading_time($article->ID) . ' мин чтения',
    );
}

function karpovka_article_cards_from_ids($ids)
{
    $cards = array();
    foreach ((array) $ids as $id) {
        $card = karpovka_article_card_data(absint($id));
        if ($card) $cards[] = $card;
    }
    return $cards;
}

function karpovka_published_article_ids()
{
    static $ids = null;
    if (null === $ids) {
        $ids = get_posts(array(
            'post_type'      => 'articles',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'fields'         => 'ids',
        ));
        $ids = array_map('absint', $ids);
    }
    return $ids;
}

function karpovka_article_categories()
{
    $ids = karpovka_published_article_ids();
    if (! $ids) return array();
    $terms = wp_get_object_terms($ids, 'category', array('orderby' => 'name', 'order' => 'ASC'));
    return is_wp_error($terms) ? array() : $terms;
}

function karpovka_article_categories_note()
{
    echo '<p>Фильтры формируются автоматически из стандартных рубрик записей «Статьи».</p>';
}

function karpovka_articles_all_note()
{
    echo '<p>Все опубликованные статьи выводятся автоматически, от новых к старым.</p>';
}

function karpovka_article_reading_time($post_id = 0)
{
    $post_id = $post_id ?: get_the_ID();
    $content = (string) get_post_field('post_content', $post_id);
    if ('' === trim($content) && function_exists('karpovka_article_legacy_blocks_to_content')) {
        $content = karpovka_article_legacy_blocks_to_content(get_post_meta($post_id, 'ab_blocks', true));
    }
    $text    = wp_strip_all_tags(strip_shortcodes($content));
    preg_match_all('/[\p{L}\p{N}]+/u', $text, $words);
    return max(1, (int) ceil(count($words[0]) / 180));
}

function karpovka_articles_page_url()
{
    static $url = null;
    if (null !== $url) return $url;
    $pages = get_posts(array(
        'post_type'      => 'page',
        'posts_per_page' => 1,
        'meta_key'       => '_wp_page_template',
        'meta_value'     => 'articles.php',
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ));
    $url = $pages ? get_permalink($pages[0]) : home_url('/articles/');
    return $url;
}
