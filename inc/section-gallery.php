<?php
add_action('add_meta_boxes', function() {
  add_meta_box(
    'spa_gallery_box',
    'Секция: Галерея',
    'spa_gallery_render_box',
    'page',
    'normal',
    'high'
  );
});

function spa_gallery_render_box($post) {

  wp_nonce_field('spa_gallery_nonce', 'spa_gallery_nonce_field');

  $eyebrow = get_post_meta($post->ID, 'spa_gallery_eyebrow', true);
  $title   = get_post_meta($post->ID, 'spa_gallery_title', true);
  $gold    = get_post_meta($post->ID, 'spa_gallery_gold', true);
  $sec_id  = get_post_meta($post->ID, 'spa_gallery_id', true);
  $items   = get_post_meta($post->ID, 'spa_gallery_items', true);

  if (!is_array($items)) $items = array();

  ?>
  <style>
    .sg-field { margin-bottom: 16px; }
    .sg-field label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; color: #1d2327; }
    .sg-field input[type="text"], .sg-field textarea, .sg-field select { width: 100%; padding: 8px 10px; border: 1px solid #8c8f94; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
    .sg-field .description { font-size: 12px; color: #646970; margin-top: 4px; }
    .sg-item { background: #f6f7f7; border: 1px solid #dcdcde; border-radius: 4px; padding: 12px; margin-bottom: 10px; position: relative; }
    .sg-item .sg-field { margin-bottom: 10px; }
    .sg-item .sg-field:last-child { margin-bottom: 0; }
    .sg-remove-item { position: absolute; top: 8px; right: 8px; background: #d63638; color: #fff; border: none; border-radius: 3px; padding: 2px 8px; font-size: 12px; cursor: pointer; }
    .sg-add-item { background: #2271b1; color: #fff; border: none; border-radius: 4px; padding: 6px 14px; font-size: 13px; cursor: pointer; margin-top: 6px; }
    .sg-add-item:hover { background: #135e96; }
    .sg-preview { margin-top: 8px; max-width: 300px; }
    .sg-preview img { max-width: 100%; height: auto; border: 1px solid #dcdcde; border-radius: 4px; }
    .sg-img-btn { display: inline-block; margin-top: 6px; }
    .sg-img-btn button { background: #2271b1; color: #fff; border: none; border-radius: 4px; padding: 5px 12px; font-size: 13px; cursor: pointer; }
    .sg-img-btn .sg-remove-img { background: #d63638; margin-left: 6px; }
  </style>

  <div class="sg-field">
    <label>Надзаголовок (eyebrow)</label>
    <input type="text" name="spa_gallery_eyebrow" value="<?php echo esc_attr($eyebrow); ?>" placeholder="Галерея">
  </div>
  <div class="sg-field">
    <label>Заголовок</label>
    <input type="text" name="spa_gallery_title" value="<?php echo esc_attr($title); ?>" placeholder="Галерея SPA-центра">
  </div>
  <div class="sg-field">
    <label>Золотой текст в заголовке</label>
    <input type="text" name="spa_gallery_gold" value="<?php echo esc_attr($gold); ?>" placeholder="SPA">
  </div>
  <div class="sg-field">
    <label>ID секции (якорь)</label>
    <input type="text" name="spa_gallery_id" value="<?php echo esc_attr($sec_id); ?>" placeholder="gallery">
  </div>

  <div class="sg-field">
    <label>Фотографии галереи</label>
    <div id="spa-gallery-items-list">
      <?php foreach ($items as $i => $item) :
        $img   = $item['img'] ?? '';
        $alt   = $item['alt'] ?? '';
        $tall  = !empty($item['tall']) ? '1' : '';
      ?>
        <div class="sg-item">
          <button type="button" class="sg-remove-item" onclick="this.closest('.sg-item').remove();">Удалить</button>
          <div class="sg-field">
            <label>Изображение</label>
            <input type="hidden" name="spa_gallery_items[<?php echo $i; ?>][img]" value="<?php echo esc_attr($img); ?>">
            <div class="sg-img-btn">
              <button type="button" onclick="uploadGalleryImg(this)">Выбрать</button>
              <button type="button" class="sg-remove-img" onclick="removeGalleryImg(this)">Удалить</button>
            </div>
            <?php if ($img) : ?>
              <div class="sg-preview"><img src="<?php echo esc_url($img); ?>" alt="preview"></div>
            <?php endif; ?>
          </div>
          <div class="sg-field">
            <label>Alt-текст (описание картинки)</label>
            <input type="text" name="spa_gallery_items[<?php echo $i; ?>][alt]" value="<?php echo esc_attr($alt); ?>" placeholder="Бассейн SPA-центра">
          </div>
          <div class="sg-field">
            <label>Размер</label>
            <select name="spa_gallery_items[<?php echo $i; ?>][tall]">
              <option value="" <?php selected($tall, ''); ?>>Обычный</option>
              <option value="1" <?php selected($tall, '1'); ?>>Высокий (tall)</option>
            </select>
            <p class="description">Высокие картинки занимают 2 ряда в masonry-сетке</p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <button type="button" class="sg-add-item" id="spa-add-gallery-btn">+ Добавить фото</button>
  </div>

  <script>
  jQuery(document).ready(function($) {

    var gi = <?php echo count($items); ?>;
    $('#spa-add-gallery-btn').on('click', function() {
      var h = '<div class="sg-item"><button type="button" class="sg-remove-item" onclick="this.closest(\'.sg-item\').remove();">Удалить</button>' +
        '<div class="sg-field"><label>Изображение</label>' +
        '<input type="hidden" name="spa_gallery_items['+gi+'][img]" value="">' +
        '<div class="sg-img-btn"><button type="button" onclick="uploadGalleryImg(this)">Выбрать</button>' +
        '<button type="button" class="sg-remove-img" onclick="removeGalleryImg(this)">Удалить</button></div></div>' +
        '<div class="sg-field"><label>Alt-текст</label><input type="text" name="spa_gallery_items['+gi+'][alt]" placeholder="Описание картинки"></div>' +
        '<div class="sg-field"><label>Размер</label><select name="spa_gallery_items['+gi+'][tall]">' +
        '<option value="">Обычный</option><option value="1">Высокий (tall)</option></select>' +
        '<p class="description">Высокие картинки занимают 2 ряда</p></div></div>';
      $('#spa-gallery-items-list').append(h);
      gi++;
    });

  });

  function uploadGalleryImg(btn) {
    var frame;
    if (frame) { frame.open(); return; }
    frame = wp.media({ title: 'Выбрать фото для галереи', button: { text: 'Использовать' }, multiple: false });
    frame.on('select', function() {
      var a = frame.state().get('selection').first().toJSON();
      var item = jQuery(btn).closest('.sg-item');
      item.find('input[name*="[img]"]').val(a.url);
      item.find('.sg-preview').remove();
      jQuery('<div class="sg-preview"><img src="'+a.url+'" alt="preview"></div>').insertAfter(jQuery(btn).parent());
    });
    frame.open();
  }

  function removeGalleryImg(btn) {
    var item = jQuery(btn).closest('.sg-item');
    item.find('input[name*="[img]"]').val('');
    item.find('.sg-preview').remove();
  }
  </script>

  <?php
}

add_action('save_post', function($post_id) {
  if (!isset($_POST['spa_gallery_nonce_field'])) return;
  if (!wp_verify_nonce($_POST['spa_gallery_nonce_field'], 'spa_gallery_nonce')) return;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!current_user_can('edit_page', $post_id)) return;

  $simple = array('spa_gallery_eyebrow', 'spa_gallery_title', 'spa_gallery_gold', 'spa_gallery_id');
  foreach ($simple as $field) {
    if (isset($_POST[$field])) {
      update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
    }
  }

  if (isset($_POST['spa_gallery_items']) && is_array($_POST['spa_gallery_items'])) {
    $items = array();
    foreach ($_POST['spa_gallery_items'] as $item) {
      $img  = esc_url_raw($item['img'] ?? '');
      $alt  = sanitize_text_field($item['alt'] ?? '');
      $tall = !empty($item['tall']) ? '1' : '';
      if ($img) {
        $items[] = array('img' => $img, 'alt' => $alt, 'tall' => $tall);
      }
    }
    update_post_meta($post_id, 'spa_gallery_items', $items);
  } else {
    delete_post_meta($post_id, 'spa_gallery_items');
  }
});