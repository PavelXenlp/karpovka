<?php
add_action('add_meta_boxes', function() {
  add_meta_box(
    'spa_articles_box',
    'Секция: Полезные статьи',
    'spa_articles_render_box',
    'page',
    'normal',
    'high'
  );
});

function spa_articles_render_box($post) {

  wp_nonce_field('spa_articles_nonce', 'spa_articles_nonce_field');

  $eyebrow   = get_post_meta($post->ID, 'spa_articles_eyebrow', true);
  $title     = get_post_meta($post->ID, 'spa_articles_title', true);
  $gold      = get_post_meta($post->ID, 'spa_articles_gold', true);
  $sec_id    = get_post_meta($post->ID, 'spa_articles_id', true);
  $feat_tag  = get_post_meta($post->ID, 'spa_articles_feat_tag', true);
  $feat_title = get_post_meta($post->ID, 'spa_articles_feat_title', true);
  $feat_text = get_post_meta($post->ID, 'spa_articles_feat_text', true);
  $feat_link = get_post_meta($post->ID, 'spa_articles_feat_link', true);
  $feat_img  = get_post_meta($post->ID, 'spa_articles_feat_img', true);
  $rows      = get_post_meta($post->ID, 'spa_articles_rows', true);

  if (!is_array($rows)) $rows = array();

  ?>
  <style>
    .sa-field { margin-bottom: 16px; }
    .sa-field label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; color: #1d2327; }
    .sa-field input[type="text"], .sa-field textarea { width: 100%; padding: 8px 10px; border: 1px solid #8c8f94; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
    .sa-field textarea { min-height: 80px; resize: vertical; }
    .sa-field .description { font-size: 12px; color: #646970; margin-top: 4px; }
    .sa-field h4 { margin: 20px 0 12px; padding-bottom: 8px; border-bottom: 1px solid #dcdcde; color: #2271b1; font-size: 14px; }
    .sa-row { background: #f6f7f7; border: 1px solid #dcdcde; border-radius: 4px; padding: 12px; margin-bottom: 10px; position: relative; }
    .sa-row .sa-field { margin-bottom: 10px; }
    .sa-remove-row { position: absolute; top: 8px; right: 8px; background: #d63638; color: #fff; border: none; border-radius: 3px; padding: 2px 8px; font-size: 12px; cursor: pointer; }
    .sa-add-row { background: #2271b1; color: #fff; border: none; border-radius: 4px; padding: 6px 14px; font-size: 13px; cursor: pointer; margin-top: 6px; }
    .sa-add-row:hover { background: #135e96; }
    .sa-preview { margin-top: 8px; max-width: 300px; }
    .sa-preview img { max-width: 100%; height: auto; border: 1px solid #dcdcde; border-radius: 4px; }
    .sa-img-btn { display: inline-block; margin-top: 6px; }
    .sa-img-btn button { background: #2271b1; color: #fff; border: none; border-radius: 4px; padding: 5px 12px; font-size: 13px; cursor: pointer; }
    .sa-img-btn .sa-remove-img { background: #d63638; margin-left: 6px; }
  </style>

  <div class="sa-field">
    <label>Надзаголовок (eyebrow)</label>
    <input type="text" name="spa_articles_eyebrow" value="<?php echo esc_attr($eyebrow); ?>" placeholder="Читателям">
  </div>
  <div class="sa-field">
    <label>Заголовок</label>
    <input type="text" name="spa_articles_title" value="<?php echo esc_attr($title); ?>" placeholder="Полезные статьи">
  </div>
  <div class="sa-field">
    <label>Золотой текст в заголовке</label>
    <input type="text" name="spa_articles_gold" value="<?php echo esc_attr($gold); ?>" placeholder="статьи">
  </div>
  <div class="sa-field">
    <label>ID секции (якорь)</label>
    <input type="text" name="spa_articles_id" value="<?php echo esc_attr($sec_id); ?>" placeholder="articles">
  </div>

  <h4>Ведущая статья (крупная карточка)</h4>
  <div class="sa-field">
    <label>Тег</label>
    <input type="text" name="spa_articles_feat_tag" value="<?php echo esc_attr($feat_tag); ?>" placeholder="Гид по выбору">
  </div>
  <div class="sa-field">
    <label>Заголовок</label>
    <input type="text" name="spa_articles_feat_title" value="<?php echo esc_attr($feat_title); ?>" placeholder="Как выбрать формат проживания за городом">
  </div>
  <div class="sa-field">
    <label>Описание</label>
    <textarea name="spa_articles_feat_text" rows="3"><?php echo esc_textarea($feat_text); ?></textarea>
  </div>
  <div class="sa-field">
    <label>Ссылка на статью (slug)</label>
    <input type="text" name="spa_articles_feat_link" value="<?php echo esc_attr($feat_link); ?>" placeholder="kak-vybrat-format-prozhivaniya">
    <p class="description">Введите slug статьи. Ссылка будет: /article/ВАШ_SLUG</p>
  </div>
  <div class="sa-field">
    <label>Фоновое изображение</label>
    <input type="hidden" name="spa_articles_feat_img" id="sa_feat_img_input" value="<?php echo esc_attr($feat_img); ?>">
    <div class="sa-img-btn">
      <button type="button" id="sa-upload-feat-btn">Выбрать</button>
      <button type="button" class="sa-remove-img" id="sa-remove-feat-btn">Удалить</button>
    </div>
    <?php if ($feat_img) : ?>
      <div class="sa-preview"><img src="<?php echo esc_url($feat_img); ?>" alt="preview"></div>
    <?php endif; ?>
  </div>

  <h4>Остальные статьи (список)</h4>
  <div id="spa-articles-rows-list">
    <?php foreach ($rows as $i => $r) :
      $num  = $r['num'] ?? '';
      $title = $r['title'] ?? '';
      $text  = $r['text'] ?? '';
      $link  = $r['link'] ?? '';
    ?>
      <div class="sa-row">
        <button type="button" class="sa-remove-row" onclick="this.closest('.sa-row').remove();">Удалить</button>
        <div class="sa-field">
          <label>Номер</label>
          <input type="text" name="spa_articles_rows[<?php echo $i; ?>][num]" value="<?php echo esc_attr($num); ?>" placeholder="02">
        </div>
        <div class="sa-field">
          <label>Заголовок</label>
          <input type="text" name="spa_articles_rows[<?php echo $i; ?>][title]" value="<?php echo esc_attr($title); ?>" placeholder="Что взять с собой в загородный отель">
        </div>
        <div class="sa-field">
          <label>Описание</label>
          <textarea name="spa_articles_rows[<?php echo $i; ?>][text]" rows="2"><?php echo esc_textarea($text); ?></textarea>
        </div>
        <div class="sa-field">
          <label>Ссылка на статью (slug)</label>
          <input type="text" name="spa_articles_rows[<?php echo $i; ?>][link]" value="<?php echo esc_attr($link); ?>" placeholder="chto-vzyat-s-soboj">
          <p class="description">Ссылка будет: /article/ВАШ_SLUG</p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <button type="button" class="sa-add-row" id="spa-add-article-row-btn">+ Добавить статью</button>

  <script>
  jQuery(document).ready(function($) {

    var featFrame;
    $('#sa-upload-feat-btn').on('click', function(e) {
      e.preventDefault();
      if (featFrame) { featFrame.open(); return; }
      featFrame = wp.media({ title: 'Выбрать фото для ведущей статьи', button: { text: 'Использовать' }, multiple: false });
      featFrame.on('select', function() {
        var a = featFrame.state().get('selection').first().toJSON();
        $('#sa_feat_img_input').val(a.url);
        $('.sa-preview').remove();
        $('<div class="sa-preview"><img src="'+a.url+'" alt="preview"></div>').insertAfter('#sa-remove-feat-btn').parent();
      });
      featFrame.open();
    });
    $('#sa-remove-feat-btn').on('click', function(e) {
      e.preventDefault();
      $('#sa_feat_img_input').val('');
      $('.sa-preview').remove();
    });

    var ri = <?php echo count($rows); ?>;
    $('#spa-add-article-row-btn').on('click', function() {
      var num = String(ri + 2).padStart(2, '0');
      var h = '<div class="sa-row"><button type="button" class="sa-remove-row" onclick="this.closest(\'.sa-row\').remove();">Удалить</button>' +
        '<div class="sa-field"><label>Номер</label><input type="text" name="spa_articles_rows['+ri+'][num]" value="'+num+'"></div>' +
        '<div class="sa-field"><label>Заголовок</label><input type="text" name="spa_articles_rows['+ri+'][title]" placeholder="Название статьи"></div>' +
        '<div class="sa-field"><label>Описание</label><textarea name="spa_articles_rows['+ri+'][text]" rows="2"></textarea></div>' +
        '<div class="sa-field"><label>Ссылка (slug)</label><input type="text" name="spa_articles_rows['+ri+'][link]" placeholder="slug-stati"></div></div>';
      $('#spa-articles-rows-list').append(h);
      ri++;
    });

  });
  </script>

  <?php
}

add_action('save_post', function($post_id) {
  if (!isset($_POST['spa_articles_nonce_field'])) return;
  if (!wp_verify_nonce($_POST['spa_articles_nonce_field'], 'spa_articles_nonce')) return;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!current_user_can('edit_page', $post_id)) return;

  $simple = array('spa_articles_eyebrow', 'spa_articles_title', 'spa_articles_gold', 'spa_articles_id', 'spa_articles_feat_tag', 'spa_articles_feat_title', 'spa_articles_feat_text', 'spa_articles_feat_link', 'spa_articles_feat_img');
  foreach ($simple as $field) {
    if (isset($_POST[$field])) {
      update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
    }
  }

  if (isset($_POST['spa_articles_rows']) && is_array($_POST['spa_articles_rows'])) {
    $rows = array();
    foreach ($_POST['spa_articles_rows'] as $r) {
      $num   = sanitize_text_field($r['num'] ?? '');
      $title = sanitize_text_field($r['title'] ?? '');
      $text  = sanitize_textarea_field($r['text'] ?? '');
      $link  = sanitize_text_field($r['link'] ?? '');
      if ($title || $text) {
        $rows[] = array('num' => $num, 'title' => $title, 'text' => $text, 'link' => $link);
      }
    }
    update_post_meta($post_id, 'spa_articles_rows', $rows);
  } else {
    delete_post_meta($post_id, 'spa_articles_rows');
  }
});