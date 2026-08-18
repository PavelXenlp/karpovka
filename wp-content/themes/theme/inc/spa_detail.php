<?php
// ─── Meta box: Секция SPA-детали (без плагинов) ───────────

// ── 1. Добавляем meta box в редактор ──
add_action('add_meta_boxes', function() {
  add_meta_box(
    'spa_detail_box',
    'Секция: SPA-детали (бассейн, водная зона)',
    'spa_detail_render_box',
    'page',
    'normal',
    'high'
  );
});

// ── 2. HTML полей в meta box ──
function spa_detail_render_box($post) {

  // Защита от CSRF
  wp_nonce_field('spa_detail_nonce', 'spa_detail_nonce_field');

  // Получаем сохранённые значения
  $eyebrow  = get_post_meta($post->ID, 'spa_detail_eyebrow', true);
  $title    = get_post_meta($post->ID, 'spa_detail_title', true);
  $gold     = get_post_meta($post->ID, 'spa_detail_gold', true);
  $text     = get_post_meta($post->ID, 'spa_detail_text', true);
  $img      = get_post_meta($post->ID, 'spa_detail_img', true);
  $sec_id   = get_post_meta($post->ID, 'spa_detail_id', true);
  $features = get_post_meta($post->ID, 'spa_detail_features', true);

  // Если features нет — ставим пустой массив
  if (!is_array($features)) $features = array();

  ?>
  <style>
    .spa-detail-field { margin-bottom: 16px; }
    .spa-detail-field label {
      display: block;
      font-weight: 600;
      margin-bottom: 4px;
      font-size: 13px;
      color: #1d2327;
    }
    .spa-detail-field input[type="text"],
    .spa-detail-field textarea {
      width: 100%;
      padding: 8px 10px;
      border: 1px solid #8c8f94;
      border-radius: 4px;
      font-size: 14px;
      box-sizing: border-box;
    }
    .spa-detail-field textarea { min-height: 80px; resize: vertical; }
    .spa-detail-field .description {
      font-size: 12px;
      color: #646970;
      margin-top: 4px;
    }
    .spa-feature-row {
      background: #f6f7f7;
      border: 1px solid #dcdcde;
      border-radius: 4px;
      padding: 12px;
      margin-bottom: 10px;
      position: relative;
    }
    .spa-feature-row .spa-detail-field { margin-bottom: 10px; }
    .spa-feature-row .spa-detail-field:last-child { margin-bottom: 0; }
    .spa-remove-feature {
      position: absolute;
      top: 8px;
      right: 8px;
      background: #d63638;
      color: #fff;
      border: none;
      border-radius: 3px;
      padding: 2px 8px;
      font-size: 12px;
      cursor: pointer;
    }
    .spa-add-feature {
      background: #2271b1;
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: 6px 14px;
      font-size: 13px;
      cursor: pointer;
      margin-top: 6px;
    }
    .spa-add-feature:hover { background: #135e96; }
    .spa-img-preview {
      margin-top: 8px;
      max-width: 300px;
    }
    .spa-img-preview img {
      max-width: 100%;
      height: auto;
      border: 1px solid #dcdcde;
      border-radius: 4px;
    }
    .spa-img-btn {
      display: inline-block;
      margin-top: 6px;
    }
    .spa-img-btn button {
      background: #2271b1;
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: 5px 12px;
      font-size: 13px;
      cursor: pointer;
    }
    .spa-img-btn .spa-remove-img {
      background: #d63638;
      margin-left: 6px;
    }
  </style>

  <!-- Eyebrow -->
  <div class="spa-detail-field">
    <label>Надзаголовок (eyebrow)</label>
    <input type="text" name="spa_detail_eyebrow" value="<?php echo esc_attr($eyebrow); ?>" placeholder="Например: Водная зона">
  </div>

  <!-- Заголовок -->
  <div class="spa-detail-field">
    <label>Заголовок</label>
    <input type="text" name="spa_detail_title" value="<?php echo esc_attr($title); ?>" placeholder="Например: Бассейны и водная зона">
  </div>

  <!-- Золотой текст -->
  <div class="spa-detail-field">
    <label>Золотой текст в заголовке (внутри &lt;em&gt;)</label>
    <input type="text" name="spa_detail_gold" value="<?php echo esc_attr($gold); ?>" placeholder="Например: водная зона">
    <p class="description">Этот текст будет выделен золотым курсивом внутри заголовка</p>
  </div>

  <!-- Описание -->
  <div class="spa-detail-field">
    <label>Описание секции</label>
    <textarea name="spa_detail_text" rows="4"><?php echo esc_textarea($text); ?></textarea>
  </div>

  <!-- Преимущества (repeater) -->
  <div class="spa-detail-field">
    <label>Преимущества (блоки 01, 02, 03...)</label>
    <p class="description">Добавляйте блоки преимуществ. Каждый блок = номер + жирный заголовок + описание.</p>

    <div id="spa-features-list">
      <?php foreach ($features as $i => $f) : ?>
        <div class="spa-feature-row">
          <button type="button" class="spa-remove-feature" onclick="this.closest('.spa-feature-row').remove();">Удалить</button>

          <div class="spa-detail-field">
            <label>Номер</label>
            <input type="text" name="spa_features[<?php echo $i; ?>][num]" value="<?php echo esc_attr($f['num'] ?? ''); ?>" placeholder="01">
          </div>

          <div class="spa-detail-field">
            <label>Жирный заголовок</label>
            <input type="text" name="spa_features[<?php echo $i; ?>][bold]" value="<?php echo esc_attr($f['bold'] ?? ''); ?>" placeholder="Подогреваемый бассейн">
          </div>

          <div class="spa-detail-field">
            <label>Описание после тире</label>
            <textarea name="spa_features[<?php echo $i; ?>][desc]" rows="2"><?php echo esc_textarea($f['desc'] ?? ''); ?></textarea>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <button type="button" class="spa-add-feature" id="spa-add-feature-btn">+ Добавить преимущество</button>
  </div>

  <!-- Изображение -->
  <div class="spa-detail-field">
    <label>Изображение</label>
    <input type="hidden" name="spa_detail_img" id="spa_detail_img_input" value="<?php echo esc_attr($img); ?>">
    <div class="spa-img-btn">
      <button type="button" id="spa-upload-img-btn">Выбрать изображение</button>
      <button type="button" class="spa-remove-img" id="spa-remove-img-btn">Удалить</button>
    </div>
    <?php if ($img) : ?>
      <div class="spa-img-preview">
        <img src="<?php echo esc_url($img); ?>" alt="preview">
      </div>
    <?php endif; ?>
  </div>

  <!-- ID секции -->
  <div class="spa-detail-field">
    <label>ID секции (якорь для ссылок)</label>
    <input type="text" name="spa_detail_id" value="<?php echo esc_attr($sec_id); ?>" placeholder="pool">
    <p class="description">Используется для ссылок на страницу, например: /page#pool</p>
  </div>

  <!-- Media uploader script -->
  <script>
    jQuery(document).ready(function($) {

      // Загрузка изображения
      var frame;
      $('#spa-upload-img-btn').on('click', function(e) {
        e.preventDefault();
        if (frame) { frame.open(); return; }
        frame = wp.media({
          title: 'Выбрать изображение для секции',
          button: { text: 'Использовать это изображение' },
          multiple: false
        });
        frame.on('select', function() {
          var attachment = frame.state().get('selection').first().toJSON();
          $('#spa_detail_img_input').val(attachment.url);
          // Превью
          $('.spa-img-preview').remove();
          $('<div class="spa-img-preview"><img src="' + attachment.url + '" alt="preview"></div>').insertAfter('#spa-remove-img-btn').parent();
        });
        frame.open();
      });

      // Удаление изображения
      $('#spa-remove-img-btn').on('click', function(e) {
        e.preventDefault();
        $('#spa_detail_img_input').val('');
        $('.spa-img-preview').remove();
      });

      // Добавить блок преимущества
      var featureIndex = <?php echo count($features); ?>;
      $('#spa-add-feature-btn').on('click', function() {
        var html = '<div class="spa-feature-row">' +
          '<button type="button" class="spa-remove-feature" onclick="this.closest(\'.spa-feature-row\').remove();">Удалить</button>' +
          '<div class="spa-detail-field"><label>Номер</label>' +
          '<input type="text" name="spa_features[' + featureIndex + '][num]" value="' + String(featureIndex + 1).padStart(2, '0') + '" placeholder="01"></div>' +
          '<div class="spa-detail-field"><label>Жирный заголовок</label>' +
          '<input type="text" name="spa_features[' + featureIndex + '][bold]" placeholder="Подогреваемый бассейн"></div>' +
          '<div class="spa-detail-field"><label>Описание после тире</label>' +
          '<textarea name="spa_features[' + featureIndex + '][desc]" rows="2" placeholder="размер 12 x 6 м..."></textarea></div>' +
          '</div>';
        $('#spa-features-list').append(html);
        featureIndex++;
      });

    });
  </script>

  <?php
}

// ── 3. Сохранение полей ──
add_action('save_post', function($post_id) {

  // Проверяем nonce
  if (!isset($_POST['spa_detail_nonce_field'])) return;
  if (!wp_verify_nonce($_POST['spa_detail_nonce_field'], 'spa_detail_nonce')) return;

  // Не сохраняем при автосохранении
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

  // Проверяем права
  if (!current_user_can('edit_page', $post_id)) return;

  // Сохраняем простые текстовые поля
  $text_fields = array('spa_detail_eyebrow', 'spa_detail_title', 'spa_detail_gold', 'spa_detail_text', 'spa_detail_img', 'spa_detail_id');
  foreach ($text_fields as $field) {
    if (isset($_POST[$field])) {
      update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
    }
  }

  // Сохраняем преимущества (массив)
  if (isset($_POST['spa_features']) && is_array($_POST['spa_features'])) {
    $features = array();
    foreach ($_POST['spa_features'] as $f) {
      $num  = sanitize_text_field($f['num'] ?? '');
      $bold = sanitize_text_field($f['bold'] ?? '');
      $desc = sanitize_textarea_field($f['desc'] ?? '');
      if ($num || $bold || $desc) {
        $features[] = array('num' => $num, 'bold' => $bold, 'desc' => $desc);
      }
    }
    update_post_meta($post_id, 'spa_detail_features', $features);
  } else {
    delete_post_meta($post_id, 'spa_detail_features');
  }

});