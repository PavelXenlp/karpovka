<?php
 $eyebrow  = get_post_meta(get_the_ID(), 'spa_salt_eyebrow', true);
 $title    = get_post_meta(get_the_ID(), 'spa_salt_title', true);
 $gold     = get_post_meta(get_the_ID(), 'spa_salt_gold', true);
 $text     = get_post_meta(get_the_ID(), 'spa_salt_text', true);
 $features = get_post_meta(get_the_ID(), 'spa_salt_features', true);
 $img      = get_post_meta(get_the_ID(), 'spa_salt_img', true);
 $sec_id   = get_post_meta(get_the_ID(), 'spa_salt_id', true);

 $eyebrow  = !empty($eyebrow) ? $eyebrow : 'Галотерапия';
 $title    = !empty($title)   ? $title   : 'Соляная комната';
 $gold     = !empty($gold)    ? $gold    : 'комната';
 $text     = !empty($text)    ? $text    : 'Природная соляная пещера из гималайской соли — эффективная профилактика простудных заболеваний, астмы и аллергии. Микроклимат комнаты укрепляет иммунитет и улучшает состояние кожи.';
 $sec_id   = !empty($sec_id)  ? $sec_id  : 'salt';
 $features = !empty($features) && is_array($features) ? $features : array();
?>

<section class="c-spa-detail c-spa-detail--reversed" id="<?php echo esc_attr($sec_id); ?>">
  <div class="container">
    <div class="c-spa-detail__layout reveal">
      <div class="c-spa-detail__text">
        <div class="section-head">
          <h2 class="eyebrow"><?php echo esc_html($eyebrow); ?></h2>
          <h2 class="section-title">
            <?php echo esc_html($title); ?>
            <?php if ($gold) : ?>
              <span class="gold-header"><em><?php echo esc_html($gold); ?></em></span>
            <?php endif; ?>
          </h2>
        </div>
        <?php if ($text) : ?>
          <p><?php echo wp_kses_post(nl2br($text)); ?></p>
        <?php endif; ?>
        <?php if ($features) : ?>
        <div class="c-spa-detail__features">
          <?php foreach ($features as $i => $f) :
            $num  = !empty($f['num'])  ? esc_html($f['num'])  : sprintf('%02d', $i + 1);
            $bold = !empty($f['bold']) ? esc_html($f['bold']) : '';
            $desc = !empty($f['desc']) ? wp_kses_post(nl2br($f['desc'])) : '';
          ?>
            <div class="c-spa-detail__feature">
              <span class="c-spa-detail__feature-num"><?php echo $num; ?></span>
              <div class="c-spa-detail__feature-text">
                <?php if ($bold) : ?>
                  <strong><?php echo $bold; ?></strong>
                <?php endif; ?>
                <?php if ($bold && $desc) echo ' — '; ?>
                <?php echo $desc; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <?php if ($img) : ?>
      <div class="c-spa-detail__media">
        <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($eyebrow); ?>" loading="lazy">
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>