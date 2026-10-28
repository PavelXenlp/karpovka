<?php
 $eyebrow = get_post_meta(get_the_ID(), 'spa_gallery_eyebrow', true);
 $title   = get_post_meta(get_the_ID(), 'spa_gallery_title', true);
 $gold    = get_post_meta(get_the_ID(), 'spa_gallery_gold', true);
 $subtitle = get_post_meta(get_the_ID(), 'spa_gallery_subtitle', true);
 $sec_id  = get_post_meta(get_the_ID(), 'spa_gallery_id', true);
 $items   = get_post_meta(get_the_ID(), 'spa_gallery_items', true);

 $eyebrow = !empty($eyebrow) ? $eyebrow : 'Галерея';
 $title   = !empty($title)   ? $title   : 'Галерея SPA-центра';
 $gold    = !empty($gold)    ? $gold    : 'SPA';
 $sec_id  = !empty($sec_id)  ? $sec_id  : 'gallery';
 $items   = !empty($items) && is_array($items) ? $items : array();
?>

<section class="c-spa-gallery" id="<?php echo esc_attr($sec_id); ?>">
  <div class="container">
    <div class="section-head section-head--center reveal">
      <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
      <h2 class="section-title">
        <?php echo esc_html($title); ?>
        <?php if ($gold) : ?>
          <span class="gold-header"><em><?php echo esc_html($gold); ?></em></span>
        <?php endif; ?>
      </h2>
      <?php if ($subtitle) : ?><p class="section-sub"><?php echo esc_html($subtitle); ?></p><?php endif; ?>
    </div>
  </div>

  <?php if ($items) : ?>
  <div class="c-spa-gallery__masonry reveal">
    <?php foreach ($items as $item) :
      $img  = !empty($item['img']) ? esc_url($item['img']) : '';
      $alt  = !empty($item['alt']) ? esc_attr($item['alt']) : '';
      $tall = !empty($item['tall']) ? ' c-spa-gallery__item--tall' : '';
    ?>
      <a class="c-spa-gallery__item<?php echo $tall; ?>" href="<?php echo $img; ?>" data-fancybox="spa-gallery" data-caption="<?php echo $alt; ?>">
        <img src="<?php echo $img; ?>" alt="<?php echo $alt; ?>" loading="lazy">
      </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>

<script>
if (window.Fancybox) Fancybox.bind('[data-fancybox="spa-gallery"]', {});
</script>
