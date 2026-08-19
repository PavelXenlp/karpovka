<?php
/**
 * Template: Dining Booking Section (аккордеон)
 */
 $v = get_post_meta( get_the_ID(), 'dining_booking', true ) ?: [];

 $eyebrow    = $v['eyebrow']    ?? '';
 $title      = $v['title']      ?? '';
 $gold_text  = $v['gold_text']  ?? '';
 $subtitle   = $v['subtitle']   ?? '';
 $section_id = $v['section_id'] ?? 'booking';
 $items      = $v['items']      ?? [];

if ( empty( $items ) && empty( $eyebrow ) && empty( $title ) ) return;
?>

<section class="c-dining-booking" id="<?php echo esc_attr( $section_id ); ?>">
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

    <?php if ( ! empty( $items ) ) : ?>
    <div class="c-dining-booking__list reveal" data-faq="">

      <?php foreach ( $items as $item ) :
          $num      = $item['num']      ?? '';
          $question = $item['question'] ?? '';
          $answer   = $item['answer']   ?? '';
          if ( ! $question ) continue;
      ?>

      <div class="c-dining-booking__item" data-faq-item="">
        <button class="c-dining-booking__q" type="button" data-faq-toggle="" aria-expanded="false">
          <?php if ( $num ) : ?>
          <span class="c-dining-booking__num"><?php echo esc_html( $num ); ?></span>
          <?php endif; ?>
          <span><?php echo esc_html( $question ); ?></span>
          <span class="c-dining-booking__icon" aria-hidden="true"></span>
        </button>
        <div class="c-dining-booking__a" data-faq-answer="">
          <p><?php echo wp_kses_post( $answer ); ?></p>
        </div>
      </div>

      <?php endforeach; ?>

    </div>
    <?php endif; ?>

  </div>
</section>