<?php
$post_id = get_the_ID();
$layout = get_post_meta($post_id, 'spa_reviews_layout', true) ?: 'slider';
$eyebrow = get_post_meta($post_id, 'spa_reviews_eyebrow', true);
$title = get_post_meta($post_id, 'spa_reviews_title', true) ?: 'Отзывы наших гостей';
$gold = get_post_meta($post_id, 'spa_reviews_gold', true) ?: 'гостей';
$subtitle = get_post_meta($post_id, 'spa_reviews_subtitle', true);
$reviews = get_post_meta($post_id, 'spa_reviews_items', true);
$reviews = is_array($reviews) ? $reviews : array();

$review_stars = static function ($count) {
  $count = max(1, min(5, (int) $count));
  return str_repeat('★', $count) . str_repeat('☆', 5 - $count);
};

$review_initials = static function ($name) {
  $parts = preg_split('/\s+/u', trim((string) $name), -1, PREG_SPLIT_NO_EMPTY);
  $initials = '';
  foreach (array_slice($parts, 0, 2) as $part) {
    $initials .= function_exists('mb_substr') ? mb_substr($part, 0, 1) : substr($part, 0, 1);
  }
  return $initials;
};

if ('spa_featured' === $layout) :
  $featured = $reviews[0] ?? array();
  $side_reviews = array_slice($reviews, 1);
?>
<section class="c-spa-reviews" id="reviews">
  <div class="container">
    <div class="section-head section-head--center reveal">
      <?php if ($eyebrow) : ?><p class="eyebrow"><?php echo esc_html($eyebrow); ?></p><?php endif; ?>
      <h2 class="section-title"><?php echo esc_html($title); ?> <?php if ($gold) : ?><em><?php echo esc_html($gold); ?></em><?php endif; ?></h2>
      <?php if ($subtitle) : ?><p class="section-sub"><?php echo esc_html($subtitle); ?></p><?php endif; ?>
    </div>

    <?php if ($reviews) : ?>
    <div class="c-spa-reviews__layout reveal">
      <div class="c-spa-review-featured">
        <div class="c-spa-review-featured__quote" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"/></svg>
        </div>
        <div class="c-spa-review-featured__stars" aria-label="Оценка: <?php echo (int) ($featured['stars'] ?? 5); ?> из 5"><?php echo esc_html($review_stars($featured['stars'] ?? 5)); ?></div>
        <p class="c-spa-review-featured__text"><?php echo wp_kses_post($featured['text'] ?? ''); ?></p>
        <div class="c-spa-review-featured__author">
          <div class="c-spa-review-featured__avatar"><?php echo esc_html($review_initials($featured['name'] ?? '')); ?></div>
          <div>
            <span class="c-spa-review-featured__name"><?php echo esc_html($featured['name'] ?? ''); ?></span>
            <span class="c-spa-review-featured__date"><?php echo esc_html($featured['date'] ?? ''); ?></span>
          </div>
        </div>
      </div>

      <?php if ($side_reviews) : ?>
      <div class="c-spa-reviews__side">
        <?php foreach ($side_reviews as $review) : ?>
        <div class="c-spa-review-mini">
          <div class="c-spa-review-mini__stars" aria-label="Оценка: <?php echo (int) ($review['stars'] ?? 5); ?> из 5"><?php echo esc_html($review_stars($review['stars'] ?? 5)); ?></div>
          <p class="c-spa-review-mini__text"><?php echo wp_kses_post($review['text'] ?? ''); ?></p>
          <div class="c-spa-review-mini__author">
            <span class="c-spa-review-mini__name"><?php echo esc_html($review['name'] ?? ''); ?></span>
            <span class="c-spa-review-mini__date"><?php echo esc_html($review['date'] ?? ''); ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php else : ?>
<section class="section reviews-section">
  <div class="container">
    <div class="section-head reveal">
      <?php if ($eyebrow) : ?><p class="eyebrow"><?php echo esc_html($eyebrow); ?></p><?php endif; ?>
      <h2 class="section-title"><?php echo esc_html($title); ?> <?php if ($gold) : ?><em><?php echo esc_html($gold); ?></em><?php endif; ?></h2>
      <?php if ($subtitle) : ?><p class="section-sub"><?php echo esc_html($subtitle); ?></p><?php endif; ?>
    </div>
  </div>

  <?php if ($reviews) : ?>
  <div class="reviews-slider swiper reveal" data-reviews>
    <div class="swiper-wrapper">
      <?php foreach ($reviews as $review) : ?>
      <blockquote class="review swiper-slide" data-review>
        <div class="review__stars"><?php echo esc_html($review_stars($review['stars'] ?? 5)); ?></div>
        <p class="review__text"><?php echo wp_kses_post($review['text'] ?? ''); ?></p>
        <?php if (!empty($review['name']) || !empty($review['date'])) : ?>
        <div class="review__author">
          <?php if (!empty($review['name'])) : ?><div class="review__name"><?php echo esc_html($review['name']); ?></div><?php endif; ?>
          <?php if (!empty($review['date'])) : ?><div class="review__date"><?php echo esc_html($review['date']); ?></div><?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="review__fade" aria-hidden="true"></div>
        <button class="review__toggle" type="button" data-review-toggle>Читать полностью</button>
      </blockquote>
      <?php endforeach; ?>
    </div>
    <button class="reviews-nav reviews-nav--prev" type="button" data-reviews-prev aria-label="Предыдущий отзыв"><svg class="icon"><use href="#icon-arrow-left"></use></svg></button>
    <button class="reviews-nav reviews-nav--next" type="button" data-reviews-next aria-label="Следующий отзыв"><svg class="icon"><use href="#icon-arrow-right"></use></svg></button>
  </div>
  <?php endif; ?>
</section>

<div class="review-modal" data-review-modal hidden>
  <div class="review-modal__overlay" data-review-modal-close></div>
  <div class="review-modal__dialog" role="dialog" aria-modal="true" aria-label="Отзыв гостя">
    <button class="review-modal__close" type="button" data-review-modal-close aria-label="Закрыть"><svg class="icon"><use href="#icon-close"></use></svg></button>
    <div class="review-modal__stars" data-review-modal-stars></div>
    <div class="review-modal__text" data-review-modal-text></div>
    <div class="review-modal__author">
      <div class="review-modal__name" data-review-modal-name></div>
      <div class="review-modal__date" data-review-modal-date></div>
    </div>
  </div>
</div>
<?php endif; ?>
