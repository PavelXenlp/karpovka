<?php
// ─── Meta box: Секция Массаж (без плагинов) ───────────

add_action('add_meta_boxes', function() {
  add_meta_box(
    'spa_massage_box',
    'Секция: Массаж (виды массажа)',
    'spa_massage_render_box',
    'page',
    'normal',
    'high'
  );
});

function spa_massage_render_box($post) {

  wp_nonce_field('spa_massage_nonce', 'spa_massage_nonce_field');

  $eyebrow   = get_post_meta($post->ID, 'spa_massage_eyebrow', true);
  $title     = get_post_meta($post->ID, 'spa_massage_title', true);
  $gold      = get_post_meta($post->ID, 'spa_massage_gold', true);
  $subtitle  = get_post_meta($post->ID, 'spa_massage_subtitle', true);
  $bg_img    = get_post_meta($post->ID, 'spa_massage_bg_img', true);
  $btn_text  = get_post_meta($post->ID, 'spa_massage_btn_text', true);
  $btn_link  = get_post_meta($post->ID, 'spa_massage_btn_link', true);
  $sec_id    = get_post_meta($post->ID, 'spa_massage_id', true);
  $items     = get_post_meta($post->ID, 'spa_massage_items', true);

  if (!is_array($items)) $items = array();

  ?>
  <style>
    .spa-massage-field { margin-bottom: 16px; }
    .spa-massage-field label {
      display: block; font-weight: 600; margin-bottom: 4px;
      font-size: 13px; color: #1d2327;
    }
    .spa-massage-field input[type="text"],
    .spa-massage-field textarea {
      width: 100%; padding: 8px 10px; border: 1px solid #8c8f94;
      border-radius: 4px; font-size: 14px; box-sizing: border-box;
    }
    .spa-massage-field textarea { min-height: 80px; resize: vertical; }
    .spa-massage-field .description { font-size: 12px; color: #646970; margin-top: 4px; }
    .spa-massage-item {
      background: #f6f7f7; border: 1px solid #dcdcde; border-radius: 4px;
      padding: 12px; margin-bottom: 10px; position: relative;
    }
    .spa-massage-item .spa-massage-field { margin-bottom: 10px; }
    .spa-massage-item .spa-massage-field:last-child { margin-bottom: 0; }
    .spa-remove-item {
      position: absolute; top: 8px; right: 8px; background: #d63638;
      color: #fff; border: none; border-radius: 3px;
      padding: 2px 8px; font-size: 12px; cursor: pointer;
    }
    .spa-add-item {
      background: #2271b1; color: #fff; border: none; border-radius: 4px;
      padding: 6px 14px; font-size: 13px; cursor: pointer; margin-top: 6px;
    }
    .spa-add-item:hover { background: #135e96; }
  </style>

  <div class="spa-massage-field">
    <label>Надзаголовок (eyebrow)</label>
    <input type="text" name="spa_massage_eyebrow" value="<?php echo esc_attr($eyebrow); ?>" placeholder="Массаж">
  </div>

  <div class="spa-massage-field">
    <label>Заголовок</label>
    <input type="text" name="spa_massage_title" value="<?php echo esc_attr($title); ?>" placeholder="Виды массажа">
  </div>

  <div class="spa-massage-field">
    <label>Золотой текст в заголовке (внутри &lt;em&gt;)</label>
    <input type="text" name="spa_massage_gold" value="<?php echo esc_attr($gold); ?>" placeholder="массажа">
    <p class="description">Текст внутри &lt;em&gt;, выделяется золотым курсивом</p>
  </div>

  <div class="spa-massage-field">
    <label>Подзаголовок</label>
    <textarea name="spa_massage_subtitle" rows="2"><?php echo esc_textarea($subtitle); ?></textarea>
  </div>

  <div class="spa-massage-field">
    <label>Фоновое изображение (bg)</label>
    <input type="hidden" name="spa_massage_bg_img" id="spa_massage_bg_input" value="<?php echo esc_attr($bg_img); ?>" data-kp-media>
  </div>

  <div class="spa-massage-field">
    <label>Услуги массажа</label>
    <div id="spa-massage-items-list">
      <?php foreach ($items as $i => $item) : ?>
        <div class="spa-massage-item">
          <button type="button" class="spa-remove-item" onclick="this.closest('.spa-massage-item').remove();">Удалить</button>
          <div class="spa-massage-field">
            <label>Название услуги</label>
            <input type="text" name="spa_massage_items[<?php echo $i; ?>][name]" value="<?php echo esc_attr($item['name'] ?? ''); ?>" placeholder="Классический массаж">
          </div>
          <div class="spa-massage-field">
            <label>Описание</label>
            <textarea name="spa_massage_items[<?php echo $i; ?>][desc]" rows="2"><?php echo esc_textarea($item['desc'] ?? ''); ?></textarea>
          </div>
          <div class="spa-massage-field">
            <label>Длительность</label>
            <input type="text" name="spa_massage_items[<?php echo $i; ?>][dur]" value="<?php echo esc_attr($item['dur'] ?? ''); ?>" placeholder="30 / 60 / 90 мин">
          </div>
          <div class="spa-massage-field">
            <label>Цена</label>
            <input type="text" name="spa_massage_items[<?php echo $i; ?>][price]" value="<?php echo esc_attr($item['price'] ?? ''); ?>" placeholder="от 3 500 ₽">
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <button type="button" class="spa-add-item" id="spa-add-massage-btn">+ Добавить услугу</button>
  </div>

  <div class="spa-massage-field">
    <label>Текст кнопки</label>
    <input type="text" name="spa_massage_btn_text" value="<?php echo esc_attr($btn_text); ?>" placeholder="Полный прайс-лист">
  </div>

  <div class="spa-massage-field">
    <label>Ссылка кнопки</label>
    <input type="text" name="spa_massage_btn_link" value="<?php echo esc_attr($btn_link); ?>" placeholder="#price">
  </div>

  <div class="spa-massage-field">
    <label>ID секции (якорь)</label>
    <input type="text" name="spa_massage_id" value="<?php echo esc_attr($sec_id); ?>" placeholder="massage">
  </div>

    <script>
  jQuery(document).ready(function($) {

    var mi = <?php echo count($items); ?>;
    $('#spa-add-massage-btn').on('click', function() {
      var h = '<div class="spa-massage-item">' +
        '<button type="button" class="spa-remove-item" onclick="this.closest(\'.spa-massage-item\').remove();">Удалить</button>' +
        '<div class="spa-massage-field"><label>Название услуги</label><input type="text" name="spa_massage_items['+mi+'][name]" placeholder="Классический массаж"></div>' +
        '<div class="spa-massage-field"><label>Описание</label><textarea name="spa_massage_items['+mi+'][desc]" rows="2"></textarea></div>' +
        '<div class="spa-massage-field"><label>Длительность</label><input type="text" name="spa_massage_items['+mi+'][dur]" placeholder="30 / 60 мин"></div>' +
        '<div class="spa-massage-field"><label>Цена</label><input type="text" name="spa_massage_items['+mi+'][price]" placeholder="от 3 500 ₽" data-rub="1"></div></div>';
      $('#spa-massage-items-list').append(h);
      mi++;
    });

  });
  </script>

  <?php
}

add_action('save_post', function($post_id) {

  if (!isset($_POST['spa_massage_nonce_field'])) return;
  if (!wp_verify_nonce($_POST['spa_massage_nonce_field'], 'spa_massage_nonce')) return;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!current_user_can('edit_page', $post_id)) return;

  $simple = array('spa_massage_eyebrow', 'spa_massage_title', 'spa_massage_gold', 'spa_massage_subtitle', 'spa_massage_bg_img', 'spa_massage_btn_text', 'spa_massage_btn_link', 'spa_massage_id');
  foreach ($simple as $field) {
    if (isset($_POST[$field])) {
      update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
    }
  }

  if (isset($_POST['spa_massage_items']) && is_array($_POST['spa_massage_items'])) {
    $items = array();
    foreach ($_POST['spa_massage_items'] as $item) {
      $name  = sanitize_text_field($item['name'] ?? '');
      $desc  = sanitize_textarea_field($item['desc'] ?? '');
      $dur   = sanitize_text_field($item['dur'] ?? '');
      $price = sanitize_text_field($item['price'] ?? ''); $price = sanitize_text_field($item['price'] ?? '');
if ($price && mb_substr($price, -1) !== '₽') {
  $price = $price . ' ₽';
}
      if ($name || $desc || $dur || $price) {
        $items[] = array('name' => $name, 'desc' => $desc, 'dur' => $dur, 'price' => $price);
      }
    }
    update_post_meta($post_id, 'spa_massage_items', $items);
  } else {
    delete_post_meta($post_id, 'spa_massage_items');
  }
});
