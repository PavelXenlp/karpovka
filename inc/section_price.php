<?php
add_action('add_meta_boxes', function() {
  add_meta_box(
    'spa_price_box',
    'Секция: Прайс (таблица)',
    'spa_price_render_box',
    'page',
    'normal',
    'high'
  );
});

function spa_price_render_box($post) {

  wp_nonce_field('spa_price_nonce', 'spa_price_nonce_field');

  $eyebrow   = get_post_meta($post->ID, 'spa_price_eyebrow', true);
  $title     = get_post_meta($post->ID, 'spa_price_title', true);
  $gold      = get_post_meta($post->ID, 'spa_price_gold', true);
  $subtitle  = get_post_meta($post->ID, 'spa_price_subtitle', true);
  $sec_id    = get_post_meta($post->ID, 'spa_price_id', true);
  $rows      = get_post_meta($post->ID, 'spa_price_rows', true);
  $cards     = get_post_meta($post->ID, 'spa_price_cards', true);

  if (!is_array($rows)) $rows = array();
  if (!is_array($cards)) $cards = array();

  ?>
  <style>
    .sp-field { margin-bottom: 16px; }
    .sp-field label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; color: #1d2327; }
    .sp-field input[type="text"], .sp-field textarea { width: 100%; padding: 8px 10px; border: 1px solid #8c8f94; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
    .sp-field textarea { min-height: 80px; resize: vertical; }
    .sp-field .description { font-size: 12px; color: #646970; margin-top: 4px; }
    .sp-field h4 { margin: 20px 0 12px; padding-bottom: 8px; border-bottom: 1px solid #dcdcde; color: #2271b1; font-size: 14px; }
    .sp-row { background: #f6f7f7; border: 1px solid #dcdcde; border-radius: 4px; padding: 12px; margin-bottom: 10px; position: relative; }
    .sp-row .sp-field { margin-bottom: 10px; }
    .sp-row .sp-row-fields { display: grid; grid-template-columns: 1fr 1fr 80px 100px; gap: 8px; }
    .sp-row .sp-row-fields input { width: 100%; padding: 6px 8px; border: 1px solid #8c8f94; border-radius: 4px; font-size: 13px; box-sizing: border-box; }
    .sp-remove-row { position: absolute; top: 8px; right: 8px; background: #d63638; color: #fff; border: none; border-radius: 3px; padding: 2px 8px; font-size: 12px; cursor: pointer; }
    .sp-add-row { background: #2271b1; color: #fff; border: none; border-radius: 4px; padding: 6px 14px; font-size: 13px; cursor: pointer; margin-top: 6px; }
    .sp-add-row:hover { background: #135e96; }
    .sp-card { background: #f0f6fc; border: 1px solid #c3c4c7; border-radius: 4px; padding: 12px; margin-bottom: 10px; position: relative; }
    .sp-card .sp-field { margin-bottom: 10px; }
    .sp-remove-card { position: absolute; top: 8px; right: 8px; background: #d63638; color: #fff; border: none; border-radius: 3px; padding: 2px 8px; font-size: 12px; cursor: pointer; }
    .sp-add-card { background: #2271b1; color: #fff; border: none; border-radius: 4px; padding: 6px 14px; font-size: 13px; cursor: pointer; margin-top: 6px; }
    .sp-add-card:hover { background: #135e96; }
    .sp-card-row-wrap { background: #fff; border: 1px solid #dcdcde; border-radius: 3px; padding: 8px; margin-bottom: 6px; }
    .sp-card-row-fields { display: grid; grid-template-columns: 1fr 100px; gap: 6px; }
    .sp-card-row-fields input { width: 100%; padding: 5px 7px; border: 1px solid #8c8f94; border-radius: 3px; font-size: 12px; box-sizing: border-box; }
    .sp-remove-card-row { background: #d63638; color: #fff; border: none; border-radius: 3px; padding: 1px 6px; font-size: 11px; cursor: pointer; margin-left: 4px; }
  </style>

  <div class="sp-field">
    <label>Надзаголовок (eyebrow)</label>
    <input type="text" name="spa_price_eyebrow" value="<?php echo esc_attr($eyebrow); ?>" placeholder="Стоимость">
  </div>
  <div class="sp-field">
    <label>Заголовок</label>
    <input type="text" name="spa_price_title" value="<?php echo esc_attr($title); ?>" placeholder="Прайс на баню">
  </div>
  <div class="sp-field">
    <label>Золотой текст в заголовке</label>
    <input type="text" name="spa_price_gold" value="<?php echo esc_attr($gold); ?>" placeholder="баню">
  </div>
  <div class="sp-field">
    <label>Подзаголовок</label>
    <input type="text" name="spa_price_subtitle" value="<?php echo esc_attr($subtitle); ?>" placeholder="Бронирование минимум за 2 часа...">
  </div>
  <div class="sp-field">
    <label>ID секции (якорь)</label>
    <input type="text" name="spa_price_id" value="<?php echo esc_attr($sec_id); ?>" placeholder="price">
  </div>

  <h4>Строки таблицы (десктоп)</h4>
  <div id="spa-price-rows-list">
    <?php foreach ($rows as $i => $r) : ?>
      <div class="sp-row">
        <button type="button" class="sp-remove-row" onclick="this.closest('.sp-row').remove();">Удалить</button>
        <div class="sp-row-fields">
          <input type="text" name="spa_price_rows[<?php echo $i; ?>][name]" value="<?php echo esc_attr($r['name'] ?? ''); ?>" placeholder="Услуга">
          <input type="text" name="spa_price_rows[<?php echo $i; ?>][desc]" value="<?php echo esc_attr($r['desc'] ?? ''); ?>" placeholder="Описание">
          <input type="text" name="spa_price_rows[<?php echo $i; ?>][dur]" value="<?php echo esc_attr($r['dur'] ?? ''); ?>" placeholder="2 часа">
          <input type="text" name="spa_price_rows[<?php echo $i; ?>][price]" value="<?php echo esc_attr($r['price'] ?? ''); ?>" placeholder="4 500">
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <button type="button" class="sp-add-row" id="spa-add-price-row-btn">+ Добавить строку</button>

  <h4>Мобильные карточки</h4>
  <div id="spa-price-cards-list">
    <?php foreach ($cards as $i => $c) : ?>
      <div class="sp-card">
        <button type="button" class="sp-remove-card" onclick="this.closest('.sp-card').remove();">Удалить</button>
        <div class="sp-field">
          <label>Название карточки</label>
          <input type="text" name="spa_price_cards[<?php echo $i; ?>][name]" value="<?php echo esc_attr($c['name'] ?? ''); ?>" placeholder="Русская баня">
        </div>
        <?php if (!empty($c['rows']) && is_array($c['rows'])) : ?>
          <?php foreach ($c['rows'] as $j => $cr) : ?>
            <div class="sp-card-row-wrap">
              <div class="sp-card-row-fields">
                <input type="text" name="spa_price_cards[<?php echo $i; ?>][rows][<?php echo $j; ?>][text]" value="<?php echo esc_attr($cr['text'] ?? ''); ?>" placeholder="2 часа, парная + отдых">
                <input type="text" name="spa_price_cards[<?php echo $i; ?>][rows][<?php echo $j; ?>][price]" value="<?php echo esc_attr($cr['price'] ?? ''); ?>" placeholder="4 500 ₽">
              </div>
              <button type="button" class="sp-remove-card-row" onclick="this.closest('.sp-card-row-wrap').remove();">X</button>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
        <button type="button" class="sp-add-row" onclick="addCardRow(this)">+ Строка карточки</button>
      </div>
    <?php endforeach; ?>
  </div>
  <button type="button" class="sp-add-card" id="spa-add-price-card-btn">+ Добавить карточку</button>

  <script>
  jQuery(document).ready(function($) {

    var ri = <?php echo count($rows); ?>;
    $('#spa-add-price-row-btn').on('click', function() {
      var h = '<div class="sp-row"><button type="button" class="sp-remove-row" onclick="this.closest(\'.sp-row\').remove();">Удалить</button>' +
        '<div class="sp-row-fields">' +
        '<input type="text" name="spa_price_rows['+ri+'][name]" placeholder="Услуга">' +
        '<input type="text" name="spa_price_rows['+ri+'][desc]" placeholder="Описание">' +
        '<input type="text" name="spa_price_rows['+ri+'][dur]" placeholder="2 часа">' +
        '<input type="text" name="spa_price_rows['+ri+'][price]" placeholder="4 500"></div></div>';
      $('#spa-price-rows-list').append(h);
      ri++;
    });

    var ci = <?php echo count($cards); ?>;
    $('#spa-add-price-card-btn').on('click', function() {
      var h = '<div class="sp-card"><button type="button" class="sp-remove-card" onclick="this.closest(\'.sp-card\').remove();">Удалить</button>' +
        '<div class="sp-field"><label>Название карточки</label><input type="text" name="spa_price_cards['+ci+'][name]" placeholder="Русская баня"></div>' +
        '<button type="button" class="sp-add-row" onclick="addCardRow(this)">+ Строка карточки</button></div>';
      $('#spa-price-cards-list').append(h);
      ci++;
    });

  });

  function addCardRow(btn) {
    var card = jQuery(btn).closest('.sp-card');
    var nameInput = card.find('input[name*="[name]"]');
    var nameAttr = nameInput.attr('name');
    var match = nameAttr.match(/\[(\d+)\]/);
    var idx = match ? match[1] : '0';
    var rows = card.find('.sp-card-row-wrap');
    var ji = rows.length;
    var h = '<div class="sp-card-row-wrap"><div class="sp-card-row-fields">' +
      '<input type="text" name="spa_price_cards['+idx+'][rows]['+ji+'][text]" placeholder="2 часа, парная + отдых">' +
      '<input type="text" name="spa_price_cards['+idx+'][rows]['+ji+'][price]" placeholder="4 500 ₽"></div>' +
      '<button type="button" class="sp-remove-card-row" onclick="this.closest(\'.sp-card-row-wrap\').remove();">X</button></div>';
    jQuery(btn).before(h);
  }
  </script>

  <?php
}

add_action('save_post', function($post_id) {
  if (!isset($_POST['spa_price_nonce_field'])) return;
  if (!wp_verify_nonce($_POST['spa_price_nonce_field'], 'spa_price_nonce')) return;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!current_user_can('edit_page', $post_id)) return;

  $simple = array('spa_price_eyebrow', 'spa_price_title', 'spa_price_gold', 'spa_price_subtitle', 'spa_price_id');
  foreach ($simple as $field) {
    if (isset($_POST[$field])) {
      update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
    }
  }

  if (isset($_POST['spa_price_rows']) && is_array($_POST['spa_price_rows'])) {
    $rows = array();
    foreach ($_POST['spa_price_rows'] as $r) {
      $name  = sanitize_text_field($r['name'] ?? '');
      $desc  = sanitize_text_field($r['desc'] ?? '');
      $dur   = sanitize_text_field($r['dur'] ?? '');
      $price = sanitize_text_field($r['price'] ?? '');
      if ($name || $desc || $dur || $price) {
        if ($price && mb_substr($price, -1) !== '₽') $price = $price . ' ₽';
        $rows[] = array('name' => $name, 'desc' => $desc, 'dur' => $dur, 'price' => $price);
      }
    }
    update_post_meta($post_id, 'spa_price_rows', $rows);
  } else {
    delete_post_meta($post_id, 'spa_price_rows');
  }

  if (isset($_POST['spa_price_cards']) && is_array($_POST['spa_price_cards'])) {
    $cards = array();
    foreach ($_POST['spa_price_cards'] as $c) {
      $name = sanitize_text_field($c['name'] ?? '');
      $card_rows = array();
      if (!empty($c['rows']) && is_array($c['rows'])) {
        foreach ($c['rows'] as $cr) {
          $text  = sanitize_text_field($cr['text'] ?? '');
          $price = sanitize_text_field($cr['price'] ?? '');
          if ($text || $price) {
            if ($price && mb_substr($price, -1) !== '₽') $price = $price . ' ₽';
            $card_rows[] = array('text' => $text, 'price' => $price);
          }
        }
      }
      if ($name || $card_rows) {
        $cards[] = array('name' => $name, 'rows' => $card_rows);
      }
    }
    update_post_meta($post_id, 'spa_price_cards', $cards);
  } else {
    delete_post_meta($post_id, 'spa_price_cards');
  }
});