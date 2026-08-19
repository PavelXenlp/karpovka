<?php
/**
 * Template: CTA Review Section + Callback & Booking Modals
 */
 $v = get_post_meta( get_the_ID(), 'cta_review', true ) ?: [];

 $eyebrow    = $v['eyebrow']    ?? '';
 $title      = $v['title']      ?? '';
 $gold_text  = $v['gold_text']  ?? '';
 $text       = $v['text']       ?? '';
 $phone      = $v['phone']      ?? '';
 $btn_text   = $v['btn_text']   ?? '';
 $btn2_text  = $v['btn2_text']  ?? '';
 $section_id = $v['section_id'] ?? 'cta';

if ( empty( $eyebrow ) && empty( $title ) && empty( $text ) ) return;
?>

<!-- ═══════════ CTA ═══════════ -->
<section class="c-cta-review" id="<?php echo esc_attr( $section_id ); ?>">
  <div class="container">
    <div class="c-cta-review__inner reveal">

      <?php if ( $eyebrow ) : ?>
      <p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
      <?php endif; ?>

      <?php if ( $title || $gold_text ) : ?>
      <h2 class="section-title">
        <?php echo esc_html( $title ); ?>
        <?php if ( $gold_text ) : ?>
        <span class="gold-header"><em><?php echo esc_html( $gold_text ); ?></em></span>
        <?php endif; ?>
      </h2>
      <?php endif; ?>

      <?php if ( $text ) : ?>
      <p class="c-cta-review__text"><?php echo esc_html( $text ); ?></p>
      <?php endif; ?>

      <?php if ( $phone || $btn2_text ) : ?>
      <div class="c-cta-review__actions">
        <?php if ( $phone && $btn_text ) : ?>
        <a href="<?php echo esc_attr( $phone ); ?>" class="btn btn--gold"><?php echo esc_html( $btn_text ); ?></a>
        <?php endif; ?>
        <?php if ( $btn2_text ) : ?>
        <button type="button" class="btn btn--ghost js-open-modal-booking"><?php echo esc_html( $btn2_text ); ?></button>
        <?php endif; ?>
      </div>
      <?php endif; ?>

    </div>
  </div>
</section>

<!-- ═══════════ ПОПАП «ОБРАТНЫЙ ЗВОНОК» ═══════════ -->
<div class="site-modal" id="site-modal-callback" hidden>
  <div class="site-modal__overlay" data-site-modal-close></div>
  <div class="site-modal__dialog">
    <button class="site-modal__close" data-site-modal-close aria-label="Закрыть">&#10005;</button>
    <div class="site-modal__body">
      <div id="callback-form-wrap">
        <p class="site-modal__badge">Обратный звонок</p>
        <h3 class="site-modal__title">Заказать звонок</h3>
        <p class="site-modal__subtitle">Оставьте номер и мы перезвоним вам в течение 15 минут в рабочее время</p>
        <form class="site-modal__form" id="site-callback-form" novalidate>
          <div class="site-modal__row">
            <div class="site-modal__field">
              <label class="site-modal__label" for="cb-name">Имя *</label>
              <input class="site-modal__input" type="text" id="cb-name" name="name" placeholder="Ваше имя" required>
            </div>
            <div class="site-modal__field">
              <label class="site-modal__label" for="cb-phone">Телефон *</label>
              <input class="site-modal__input" type="tel" id="cb-phone" name="phone" placeholder="+7 (___) ___-__-__" required>
            </div>
          </div>
          <div class="site-modal__field">
            <label class="site-modal__label" for="cb-time">Удобное время для звонка</label>
            <input class="site-modal__input" type="text" id="cb-time" name="time" placeholder="Например: с 10:00 до 18:00">
          </div>
          <p class="site-modal__note">* Поля, отмеченные звёздочкой, обязательны для заполнения</p>
          <div class="site-modal__submit">
            <button type="submit" class="btn btn--gold">Перезвоните мне</button>
          </div>
          <p class="site-modal__consent">
            Нажимая кнопку, вы соглашаетесь с <a href="#">политикой конфиденциальности</a>
          </p>
        </form>
      </div>
      <div class="site-modal__success" id="callback-success">
        <div class="site-modal__success-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <h3 class="site-modal__success-title">Заявка принята</h3>
        <p class="site-modal__success-text">Мы перезвоним вам в течение 15 минут в указанное время</p>
      </div>
    </div>
  </div>
</div>

<!-- ═══════════ ПОПАП «ОСТАВИТЬ ЗАЯВКУ» ═══════════ -->
<div class="site-modal" id="site-modal-booking" hidden>
  <div class="site-modal__overlay" data-site-modal-close></div>
  <div class="site-modal__dialog">
    <button class="site-modal__close" data-site-modal-close aria-label="Закрыть">&#10005;</button>
    <div class="site-modal__body">
      <div id="booking-form-wrap">
        <p class="site-modal__badge">Заявка</p>
        <h3 class="site-modal__title">Оставить заявку</h3>
        <p class="site-modal__subtitle">Заполните форму и мы свяжемся с вами в ближайшее время для подбора оптимального варианта</p>
        <form class="site-modal__form" id="site-booking-form" novalidate>
          <div class="site-modal__row">
            <div class="site-modal__field">
              <label class="site-modal__label" for="bk-name">Имя *</label>
              <input class="site-modal__input" type="text" id="bk-name" name="name" placeholder="Ваше имя" required>
            </div>
            <div class="site-modal__field">
              <label class="site-modal__label" for="bk-phone">Телефон *</label>
              <input class="site-modal__input" type="tel" id="bk-phone" name="phone" placeholder="+7 (___) ___-__-__" required>
            </div>
          </div>
          <div class="site-modal__field">
            <label class="site-modal__label" for="bk-email">E-mail</label>
            <input class="site-modal__input" type="email" id="bk-email" name="email" placeholder="email@example.com">
          </div>
          <div class="site-modal__field">
            <label class="site-modal__label" for="bk-date">Желаемые даты</label>
            <input class="site-modal__input" type="text" id="bk-date" name="date" placeholder="Например: 15–18 августа">
          </div>
          <div class="site-modal__field">
            <label class="site-modal__label" for="bk-guests">Количество гостей</label>
            <input class="site-modal__input" type="text" id="bk-guests" name="guests" placeholder="Например: 2 взрослых, 1 ребёнок">
          </div>
          <div class="site-modal__field">
            <label class="site-modal__label" for="bk-msg">Комментарий</label>
            <textarea class="site-modal__textarea" id="bk-msg" name="message" placeholder="Пожелания, вопросы, предпочтения..."></textarea>
          </div>
          <p class="site-modal__note">* Поля, отмеченные звёздочкой, обязательны для заполнения</p>
          <div class="site-modal__submit">
            <button type="submit" class="btn btn--gold" style="width:100%;">Отправить заявку</button>
          </div>
          <p class="site-modal__consent">
            Нажимая кнопку, вы соглашаетесь с <a href="#">политикой конфиденциальности</a>
          </p>
        </form>
      </div>
      <div class="site-modal__success" id="booking-success">
        <div class="site-modal__success-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <h3 class="site-modal__success-title">Заявка отправлена</h3>
        <p class="site-modal__success-text">Мы свяжемся с вами в ближайшее время для подтверждения бронирования</p>
      </div>
    </div>
  </div>
</div>