<?php
add_action('add_meta_boxes', function() {
  add_meta_box(
    'spa_reviews_box',
    'Секция: Отзывы гостей',
    'spa_reviews_render_box',
    'page',
    'normal',
    'high'
  );
});

function spa_reviews_render_box($post) {

  wp_nonce_field('spa_reviews_nonce', 'spa_reviews_nonce_field');

  $title  = get_post_meta($post->ID, 'spa_reviews_title', true);
  $gold   = get_post_meta($post->ID, 'spa_reviews_gold', true);
  $reviews = get_post_meta($post->ID, 'spa_reviews_items', true);

  if (!is_array($reviews)) $reviews = array();

  ?>
  <style>
    .sr-field { margin-bottom: 16px; }
    .sr-field label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; color: #1d2327; }
    .sr-field input[type="text"], .sr-field textarea, .sr-field select { width: 100%; padding: 8px 10px; border: 1px solid #8c8f94; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
    .sr-field textarea { min-height: 100px; resize: vertical; }
    .sr-field .description { font-size: 12px; color: #646970; margin-top: 4px; }
    .sr-review { background: #f6f7f7; border: 1px solid #dcdcde; border-radius: 4px; padding: 12px; margin-bottom: 10px; position: relative; }
    .sr-review .sr-field { margin-bottom: 10px; }
    .sr-remove-review { position: absolute; top: 8px; right: 8px; background: #d63638; color: #fff; border: none; border-radius: 3px; padding: 2px 8px; font-size: 12px; cursor: pointer; }
    .sr-add-review { background: #2271b1; color: #fff; border: none; border-radius: 4px; padding: 6px 14px; font-size: 13px; cursor: pointer; margin-top: 6px; }
    .sr-add-review:hover { background: #135e96; }
  </style>

  <div class="sr-field">
    <label>Заголовок секции</label>
    <input type="text" name="spa_reviews_title" value="<?php echo esc_attr($title); ?>" placeholder="Отзывы наших гостей">
    <p class="description">Оставьте пустым, чтобы использовать заголовок по умолчанию</p>
  </div>
  <div class="sr-field">
    <label>Золотой текст в заголовке</label>
    <input type="text" name="spa_reviews_gold" value="<?php echo esc_attr($gold); ?>" placeholder="гостей">
  </div>

  <div class="sr-field">
    <label>Отзывы</label>
    <div id="spa-reviews-list">
      <?php foreach ($reviews as $i => $r) :
        $stars = $r['stars'] ?? '5';
        $text  = $r['text'] ?? '';
        $name  = $r['name'] ?? '';
        $date  = $r['date'] ?? '';
      ?>
        <div class="sr-review">
          <button type="button" class="sr-remove-review" onclick="this.closest('.sr-review').remove();">Удалить</button>
          <div class="sr-field">
            <label>Рейтинг (количество звёзд)</label>
            <select name="spa_reviews_items[<?php echo $i; ?>][stars]">
              <?php for ($s = 5; $s >= 1; $s--) : ?>
                <option value="<?php echo $s; ?>" <?php selected($stars, (string)$s); ?>><?php echo str_repeat('★', $s); ?><?php echo str_repeat('☆', 5 - $s); ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <div class="sr-field">
            <label>Текст отзыва</label>
            <textarea name="spa_reviews_items[<?php echo $i; ?>][text]" rows="5"><?php echo esc_textarea($text); ?></textarea>
            <p class="description">Переносы строк сохраняются. Используйте &lt;br&gt; для точных переносов.</p>
          </div>
          <div class="sr-field">
            <label>Имя автора</label>
            <input type="text" name="spa_reviews_items[<?php echo $i; ?>][name]" value="<?php echo esc_attr($name); ?>" placeholder="Имя Фамилия">
          </div>
          <div class="sr-field">
            <label>Дата отзыва</label>
            <input type="text" name="spa_reviews_items[<?php echo $i; ?>][date]" value="<?php echo esc_attr($date); ?>" placeholder="19 апреля 2026">
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <button type="button" class="sr-add-review" id="spa-add-review-btn">+ Добавить отзыв</button>
  </div>

  <script>
  jQuery(document).ready(function($) {
    var ri = <?php echo count($reviews); ?>;
    $('#spa-add-review-btn').on('click', function() {
      var h = '<div class="sr-review"><button type="button" class="sr-remove-review" onclick="this.closest(\'.sr-review\').remove();">Удалить</button>' +
        '<div class="sr-field"><label>Рейтинг (количество звёзд)</label>' +
        '<select name="spa_reviews_items['+ri+'][stars]">' +
        '<option value="5">★★★★★</option><option value="4">★★★★☆</option>' +
        '<option value="3">★★★☆☆</option><option value="2">★★☆☆☆</option>' +
        '<option value="1">★☆☆☆☆</option></select></div>' +
        '<div class="sr-field"><label>Текст отзыва</label>' +
        '<textarea name="spa_reviews_items['+ri+'][text]" rows="5" placeholder="Текст отзыва..."></textarea></div>' +
        '<div class="sr-field"><label>Имя автора</label>' +
        '<input type="text" name="spa_reviews_items['+ri+'][name]" placeholder="Имя Фамилия"></div>' +
        '<div class="sr-field"><label>Дата отзыва</label>' +
        '<input type="text" name="spa_reviews_items['+ri+'][date]" placeholder="19 апреля 2026"></div></div>';
      $('#spa-reviews-list').append(h);
      ri++;
    });
  });
  </script>

  <?php
}

add_action('save_post', function($post_id) {
  if (!isset($_POST['spa_reviews_nonce_field'])) return;
  if (!wp_verify_nonce($_POST['spa_reviews_nonce_field'], 'spa_reviews_nonce')) return;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!current_user_can('edit_page', $post_id)) return;

  $simple = array('spa_reviews_title', 'spa_reviews_gold');
  foreach ($simple as $field) {
    if (isset($_POST[$field])) {
      update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
    }
  }

  if (isset($_POST['spa_reviews_items']) && is_array($_POST['spa_reviews_items'])) {
    $reviews = array();
    foreach ($_POST['spa_reviews_items'] as $r) {
      $stars = intval($r['stars'] ?? 5);
      if ($stars < 1) $stars = 5;
      if ($stars > 5) $stars = 5;
      $text = wp_kses_post($r['text'] ?? '');
      $name = sanitize_text_field($r['name'] ?? '');
      $date = sanitize_text_field($r['date'] ?? '');
      if ($text || $name) {
        $reviews[] = array('stars' => $stars, 'text' => $text, 'name' => $name, 'date' => $date);
      }
    }
    update_post_meta($post_id, 'spa_reviews_items', $reviews);
  } else {
    delete_post_meta($post_id, 'spa_reviews_items');
  }
});