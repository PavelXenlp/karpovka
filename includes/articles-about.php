<?php
 $v = get_post_meta( get_the_ID(), 'articles_seo_section', true );

if ( empty( $v ) ) return;

 $eyebrow       = esc_html( $v['eyebrow'] ?? '' );
 $title         = $v['title'] ?? '';
 $section_id    = esc_attr( $v['section_id'] ?? 'about' );
 $bg_img        = esc_url( $v['bg_img'] ?? '' );
 $text_raw      = $v['text'] ?? '';
 $media_img     = esc_url( $v['media_img'] ?? '' );
 $cta_title     = esc_html( $v['cta_title'] ?? '' );
 $cta_text      = esc_html( $v['cta_text'] ?? '' );
 $cta_btn_text  = esc_html( $v['cta_btn_text'] ?? '' );
 $cta_btn_link  = esc_url( $v['cta_btn_link'] ?? '' );
 $cta_btn2_text = esc_html( $v['cta_btn2_text'] ?? '' );
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

if ( empty( $text_raw ) && empty( $title ) ) return;

// Разбиваем текст на абзацы по двойному переносу
 $paragraphs = preg_split( '/\n\s*\n/', $text_raw );
 $paragraphs = array_filter( $paragraphs, function( $p ) { return trim( $p ) !== ''; } );
?>

<section class="c-articles-seo" id="<?php echo $section_id; ?>">
    <?php if ( $bg_img ) : ?>
        <img class="c-articles-seo__bg" src="<?php echo $bg_img; ?>" alt="" aria-hidden="true">
    <?php endif; ?>
    <div class="c-articles-seo__overlay"></div>
    <div class="container">
        <div class="c-articles-seo__layout reveal">
            <div class="c-articles-seo__text">
                <div class="section-head">
                    <?php if ( $eyebrow ) : ?>
                        <p class="eyebrow"><?php echo $eyebrow; ?></p>
                    <?php endif; ?>
                    <?php if ( $title ) : ?>
                        <h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2>
                    <?php endif; ?>
                </div>
                <?php foreach ( $paragraphs as $p ) : ?>
                    <p><?php echo esc_html( trim( $p ) ); ?></p>
                <?php endforeach; ?>
            </div>
            <div class="c-articles-seo__media">
                <?php if ( $media_img ) : ?>
                    <img src="<?php echo $media_img; ?>" alt="" loading="lazy">
                <?php endif; ?>
                <?php if ( $cta_title || $cta_text || $cta_btn_text || $cta_btn2_text ) : ?>
                    <div class="c-articles-seo__cta-box">
                        <?php if ( $cta_title ) : ?>
                            <h3><?php echo $cta_title; ?></h3>
                        <?php endif; ?>
                        <?php if ( $cta_text ) : ?>
                            <p><?php echo $cta_text; ?></p>
                        <?php endif; ?>
                        <div class="c-articles-seo__cta-actions">
                            <?php if ( $cta_btn_text ) : ?>
                                <a href="<?php echo $cta_btn_link ?: '#'; ?>" class="btn btn--gold"><?php echo $cta_btn_text; ?></a>
                            <?php endif; ?>
                            <?php if ( $cta_btn2_text ) : ?>
                                <button type="button" class="btn btn--ghost js-open-modal-booking"><?php echo $cta_btn2_text; ?></button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Попап «Оставить заявку» -->
<?php if ( $m_title ) : ?>
<div class="site-modal" id="site-modal-booking" hidden>
    <div class="site-modal__overlay" data-site-modal-close></div>
    <div class="site-modal__dialog">
        <button class="site-modal__close" data-site-modal-close aria-label="Закрыть">✕</button>
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
                    <div class="site-modal__field">
                        <label class="site-modal__label" for="bk-date"><?php echo $m_date_l; ?></label>
                        <input class="site-modal__input" type="text" id="bk-date" name="date" placeholder="<?php echo $m_date_ph; ?>">
                    </div>
                    <div class="site-modal__field">
                        <label class="site-modal__label" for="bk-guests"><?php echo $m_guests_l; ?></label>
                        <input class="site-modal__input" type="text" id="bk-guests" name="guests" placeholder="<?php echo $m_guests_ph; ?>">
                    </div>
                    <div class="site-modal__field">
                        <label class="site-modal__label" for="bk-msg"><?php echo $m_msg_l; ?></label>
                        <textarea class="site-modal__textarea" id="bk-msg" name="message" placeholder="<?php echo $m_msg_ph; ?>"></textarea>
                    </div>
                    <?php if ( $m_req_note ) : ?>
                        <p class="site-modal__note"><?php echo $m_req_note; ?></p>
                    <?php endif; ?>
                    <div class="site-modal__submit">
                        <button type="submit" class="btn btn--gold" style="width:100%;"><?php echo $m_submit; ?></button>
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