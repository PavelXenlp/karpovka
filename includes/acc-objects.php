<?php
// Template: Дома и номера (размещение)
 $v = get_post_meta( get_the_ID(), 'acc_objects', true );
if ( empty( $v ) ) return;

 $eyebrow = $v['eyebrow'] ?? '';
 $title   = $v['title'] ?? '';
 $filters = $v['filters'] ?? [];
 $mode    = 'selected' === ( $v['selection_mode'] ?? '' ) ? 'selected' : 'all';
 $query   = [
    'post_type'      => 'karpovka_house',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => [ 'menu_order' => 'ASC', 'title' => 'ASC' ],
 ];
 if ( 'selected' === $mode ) {
    $query['post__in'] = array_values( array_filter( array_map( 'intval', (array) ( $v['house_ids'] ?? [] ) ) ) );
    $query['orderby'] = 'post__in';
 }
 $house_posts = 'selected' === $mode && empty( $query['post__in'] ) ? [] : get_posts( $query );
 $cards = array_map( static function ( $house ) {
    return karpovka_get_house_card( $house->ID );
 }, $house_posts );

 // До первого открытия редактора сохраняем вывод старых карточек, затем миграция удалит их из страницы.
 if ( empty( $cards ) && ! empty( $v['cards'] ) && is_array( $v['cards'] ) ) {
    $cards = $v['cards'];
 }
if ( empty( $cards ) ) return;
?>

<section class="section acc-objects" id="objects">
    <div class="section-head reveal">
        <?php if ( $eyebrow ) : ?>
            <p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
        <?php endif; ?>
        <?php if ( $title ) : ?>
            <h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2>
        <?php endif; ?>
    </div>

    <?php if ( ! empty( $filters ) ) : ?>
    <div class="acc-filter reveal" id="filter">
        <?php foreach ( $filters as $fg ) : ?>
        <div class="acc-filter__group" role="group" aria-label="<?php echo esc_attr( $fg['label'] ?? '' ); ?>" data-acc-filter>
            <span class="acc-filter__label"><?php echo esc_html( $fg['label'] ?? '' ); ?></span>
            <div class="acc-filter__options">
                <?php foreach ( $fg['options'] ?? [] as $opt ) :
                    $active = ! empty( $opt['is_active'] ) ? ' is-active' : '';
                    $pressed = ! empty( $opt['is_active'] ) ? 'true' : 'false';
                ?>
                <button class="acc-chip<?php echo $active; ?>" type="button" data-filter="<?php echo esc_attr( $opt['slug'] ?? '' ); ?>" aria-pressed="<?php echo $pressed; ?>"><?php echo esc_html( $opt['text'] ?? '' ); ?></button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="acc-grid" data-acc-grid>
        <?php foreach ( $cards as $card ) :
            $type       = $card['type'] ?? 'eco';
            $audience   = $card['audience'] ?? [];
            $aud_str    = is_array( $audience ) ? esc_attr( implode( ' ', $audience ) ) : '';
            $img        = $card['img'] ?? '';
            $img_alt    = $card['img_alt'] ?? '';
            $badge      = $card['badge'] ?? '';
            $card_title = $card['title'] ?? '';
            $meta       = $card['meta'] ?? '';
            $features   = $card['features'] ?? [];
            $pp         = $card['price_prefix'] ?? '';
            $price      = $card['price'] ?? '';
            $ps         = $card['price_suffix'] ?? '';
            $more_text  = $card['more_text'] ?? 'Подробнее';
            $more_link  = $card['more_link'] ?? '/house';
            $book_text  = $card['book_text'] ?? 'Забронировать';
            $book_link  = $card['book_link'] ?? '#booking';
            $d_intro    = $card['details_intro'] ?? '';
            $d_items    = $card['details_items'] ?? [];
            $d_pn       = $card['details_pricenote'] ?? '';
        ?>
        <article class="acc-card reveal" data-type="<?php echo esc_attr( $type ); ?>" data-audience="<?php echo $aud_str; ?>">
            <div class="acc-card__media">
                <?php if ( $img ) : ?>
                <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>" loading="lazy" />
                <?php endif; ?>
                <?php if ( $badge ) : ?>
                <span class="acc-card__badge"><?php echo esc_html( $badge ); ?></span>
                <?php endif; ?>
            </div>
            <div class="acc-card__body">
                <?php if ( $card_title ) : ?>
                <h3 class="acc-card__title"><?php echo esc_html( $card_title ); ?></h3>
                <?php endif; ?>
                <?php if ( $meta ) : ?>
                <p class="acc-card__meta"><?php echo esc_html( $meta ); ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $features ) ) : ?>
                <ul class="acc-card__features">
                    <?php foreach ( $features as $f ) :
                        $ft = $f['text'] ?? '';
                        if ( $ft === '' ) continue;
                    ?>
                    <li><?php echo esc_html( $ft ); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <div class="acc-card__foot">
                    <?php if ( $price ) : ?>
                    <p class="acc-card__price">
                        <?php if ( $pp ) : ?><span><?php echo esc_html( $pp ); ?></span><?php endif; ?>
                        <?php echo esc_html( $price ); ?>
                        <?php if ( $ps ) : ?><small><?php echo esc_html( $ps ); ?></small><?php endif; ?>
                    </p>
                    <?php endif; ?>
                    <div class="acc-card__actions">
                        <?php if ( $more_link ) : ?>
                        <a class="btn btn--ghost" href="<?php echo esc_url( $more_link ); ?>"><?php echo esc_html( $more_text ); ?></a>
                        <?php else : ?>
                        <button class="btn btn--ghost acc-card__toggle" type="button" data-acc-more><?php echo esc_html( $more_text ); ?></button>
                        <?php endif; ?>
                        <a class="btn btn--gold" href="<?php echo esc_url( $book_link ); ?>"><?php echo esc_html( $book_text ); ?></a>
                    </div>
                </div>

                <?php if ( $d_intro || ! empty( $d_items ) || $d_pn ) : ?>
                <div class="acc-card__details" data-acc-details hidden aria-hidden="true">
                    <?php if ( $d_intro ) : ?>
                    <p><?php echo wp_kses_post( $d_intro ); ?></p>
                    <?php endif; ?>
                    <?php if ( ! empty( $d_items ) ) : ?>
                    <ul>
                        <?php foreach ( $d_items as $d ) :
                            $dt = $d['text'] ?? '';
                            if ( $dt === '' ) continue;
                            $allowed = [ 'strong' => [], 'b' => [], 'em' => [], 'br' => [] ];
                        ?>
                        <li><?php echo wp_kses( $dt, $allowed ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <?php if ( $d_pn ) : ?>
                    <p class="acc-card__pricenote"><?php echo esc_html( $d_pn ); ?></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </article>
        <?php endforeach; ?>
    </div>

    <p class="acc-grid__empty" data-acc-empty hidden>По этому сочетанию фильтров вариантов нет — сбросьте один из них.</p>
</section>

<div class="acc-modal" data-acc-modal hidden>
    <div class="acc-modal__overlay" data-acc-modal-close></div>
    <div class="acc-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="accModalTitle" tabindex="-1">
        <button class="acc-modal__close" type="button" data-acc-modal-close aria-label="Закрыть">×</button>
        <img class="acc-modal__photo" data-acc-modal-photo src="" alt="">
        <div class="acc-modal__body">
            <p class="acc-modal__badge" data-acc-modal-badge></p>
            <h2 class="acc-modal__title" id="accModalTitle" data-acc-modal-title></h2>
            <p class="acc-modal__meta" data-acc-modal-meta></p>
            <div class="acc-modal__details" data-acc-modal-details></div>
            <div class="acc-modal__foot">
                <p class="acc-modal__price" data-acc-modal-price></p>
                <a class="btn btn--gold" href="#booking" data-acc-modal-book>Забронировать</a>
            </div>
        </div>
    </div>
</div>
