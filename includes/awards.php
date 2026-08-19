<?php
// Template: Награды и премии
 $v = get_post_meta( get_the_ID(), 'awards', true );
 $items = $v['items'] ?? [];
if ( empty( $items ) ) return;

 $eyebrow = $v['eyebrow'] ?? '';
 $title   = $v['title'] ?? '';
?>

<section class="section awards-section" id="awards">
    <div class="container">
        <div class="section-head reveal">
            <?php if ( $eyebrow ) : ?>
                <p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
            <?php endif; ?>
            <?php if ( $title ) : ?>
                <h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2>
            <?php endif; ?>
        </div>

        <div class="awards2 reveal">
            <?php foreach ( $items as $item ) :
                $badge_type  = $item['badge_type'] ?? 'image';
                $logo        = $item['logo'] ?? '';
                $iframe_src  = $item['iframe_src'] ?? '';
                $iframe_w    = absint( $item['iframe_w'] ?? 150 );
                $iframe_h    = absint( $item['iframe_h'] ?? 50 );
                $iframe_title= $item['iframe_title'] ?? '';
                $source      = $item['source'] ?? '';
                $award_title = $item['award_title'] ?? '';
                $desc        = $item['desc'] ?? '';
                $years       = $item['years'] ?? [];
            ?>
            <article class="award-card2">
                <div class="award-card2__badge">
                    <?php if ( $badge_type === 'iframe' && $iframe_src ) : ?>
                        <iframe
                            class="award-card2__yandex"
                            src="<?php echo esc_url( $iframe_src ); ?>"
                            width="<?php echo $iframe_w; ?>"
                            height="<?php echo $iframe_h; ?>"
                            frameborder="0"
                            title="<?php echo esc_attr( $iframe_title ); ?>"
                        ></iframe>
                    <?php elseif ( $logo ) : ?>
                        <img class="award-card2__logo" src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( $award_title ); ?>" />
                    <?php endif; ?>
                </div>
                <div class="award-card2__body">
                    <?php if ( $source ) : ?>
                        <span class="award-card2__source"><?php echo esc_html( $source ); ?></span>
                    <?php endif; ?>
                    <?php if ( $award_title ) : ?>
                        <h3 class="award-card2__title"><?php echo esc_html( $award_title ); ?></h3>
                    <?php endif; ?>
                    <?php if ( $desc ) : ?>
                        <p class="award-card2__desc"><?php echo esc_html( $desc ); ?></p>
                    <?php endif; ?>
                </div>
                <?php if ( ! empty( $years ) ) : ?>
                <div class="award-card2__years">
                    <?php foreach ( $years as $y ) : ?>
                        <span><?php echo esc_html( $y ); ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>