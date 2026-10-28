<?php
 $v = get_post_meta( get_the_ID(), 'hero_main', true );
if ( empty( $v ) ) return;

 $bg_img        = esc_url( $v['bg_img'] ?? '' );
 $bg_video      = esc_url( $v['bg_video'] ?? '' );
 $eyebrow       = esc_html( $v['eyebrow'] ?? '' );
 $slides        = $v['slides'] ?? [];
 $lead          = esc_html( $v['lead'] ?? '' );
 $buttons       = $v['buttons'] ?? [];
 $socials       = $v['socials'] ?? [];
 $bk_title      = esc_html( $v['bk_title'] ?? '' );
 $bk_checkin_l  = esc_html( $v['bk_checkin_l'] ?? 'Заезд' );
 $bk_checkout_l = esc_html( $v['bk_checkout_l'] ?? 'Выезд' );
 $bk_guests_l   = esc_html( $v['bk_guests_l'] ?? 'Гости' );
 $bk_submit     = esc_html( $v['bk_submit'] ?? 'Показать' );
// Modal
 $m_badge       = esc_html( $v['m_badge'] ?? '' );
 $m_title       = esc_html( $v['m_title'] ?? '' );
 $m_subtitle    = esc_html( $v['m_subtitle'] ?? '' );
 $m_name_l      = esc_html( $v['m_name_l'] ?? 'Имя *' );
 $m_name_ph     = esc_attr( $v['m_name_ph'] ?? '' );
 $m_phone_l     = esc_html( $v['m_phone_l'] ?? 'Телефон *' );
 $m_phone_ph    = esc_attr( $v['m_phone_ph'] ?? '' );
 $m_email_l     = esc_html( $v['m_email_l'] ?? 'E-mail' );
 $m_email_ph    = esc_attr( $v['m_email_ph'] ?? '' );
 $m_date_l      = esc_html( $v['m_date_l'] ?? 'Желаемые даты' );
 $m_date_ph     = esc_attr( $v['m_date_ph'] ?? '' );
 $m_guests_l    = esc_html( $v['m_guests_l'] ?? 'Количество гостей' );
 $m_guests_ph   = esc_attr( $v['m_guests_ph'] ?? '' );
 $m_msg_l       = esc_html( $v['m_msg_l'] ?? 'Комментарий' );
 $m_msg_ph      = esc_attr( $v['m_msg_ph'] ?? '' );
 $m_req_note    = esc_html( $v['m_req_note'] ?? '' );
 $m_submit      = esc_html( $v['m_submit'] ?? '' );
 $m_consent     = $v['m_consent'] ?? '';
 $m_consent_lnk = esc_html( $v['m_consent_lnk'] ?? '' );
 $m_consent_url = esc_url( $v['m_consent_url'] ?? '#' );
 $m_ok_title    = esc_html( $v['m_ok_title'] ?? '' );
 $m_ok_text     = esc_html( $v['m_ok_text'] ?? '' );

?>

<section class="hero">
    <div class="hero__bg">
        <?php if ( $bg_video ) : ?>
            <video autoplay muted loop playsinline preload="metadata"<?php echo $bg_img ? ' poster="' . esc_attr( $bg_img ) . '"' : ''; ?> aria-hidden="true">
                <source src="<?php echo $bg_video; ?>">
            </video>
        <?php elseif ( $bg_img ) : ?>
            <img src="<?php echo $bg_img; ?>" alt="" />
        <?php endif; ?>
        <div class="hero__overlay"></div>
    </div>

    <div class="container hero__content">
        <div class="hero__text">
            <?php if ( $eyebrow ) : ?>
                <p class="eyebrow eyebrow--wide"><?php echo $eyebrow; ?></p>
            <?php endif; ?>

            <?php if ( ! empty( $slides ) ) : ?>
                <div class="hero__title" id="heroTitle">
                    <?php foreach ( $slides as $si => $slide ) :
                        $class = 'hero__title-slide';
                        if ( $si === 0 ) $class .= ' is-active';
                        $hidden = ( $si > 0 ) ? ' aria-hidden="true"' : '';
                    ?>
                        <span class="<?php echo $class; ?>"<?php echo $hidden; ?>>
                            <?php echo wp_kses_post( $slide['text'] ?? '' ); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ( $lead ) : ?>
                <p class="section-sub hero__lead"><?php echo $lead; ?></p>
            <?php endif; ?>

            <?php if ( ! empty( $buttons ) ) : ?>
                <div class="hero__actions">
                    <?php foreach ( $buttons as $b ) :
                        $cls = ( $b['style'] ?? '' ) === 'ghost' ? 'btn btn--ghost' : 'btn btn--gold';
                        $link = (string) ( $b['link'] ?? '#' );
                        $is_booking_modal = in_array( $link, [ '#booking', '#booking-modal', 'modal:booking' ], true );
                    ?>
                        <?php if ( $is_booking_modal ) : ?>
                            <button type="button" class="<?php echo esc_attr( $cls . ' js-open-modal-booking' ); ?>"><?php echo esc_html( $b['text'] ?? '' ); ?></button>
                        <?php else : ?>
                            <a href="<?php echo esc_url( $link ?: '#' ); ?>" class="<?php echo esc_attr( $cls ); ?>"><?php echo esc_html( $b['text'] ?? '' ); ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $socials ) ) : ?>
                <div class="socials hero__socials">
                    <?php foreach ( $socials as $sc ) :
                        $href  = esc_url( $sc['href'] ?? '#' );
                        $aria  = esc_attr( $sc['aria'] ?? '' );
                        $extra = esc_attr( $sc['css_class'] ?? '' );
                        $s_cls = 'social' . ( $extra ? ' ' . $extra : '' );
                        $svg   = $sc['svg'] ?? '';
                    ?>
                        <a class="<?php echo $s_cls; ?>" href="<?php echo $href; ?>" aria-label="<?php echo $aria; ?>"<?php echo ( strpos( $href, 'mailto:' ) === 0 ) ? ' target="_blank" rel="nofollow"' : ' target="_blank" rel="noopener"'; ?>>
                            <?php if ( $svg ) echo $svg; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ( $bk_title ) : ?>
            <form class="booking" id="booking" data-booking-form>
                <div class="booking__title"><?php echo $bk_title; ?></div>
                <div class="booking__field">
                    <label for="booking-checkin"><?php echo $bk_checkin_l; ?></label>
                    <input id="booking-checkin" name="checkin" type="date" />
                </div>
                <div class="booking__field">
                    <label for="booking-checkout"><?php echo $bk_checkout_l; ?></label>
                    <input id="booking-checkout" name="checkout" type="date" />
                </div>
                <div class="booking__field booking__field--guests" data-booking-guests>
                    <label id="booking-guests-label"><?php echo $bk_guests_l; ?></label>
                    <button class="booking__guests-trigger" type="button" aria-expanded="false" aria-controls="booking-guests-panel" aria-labelledby="booking-guests-label booking-guests-summary" data-booking-guests-toggle>
                        <span id="booking-guests-summary" data-booking-guests-summary>2 взрослых, 1 номер</span>
                        <span class="booking__guests-chevron" aria-hidden="true"></span>
                    </button>
                    <div class="booking-guests" id="booking-guests-panel" hidden data-booking-guests-panel>
                        <div class="booking-guests__head">
                            <strong>Количество гостей</strong>
                            <button type="button" class="booking-guests__close" aria-label="Закрыть выбор гостей" data-booking-guests-close>×</button>
                        </div>
                        <div class="booking-guests__rooms" data-booking-rooms></div>
                        <div class="booking-guests__footer">
                            <button type="button" class="booking-guests__add" data-booking-room-add>Добавить номер</button>
                            <button type="button" class="booking-guests__done" data-booking-guests-done>Готово</button>
                        </div>
                    </div>
                    <input type="hidden" name="guests" value="2 взрослых, 1 номер" data-booking-guests-value>
                    <input type="hidden" name="rooms_count" value="1" data-booking-rooms-count>
                </div>
                <button class="btn btn--gold" type="submit"><?php echo $bk_submit; ?></button>
            </form>
        <?php endif; ?>
    </div>
</section>

<?php if ( $m_title ) : ?>
<div class="site-modal" id="site-modal-booking" hidden>
    <div class="site-modal__overlay" data-site-modal-close></div>
    <div class="site-modal__dialog">
        <button class="site-modal__close" data-site-modal-close aria-label="Закрыть">&#10005;</button>
        <div class="site-modal__body">
            <div id="booking-form-wrap">
                <?php if ( $m_badge ) : ?>
                    <p class="site-modal__badge"><?php echo $m_badge; ?></p>
                <?php endif; ?>
                <h3 class="site-modal__title"><?php echo $m_title; ?></h3>
                <?php if ( $m_subtitle ) : ?>
                    <p class="site-modal__subtitle"><?php echo $m_subtitle; ?></p>
                <?php endif; ?>
                <form class="site-modal__form" id="site-booking-form" novalidate>
                    <div class="site-modal__row">
                        <div class="site-modal__field">
                            <label class="site-modal__label" for="bk-name"><?php echo $m_name_l; ?></label>
                            <input class="site-modal__input" type="text" id="bk-name" name="name" placeholder="<?php echo $m_name_ph; ?>" required>
                        </div>
                        <div class="site-modal__field">
                            <label class="site-modal__label" for="bk-phone"><?php echo $m_phone_l; ?></label>
                            <input class="site-modal__input" type="tel" id="bk-phone" name="phone" placeholder="<?php echo $m_phone_ph; ?>" required>
                        </div>
                    </div>
                    <div class="site-modal__field">
                        <label class="site-modal__label" for="bk-email"><?php echo $m_email_l; ?></label>
                        <input class="site-modal__input" type="email" id="bk-email" name="email" placeholder="<?php echo $m_email_ph; ?>">
                    </div>
                    <div class="site-modal__row">
                        <div class="site-modal__field">
                            <label class="site-modal__label" for="bk-date"><?php echo $m_date_l; ?></label>
                            <input class="site-modal__input" type="text" id="bk-date" name="date" placeholder="<?php echo $m_date_ph; ?>">
                        </div>
                        <div class="site-modal__field">
                            <label class="site-modal__label" for="bk-guests"><?php echo $m_guests_l; ?></label>
                            <input class="site-modal__input" type="text" id="bk-guests" name="guests" placeholder="<?php echo $m_guests_ph; ?>">
                        </div>
                    </div>
                    <div class="site-modal__field">
                        <label class="site-modal__label" for="bk-msg"><?php echo $m_msg_l; ?></label>
                        <textarea class="site-modal__textarea" id="bk-msg" name="message" placeholder="<?php echo $m_msg_ph; ?>"></textarea>
                    </div>
                    <?php if ( $m_req_note ) : ?>
                        <p class="site-modal__note"><?php echo $m_req_note; ?></p>
                    <?php endif; ?>
                    <div class="site-modal__submit">
                        <button type="submit" class="btn btn--gold"><?php echo $m_submit; ?></button>
                    </div>
                    <?php if ( $m_consent || $m_consent_lnk ) : ?>
                        <p class="site-modal__consent"><?php echo $m_consent; ?> <a href="<?php echo $m_consent_url; ?>"><?php echo $m_consent_lnk; ?></a></p>
                    <?php endif; ?>
                </form>
            </div>
            <div class="site-modal__success" id="booking-success">
                <div class="site-modal__success-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <?php if ( $m_ok_title ) : ?>
                    <h3 class="site-modal__success-title"><?php echo $m_ok_title; ?></h3>
                <?php endif; ?>
                <?php if ( $m_ok_text ) : ?>
                    <p class="site-modal__success-text"><?php echo $m_ok_text; ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
