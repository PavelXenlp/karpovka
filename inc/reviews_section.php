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
  $layout = get_post_meta($post->ID, 'spa_reviews_layout', true);
  $eyebrow = get_post_meta($post->ID, 'spa_reviews_eyebrow', true);
  $subtitle = get_post_meta($post->ID, 'spa_reviews_subtitle', true);
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
    <label>Вариант отображения</label>
    <select name="spa_reviews_layout">
      <option value="slider" <?php selected($layout, 'slider'); ?>>Слайдер отзывов</option>
      <option value="spa_featured" <?php selected($layout, 'spa_featured'); ?>>Большой отзыв и два компактных (SPA)</option>
    </select>
    <p class="description">В SPA-варианте первый отзыв показывается крупно, остальные — справа.</p>
  </div>
  <div class="sr-field">
    <label>Надзаголовок (eyebrow)</label>
    <input type="text" name="spa_reviews_eyebrow" value="<?php echo esc_attr($eyebrow); ?>" placeholder="Отзывы">
  </div>
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
    <label>Подзаголовок</label>
    <input type="text" name="spa_reviews_subtitle" value="<?php echo esc_attr($subtitle); ?>" placeholder="Что говорят гости">
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

  $simple = array('spa_reviews_layout', 'spa_reviews_eyebrow', 'spa_reviews_title', 'spa_reviews_gold', 'spa_reviews_subtitle');
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

function karpovka_home_reviews_import_items() {
  return array(
    array(
      'stars' => 5,
      'text' => <<<'REVIEW'
Часто ездим отдыхать в парк отель Карповка с семьёй и друзьями!

Мы здесь гуляем, купаемся, отмечаем праздники, даже работаем онлайн на свежем воздухе (везде есть интернет)!

Есть разные развлечения — катамараны, лодки, велосипеды в прокат, снегоходы, несколько бассейнов и саун, детям никогда не скучно) Можно заранее заказать еду в ресторане!
REVIEW,
      'name' => 'Anya N.',
      'date' => '24 апреля 2026',
    ),
    array(
      'stars' => 5,
      'text' => <<<'REVIEW'
Спа-отель «Карповка» — место, куда всегда хочется вернуться!

Мы с семьёй отдыхаем в «Карповке» уже много лет. Несколько раз пытались сменить место, но всё равно возвращаемся сюда снова и снова. И это неудивительно!

Здесь всё на высшем уровне:
✨ Чистые и уютные номера — всегда идеальная чистота и комфорт.
✨ Гостеприимство — встречают всегда с улыбкой, чувствуешь себя как дома.
✨ Вкусная кухня — отдельное спасибо Ангелине за потрясающие завтраки и обеды! 🙏💕
✨ Бассейн и баня — это просто отдельный вид отдыха и релакса.

«Карповка» — это место, где отдых действительно заряжает энергией. Рекомендуем всем, кто ценит уют, вкусную еду и душевное отношение!
REVIEW,
      'name' => 'Дмитрий Власов',
      'date' => '19 апреля 2026',
    ),
    array(
      'stars' => 5,
      'text' => <<<'REVIEW'
Хотите сбежать от суеты, провести незабываемый день с семьёй, устроить волшебный девичник или романтическое свидание? Добро пожаловать в Карповку — лучшее место для отдыха в Пермском крае! Здесь каждый найдёт своё: дети — радость и простор; влюблённые — атмосферу уединения и романтики; профессионалы — идеальную площадку для своих мероприятий. И я — не исключение. Вот уже 4 года Карповка — единственная площадка, которая отвечает всем моим профессиональным требованиям!

А какая здесь вкусная кухня, расслабляющие спа-процедуры и уют гостевых домиков!

Можно бесконечно долго писать хвалебный отзыв, а можно один раз приехать и прочувствовать на себе магию этого места — где отдых становится исцелением, а впечатления остаются на всю жизнь! ❤
REVIEW,
      'name' => 'Юлия Х.',
      'date' => '30 апреля 2026',
    ),
    array(
      'stars' => 5,
      'text' => 'О Карповка!!! Ты лучший Спа отель! Умиротворение, чувство спокойствия и отдыха, персонал всегда на связи, ты всегда под защитой, басссейн огонь. Зимой и летом всегда шикарно особенно летом или зимой еще не решили 😊😊. Рекомендуем 10000000%',
      'name' => 'Павел Иванов',
      'date' => '19 февраля 2026',
    ),
    array(
      'stars' => 5,
      'text' => 'Всей душой и сердцем любим это место, невероятная сказка зимой, весной наслаждаешься тишиной, летом есть куча активностей и все на одной территории, осенью наслаждаешься золотым лесом. Сюда хочется возвращаться и советовать всем своим знакомым. Удовольствие получаешь с самого начала, от того как вежливо тебя встречает персонал, как уютно в каждом домике и номере. Вкусная еда, большая территория, потрясающие бассейны. Это место влюбляет в себя с первых секунд ❤',
      'name' => 'Дарья Носкова',
      'date' => '10 апреля 2026',
    ),
    array(
      'stars' => 5,
      'text' => <<<'REVIEW'
Замечательно отдохнули в очередной раз в Карповке!
Что мы для себя открыли в этом месте:
Теплые чистые бассейны, даже на улице!
Сказочное место, с красивой территорий, уютными местами для отдыха, пикника и игр для детей.
Карповый пруд с лебедями.
Вкуснейший ресторан для всей семьи.
Профессиональный массаж, для взрослых и детей, соляная комната, прессотерапия и многое другое.
Свежий воздух, красивые номера в современном стиле с качественнрй мебелью и всем необходимым.
Вежливый персонал с заботой о гостях.

Обязательно приедем и не раз!
REVIEW,
      'name' => 'OksaBat',
      'date' => '20 октября 2025',
    ),
    array(
      'stars' => 5,
      'text' => 'Очень душевное место среди красоты природы. Жили в VIP доме, всё очень достойно, удобно, красиво. Прекрасный релакс в теплом бассейне под открытым небом, сауне. Для ребенка было чем заняться на детской площадке.',
      'name' => 'Larisa.Kurbatova.73',
      'date' => '31 мая 2026',
    ),
    array(
      'stars' => 5,
      'text' => 'Прекрасное удалённое место для отдыха, уютные стильные обустроенные домики. Детская площадка. Возможность половить рыбу в местном пруду. Мангальные зоны, можно заранее заказать питание из большого выбора блюд. Бассейн крытый и тёплый на улице. Сауна. Вежливый персонал. Приезжайте — не пожалеете.',
      'name' => 'Константин Марков',
      'date' => '12 октября 2025',
    ),
    array(
      'stars' => 5,
      'text' => 'Бываю здесь летом, и зимой — всегда есть чем заняться. Сервис на высоте, ремонт и интерьер сделаны с настоящей заботой о клиентах. В домиках очень красиво, уютно и кристально чисто. Сауны, бассейн, природа — всё, что душе угодно. Съездила уже больше 15 раз и снова хочется вернуться!',
      'name' => 'Marina',
      'date' => '27 апреля 2026',
    ),
    array(
      'stars' => 5,
      'text' => <<<'REVIEW'
Благодарю за волшебный отдых на Базе Парк оттель Карповка.
Баня, сауна, бассейн 🏊
Великолепная природа и рыбалка 🎣
Лесная беседка — самое комфортное место для большой компании.
REVIEW,
      'name' => 'Анастасия Трушникова',
      'date' => '1 июля 2026',
    ),
  );
}

function karpovka_import_home_reviews( $post_id ) {
  if ( 'index.php' !== get_page_template_slug( $post_id ) ) return false;
  if ( get_post_meta( $post_id, '_karpovka_reviews_import_20260824', true ) ) return false;

  $existing = get_post_meta( $post_id, 'spa_reviews_items', true );
  $existing = is_array( $existing ) ? array_values( $existing ) : array();
  $known = array();
  foreach ( $existing as $review ) {
    $known[ md5( ( $review['name'] ?? '' ) . '|' . ( $review['date'] ?? '' ) . '|' . ( $review['text'] ?? '' ) ) ] = true;
  }

  foreach ( karpovka_home_reviews_import_items() as $review ) {
    $key = md5( $review['name'] . '|' . $review['date'] . '|' . $review['text'] );
    if ( isset( $known[ $key ] ) ) continue;
    $existing[] = $review;
    $known[ $key ] = true;
  }

  update_post_meta( $post_id, 'spa_reviews_items', $existing );
  if ( '' === get_post_meta( $post_id, 'spa_reviews_title', true ) ) {
    update_post_meta( $post_id, 'spa_reviews_title', 'Отзывы наших' );
  }
  if ( '' === get_post_meta( $post_id, 'spa_reviews_gold', true ) ) {
    update_post_meta( $post_id, 'spa_reviews_gold', 'гостей' );
  }
  update_post_meta( $post_id, '_karpovka_reviews_import_20260824', '1' );
  return true;
}

add_action( 'admin_init', function () {
  if ( ! isset( $_GET['post'] ) ) return;
  $post_id = absint( $_GET['post'] );
  if ( ! $post_id || ! current_user_can( 'edit_page', $post_id ) ) return;
  karpovka_import_home_reviews( $post_id );
} );
