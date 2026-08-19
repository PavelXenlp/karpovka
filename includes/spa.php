<?php
 $v = get_post_meta( get_the_ID(), 'spa_interactive', true );

if ( empty( $v ) || empty( $v['slides'] ) ) return;

 $eyebrow    = esc_html( $v['eyebrow'] ?? '' );
 $section_id = esc_attr( $v['section_id'] ?? 'spa' );
 $slides     = $v['slides'];
?>

<section id="<?php echo $section_id; ?>" class="spa reveal" data-spa>
    <div class="spa__body">
        <div class="spa__text">
            <div class="spa__head">
                <?php if ( $eyebrow ) : ?>
                    <p class="eyebrow"><?php echo $eyebrow; ?></p>
                <?php endif; ?>
            </div>

            <div class="spa__tabs" role="tablist" aria-label="Услуги СПА-центра" data-spa-tabs></div>
            <div class="spa__text-inner" data-spa-textwrap>
                <h2 class="spa__title" data-spa-title></h2>
                <p class="spa__desc" data-spa-desc></p>
                <div data-spa-details></div>
            </div>

            <div class="spa__nav">
                <div class="spa__dots" data-spa-dots></div>
                <div class="spa__arrows" data-spa-arrows>
                    <button class="spa__arrow" type="button" data-spa-prev aria-label="Предыдущий слайд">
                        <svg class="icon"><use href="#icon-arrow-left"></use></svg>
                    </button>
                    <button class="spa__arrow" type="button" data-spa-next aria-label="Следующий слайд">
                        <svg class="icon"><use href="#icon-arrow-right"></use></svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="spa__media">
            <img class="spa__img" data-spa-img src="" alt="" />
            <div class="spa__media-fade"></div>
            <div class="spa__counter" data-spa-counter></div>
        </div>
    </div>
    <div class="spa__strip" data-spa-strip></div>
</section>

<script>
window.wpSpaData = <?php
    $out = [];
    foreach ( $slides as $s ) {
        $out[] = [
            'tab'     => $s['tab'] ?? '',
            'title'   => $s['title'] ?? '',
            'desc'    => $s['desc'] ?? '',
            'details' => $s['details'] ?? '',
            'img'     => $s['img'] ?? '',
        ];
    }
    echo wp_json_encode( $out, JSON_UNESCAPED_UNICODE );
?>;
</script>