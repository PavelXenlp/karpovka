<?php
/**
 * Template: Bath Types Section — карточки со скроллом к bath_details
 */
 $v = get_post_meta( get_the_ID(), 'bath_types', true ) ?: [];

 $eyebrow    = $v['eyebrow']    ?? '';
 $title      = $v['title']      ?? '';
 $gold_text  = $v['gold_text']  ?? '';
 $subtitle   = $v['subtitle']   ?? '';
 $section_id = $v['section_id'] ?? 'directions';
 $cards      = $v['cards']      ?? [];

if ( empty( $cards ) && empty( $eyebrow ) && empty( $title ) ) return;
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
          $card_image = $c['card_image'] ?? '';
          $card_tag   = $c['card_tag'] ?? '';
          $card_title = $c['card_title'] ?? '';
          $card_desc  = $c['card_desc'] ?? '';
          $card_style = $c['card_style'] ?? '';
          $link       = $c['link'] ?? '';

          $style_class = '';
          if ( $card_style === 'wide' )    $style_class = ' c-bath-types__card--wide';
          if ( $card_style === 'wide-alt' ) $style_class = ' c-bath-types__card--wide-alt';
      ?>

      <a href="#<?php echo esc_attr( $link ); ?>" class="c-bath-types__card<?php echo $style_class; ?>">
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