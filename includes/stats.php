<?php
// Template: Stories — слайдер форматов отдыха
 $v = get_post_meta( get_the_ID(), 'stories', true );
if ( empty( $v ) ) return;

 $title  = $v['title'] ?? '';
 $slides = $v['slides'] ?? [];
if ( empty( $slides ) ) return;
?>

<section class="section stories-section" id="stories">
    <div class="container">
        <?php if ( $title ) : ?>
        <h2 class="center-title reveal"><?php echo esc_html( $title ); ?></h2>
        <?php endif; ?>
    </div>

    <div class="stories-slider swiper reveal" data-stories>
        <div class="swiper-wrapper">
            <?php foreach ( $slides as $slide ) :
                $img      = $slide['img'] ?? '';
                $img_alt  = $slide['img_alt'] ?? '';
                $st_title = $slide['title'] ?? '';
                $link     = $slide['link'] ?? '#';
                $more     = $slide['more_text'] ?? 'Подробнее';
            ?>
            <article class="story-slide swiper-slide">
                <a class="story-slide__card" href="<?php echo esc_url( $link ); ?>">
                    <?php if ( $img ) : ?>
                    <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>" />
                    <?php endif; ?>
                    <span class="story-slide__title"><?php echo esc_html( $st_title ); ?></span>
                    <span class="story-slide__more"><?php echo esc_html( $more ); ?></span>
                </a>
            </article>
            <?php endforeach; ?>
        </div>

        <button class="stories-nav stories-nav--prev" type="button" data-stories-prev aria-label="Предыдущий слайд">
            <svg class="icon"><use href="#icon-arrow-left"></use></svg>
        </button>
        <button class="stories-nav stories-nav--next" type="button" data-stories-next aria-label="Следующий слайд">
            <svg class="icon"><use href="#icon-arrow-right"></use></svg>
        </button>
    </div>
</section>

