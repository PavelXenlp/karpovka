<?php
// Template: SPA-программы
 $v = get_post_meta( get_the_ID(), 'spa_programs', true );
if ( empty( $v ) ) return;

 $eyebrow      = $v['eyebrow'] ?? '';
 $title_before = $v['title_before'] ?? '';
 $title_em     = $v['title_em'] ?? '';
 $subtitle     = $v['subtitle'] ?? '';
 $bg_img       = $v['bg_img'] ?? '';
 $cards        = $v['cards'] ?? [];
if ( empty( $cards ) ) return;
?>

<section class="c-spa-programs" id="programs">
    <?php if ( $bg_img ) : ?>
    <div class="c-spa-programs__bg">
        <img src="<?php echo esc_url( $bg_img ); ?>" alt="" aria-hidden="true">
    </div>
    <?php endif; ?>
    <div class="container">
        <div class="section-head section-head--center reveal">
            <?php if ( $eyebrow ) : ?>
            <p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
            <?php endif; ?>
            <?php if ( $title_before || $title_em ) : ?>
            <h2 class="section-title">
                <?php echo esc_html( $title_before ); ?>
                <?php if ( $title_em ) : ?>
                <span class="gold-header"><em><?php echo esc_html( $title_em ); ?></em></span>
                <?php endif; ?>
            </h2>
            <?php endif; ?>
            <?php if ( $subtitle ) : ?>
            <p class="section-sub"><?php echo esc_html( $subtitle ); ?></p>
            <?php endif; ?>
        </div>

        <div class="c-spa-programs__grid reveal">
            <?php foreach ( $cards as $card ) :
                $modifier    = $card['modifier'] ?? '';
                $mod_class   = $modifier ? ' c-spa-program--' . esc_attr( $modifier ) : '';
                $badge       = $card['badge'] ?? '';
                $name        = $card['name'] ?? '';
                $duration    = $card['duration'] ?? '';
                $desc        = $card['desc'] ?? '';
                $items       = $card['items'] ?? [];
                $price       = $card['price'] ?? '';
                $price_suf   = $card['price_suffix'] ?? '';
                $link        = $card['link'] ?? '#';
                $btn_text    = $card['btn_text'] ?? 'Подробнее';
            ?>
            <div class="c-spa-program<?php echo $mod_class; ?>">
                <?php if ( $badge ) : ?>
                <span class="c-spa-program__badge"><?php echo esc_html( $badge ); ?></span>
                <?php endif; ?>
                <div class="c-spa-program__header">
                    <h3 class="c-spa-program__name"><?php echo esc_html( $name ); ?></h3>
                    <?php if ( $duration ) : ?>
                    <span class="c-spa-program__duration"><i class="fa-regular fa-clock"></i> <?php echo esc_html( $duration ); ?></span>
                    <?php endif; ?>
                </div>
                <?php if ( $desc ) : ?>
                <p class="c-spa-program__desc"><?php echo esc_html( $desc ); ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $items ) ) : ?>
                <ul class="c-spa-program__list">
                    <?php foreach ( $items as $item ) :
                        $t = $item['text'] ?? '';
                        if ( $t === '' ) continue;
                    ?>
                    <li><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> <?php echo esc_html( $t ); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <div class="c-spa-program__footer">
                    <?php if ( $price ) : ?>
                    <div class="c-spa-program__price">
                        <?php echo esc_html( $price ); ?>
                        <?php if ( $price_suf ) : ?><small><?php echo esc_html( $price_suf ); ?></small><?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <a href="<?php echo esc_url( $link ); ?>" class="btn btn--gold btn--sm"><?php echo esc_html( $btn_text ); ?></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>