<?php
 $v = get_post_meta( get_the_ID(), 'platforms_section', true );

if ( empty( $v ) || empty( $v['cards'] ) ) return;

 $eyebrow    = esc_html( $v['eyebrow'] ?? '' );
 $title      = $v['title'] ?? '';
 $section_id = esc_attr( $v['section_id'] ?? 'platforms' );
 $cards      = $v['cards'];
?>

<section class="c-platforms" id="<?php echo $section_id; ?>">
    <div class="container">
        <div class="section-head section-head--center reveal">
            <?php if ( $eyebrow ) : ?>
                <p class="eyebrow"><?php echo $eyebrow; ?></p>
            <?php endif; ?>
            <?php if ( $title ) : ?>
                <h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2>
            <?php endif; ?>
        </div>
        <div class="c-platforms__grid reveal">
            <?php foreach ( $cards as $c ) :
                $name       = esc_html( $c['name'] ?? '' );
                $rating     = esc_html( $c['rating'] ?? '' );
                $count      = esc_html( $c['count'] ?? '' );
                $link       = esc_url( $c['link'] ?? '#' );
                $icon       = esc_attr( $c['icon'] ?? '' );
                $icon_color = esc_attr( $c['icon_color'] ?? '' );
            ?>
                <a href="<?php echo $link; ?>" class="c-platforms__card" target="_blank" rel="noopener">
                    <?php if ( $icon ) : ?>
                        <div class="c-platforms__icon">
                            <i class="<?php echo $icon; ?>"<?php if ( $icon_color ) echo ' style="font-size: 26px; color: ' . $icon_color . ';"'; ?>></i>
                        </div>
                    <?php endif; ?>
                    <div class="c-platforms__info">
                        <span class="c-platforms__name"><?php echo $name; ?></span>
                        <?php if ( $rating ) : ?>
                            <span class="c-platforms__rating"><?php echo $rating; ?> <svg class="c-rating__star" viewBox="0 0 24 24"><use href="#i-star"></use></svg></span>
                        <?php endif; ?>
                    </div>
                    <?php if ( $count ) : ?>
                        <span class="c-platforms__count"><?php echo $count; ?></span>
                    <?php endif; ?>
                    <svg class="c-platforms__arrow" viewBox="0 0 24 24"><use href="#i-external"></use></svg>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>