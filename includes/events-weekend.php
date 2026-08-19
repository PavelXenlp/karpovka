<?php
/**
 * Template: Events Weekend Section
 */
 $v     = get_post_meta( get_the_ID(), 'events_weekend', true ) ?: [];
 $cards = $v['cards'] ?? [];

if ( empty( $cards ) && empty( $v['eyebrow'] ?? '' ) && empty( $v['title'] ?? '' ) ) return;
?>

<section class="c-events-weekend" id="<?php echo esc_attr( $v['section_id'] ?? 'weekend' ); ?>">
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
    <div class="c-events-weekend__grid reveal">

      <?php foreach ( $cards as $c ) : ?>

      <div class="c-events-weekend__card">
        <?php if ( ! empty( $c['icon'] ) ) : ?>
        <div class="c-events-weekend__card-icon">
          <i class="<?php echo esc_attr( $c['icon'] ); ?>"></i>
        </div>
        <?php endif; ?>
        <h3 class="c-events-weekend__card-title"><?php echo esc_html( $c['title'] ?? '' ); ?></h3>
        <?php if ( ! empty( $c['desc'] ) ) : ?>
        <p class="c-events-weekend__card-desc"><?php echo esc_html( $c['desc'] ); ?></p>
        <?php endif; ?>
        <?php if ( ! empty( $c['tag'] ) ) : ?>
        <span class="c-events-weekend__card-tag"><?php echo esc_html( $c['tag'] ); ?></span>
        <?php endif; ?>
      </div>

      <?php endforeach; ?>

    </div>
    <?php endif; ?>

  </div>
</section>

  