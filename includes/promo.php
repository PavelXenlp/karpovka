<?php
// Template: Промо-панели
 $v = get_post_meta( get_the_ID(), 'promo_panels', true );
 $panels = $v['panels'] ?? [];
if ( empty( $panels ) ) return;
?>

<section class="promo" id="loyalty">
    <?php foreach ( $panels as $panel ) :
        $modifier = $panel['modifier'] ?? 'loyalty';
        $bg       = $panel['bg'] ?? '';
        $bg_pos   = $panel['bg_pos'] ?? '';
        $eyebrow  = $panel['eyebrow'] ?? '';
        $title    = $panel['title'] ?? '';
        $lead     = $panel['lead'] ?? '';
        $btn_text = $panel['btn_text'] ?? '';
        $btn_link = $panel['btn_link'] ?? '#';
        $btn_style= $panel['btn_style'] ?? 'gold';
        $bg_class = $bg_pos === 'top' ? ' promo__bg--top' : '';
    ?>
    <article class="promo__panel promo__panel--<?php echo esc_attr( $modifier ); ?> reveal">
        <?php if ( $bg ) : ?>
        <img class="promo__bg<?php echo $bg_class; ?>" src="<?php echo esc_url( $bg ); ?>" alt="" aria-hidden="true" />
        <?php endif; ?>
        <div class="promo__overlay"></div>
        <div class="promo__content">
            <?php if ( $eyebrow ) : ?>
            <p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
            <?php endif; ?>
            <?php if ( $title ) : ?>
            <h2 class="promo__title"><?php echo wp_kses_post( $title ); ?></h2>
            <?php endif; ?>
            <?php if ( $lead ) : ?>
            <p class="promo__lead"><?php echo esc_html( $lead ); ?></p>
            <?php endif; ?>
            <?php if ( $btn_text ) : ?>
            <a class="btn btn--<?php echo esc_attr( $btn_style ); ?>" href="<?php echo esc_url( $btn_link ); ?>"><?php echo esc_html( $btn_text ); ?></a>
            <?php endif; ?>
        </div>
    </article>
    <?php endforeach; ?>
</section>