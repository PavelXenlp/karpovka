<?php
/* ===== Шаблон: Активности — Сезоны ===== */
 $pid = get_the_ID();

 $e  = get_post_meta($pid, 'rec_seasons_eyebrow', true);
 $tb = get_post_meta($pid, 'rec_seasons_title_before', true);
 $te = get_post_meta($pid, 'rec_seasons_title_em', true);
 $su = get_post_meta($pid, 'rec_seasons_sub', true);
 $ch = get_post_meta($pid, 'rec_seasons_chips', true);
if (!is_array($ch)) $ch = [];
?>

<section class="c-act-seasons" id="seasons">
  <div class="container">
    <div class="section-head section-head--center reveal">
      <?php if ($e) : ?><p class="eyebrow"><?php echo esc_html($e); ?></p><?php endif; ?>
      <?php if ($tb || $te) : ?>
        <h2 class="section-title"><?php echo esc_html($tb); ?><?php if ($te) : ?><span class="gold-header"><em><?php echo esc_html($te); ?></em></span><?php endif; ?></h2>
      <?php endif; ?>
      <?php if ($su) : ?><p class="section-sub"><?php echo esc_html($su); ?></p><?php endif; ?>
    </div>
    <?php if (!empty($ch)) : ?>
    <div class="c-act-seasons__chips reveal">
      <?php foreach ($ch as $c) :
        $cls = 'c-act-seasons__chip' . ($c['active'] === '1' ? ' is-active' : '');
      ?>
        <button class="<?php echo $cls; ?>" type="button" data-season="<?php echo esc_attr($c['value']); ?>"><?php echo esc_html($c['text']); ?></button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>