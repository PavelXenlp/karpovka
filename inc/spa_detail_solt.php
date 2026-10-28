<?php
add_action('add_meta_boxes', function() {
  add_meta_box(
    'spa_salt_box',
    'Секция: Соляная комната (галотерапия)',
    'spa_salt_render_box',
    'page',
    'normal',
    'high'
  );
});

function spa_salt_render_box($post) {

  wp_nonce_field('spa_salt_nonce', 'spa_salt_nonce_field');

  $eyebrow  = get_post_meta($post->ID, 'spa_salt_eyebrow', true);
  $title    = get_post_meta($post->ID, 'spa_salt_title', true);
  $gold     = get_post_meta($post->ID, 'spa_salt_gold', true);
  $text     = get_post_meta($post->ID, 'spa_salt_text', true);
  $img      = get_post_meta($post->ID, 'spa_salt_img', true);
  $sec_id   = get_post_meta($post->ID, 'spa_salt_id', true);
  $features = get_post_meta($post->ID, 'spa_salt_features', true);

  if (!is_array($features)) $features = array();

  ?>
  <style>
    .spa-salt-field { margin-bottom: 16px; }
    .spa-salt-field label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; color: #1d2327; }
    .spa-salt-field input[type="text"], .spa-salt-field textarea { width: 100%; padding: 8px 10px; border: 1px solid #8c8f94; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
    .spa-salt-field textarea { min-height: 80px; resize: vertical; }
    .spa-salt-field .description { font-size: 12px; color: #646970; margin-top: 4px; }
    .spa-salt-feature { background: #f6f7f7; border: 1px solid #dcdcde; border-radius: 4px; padding: 12px; margin-bottom: 10px; position: relative; }
    .spa-salt-feature .spa-salt-field { margin-bottom: 10px; }
    .spa-salt-feature .spa-salt-field:last-child { margin-bottom: 0; }
    .spa-remove-salt { position: absolute; top: 8px; right: 8px; background: #d63638; color: #fff; border: none; border-radius: 3px; padding: 2px 8px; font-size: 12px; cursor: pointer; }
    .spa-add-salt { background: #2271b1; color: #fff; border: none; border-radius: 4px; padding: 6px 14px; font-size: 13px; cursor: pointer; margin-top: 6px; }
    .spa-add-salt:hover { background: #135e96; }
  </style>

  <div class="spa-salt-field">
    <label>Надзаголовок (eyebrow)</label>
    <input type="text" name="spa_salt_eyebrow" value="<?php echo esc_attr($eyebrow); ?>" placeholder="Галотерапия">
  </div>
  <div class="spa-salt-field">
    <label>Заголовок</label>
    <input type="text" name="spa_salt_title" value="<?php echo esc_attr($title); ?>" placeholder="Соляная комната">
  </div>
  <div class="spa-salt-field">
    <label>Золотой текст в заголовке</label>
    <input type="text" name="spa_salt_gold" value="<?php echo esc_attr($gold); ?>" placeholder="комната">
    <p class="description">Текст внутри &lt;em&gt;, выделяется золотым курсивом</p>
  </div>
  <div class="spa-salt-field">
    <label>Описание секции</label>
    <textarea name="spa_salt_text" rows="4"><?php echo esc_textarea($text); ?></textarea>
  </div>
  <div class="spa-salt-field">
    <label>Преимущества</label>
    <div id="spa-salt-features-list">
      <?php foreach ($features as $i => $f) : ?>
        <div class="spa-salt-feature">
          <button type="button" class="spa-remove-salt" onclick="this.closest('.spa-salt-feature').remove();">Удалить</button>
          <div class="spa-salt-field">
            <label>Номер</label>
            <input type="text" name="spa_salt_features[<?php echo $i; ?>][num]" value="<?php echo esc_attr($f['num'] ?? ''); ?>" placeholder="01">
          </div>
          <div class="spa-salt-field">
            <label>Жирный заголовок</label>
            <input type="text" name="spa_salt_features[<?php echo $i; ?>][bold]" value="<?php echo esc_attr($f['bold'] ?? ''); ?>" placeholder="Польза для дыхания">
          </div>
          <div class="spa-salt-field">
            <label>Описание после тире</label>
            <textarea name="spa_salt_features[<?php echo $i; ?>][desc]" rows="2"><?php echo esc_textarea($f['desc'] ?? ''); ?></textarea>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <button type="button" class="spa-add-salt" id="spa-add-salt-btn">+ Добавить преимущество</button>
  </div>
  <div class="spa-salt-field">
    <label>Изображение</label>
    <input type="hidden" name="spa_salt_img" id="spa_salt_img_input" value="<?php echo esc_attr($img); ?>" data-kp-media>
  </div>
  <div class="spa-salt-field">
    <label>ID секции (якорь)</label>
    <input type="text" name="spa_salt_id" value="<?php echo esc_attr($sec_id); ?>" placeholder="salt">
  </div>

  <script>
  jQuery(document).ready(function($) {
    var si = <?php echo count($features); ?>;
    $('#spa-add-salt-btn').on('click', function() {
      var h = '<div class="spa-salt-feature">' +
        '<button type="button" class="spa-remove-salt" onclick="this.closest(\'.spa-salt-feature\').remove();">Удалить</button>' +
        '<div class="spa-salt-field"><label>Номер</label><input type="text" name="spa_salt_features['+si+'][num]" value="'+String(si+1).padStart(2,'0')+'"></div>' +
        '<div class="spa-salt-field"><label>Жирный заголовок</label><input type="text" name="spa_salt_features['+si+'][bold]"></div>' +
        '<div class="spa-salt-field"><label>Описание после тире</label><textarea name="spa_salt_features['+si+'][desc]" rows="2"></textarea></div></div>';
      $('#spa-salt-features-list').append(h);
      si++;
    });
  });
  </script>

  <?php
}

add_action('save_post', function($post_id) {
  if (!isset($_POST['spa_salt_nonce_field'])) return;
  if (!wp_verify_nonce($_POST['spa_salt_nonce_field'], 'spa_salt_nonce')) return;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!current_user_can('edit_page', $post_id)) return;

  $simple = array('spa_salt_eyebrow', 'spa_salt_title', 'spa_salt_gold', 'spa_salt_text', 'spa_salt_img', 'spa_salt_id');
  foreach ($simple as $field) {
    if (isset($_POST[$field])) {
      update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
    }
  }

  if (isset($_POST['spa_salt_features']) && is_array($_POST['spa_salt_features'])) {
    $features = array();
    foreach ($_POST['spa_salt_features'] as $f) {
      $num  = sanitize_text_field($f['num'] ?? '');
      $bold = sanitize_text_field($f['bold'] ?? '');
      $desc = sanitize_textarea_field($f['desc'] ?? '');
      if ($num || $bold || $desc) {
        $features[] = array('num' => $num, 'bold' => $bold, 'desc' => $desc);
      }
    }
    update_post_meta($post_id, 'spa_salt_features', $features);
  } else {
    delete_post_meta($post_id, 'spa_salt_features');
  }
});
