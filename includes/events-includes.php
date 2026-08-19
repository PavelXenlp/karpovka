<?php
/**
 * Template: Events Includes Section
 */
 $v     = get_post_meta( get_the_ID(), 'events_includes', true ) ?: [];
 $items = $v['items'] ?? [];

if ( empty( $items ) && empty( $v['eyebrow'] ?? '' ) && empty( $v['title'] ?? '' ) ) return;
?>

<section class="c-events-includes" id="<?php echo esc_attr( $v['section_id'] ?? 'includes' ); ?>">
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

    <?php if ( ! empty( $items ) ) : ?>
    <div class="c-events-includes__grid reveal">

      <?php foreach ( $items as $it ) : ?>

      <div class="c-events-includes__item">
        <?php if ( ! empty( $it['icon'] ) ) : ?>
        <div class="c-events-includes__item-icon">
          <i class="<?php echo esc_attr( $it['icon'] ); ?>"></i>
        </div>
        <?php endif; ?>
        <h3 class="c-events-includes__item-title"><?php echo esc_html( $it['title'] ?? '' ); ?></h3>
        <?php if ( ! empty( $it['text'] ) ) : ?>
        <p class="c-events-includes__item-text"><?php echo esc_html( $it['text'] ); ?></p>
        <?php endif; ?>
      </div>

      <?php endforeach; ?>

    </div>
    <?php endif; ?>

  </div>
</section>