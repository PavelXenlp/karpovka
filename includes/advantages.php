<?php
// Template: Наши преимущества (marquee)
 $v = get_post_meta( get_the_ID(), 'advantages', true );
if ( empty( $v ) ) return;

 $eyebrow = $v['eyebrow'] ?? '';
 $title   = $v['title'] ?? '';
 $rows    = $v['rows'] ?? [];
if ( empty( $rows ) ) return;
?>

<section class="marquee-section" id="advantages">
    <div class="container">
        <div class="section-head reveal">
            <?php if ( $eyebrow ) : ?>
                <p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
            <?php endif; ?>
            <?php if ( $title ) : ?>
                <h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2>
            <?php endif; ?>
        </div>
    </div>

    <div class="marquee">
        <?php foreach ( $rows as $row ) :
            $speed     = absint( $row['speed'] ?? 55 );
            $direction = $row['direction'] ?? 'left';
            $items     = $row['items'] ?? [];
            if ( empty( $items ) ) continue;
            $dir_class = ( $direction === 'right' ) ? ' marquee__track--right' : '';
        ?>
        <div class="marquee__row">
            <div class="marquee__track<?php echo $dir_class; ?>" data-marquee data-marquee-speed="<?php echo $speed; ?>">
                <div class="marquee__set" data-marquee-set>
                    <?php foreach ( $items as $item ) :
                        $t = $item['text'] ?? '';
                        if ( $t === '' ) continue;
                    ?>
                    <span class="marquee__item"><?php echo esc_html( $t ); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>