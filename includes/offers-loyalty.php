<?php
 $v = get_post_meta( get_the_ID(), 'offers_loyalty', true );

if ( empty( $v ) ) return;

 $eyebrow    = esc_html( $v['eyebrow'] ?? '' );
 $title      = $v['title'] ?? '';
 $subtitle   = esc_html( $v['subtitle'] ?? '' );
 $section_id = esc_attr( $v['section_id'] ?? 'loyalty' );
 $lead       = esc_html( $v['lead'] ?? '' );
 $cash_num   = esc_html( $v['cash_num'] ?? '' );
 $cash_bold  = esc_html( $v['cash_bold'] ?? '' );
 $cash_small = esc_html( $v['cash_small'] ?? '' );
 $items      = $v['items'] ?? [];
?>

<section class="c-offers-loyalty" id="<?php echo $section_id; ?>">
    <div class="container">
        <div class="section-head section-head--center reveal">
            <?php if ( $eyebrow ) : ?>
                <p class="eyebrow"><?php echo $eyebrow; ?></p>
            <?php endif; ?>
            <?php if ( $title ) : ?>
                <h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2>
            <?php endif; ?>
            <?php if ( $subtitle ) : ?>
                <p class="section-sub"><?php echo $subtitle; ?></p>
            <?php endif; ?>
        </div>

        <div class="c-offers-loyalty__inner reveal">
            <div class="c-offers-loyalty__aside">
                <?php if ( $lead ) : ?>
                    <p class="c-offers-loyalty__lead"><?php echo $lead; ?></p>
                <?php endif; ?>

                <?php if ( $cash_num || $cash_bold ) : ?>
                    <div class="c-offers-loyalty__count">
                        <?php if ( $cash_num ) : ?>
                            <span><?php echo $cash_num; ?></span>
                        <?php endif; ?>
                        <div>
                            <?php if ( $cash_bold ) : ?>
                                <strong><?php echo $cash_bold; ?></strong>
                            <?php endif; ?>
                            <?php if ( $cash_small ) : ?>
                                <small><?php echo $cash_small; ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="c-offers-loyalty__list">
                <?php foreach ( $items as $item ) :
                    $num   = esc_html( $item['num'] ?? '' );
                    $i_ttl = esc_html( $item['title'] ?? '' );
                    $i_dsc = esc_html( $item['desc'] ?? '' );
                ?>
                    <div class="c-offers-loyalty__item">
                        <?php if ( $num ) : ?>
                            <span class="c-offers-loyalty__num"><?php echo $num; ?></span>
                        <?php endif; ?>
                        <div class="c-offers-loyalty__item-text">
                            <?php if ( $i_ttl ) : ?>
                                <h4 class="c-offers-loyalty__item-title"><?php echo $i_ttl; ?></h4>
                            <?php endif; ?>
                            <?php if ( $i_dsc ) : ?>
                                <p><?php echo $i_dsc; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>