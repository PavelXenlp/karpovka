<?php
 $title   = get_post_meta(get_the_ID(), 'spa_reviews_title', true);
 $gold    = get_post_meta(get_the_ID(), 'spa_reviews_gold', true);
 $reviews = get_post_meta(get_the_ID(), 'spa_reviews_items', true);

 $title   = !empty($title) ? $title : 'Отзывы наших гостей';
 $gold    = !empty($gold)  ? $gold  : 'гостей';
 $reviews = !empty($reviews) && is_array($reviews) ? $reviews : array();
?>

<section class="section reviews-section">
  <div class="container">
    <div class="section-head reveal">
      <h2 class="section-title">
        <?php echo esc_html($title); ?>
        <?php if ($gold) : ?>
          <em><?php echo esc_html($gold); ?></em>
        <?php endif; ?>
      </h2>
    </div>
  </div>

  <?php if ($reviews) : ?>
  <div class="reviews-slider swiper reveal" data-reviews>
    <div class="swiper-wrapper">
      <?php foreach ($reviews as $r) :
        $stars = intval($r['stars'] ?? 5);
        $text  = !empty($r['text']) ? $r['text'] : '';
        $name  = !empty($r['name']) ? esc_html($r['name']) : '';
        $date  = !empty($r['date']) ? esc_html($r['date']) : '';
        $star_html = str_repeat('★', $stars) . str_repeat('☆', 5 - $stars);
      ?>
        <blockquote class="review swiper-slide" data-review>
          <div class="review__stars"><?php echo $star_html; ?></div>
          <p class="review__text"><?php echo wp_kses_post($text); ?></p>
          <?php if ($name || $date) : ?>
          <div class="review__author">
            <?php if ($name) : ?>
              <div class="review__name"><?php echo $name; ?></div>
            <?php endif; ?>
            <?php if ($date) : ?>
              <div class="review__date"><?php echo $date; ?></div>
            <?php endif; ?>
          </div>
          <?php endif; ?>
          <div class="review__fade" aria-hidden="true"></div>
          <button class="review__toggle" type="button" data-review-toggle>Читать полностью</button>
        </blockquote>
      <?php endforeach; ?>
    </div>

    <button class="reviews-nav reviews-nav--prev" type="button" data-reviews-prev aria-label="Предыдущий отзыв">
      <svg class="icon"><use href="#icon-arrow-left"></use></svg>
    </button>
    <button class="reviews-nav reviews-nav--next" type="button" data-reviews-next aria-label="Следующий отзыв">
      <svg class="icon"><use href="#icon-arrow-right"></use></svg>
    </button>
  </div>
  <?php endif; ?>
</section>

<!-- Попап с полным текстом отзыва -->
<div class="review-modal" data-review-modal hidden>
  <div class="review-modal__overlay" data-review-modal-close></div>
  <div class="review-modal__dialog" role="dialog" aria-modal="true" aria-label="Отзыв гостя">
    <button class="review-modal__close" type="button" data-review-modal-close aria-label="Закрыть">
      <svg class="icon"><use href="#icon-close"></use></svg>
    </button>
    <div class="review-modal__stars" data-review-modal-stars></div>
    <div class="review-modal__text" data-review-modal-text></div>
    <div class="review-modal__author">
      <div class="review-modal__name" data-review-modal-name></div>
      <div class="review-modal__date" data-review-modal-date></div>
    </div>
  </div>
</div>