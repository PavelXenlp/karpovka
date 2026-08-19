<?php
/**
 * Template: Directions Section
 */
 $v = get_post_meta( get_the_ID(), 'directions_section', true ) ?: [];

 $eyebrow    = $v['eyebrow']    ?? '';
 $title      = $v['title']      ?? '';
 $subtitle   = $v['subtitle']   ?? '';
 $section_id = $v['section_id'] ?? 'directions';
 $side_image = $v['side_image'] ?? '';
 $primary    = $v['primary']    ?? [];
 $steps      = $v['steps']      ?? [];

if ( empty( $eyebrow ) && empty( $title ) && empty( $primary['title'] ?? '' ) ) return;
?>

<section class="c-directions" id="<?php echo esc_attr( $section_id ); ?>">
  <div class="container">

    <div class="section-head section-head--center reveal">
      <?php if ( $eyebrow ) : ?>
      <p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
      <?php endif; ?>

      <?php if ( $title ) : ?>
      <h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
      <?php endif; ?>

      <?php if ( $subtitle ) : ?>
      <p class="section-sub"><?php echo esc_html( $subtitle ); ?></p>
      <?php endif; ?>
    </div>

  </div>

  <div class="c-dir-layout">

    <!-- Левая колонка — контент -->
    <div class="c-dir-layout__content">
      <div class="c-dir-layout__inner">

        <?php if ( ! empty( $primary['title'] ) ) : ?>
        <!-- Step 01 — Primary -->
        <div class="c-dir-step c-dir-step--primary reveal">
          <div class="c-dir-step__number" aria-hidden="true">01</div>
          <div class="c-dir-step__body">
            <div class="c-dir-step__header">
              <?php if ( ! empty( $primary['icon'] ) ) : ?>
              <div class="c-dir-step__icon">
                <svg><use href="<?php echo esc_attr( $primary['icon'] ); ?>"></use></svg>
              </div>
              <?php endif; ?>
              <div>
                <h3><?php echo esc_html( $primary['title'] ); ?></h3>
                <?php if ( ! empty( $primary['subtitle'] ) ) : ?>
                <p><?php echo esc_html( $primary['subtitle'] ); ?></p>
                <?php endif; ?>
              </div>
            </div>
            <?php if ( ! empty( $primary['lead'] ) ) : ?>
            <p class="c-dir-step__lead"><?php echo wp_kses_post( $primary['lead'] ); ?></p>
            <?php endif; ?>
            <?php if ( ! empty( $primary['list'] ) ) : ?>
            <ol class="c-dir-step__list">
              <?php foreach ( $primary['list'] as $li ) : ?>
              <li><span><?php echo wp_kses_post( $li['text'] ?? '' ); ?></span></li>
              <?php endforeach; ?>
            </ol>
            <?php endif; ?>
            <?php if ( ! empty( $primary['tag'] ) ) : ?>
            <div class="c-dir-step__tag"><?php echo esc_html( $primary['tag'] ); ?></div>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <?php if ( ! empty( $steps ) ) : ?>
        <div class="c-dir-row reveal">

          <?php foreach ( $steps as $s ) :
              if ( empty( $s['title'] ) ) continue;
          ?>

          <div class="c-dir-step">
            <div class="c-dir-step__number" aria-hidden="true"><?php echo esc_html( $s['num'] ?? '' ); ?></div>
            <div class="c-dir-step__body">
              <div class="c-dir-step__header">
                <?php if ( ! empty( $s['icon'] ) ) : ?>
                <div class="c-dir-step__icon">
                  <svg><use href="<?php echo esc_attr( $s['icon'] ); ?>"></use></svg>
                </div>
                <?php endif; ?>
                <div>
                  <h3><?php echo esc_html( $s['title'] ); ?></h3>
                  <?php if ( ! empty( $s['subtitle'] ) ) : ?>
                  <p><?php echo esc_html( $s['subtitle'] ); ?></p>
                  <?php endif; ?>
                </div>
              </div>
              <?php if ( ! empty( $s['list'] ) ) : ?>
              <ol class="c-dir-step__list">
                <?php foreach ( $s['list'] as $li ) : ?>
                <li><span><?php echo wp_kses_post( $li['text'] ?? '' ); ?></span></li>
                <?php endforeach; ?>
              </ol>
              <?php endif; ?>
            </div>
          </div>

          <?php endforeach; ?>

        </div>
        <?php endif; ?>

      </div>
    </div>

    <!-- Правая колонка — фото -->
    <?php if ( $side_image ) : ?>
    <div class="c-dir-layout__media">
      <img class="c-dir-layout__img" src="<?php echo esc_url( $side_image ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" />
      <div class="c-dir-layout__media-fade"></div>
    </div>
    <?php endif; ?>

  </div>
</section>