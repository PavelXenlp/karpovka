<?php
 $eyebrow = get_post_meta(get_the_ID(), 'spa_gallery_eyebrow', true);
 $title   = get_post_meta(get_the_ID(), 'spa_gallery_title', true);
 $gold    = get_post_meta(get_the_ID(), 'spa_gallery_gold', true);
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
    </div>
  </div>

  <?php if ($items) : ?>
  <div class="c-spa-gallery__masonry reveal">
    <?php foreach ($items as $item) :
      $img  = !empty($item['img']) ? esc_url($item['img']) : '';
      $alt  = !empty($item['alt']) ? esc_attr($item['alt']) : '';
      $tall = !empty($item['tall']) ? ' c-spa-gallery__item--tall' : '';
    ?>
      <div class="c-spa-gallery__item<?php echo $tall; ?>">
        <img src="<?php echo $img; ?>" alt="<?php echo $alt; ?>" loading="lazy">
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>

<!-- Лайтбокс для галереи -->
<div class="spa-lightbox" id="spaLightbox">
  <div class="spa-lightbox__overlay"></div>
  <button class="spa-lightbox__close" aria-label="Закрыть">&times;</button>
  <button class="spa-lightbox__arrow spa-lightbox__arrow--prev" aria-label="Предыдущее">
    <svg class="icon"><use href="#icon-arrow-left"></use></svg>
  </button>
  <button class="spa-lightbox__arrow spa-lightbox__arrow--next" aria-label="Следующее">
    <svg class="icon"><use href="#icon-arrow-right"></use></svg>
  </button>
  <div class="spa-lightbox__counter"></div>
  <img class="spa-lightbox__img" src="" alt="">
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var lightbox = document.getElementById('spaLightbox');
  if (!lightbox) return;

  var img = lightbox.querySelector('.spa-lightbox__img');
  var overlay = lightbox.querySelector('.spa-lightbox__overlay');
  var closeBtn = lightbox.querySelector('.spa-lightbox__close');
  var prevBtn = lightbox.querySelector('.spa-lightbox__arrow--prev');
  var nextBtn = lightbox.querySelector('.spa-lightbox__arrow--next');
  var counter = lightbox.querySelector('.spa-lightbox__counter');

  var galleryItems = document.querySelectorAll('.c-spa-gallery__item');
  if (!galleryItems.length) return;

  var currentIndex = 0;

  function open(index) {
    currentIndex = index;
    var src = galleryItems[index].querySelector('img').src;
    img.src = src;
    counter.textContent = (index + 1) + ' / ' + galleryItems.length;
    lightbox.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function close() {
    lightbox.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  function prev() {
    currentIndex = (currentIndex - 1 + galleryItems.length) % galleryItems.length;
    open(currentIndex);
  }

  function next() {
    currentIndex = (currentIndex + 1) % galleryItems.length;
    open(currentIndex);
  }

  galleryItems.forEach(function(item, i) {
    item.style.cursor = 'pointer';
    item.addEventListener('click', function() {
      open(i);
    });
  });

  closeBtn.addEventListener('click', close);
  overlay.addEventListener('click', close);
  prevBtn.addEventListener('click', prev);
  nextBtn.addEventListener('click', next);

  document.addEventListener('keydown', function(e) {
    if (!lightbox.classList.contains('is-open')) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowLeft') prev();
    if (e.key === 'ArrowRight') next();
  });
});
</script>