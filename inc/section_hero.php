<?php
add_action('add_meta_boxes', function() {
  add_meta_box(
    'hero_section_box',
    'Секция: Основной для всех страниц hero',
    'hero_section_render_box',
    'page',
    'normal',
    'high'
  );
});

function hero_section_render_box($post) {

  wp_nonce_field('hero_section_nonce', 'hero_section_nonce_field');

  $eyebrow   = get_post_meta($post->ID, 'hero_eyebrow', true);
  $title     = get_post_meta($post->ID, 'hero_title', true);
  $gold      = get_post_meta($post->ID, 'hero_gold', true);
  $subtitle  = get_post_meta($post->ID, 'hero_subtitle', true);
  $photo     = get_post_meta($post->ID, 'hero_photo', true);
  $btn1_text = get_post_meta($post->ID, 'hero_btn1_text', true);
  $btn1_link = get_post_meta($post->ID, 'hero_btn1_link', true);
  $btn2_text = get_post_meta($post->ID, 'hero_btn2_text', true);
  $phone     = get_post_meta($post->ID, 'hero_phone', true);
  $email     = get_post_meta($post->ID, 'hero_email', true);
  $vk        = get_post_meta($post->ID, 'hero_vk', true);
  $tg        = get_post_meta($post->ID, 'hero_tg', true);

  ?>
  <style>
    .hero-field { margin-bottom: 16px; }
    .hero-field label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; color: #1d2327; }
    .hero-field input[type="text"], .hero-field textarea { width: 100%; padding: 8px 10px; border: 1px solid #8c8f94; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
    .hero-field textarea { min-height: 80px; resize: vertical; }
    .hero-field .description { font-size: 12px; color: #646970; margin-top: 4px; }
    .hero-field h4 { margin: 20px 0 12px; padding-bottom: 8px; border-bottom: 1px solid #dcdcde; color: #2271b1; font-size: 14px; }
    .hero-preview { margin-top: 8px; max-width: 300px; }
    .hero-preview img { max-width: 100%; height: auto; border: 1px solid #dcdcde; border-radius: 4px; }
    .hero-img-btn { display: inline-block; margin-top: 6px; }
    .hero-img-btn button { background: #2271b1; color: #fff; border: none; border-radius: 4px; padding: 5px 12px; font-size: 13px; cursor: pointer; }
    .hero-img-btn .hero-remove-img { background: #d63638; margin-left: 6px; }
  </style>

  <div class="hero-field">
    <label>Надзаголовок (eyebrow)</label>
    <input type="text" name="hero_eyebrow" value="<?php echo esc_attr($eyebrow); ?>" placeholder="Традиции банной культуры">
  </div>
  <div class="hero-field">
    <label>Заголовок</label>
    <input type="text" name="hero_title" value="<?php echo esc_attr($title); ?>" placeholder="Банный комплекс">
  </div>
  <div class="hero-field">
    <label>Золотой текст в заголовке</label>
    <input type="text" name="hero_gold" value="<?php echo esc_attr($gold); ?>" placeholder="комплекс">
    <p class="description">Текст внутри &lt;em&gt;, выделяется золотым курсивом</p>
  </div>
  <div class="hero-field">
    <label>Подзаголовок</label>
    <textarea name="hero_subtitle" rows="3"><?php echo esc_textarea($subtitle); ?></textarea>
  </div>

  <h4>Фотография героя</h4>
  <div class="hero-field">
    <label>Изображение</label>
    <input type="hidden" name="hero_photo" id="hero_photo_input" value="<?php echo esc_attr($photo); ?>">
    <div class="hero-img-btn">
      <button type="button" id="hero-upload-photo-btn">Выбрать</button>
      <button type="button" class="hero-remove-img" id="hero-remove-photo-btn">Удалить</button>
    </div>
    <?php if ($photo) : ?>
      <div class="hero-preview"><img src="<?php echo esc_url($photo); ?>" alt="preview"></div>
    <?php endif; ?>
  </div>

  <h4>Кнопки</h4>
  <div class="hero-field">
    <label>Кнопка 1 — Текст</label>
    <input type="text" name="hero_btn1_text" value="<?php echo esc_attr($btn1_text); ?>" placeholder="Виды бань">
  </div>
  <div class="hero-field">
    <label>Кнопка 1 — Ссылка</label>
    <input type="text" name="hero_btn1_link" value="<?php echo esc_attr($btn1_link); ?>" placeholder="#types">
  </div>
  <div class="hero-field">
    <label>Кнопка 2 — Текст (открывает попап «Оставить заявку»)</label>
    <input type="text" name="hero_btn2_text" value="<?php echo esc_attr($btn2_text); ?>" placeholder="Забронировать">
    <p class="description">Эта кнопка всегда открывает модалку бронирования</p>
  </div>

  <h4>Социальные ссылки</h4>
  <div class="hero-field">
    <label>Телефон</label>
    <input type="text" name="hero_phone" value="<?php echo esc_attr($phone); ?>" placeholder="+79127813933">
  </div>
  <div class="hero-field">
    <label>Email</label>
    <input type="text" name="hero_email" value="<?php echo esc_attr($email); ?>" placeholder="karpovka59ru@yandex.ru">
  </div>
  <div class="hero-field">
    <label>ВКонтакте (ссылка)</label>
    <input type="text" name="hero_vk" value="<?php echo esc_attr($vk); ?>" placeholder="https://vk.com/...">
  </div>
  <div class="hero-field">
    <label>Telegram (ссылка)</label>
    <input type="text" name="hero_tg" value="<?php echo esc_attr($tg); ?>" placeholder="https://t.me/...">
  </div>

  <script>
  jQuery(document).ready(function($) {
    var photoFrame;
    $('#hero-upload-photo-btn').on('click', function(e) {
      e.preventDefault();
      if (photoFrame) { photoFrame.open(); return; }
      photoFrame = wp.media({ title: 'Выбрать фото героя', button: { text: 'Использовать' }, multiple: false });
      photoFrame.on('select', function() {
        var a = photoFrame.state().get('selection').first().toJSON();
        $('#hero_photo_input').val(a.url);
        $('.hero-preview').remove();
        $('<div class="hero-preview"><img src="'+a.url+'" alt="preview"></div>').insertAfter('#hero-remove-photo-btn').parent();
      });
      photoFrame.open();
    });
    $('#hero-remove-photo-btn').on('click', function(e) {
      e.preventDefault();
      $('#hero_photo_input').val('');
      $('.hero-preview').remove();
    });
  });
  </script>

  <?php
}

add_action('save_post', function($post_id) {
  if (!isset($_POST['hero_section_nonce_field'])) return;
  if (!wp_verify_nonce($_POST['hero_section_nonce_field'], 'hero_section_nonce')) return;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!current_user_can('edit_page', $post_id)) return;

  $fields = array('hero_eyebrow', 'hero_title', 'hero_gold', 'hero_subtitle', 'hero_photo', 'hero_btn1_text', 'hero_btn1_link', 'hero_btn2_text', 'hero_phone', 'hero_email', 'hero_vk', 'hero_tg');
  foreach ($fields as $field) {
    if (isset($_POST[$field])) {
      update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
    }
  }
});