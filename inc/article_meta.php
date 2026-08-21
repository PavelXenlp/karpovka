<?php
/**
 * Метабокс «Мета статьи» (хлебные крошки, категория, дата, время чтения)
 * Подключить: require_once get_template_directory() . '/inc/article_meta.php';
 */

add_action('add_meta_boxes', function () {
    add_meta_box('am_box', 'Мета статьи', 'am_box_cb', 'page', 'normal', 'high');
});

function am_box_cb($post) {
    wp_nonce_field('am_nonce', 'am_nonce_field');

    $parent_text = get_post_meta($post->ID, 'am_parent_text', true);
    $parent_url  = get_post_meta($post->ID, 'am_parent_url', true);
    $category    = get_post_meta($post->ID, 'am_category', true);
    $date        = get_post_meta($post->ID, 'am_date', true);
    $read_time   = get_post_meta($post->ID, 'am_read_time', true);
    ?>

    <style>
        .am-field { margin-bottom: 16px; }
        .am-field label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; }
        .am-field input { width: 100%; padding: 7px 10px; box-sizing: border-box; }
        .am-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    </style>

    <div class="am-row">
        <div class="am-field">
            <label>Родительская страница — текст</label>
            <input type="text" name="am_parent_text" value="<?php echo esc_attr($parent_text); ?>" placeholder="Статьи">
        </div>
        <div class="am-field">
            <label>Родительская страница — ссылка</label>
            <input type="text" name="am_parent_url" value="<?php echo esc_attr($parent_url); ?>" placeholder="/articles.html">
        </div>
    </div>

    <div class="am-row">
        <div class="am-field">
            <label>Категория</label>
            <input type="text" name="am_category" value="<?php echo esc_attr($category); ?>" placeholder="Проживание">
        </div>
        <div class="am-field">
            <label>Дата</label>
            <input type="text" name="am_date" value="<?php echo esc_attr($date); ?>" placeholder="15 июля 2025">
        </div>
    </div>

    <div class="am-field">
        <label>Время чтения</label>
        <input type="text" name="am_read_time" value="<?php echo esc_attr($read_time); ?>" placeholder="5 мин чтения">
    </div>

    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['am_nonce_field'])) return;
    if (!wp_verify_nonce($_POST['am_nonce_field'], 'am_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array('am_parent_text', 'am_parent_url', 'am_category', 'am_date', 'am_read_time');
    foreach ($fields as $f) {
        if (isset($_POST[$f])) {
            update_post_meta($post_id, $f, sanitize_text_field($_POST[$f]));
        }
    }
});