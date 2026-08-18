<?php
 $eyebrow   = get_post_meta(get_the_ID(), 'spa_price_eyebrow', true);
 $title     = get_post_meta(get_the_ID(), 'spa_price_title', true);
 $gold      = get_post_meta(get_the_ID(), 'spa_price_gold', true);
 $subtitle  = get_post_meta(get_the_ID(), 'spa_price_subtitle', true);
 $sec_id    = get_post_meta(get_the_ID(), 'spa_price_id', true);
 $rows      = get_post_meta(get_the_ID(), 'spa_price_rows', true);
 $cards     = get_post_meta(get_the_ID(), 'spa_price_cards', true);

 $eyebrow   = !empty($eyebrow)  ? $eyebrow  : 'Стоимость';
 $title     = !empty($title)    ? $title    : 'Прайс на баню';
 $gold      = !empty($gold)     ? $gold     : 'баню';
 $subtitle  = !empty($subtitle) ? $subtitle : 'Бронирование минимум за 2 часа, VIP-бронирование — за сутки';
 $sec_id    = !empty($sec_id)   ? $sec_id   : 'price';
 $rows      = !empty($rows) && is_array($rows) ? $rows : array();
 $cards     = !empty($cards) && is_array($cards) ? $cards : array();
?>

<section class="c-spa-price" id="<?php echo esc_attr($sec_id); ?>">
  <div class="container">
    <div class="section-head section-head--center reveal">
      <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
      <h2 class="section-title">
        <?php echo esc_html($title); ?>
        <?php if ($gold) : ?>
          <span class="gold-header"><em><?php echo esc_html($gold); ?></em></span>
        <?php endif; ?>
      </h2>
      <?php if ($subtitle) : ?>
        <p class="section-sub"><?php echo esc_html($subtitle); ?></p>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($rows) : ?>
  <div class="c-spa-price__wrap reveal">
    <table class="c-spa-price__table">
      <thead>
        <tr>
          <th>Услуга</th>
          <th>Описание</th>
          <th>Длительность</th>
          <th>Стоимость</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r) :
          $name  = !empty($r['name'])  ? esc_html($r['name'])  : '';
          $desc  = !empty($r['desc'])  ? esc_html($r['desc'])  : '';
          $dur   = !empty($r['dur'])   ? esc_html($r['dur'])   : '';
          $price = !empty($r['price']) ? esc_html($r['price']) : '';
        ?>
          <tr>
            <td><?php if ($name) : ?><strong><?php echo $name; ?></strong><?php endif; ?></td>
            <td><?php echo $desc; ?></td>
            <td><?php echo $dur; ?></td>
            <td><?php if ($price) : ?><strong><?php echo $price; ?></strong><?php endif; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>

  <?php if ($cards) : ?>
  <div class="c-spa-price__cards">
    <?php foreach ($cards as $c) :
      $card_name = !empty($c['name']) ? esc_html($c['name']) : '';
      $card_rows = !empty($c['rows']) && is_array($c['rows']) ? $c['rows'] : array();
    ?>
      <?php if ($card_name || $card_rows) : ?>
      <div class="c-spa-price__card">
        <?php if ($card_name) : ?>
          <p class="c-spa-price__card-name"><?php echo $card_name; ?></p>
        <?php endif; ?>
        <?php foreach ($card_rows as $cr) :
          $text  = !empty($cr['text'])  ? esc_html($cr['text'])  : '';
          $price = !empty($cr['price']) ? esc_html($cr['price']) : '';
        ?>
          <?php if ($text || $price) : ?>
          <div class="c-spa-price__card-row">
            <?php if ($text) : ?><span><?php echo $text; ?></span><?php endif; ?>
            <?php if ($price) : ?><strong><?php echo $price; ?></strong><?php endif; ?>
          </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>