<?php
$map_data = karpovka_home_map_data( get_the_ID() );
?>
<section class="map-section" id="map">
    <div class="map" data-map>
        <h2 class="map__title"><?php echo esc_html( $map_data['title'] ); ?> <em><?php echo esc_html( $map_data['title_em'] ); ?></em></h2>
        <div class="map__canvas" data-map-canvas>
            <img class="map__img" src="<?php echo esc_url( $map_data['image'] ); ?>" alt="Карта территории парк-отеля «Карповка»" draggable="false">
        </div>
        <div class="map__markers" data-map-markers>
            <?php foreach ( $map_data['markers'] as $marker ) : ?>
                <button class="map__marker" type="button" data-map-marker
                    data-x="<?php echo esc_attr( $marker['x'] ?? '0.5' ); ?>"
                    data-y="<?php echo esc_attr( $marker['y'] ?? '0.5' ); ?>"
                    data-title="<?php echo esc_attr( $marker['title'] ?? '' ); ?>"
                    data-img="<?php echo esc_url( $marker['image'] ?? '' ); ?>"
                    data-desc="<?php echo esc_attr( $marker['description'] ?? '' ); ?>"
                    data-price="<?php echo esc_attr( $marker['price'] ?? '' ); ?>"
                    aria-label="<?php echo esc_attr( $marker['title'] ?? '' ); ?>">
                    <span class="map__pin"></span><span class="map__marker-label"><?php echo esc_html( $marker['title'] ?? '' ); ?></span>
                </button>
            <?php endforeach; ?>
        </div>
        <div class="map__veil" aria-hidden="true"></div>
        <button class="map__activate" type="button" data-map-activate>Активировать карту</button>
        <button class="map__deactivate" type="button" data-map-deactivate>Отключить карту</button>
    </div>
</section>
<div class="map-modal" data-map-modal hidden>
    <div class="map-modal__overlay" data-map-modal-close></div>
    <div class="map-modal__dialog" role="dialog" aria-modal="true" aria-label="Объект на карте">
        <button class="map-modal__close" type="button" data-map-modal-close aria-label="Закрыть"><svg class="icon"><use href="#icon-close"></use></svg></button>
        <div class="map-modal__media"><img class="map-modal__img" data-map-modal-img src="" alt=""></div>
        <div class="map-modal__body"><h3 class="map-modal__title" data-map-modal-title></h3><p class="map-modal__desc" data-map-modal-desc></p><strong class="map-modal__price" data-map-modal-price></strong></div>
    </div>
</div>
