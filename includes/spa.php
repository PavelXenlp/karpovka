<?php
$v = get_post_meta( get_the_ID(), 'spa_interactive', true );

if ( empty( $v ) || empty( $v['slides'] ) ) return;

$eyebrow    = esc_html( $v['eyebrow'] ?? '' );
$section_id = esc_attr( ! empty( $v['section_id'] ) ? $v['section_id'] : 'spa' );
$slides     = array();
foreach ( $v['slides'] as $slide ) {
    if ( ! is_array( $slide ) || empty( $slide['tab'] ) ) {
        continue;
    }
    $slides[] = array(
        'tab'     => (string) $slide['tab'],
        'title'   => (string) ( $slide['title'] ?? '' ),
        'desc'    => (string) ( $slide['desc'] ?? '' ),
        'details' => wp_kses_post( $slide['details'] ?? '' ),
        'img'     => esc_url( $slide['img'] ?? '' ),
    );
}
if ( ! $slides ) return;
?>

<section id="<?php echo $section_id; ?>" class="spa reveal" data-spa>
    <script type="application/json" data-spa-data><?php echo wp_json_encode( $slides, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE ); ?></script>
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
