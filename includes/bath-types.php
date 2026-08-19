<?php
/**
 * Template: Bath Types Section + Modals
 */
 $v = get_post_meta( get_the_ID(), 'bath_types', true ) ?: [];

 $eyebrow    = $v['eyebrow']    ?? '';
 $title      = $v['title']      ?? '';
 $gold_text  = $v['gold_text']  ?? '';
 $subtitle   = $v['subtitle']   ?? '';
 $section_id = $v['section_id'] ?? 'directions';
 $cards      = $v['cards']      ?? [];

if ( empty( $cards ) && empty( $eyebrow ) && empty( $title ) ) return;

/* SVG-иконка галочки для списка */
 $check_svg = '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>';
?>

<section class="c-bath-types" id="<?php echo esc_attr( $section_id ); ?>">
  <div class="container">

    <div class="section-head section-head--center reveal">
      <?php if ( $eyebrow ) : ?>
      <p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
      <?php endif; ?>

      <?php if ( $title || $gold_text ) : ?>
      <h2 class="section-title">
        <?php echo esc_html( $title ); ?>
        <?php if ( $gold_text ) : ?>
        <span class="gold-header"><em><?php echo esc_html( $gold_text ); ?></em></span>
        <?php endif; ?>
      </h2>
      <?php endif; ?>

      <?php if ( $subtitle ) : ?>
      <p class="section-sub"><?php echo esc_html( $subtitle ); ?></p>
      <?php endif; ?>
    </div>

    <?php if ( ! empty( $cards ) ) : ?>
    <div class="c-bath-types__grid reveal">

      <?php foreach ( $cards as $c ) :
          $slug       = $c['slug'] ?? '';
          $card_image = $c['card_image'] ?? '';
          $card_tag   = $c['card_tag'] ?? '';
          $card_title = $c['card_title'] ?? '';
          $card_desc  = $c['card_desc'] ?? '';
          $card_style = $c['card_style'] ?? '';

          $style_class = '';
          if ( $card_style === 'wide' )    $style_class = ' c-bath-types__card--wide';
          if ( $card_style === 'wide-alt' ) $style_class = ' c-bath-types__card--wide-alt';

          if ( ! $slug ) $slug = 'modal-bath-' . sanitize_title( $card_title );
      ?>

      <a href="#" class="c-bath-types__card<?php echo $style_class; ?>" data-bath-modal="<?php echo esc_attr( $slug ); ?>">
        <div class="c-bath-types__card-img">
          <?php if ( $card_image ) : ?>
          <img src="<?php echo esc_url( $card_image ); ?>" alt="<?php echo esc_attr( $card_title ); ?>" loading="lazy">
          <?php endif; ?>
        </div>
        <div class="c-bath-types__card-overlay"></div>
        <div class="c-bath-types__card-content">
          <?php if ( $card_tag ) : ?>
          <span class="c-bath-types__card-tag"><?php echo esc_html( $card_tag ); ?></span>
          <?php endif; ?>
          <h3 class="c-bath-types__card-title"><?php echo esc_html( $card_title ); ?></h3>
          <?php if ( $card_desc ) : ?>
          <p class="c-bath-types__card-desc"><?php echo esc_html( $card_desc ); ?></p>
          <?php endif; ?>
          <span class="c-bath-types__card-link">Подробнее <i class="fa-solid fa-arrow-right"></i></span>
        </div>
      </a>

      <?php endforeach; ?>

    </div>
    <?php endif; ?>

  </div>
</section>

<?php if ( ! empty( $cards ) ) : foreach ( $cards as $c ) :
    $slug          = $c['slug'] ?? '';
    $modal_photo   = $c['modal_photo'] ?? '';
    $modal_tag     = $c['modal_tag'] ?? '';
    $modal_title   = $c['modal_title'] ?? '';
    $modal_meta    = $c['modal_meta'] ?? '';
    $modal_text    = $c['modal_text'] ?? '';
    $includes_title = $c['includes_title'] ?? '';
    $includes      = $c['includes'] ?? [];
    $price         = $c['price'] ?? '';
    $price_small   = $c['price_small'] ?? '';

    if ( ! $slug ) $slug = 'modal-bath-' . sanitize_title( $modal_title );

    /* Разбиваем описание по двойным переносам на абзацы */
    $paragraphs = array_filter( preg_split( '/\n\s*\n/', $modal_text ) );
?>

<!-- ====== ПОПАП: <?php echo esc_html( $modal_title ); ?> ====== -->
<div class="bath-modal" id="<?php echo esc_attr( $slug ); ?>" hidden>
  <div class="bath-modal__overlay" data-bath-modal-close></div>
  <div class="bath-modal__dialog">
    <button class="bath-modal__close" data-bath-modal-close aria-label="Закрыть">
      <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><line x1="6" y1="6" x2="18" y2="18"/><line x1="18" y1="6" x2="6" y2="18"/></svg>
    </button>
    <?php if ( $modal_photo ) : ?>
    <img class="bath-modal__photo" src="<?php echo esc_url( $modal_photo ); ?>" alt="<?php echo esc_attr( $modal_title ); ?>">
    <?php endif; ?>
    <div class="bath-modal__body">
      <?php if ( $modal_tag ) : ?>
      <p class="bath-modal__tag"><?php echo esc_html( $modal_tag ); ?></p>
      <?php endif; ?>
      <?php if ( $modal_title ) : ?>
      <h3 class="bath-modal__title"><?php echo esc_html( $modal_title ); ?></h3>
      <?php endif; ?>
      <?php if ( $modal_meta ) : ?>
      <p class="bath-modal__meta"><?php echo esc_html( $modal_meta ); ?></p>
      <?php endif; ?>
      <div class="bath-modal__details">
        <?php if ( ! empty( $paragraphs ) ) : foreach ( $paragraphs as $p ) : ?>
        <p><?php echo esc_html( trim( $p ) ); ?></p>
        <?php endforeach; endif; ?>

        <?php if ( ! empty( $includes ) ) : ?>
        <div class="bath-modal__includes">
          <?php if ( $includes_title ) : ?>
          <h4 class="bath-modal__includes-title"><?php echo esc_html( $includes_title ); ?></h4>
          <?php endif; ?>
          <?php foreach ( $includes as $incl ) : ?>
          <div class="bath-modal__incl">
            <?php echo $check_svg; ?>
            <span><?php echo esc_html( $incl['text'] ?? '' ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <?php if ( $price ) : ?>
    <div class="bath-modal__body" style="padding-top: 0;">
      <div class="bath-modal__foot">
        <p class="bath-modal__price"><?php echo esc_html( $price ); ?><?php if ( $price_small ) : ?> <small><?php echo esc_html( $price_small ); ?></small><?php endif; ?></p>
        <a href="#cta" class="btn btn--gold" data-bath-modal-close>Забронировать</a>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php endforeach; endif; ?>