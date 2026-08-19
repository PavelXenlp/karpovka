<?php
/**
 * Template: Dining Menu Section + Modals
 */
 $v     = get_post_meta( get_the_ID(), 'dining_menu', true ) ?: [];
 $cards = $v['cards'] ?? [];

if ( empty( $cards ) && empty( $v['eyebrow'] ?? '' ) && empty( $v['title'] ?? '' ) ) return;

 $check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c8a96e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
?>

<section class="c-dining-menu" id="<?php echo esc_attr( $v['section_id'] ?? 'menu' ); ?>">
  <div class="container">

    <div class="section-head section-head--center reveal">
      <?php if ( ! empty( $v['eyebrow'] ) ) : ?>
      <p class="eyebrow"><?php echo esc_html( $v['eyebrow'] ); ?></p>
      <?php endif; ?>

      <?php if ( ! empty( $v['title'] ) || ! empty( $v['gold_text'] ) ) : ?>
      <h2 class="section-title">
        <?php echo esc_html( $v['title'] ?? '' ); ?>
        <?php if ( ! empty( $v['gold_text'] ) ) : ?>
        <span class="gold-header"><em><?php echo esc_html( $v['gold_text'] ); ?></em></span>
        <?php endif; ?>
      </h2>
      <?php endif; ?>

      <?php if ( ! empty( $v['subtitle'] ) ) : ?>
      <p class="section-sub"><?php echo esc_html( $v['subtitle'] ); ?></p>
      <?php endif; ?>
    </div>

    <?php if ( ! empty( $cards ) ) : ?>
    <div class="c-dining-menu__grid reveal">

      <?php foreach ( $cards as $c ) :
          $slug = $c['slug'] ?? '';
          if ( ! $slug ) $slug = 'modal-dining-' . sanitize_title( $c['card_title'] ?? '' );
      ?>

      <article class="c-dining-menu__card" data-dining-modal="<?php echo esc_attr( $slug ); ?>">
        <div class="c-dining-menu__card-img">
          <?php if ( ! empty( $c['card_image'] ) ) : ?>
          <img src="<?php echo esc_url( $c['card_image'] ); ?>" alt="<?php echo esc_attr( $c['card_title'] ?? '' ); ?>" loading="lazy">
          <?php endif; ?>
        </div>
        <div class="c-dining-menu__card-body">
          <h3 class="c-dining-menu__card-title"><?php echo esc_html( $c['card_title'] ?? '' ); ?></h3>
          <?php if ( ! empty( $c['card_desc'] ) ) : ?>
          <p class="c-dining-menu__card-desc"><?php echo esc_html( $c['card_desc'] ); ?></p>
          <?php endif; ?>
          <?php if ( ! empty( $c['meta1_text'] ) || ! empty( $c['meta2_text'] ) ) : ?>
          <div class="c-dining-menu__card-meta">
            <?php if ( ! empty( $c['meta1_text'] ) ) : ?>
            <span><i class="<?php echo esc_attr( $c['meta1_icon'] ?? '' ); ?>"></i> <?php echo esc_html( $c['meta1_text'] ); ?></span>
            <?php endif; ?>
            <?php if ( ! empty( $c['meta2_text'] ) ) : ?>
            <span><i class="<?php echo esc_attr( $c['meta2_icon'] ?? '' ); ?>"></i> <?php echo esc_html( $c['meta2_text'] ); ?></span>
            <?php endif; ?>
          </div>
          <?php endif; ?>
          <a href="#" class="c-dining-menu__card-link">
            Подробнее <i class="icon-arrow-right"></i>
          </a>
        </div>
      </article>

      <?php endforeach; ?>

    </div>
    <?php endif; ?>

  </div>
</section>

<?php if ( ! empty( $cards ) ) : foreach ( $cards as $c ) :
    $slug             = $c['slug'] ?? '';
    $modal_photo      = $c['modal_photo'] ?? '';
    $modal_badge      = $c['modal_badge'] ?? '';
    $modal_title      = $c['modal_title'] ?? '';
    $modal_meta       = $c['modal_meta'] ?? '';
    $modal_desc       = $c['modal_desc'] ?? '';
    $items_title      = $c['items_title'] ?? '';
    $items            = $c['items'] ?? [];
    $foot_price       = $c['foot_price'] ?? '';
    $foot_price_small = $c['foot_price_small'] ?? '';
    $foot_cta         = $c['foot_cta'] ?? '';
    $foot_cta_link    = $c['foot_cta_link'] ?? '#booking';

    if ( ! $slug ) $slug = 'modal-dining-' . sanitize_title( $modal_title );
?>

<!-- ═══ Попап: <?php echo esc_html( $modal_title ); ?> ═══ -->
<div class="dining-modal" id="<?php echo esc_attr( $slug ); ?>" hidden data-dining-modal-close>
  <div class="dining-modal__overlay" data-dining-modal-close></div>
  <div class="dining-modal__dialog">
    <button class="dining-modal__close" data-dining-modal-close aria-label="Закрыть">&#10005;</button>
    <?php if ( $modal_photo ) : ?>
    <img class="dining-modal__photo" src="<?php echo esc_url( $modal_photo ); ?>" alt="<?php echo esc_attr( $modal_title ); ?>" loading="lazy">
    <?php endif; ?>
    <div class="dining-modal__body">
      <?php if ( $modal_badge ) : ?>
      <p class="dining-modal__badge"><?php echo esc_html( $modal_badge ); ?></p>
      <?php endif; ?>
      <?php if ( $modal_title ) : ?>
      <h3 class="dining-modal__title"><?php echo esc_html( $modal_title ); ?></h3>
      <?php endif; ?>
      <?php if ( $modal_meta ) : ?>
      <p class="dining-modal__meta"><?php echo esc_html( $modal_meta ); ?></p>
      <?php endif; ?>
      <?php if ( $modal_desc ) : ?>
      <p class="dining-modal__desc"><?php echo esc_html( $modal_desc ); ?></p>
      <?php endif; ?>

      <?php if ( ! empty( $items ) ) : ?>
      <div class="dining-modal__includes">
        <?php if ( $items_title ) : ?>
        <h4 class="dining-modal__includes-title"><?php echo esc_html( $items_title ); ?></h4>
        <?php endif; ?>
        <ul class="dining-modal__includes-list">
          <?php foreach ( $items as $it ) : ?>
          <li>
            <?php echo $check_svg; ?>
            <?php echo esc_html( $it['text'] ?? '' ); ?><?php if ( ! empty( $it['price'] ) ) : ?> — <span class="price-menu"><?php echo esc_html( $it['price'] ); ?></span><?php endif; ?>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <?php if ( $foot_price || $foot_cta ) : ?>
      <div class="dining-modal__foot">
        <?php if ( $foot_price ) : ?>
        <p class="dining-modal__price"><?php echo esc_html( $foot_price ); ?><?php if ( $foot_price_small ) : ?><small><?php echo esc_html( $foot_price_small ); ?></small><?php endif; ?></p>
        <?php endif; ?>
        <?php if ( $foot_cta ) : ?>
        <a href="<?php echo esc_attr( $foot_cta_link ); ?>" class="dining-modal__cta"><?php echo esc_html( $foot_cta ); ?> <i class="icon-arrow-right"></i></a>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php endforeach; endif; ?>