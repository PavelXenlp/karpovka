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
            <?php foreach ( $cards as $card_index => $card ) :
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
                $modal_id    = 'spa-program-modal-' . (int) $card_index;
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
                    <a href="#<?php echo esc_attr( $modal_id ); ?>" class="btn btn--gold btn--sm" data-spa-modal="<?php echo esc_attr( $modal_id ); ?>"><?php echo esc_html( $btn_text ); ?></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php foreach ( $cards as $card_index => $card ) :
    $modal_id    = 'spa-program-modal-' . (int) $card_index;
    $modal_image = $card['modal_image'] ?? '';
    $modal_title = $card['modal_title'] ?? ( $card['name'] ?? '' );
    $modal_kicker = $card['modal_kicker'] ?? ( $card['badge'] ?? '' );
    $modal_meta  = $card['modal_meta'] ?? ( $card['duration'] ?? '' );
    $modal_text  = $card['modal_text'] ?? ( $card['desc'] ?? '' );
    $modal_text_2 = $card['modal_text_2'] ?? '';
    $modal_items = is_array( $card['modal_items'] ?? null ) ? $card['modal_items'] : ( $card['items'] ?? [] );
    $modal_link  = $card['link'] ?? '#cta';
?>
<div class="spa-prog-modal" id="<?php echo esc_attr( $modal_id ); ?>" hidden>
    <div class="spa-prog-modal__overlay" data-spa-modal-close></div>
    <div class="spa-prog-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr( $modal_id ); ?>-title">
        <button class="spa-prog-modal__close" type="button" data-spa-modal-close aria-label="Закрыть"><svg class="icon"><use href="#icon-close"></use></svg></button>
        <?php if ( $modal_image ) : ?><img class="spa-prog-modal__photo" src="<?php echo esc_url( $modal_image ); ?>" alt="<?php echo esc_attr( $modal_title ); ?>" loading="lazy"><?php endif; ?>
        <div class="spa-prog-modal__body">
            <?php if ( $modal_kicker ) : ?><p class="spa-prog-modal__badge"><?php echo esc_html( $modal_kicker ); ?></p><?php endif; ?>
            <h3 class="spa-prog-modal__title" id="<?php echo esc_attr( $modal_id ); ?>-title"><?php echo esc_html( $modal_title ); ?></h3>
            <?php if ( $modal_meta ) : ?><p class="spa-prog-modal__meta"><?php echo esc_html( $modal_meta ); ?></p><?php endif; ?>
            <div class="spa-prog-modal__details">
                <?php if ( $modal_text ) : ?><p><?php echo esc_html( $modal_text ); ?></p><?php endif; ?>
                <?php if ( $modal_text_2 ) : ?><p><?php echo esc_html( $modal_text_2 ); ?></p><?php endif; ?>
                <?php if ( $modal_items ) : ?>
                <div class="spa-prog-modal__includes">
                    <h4 class="spa-prog-modal__includes-title">Что входит в стоимость</h4>
                    <?php foreach ( $modal_items as $modal_item ) : $modal_item_text = is_array( $modal_item ) ? ( $modal_item['text'] ?? '' ) : $modal_item; if ( ! $modal_item_text ) continue; ?>
                    <div class="spa-prog-modal__incl"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg><span><?php echo esc_html( $modal_item_text ); ?></span></div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="spa-prog-modal__foot">
                <p class="spa-prog-modal__price offers-modal__price"><?php echo esc_html( $card['price'] ?? '' ); ?><?php if ( ! empty( $card['price_suffix'] ) ) : ?> <small><?php echo esc_html( $card['price_suffix'] ); ?></small><?php endif; ?></p>
                <a class="btn btn--gold" href="<?php echo esc_url( $modal_link ?: '#cta' ); ?>" data-spa-modal-book>Забронировать</a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
