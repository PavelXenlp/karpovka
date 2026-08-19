<?php
// ─── Шаблон секции: SPA-детали (без плагинов) ────────────────

 $eyebrow  = get_post_meta(get_the_ID(), 'spa_detail_eyebrow', true);
 $title    = get_post_meta(get_the_ID(), 'spa_detail_title', true);
 $gold     = get_post_meta(get_the_ID(), 'spa_detail_gold', true);
 $text     = get_post_meta(get_the_ID(), 'spa_detail_text', true);
 $features = get_post_meta(get_the_ID(), 'spa_detail_features', true);
 $img      = get_post_meta(get_the_ID(), 'spa_detail_img', true);
 $sec_id   = get_post_meta(get_the_ID(), 'spa_detail_id', true);

// Дефолтные значения
 $eyebrow  = !empty($eyebrow) ? $eyebrow : 'Водная зона';
 $title    = !empty($title)   ? $title   : 'Бассейны и водная зона';
 $gold     = !empty($gold)    ? $gold    : 'водная зона';
 $text     = !empty($text)    ? $text    : 'Подогреваемый бассейн с панорамным видом на сосновый лес, джакузи с гидромассажем и зона отдыха с шезлонгами.';
 $sec_id   = !empty($sec_id)  ? $sec_id  : 'pool';
 $features = !empty($features) && is_array($features) ? $features : array();
?>

<section class="c-spa-detail" id="<?php echo esc_attr($sec_id); ?>">
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