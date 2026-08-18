<?php
/**
 * Template: Bath Detail Sections (repeater — 1, 2, 3+ секций)
 * Каждая секция: карусель + текст + фичи
 * direction: normal = карусель слева, reversed = карусель справа
 */
 $v     = get_post_meta( get_the_ID(), 'bath_details', true ) ?: [];
 $items = $v['items'] ?? [];

if ( empty( $items ) ) return;

foreach ( $items as $s ) :

    $eyebrow    = $s['eyebrow']    ?? '';
    $title      = $s['title']      ?? '';
    $gold_text  = $s['gold_text']  ?? '';
    $subtitle   = $s['subtitle']   ?? '';
    $section_id = $s['section_id'] ?? '';
    $direction  = $s['direction']  ?? 'normal';
    $text       = $s['text']       ?? '';
    $gallery    = $s['gallery']    ?? [];
    $features   = $s['features']   ?? '';

    if ( empty( $title ) && empty( $eyebrow ) ) continue;

    $section_class = 'c-spa-detail';
    if ( $direction === 'reversed' ) $section_class .= ' c-spa-detail--reversed';

    $paragraphs = array_filter( preg_split( '/\n\s*\n/', $text ) );
?>

<section class="<?php echo esc_attr( $section_class ); ?>"<?php if ( $section_id ) echo ' id="' . esc_attr( $section_id ) . '"'; ?>>
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

    <div class="c-spa-detail__layout reveal">

      <div class="c-spa-detail__media">
        <?php if ( ! empty( $gallery ) ) : ?>
        <div class="c-bath-detail__gallery" data-bath-gallery="<?php echo esc_attr( $section_id ); ?>">
          <div class="c-bath-detail__gallery-track">
            <?php foreach ( $gallery as $g ) : ?>
            <div class="c-bath-detail__gallery-slide">
              <img src="<?php echo esc_url( $g['img'] ?? '' ); ?>" alt="<?php echo esc_attr( $g['alt'] ?? '' ); ?>" loading="lazy">
            </div>
            <?php endforeach; ?>
          </div>
          <button class="c-bath-detail__gallery-prev" aria-label="Предыдущее фото"><svg class="icon"><use href="#icon-arrow-left"></use></svg></button>
          <button class="c-bath-detail__gallery-next" aria-label="Следующее фото"><svg class="icon"><use href="#icon-arrow-right"></use></svg></button>
        </div>
        <?php endif; ?>
      </div>

      <div class="c-spa-detail__text">
        <?php if ( ! empty( $paragraphs ) ) : foreach ( $paragraphs as $p ) : ?>
        <p><?php echo esc_html( trim( $p ) ); ?></p>
        <?php endforeach; endif; ?>

        <?php if ( ! empty( $features ) ) : ?>
        <div class="c-spa-detail__features">
          <?php foreach ( $features as $f ) :
              $num  = $f['num'] ?? '';
              $bold = $f['bold'] ?? '';
              $desc = $f['desc'] ?? '';
              if ( ! $bold ) continue;
          ?>
          <div class="c-spa-detail__feature">
            <?php if ( $num ) : ?>
            <span class="c-dining-features__num"><?php echo esc_html( $num ); ?></span>
            <?php endif; ?>
            <div class="c-spa-detail__feature-text">
              <strong><?php echo esc_html( $bold ); ?></strong><?php if ( $desc ) : ?> — <?php echo esc_html( $desc ); ?><?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>

<?php endforeach; ?>