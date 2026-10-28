<?php
get_header();

$house_id = get_the_ID();
$card = karpovka_get_house_card( $house_id );
$title = get_the_title( $house_id );
$intro = $card['details_intro'] ?: wp_strip_all_tags( get_the_content( null, false, $house_id ) );
$features = is_array( $card['features'] ) ? $card['features'] : [];
$details = is_array( $card['details_items'] ) ? $card['details_items'] : [];
$gallery = array_values( array_unique( array_filter( array_merge( [ $card['img'] ], (array) $card['gallery'] ) ) ) );
$hero_image = $gallery[0] ?? '';
$guest_limit = 0;
if ( preg_match( '/до\s+(\d+)/ui', $card['meta'], $capacity_match ) ) $guest_limit = (int) $capacity_match[1];
$related = get_posts( [
    'post_type'      => 'karpovka_house',
    'post_status'    => 'publish',
    'posts_per_page' => 3,
    'post__not_in'   => [ $house_id ],
    'orderby'        => [ 'menu_order' => 'ASC', 'title' => 'ASC' ],
] );
?>

<main class="house-page">
    <section class="house-hero">
        <div class="house-hero__media">
            <?php if ( $hero_image ) : ?><img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php echo esc_attr( $card['img_alt'] ?: $title ); ?>" fetchpriority="high"><?php endif; ?>
        </div>
        <div class="house-hero__overlay"></div>
        <div class="container house-hero__inner">
            <div class="house-hero__copy">
                <nav class="house-breadcrumbs" aria-label="Хлебные крошки">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Главная</a><span>/</span><a href="<?php echo esc_url( home_url( '/prozhivanie/' ) ); ?>">Проживание</a><span>/</span><span><?php echo esc_html( $title ); ?></span>
                </nav>
                <?php if ( $card['badge'] ) : ?><p class="house-hero__badge"><?php echo esc_html( $card['badge'] ); ?></p><?php endif; ?>
                <h1><?php echo esc_html( $title ); ?></h1>
                <?php if ( $intro ) : ?><p class="house-hero__lead"><?php echo esc_html( wp_trim_words( $intro, 34, '…' ) ); ?></p><?php endif; ?>
                <div class="house-hero__facts">
                    <?php if ( $card['meta'] ) : ?><span><?php echo esc_html( $card['meta'] ); ?></span><?php endif; ?>
                    <?php if ( $card['price'] ) : ?><span class="house-hero__price"><?php echo esc_html( trim( $card['price_prefix'].' '.$card['price'].' '.$card['price_suffix'] ) ); ?></span><?php endif; ?>
                </div>
                <?php if ( count( $gallery ) > 1 ) : ?><button class="house-photo-link" type="button" data-house-open-gallery>Смотреть <?php echo count( $gallery ); ?> фото</button><?php endif; ?>
            </div>

            <form class="house-booking" data-booking-form>
                <input type="hidden" name="house" value="<?php echo esc_attr( $title ); ?>">
                <div class="house-booking__head">
                    <span>Бронирование</span>
                    <strong><?php echo $card['price'] ? esc_html( trim( $card['price_prefix'].' '.$card['price'] ) ) : 'Уточнить стоимость'; ?></strong>
                    <?php if ( $card['price_suffix'] ) : ?><small><?php echo esc_html( $card['price_suffix'] ); ?></small><?php endif; ?>
                </div>
                <div class="house-booking__dates">
                    <div class="house-booking__field"><label for="house-checkin">Заезд</label><input id="house-checkin" name="checkin" type="date" required></div>
                    <div class="house-booking__field"><label for="house-checkout">Выезд</label><input id="house-checkout" name="checkout" type="date" required></div>
                </div>
                <div class="house-booking__field booking__field--guests" data-booking-guests>
                    <label id="house-guests-label">Гости</label>
                    <button class="booking__guests-trigger" type="button" aria-expanded="false" aria-controls="house-guests-panel" aria-labelledby="house-guests-label house-guests-summary" data-booking-guests-toggle>
                        <span id="house-guests-summary" data-booking-guests-summary>2 взрослых, 1 номер</span><span class="booking__guests-chevron" aria-hidden="true"></span>
                    </button>
                    <div class="booking-guests" id="house-guests-panel" hidden data-booking-guests-panel>
                        <div class="booking-guests__head"><strong>Количество гостей</strong><button type="button" class="booking-guests__close" aria-label="Закрыть" data-booking-guests-close>×</button></div>
                        <div class="booking-guests__rooms" data-booking-rooms></div>
                        <div class="booking-guests__footer"><button type="button" class="booking-guests__add" data-booking-room-add>Добавить номер</button><button type="button" class="booking-guests__done" data-booking-guests-done>Готово</button></div>
                    </div>
                    <input type="hidden" name="guests" value="2 взрослых, 1 номер" data-booking-guests-value><input type="hidden" name="rooms_count" value="1" data-booking-rooms-count>
                </div>
                <div class="house-booking__field"><label for="house-phone">Телефон</label><input id="house-phone" name="phone" type="tel" placeholder="+7 (___) ___-__-__" data-phone-mask required></div>
                <?php if ( $guest_limit ) : ?><p class="house-booking__hint">Объект рассчитан максимум на <?php echo (int) $guest_limit; ?> гостей.</p><?php endif; ?>
                <button class="btn btn--gold house-booking__submit" type="submit">Проверить даты</button>
                <p class="house-booking__note">Подтверждение бронирования после звонка администратора.</p>
            </form>
        </div>
    </section>

    <section class="house-overview">
        <div class="container house-overview__grid">
            <div class="house-section-copy">
                <p class="eyebrow">Об объекте</p>
                <h2>Продумано для <em>спокойного отдыха</em></h2>
                <?php if ( $intro ) : ?><p class="house-overview__intro"><?php echo esc_html( $intro ); ?></p><?php endif; ?>
                <?php if ( $features ) : ?><ul class="house-feature-list"><?php foreach ( $features as $feature ) : if ( empty( $feature['text'] ) ) continue; ?><li><?php echo esc_html( $feature['text'] ); ?></li><?php endforeach; ?></ul><?php endif; ?>
            </div>
            <aside class="house-rate">
                <p class="house-rate__label">Стоимость проживания</p>
                <?php if ( $card['price'] ) : ?><p class="house-rate__price"><span><?php echo esc_html( $card['price_prefix'] ); ?></span> <?php echo esc_html( $card['price'] ); ?> <small><?php echo esc_html( $card['price_suffix'] ); ?></small></p><?php endif; ?>
                <?php if ( $card['details_pricenote'] ) : ?><p><?php echo esc_html( $card['details_pricenote'] ); ?></p><?php endif; ?>
                <a class="btn btn--gold" href="#house-booking-bottom">Оставить заявку</a>
            </aside>
        </div>
    </section>

    <?php if ( $details ) : ?>
    <section class="house-equipment">
        <div class="container">
            <div class="house-section-head"><div><p class="eyebrow">Оснащение</p><h2>Всё необходимое <em>уже внутри</em></h2></div><p>Комплектация объекта собрана по данным парк-отеля. Дополнительные принадлежности можно уточнить при бронировании.</p></div>
            <ul class="house-equipment__grid">
                <?php foreach ( $details as $index=>$detail ) : if ( empty( $detail['text'] ) ) continue; ?><li><span><?php echo str_pad( (string)($index+1), 2, '0', STR_PAD_LEFT ); ?></span><p><?php echo wp_kses( $detail['text'], [ 'strong'=>[], 'b'=>[], 'em'=>[], 'br'=>[] ] ); ?></p></li><?php endforeach; ?>
            </ul>
        </div>
    </section>
    <?php endif; ?>

    <?php if ( $gallery ) : ?>
    <section class="house-gallery-section" id="gallery">
        <div class="container">
            <div class="house-section-head"><div><p class="eyebrow">Фотографии</p><h2><?php echo esc_html( $title ); ?> <em>в деталях</em></h2></div><p><?php echo count( $gallery ); ?> фотографий интерьера и территории объекта.</p></div>
            <div class="house-gallery-grid">
                <?php foreach ( array_slice( $gallery, 0, 7 ) as $index => $image ) : ?>
                    <a class="house-gallery-grid__item<?php echo 0 === $index ? ' house-gallery-grid__item--lead' : ''; ?>" href="<?php echo esc_url( $image ); ?>" data-fancybox="house-gallery" data-caption="<?php echo esc_attr( $title . ', фото ' . ( $index + 1 ) ); ?>">
                        <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>, фото <?php echo (int) $index + 1; ?>" loading="lazy">
                        <?php if ( 6 === $index && count( $gallery ) > 7 ) : ?><span>+<?php echo count( $gallery ) - 7; ?> фото</span><?php endif; ?>
                    </a>
                <?php endforeach; ?>
                <?php foreach ( array_slice( $gallery, 7 ) as $hidden_index => $image ) : ?>
                    <a class="house-gallery-grid__hidden" href="<?php echo esc_url( $image ); ?>" data-fancybox="house-gallery" data-caption="<?php echo esc_attr( $title . ', фото ' . ( $hidden_index + 8 ) ); ?>" aria-hidden="true" tabindex="-1"></a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="house-bottom-booking" id="house-booking-bottom">
        <div class="container house-bottom-booking__inner"><div><p class="eyebrow">Подбор и бронирование</p><h2>Уточнить свободные <em>даты</em></h2><p>Позвоните нам или вернитесь к форме бронирования. Администратор подтвердит стоимость и поможет добавить SPA, баню или питание.</p></div><div class="house-bottom-booking__actions"><a class="btn btn--gold" href="#top" data-scroll-house-booking>Выбрать даты</a><a class="btn btn--ghost" href="tel:+79850200203">+7 (985) 020-02-03</a></div></div>
    </section>

    <?php if ( $related ) : ?>
    <section class="house-related"><div class="container"><div class="house-section-head"><div><p class="eyebrow">Другие варианты</p><h2>Сравнить <em>проживание</em></h2></div><a href="<?php echo esc_url(home_url('/prozhivanie/')); ?>">Все варианты</a></div><div class="house-related__grid"><?php foreach($related as $related_post):$related_card=karpovka_get_house_card($related_post->ID);?><a class="house-related-card" href="<?php echo esc_url(get_permalink($related_post)); ?>"><?php if($related_card['img']):?><img src="<?php echo esc_url($related_card['img']); ?>" alt="<?php echo esc_attr(get_the_title($related_post)); ?>" loading="lazy"><?php endif;?><div><span><?php echo esc_html($related_card['badge']); ?></span><h3><?php echo esc_html(get_the_title($related_post)); ?></h3><p><?php echo esc_html($related_card['meta']); ?></p><strong><?php echo esc_html(trim($related_card['price_prefix'].' '.$related_card['price'])); ?></strong></div></a><?php endforeach;?></div></div></section>
    <?php endif; ?>
</main>

<script>
(function(){
    if(window.Fancybox){Fancybox.bind('[data-fancybox="house-gallery"]',{});}
    var openAll=document.querySelector('[data-house-open-gallery]');if(openAll)openAll.addEventListener('click',function(){var first=document.querySelector('[data-fancybox="house-gallery"]');if(first)first.click();});
    var checkin=document.getElementById('house-checkin'),checkout=document.getElementById('house-checkout');if(checkin&&checkout){var today=new Date().toISOString().split('T')[0];checkin.min=today;checkout.min=today;checkin.addEventListener('change',function(){checkout.min=checkin.value||today;if(checkout.value&&checkout.value<=checkin.value)checkout.value='';});}
    document.querySelectorAll('[data-scroll-house-booking]').forEach(function(link){link.addEventListener('click',function(e){e.preventDefault();document.querySelector('.house-booking').scrollIntoView({behavior:'smooth',block:'center'});});});
})();
</script>

<?php get_footer(); wp_footer(); ?>
