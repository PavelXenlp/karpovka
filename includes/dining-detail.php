<?php
/**
 * Template: Dining Detail Sections.
 *
 * Uses the shared bath_details meta structure, but renders cafe-specific markup.
 */
$v     = get_post_meta( get_the_ID(), 'bath_details', true ) ?: [];
$items = $v['items'] ?? [];

if ( empty( $items ) || ! is_array( $items ) ) return;

foreach ( $items as $s ) :
    $eyebrow    = $s['eyebrow'] ?? '';
    $title      = $s['title'] ?? '';
    $gold_text  = $s['gold_text'] ?? '';
    $subtitle   = $s['subtitle'] ?? '';
    $section_id = $s['section_id'] ?? '';
    $direction  = $s['direction'] ?? 'normal';
    $text       = $s['text'] ?? '';
    $gallery    = is_array( $s['gallery'] ?? null ) ? $s['gallery'] : [];
    $features   = is_array( $s['features'] ?? null ) ? $s['features'] : [];
    $image      = $gallery[0]['img'] ?? '';
    $image_alt  = $gallery[0]['alt'] ?? $title;

    if ( empty( $title ) && empty( $eyebrow ) ) continue;

    $section_class = 'c-dining-detail';
    if ( 'reversed' === $direction ) $section_class .= ' c-dining-detail--reversed';

    $paragraphs = array_filter( preg_split( '/\n\s*\n/', (string) $text ) );
?>

<section class="<?php echo esc_attr( $section_class ); ?>"<?php if ( $section_id ) echo ' id="' . esc_attr( $section_id ) . '"'; ?>>
  <div class="container">
    <div class="section-head section-head--center reveal">
      <?php if ( $eyebrow ) : ?><p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
      <?php if ( $title || $gold_text ) : ?>
      <h2 class="section-title">
        <?php echo esc_html( $title ); ?>
        <?php if ( $gold_text ) : ?><span class="gold-header"><em><?php echo esc_html( $gold_text ); ?></em></span><?php endif; ?>
      </h2>
      <?php endif; ?>
      <?php if ( $subtitle ) : ?><p class="section-sub"><?php echo esc_html( $subtitle ); ?></p><?php endif; ?>
    </div>

    <div class="c-spa-detail__layout reveal">
      <div class="c-spa-detail__text">
        <?php foreach ( $paragraphs as $paragraph ) : ?>
        <p class="c-spa-detail__lead"><?php echo esc_html( trim( $paragraph ) ); ?></p>
        <?php endforeach; ?>

        <?php if ( $features ) : ?>
        <ul class="c-dining-features">
          <?php foreach ( $features as $feature ) :
              $num  = $feature['num'] ?? '';
              $bold = $feature['bold'] ?? '';
              $desc = $feature['desc'] ?? '';
              if ( ! $bold && ! $desc ) continue;
          ?>
          <li class="c-dining-features__item">
            <?php if ( $num ) : ?><span class="c-dining-features__num"><?php echo esc_html( $num ); ?></span><?php endif; ?>
            <div>
              <?php if ( $bold ) : ?><h3 class="c-dining-features__title"><?php echo esc_html( $bold ); ?></h3><?php endif; ?>
              <?php if ( $desc ) : ?><p class="c-dining-features__desc"><?php echo esc_html( $desc ); ?></p><?php endif; ?>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
      </div>

      <?php if ( $image ) : ?>
      <div class="c-spa-detail__media">
        <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy">
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php endforeach; ?>
