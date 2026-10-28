<?php
$services_data = karpovka_home_services_data( get_the_ID() );
?>
<section class="section offers-section" id="services-grid">
    <div class="section-head section-head--row reveal">
        <h2 class="section-title"><?php echo esc_html( $services_data['title'] ); ?> <em><?php echo esc_html( $services_data['title_em'] ); ?></em></h2>
        <div class="offers-nav">
            <button class="offers-nav__btn" type="button" data-offers-prev aria-label="Предыдущие"><svg class="icon"><use href="#icon-chevron-left"></use></svg></button>
            <button class="offers-nav__btn" type="button" data-offers-next aria-label="Следующие"><svg class="icon"><use href="#icon-chevron-right"></use></svg></button>
        </div>
    </div>
    <div class="swiper offers-swiper reveal" data-offers>
        <div class="swiper-wrapper">
            <?php foreach ( $services_data['cards'] as $card ) :
                $title           = (string) ( $card['title'] ?? '' );
                $image           = (string) ( $card['image'] ?? '' );
                $secondary_image = (string) ( $card['secondary_image'] ?? '' );
                $url             = (string) ( $card['url'] ?? '' );
                ?>
                <article class="swiper-slide ocard"<?php echo ! empty( $card['anchor'] ) ? ' id="' . esc_attr( $card['anchor'] ) . '"' : ''; ?><?php echo $secondary_image ? ' data-ocard-slider' : ''; ?>>
                    <div class="ocard__media">
                        <?php if ( $secondary_image ) : ?>
                            <div class="ocard-shots">
                                <img class="ocard-shots__img" src="<?php echo esc_url( $secondary_image ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
                                <img class="ocard-shots__img is-active" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                            </div>
                            <button class="ocard-shots__nav ocard-shots__nav_prev swiper-no-swiping" type="button" data-ocard-prev aria-label="Предыдущее фото"><svg class="icon"><use href="#icon-chevron-left"></use></svg></button>
                            <button class="ocard-shots__nav ocard-shots__nav_next swiper-no-swiping" type="button" data-ocard-next aria-label="Следующее фото"><svg class="icon"><use href="#icon-chevron-right"></use></svg></button>
                            <div class="ocard-shots__dots swiper-no-swiping" data-ocard-dots></div>
                        <?php elseif ( $image ) : ?>
                            <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
                        <?php endif; ?>
                    </div>
                    <div class="ocard__body">
                        <h3 class="ocard__title"><?php echo esc_html( $title ); ?></h3>
                        <div class="ocard__foot">
                            <p class="ocard__desc"><?php echo esc_html( $card['description'] ?? '' ); ?></p>
                            <?php if ( $url ) : ?>
                                <a class="ocard__arrow" href="<?php echo esc_url( $url ); ?>" aria-label="Подробнее — <?php echo esc_attr( $title ); ?>"><svg class="icon"><use href="#icon-arrow-ur"></use></svg></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
