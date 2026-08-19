<?php
 $eyebrow   = get_post_meta(get_the_ID(), 'hero_eyebrow', true);
 $title     = get_post_meta(get_the_ID(), 'hero_title', true);
 $gold      = get_post_meta(get_the_ID(), 'hero_gold', true);
 $subtitle  = get_post_meta(get_the_ID(), 'hero_subtitle', true);
 $photo     = get_post_meta(get_the_ID(), 'hero_photo', true);
 $btn1_text = get_post_meta(get_the_ID(), 'hero_btn1_text', true);
 $btn1_link = get_post_meta(get_the_ID(), 'hero_btn1_link', true);
 $btn2_text = get_post_meta(get_the_ID(), 'hero_btn2_text', true);
 $phone     = get_post_meta(get_the_ID(), 'hero_phone', true);
 $email     = get_post_meta(get_the_ID(), 'hero_email', true);
 $vk        = get_post_meta(get_the_ID(), 'hero_vk', true);
 $tg        = get_post_meta(get_the_ID(), 'hero_tg', true);

 $eyebrow   = !empty($eyebrow)   ? $eyebrow   : 'Традиции банной культуры';
 $title     = !empty($title)     ? $title     : 'Банный комплекс';
 $gold      = !empty($gold)      ? $gold      : 'комплекс';
 $subtitle  = !empty($subtitle)  ? $subtitle  : 'Русская баня, финская сауна, турецкий хаммам и купель — четыре зоны для настоящего банного ритуала в сосновом лесу на берегу Карпова пруда';
 $btn1_text = !empty($btn1_text) ? $btn1_text : 'Виды бань';
 $btn1_link = !empty($btn1_link) ? $btn1_link : '#types';
 $btn2_text = !empty($btn2_text) ? $btn2_text : 'Забронировать';
 $phone     = !empty($phone)     ? $phone     : '+79127813933';
 $email     = !empty($email)     ? $email     : 'karpovka59ru@yandex.ru';
 $vk        = !empty($vk)        ? $vk        : 'https://vk.com/baza_otdyha_spa_perm';
 $tg        = !empty($tg)        ? $tg        : 'https://t.me/karpovka59';
?>

<section class="c-hero" id="hero" style="--hero-next: var(--navy-900)">
  <?php if ($photo) : ?>
    <img class="c-hero__photo" src="<?php echo esc_url($photo); ?>" alt="Банный комплекс Карповка" loading="eager">
  <?php endif; ?>
  <div class="c-hero__overlay"></div>
  <div class="container c-hero__content">
    <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
    <h1 class="section-title c-hero__title">
      <?php echo esc_html($title); ?>
      <?php if ($gold) : ?>
        <span class="gold-header"><em><?php echo esc_html($gold); ?></em></span>
      <?php endif; ?>
    </h1>
    <?php if ($subtitle) : ?>
      <p class="section-sub"><?php echo esc_html($subtitle); ?></p>
    <?php endif; ?>
    <div class="c-hero__actions">
      <?php if ($btn1_text && $btn1_link) : ?>
        <a href="<?php echo esc_url($btn1_link); ?>" class="btn btn--gold"><?php echo esc_html($btn1_text); ?></a>
      <?php endif; ?>
      <?php if ($btn2_text) : ?>
        <button type="button" class="btn btn--ghost js-open-modal-booking"><?php echo esc_html($btn2_text); ?></button>
      <?php endif; ?>
    </div>
    <div class="socials c-hero__socials">
      <?php if ($phone) : ?>
      <a class="social" href="tel:<?php echo esc_attr($phone); ?>" aria-label="Телефон">
        <svg class="social__icon" fill="currentColor" viewBox="0 0 100 100"><path fill-rule="evenodd" clip-rule="evenodd" d="M50 100C77.6142 100 100 77.6142 100 50C100 22.3858 77.6142 0 50 0C22.3858 0 0 22.3858 0 50C0 77.6142 22.3858 100 50 100ZM50.0089 29H51.618C56.4915 29.0061 61.1633 30.9461 64.6073 34.3938C68.0512 37.8415 69.9856 42.5151 69.9856 47.3879V48.9968C69.9338 49.5699 69.6689 50.1027 69.2433 50.49C68.8177 50.8772 68.2623 51.0908 67.6868 51.0884H67.5029C66.8966 51.0358 66.3359 50.745 65.9437 50.2796C65.5516 49.8143 65.36 49.2124 65.4109 48.6061V47.3879C65.4109 43.7303 63.9578 40.2225 61.3711 37.6362C58.7844 35.0499 55.2761 33.597 51.618 33.597H50.3997C49.79 33.6488 49.1847 33.4563 48.7169 33.0619C48.2492 32.6675 47.9573 32.1035 47.9054 31.4939C47.8536 30.8843 48.0461 30.279 48.4406 29.8114C48.835 29.3437 49.3992 29.0518 50.0089 29ZM56.889 49.0132C56.4579 48.5821 56.2157 47.9975 56.2157 47.3879C56.2157 46.1687 55.7313 44.9994 54.869 44.1373C54.0068 43.2752 52.8374 42.7909 51.618 42.7909C51.0083 42.7909 50.4236 42.5488 49.9925 42.1177C49.5614 41.6867 49.3192 41.102 49.3192 40.4924C49.3192 39.8828 49.5614 39.2982 49.9925 38.8672C50.4236 38.4361 51.0083 38.1939 51.618 38.1939C54.0568 38.1939 56.3956 39.1626 58.1201 40.8868C59.8445 42.611 60.8133 44.9495 60.8133 47.3879C60.8133 47.9975 60.5711 48.5821 60.14 49.0132C59.7089 49.4442 59.1242 49.6864 58.5145 49.6864C57.9048 49.6864 57.3201 49.4442 56.889 49.0132ZM66.4011 69.0663L66.401 69.0846C66.3999 69.5725 66.2967 70.0547 66.0981 70.5003C65.8998 70.9451 65.611 71.3435 65.2499 71.67C64.8674 72.0182 64.4123 72.2771 63.9176 72.428C63.4516 72.5702 62.9613 72.6132 62.4782 72.5546C58.2475 72.53 53.4102 70.5344 49.1802 68.1761C44.8871 65.7827 41.0444 62.915 38.8019 60.9903L38.7681 60.9613L38.7367 60.9299C32.3303 54.5198 28.2175 46.1735 27.0362 37.186C26.9623 36.6765 27.0018 36.157 27.1519 35.6645C27.3027 35.1695 27.5615 34.7142 27.9094 34.3314C28.2397 33.9658 28.6436 33.6742 29.0944 33.4757C29.5447 33.2775 30.0316 33.1766 30.5234 33.1796H37.4967C38.299 33.1636 39.0826 33.4244 39.7156 33.9184C40.3527 34.4156 40.7979 35.1184 40.9754 35.9071L41.0038 36.0335V36.1631C41.0038 36.4901 41.0787 36.795 41.1847 37.2268C41.2275 37.4012 41.2755 37.5965 41.3256 37.8221L41.326 37.8238C41.583 38.9896 41.925 40.1351 42.3491 41.251L42.7322 42.259L38.4899 44.26L38.4846 44.2625C38.204 44.3914 37.986 44.6263 37.8784 44.9157L37.8716 44.934L37.8642 44.952C37.7476 45.236 37.7476 45.5545 37.8642 45.8385L37.9144 45.9608L37.9359 46.0912C38.0802 46.9648 38.5603 48.0981 39.4062 49.4169C40.243 50.7215 41.3964 52.1437 42.808 53.5872C45.6206 56.4634 49.3981 59.3625 53.5798 61.5387C53.8533 61.6395 54.1552 61.6343 54.4257 61.5231L54.4437 61.5157L54.462 61.5089C54.7501 61.4016 54.9842 61.1848 55.1133 60.9057L55.1148 60.9023L57.0232 56.6591L58.0397 57.03C59.1934 57.4509 60.3737 57.7947 61.5729 58.0592L61.5785 58.0605L61.5841 58.0618C62.152 58.1929 62.7727 58.3042 63.3802 58.3942L63.4231 58.4006L63.4654 58.4101C64.2537 58.5877 64.956 59.0332 65.453 59.6706C65.9429 60.2991 66.2033 61.0758 66.1916 61.8721L66.4011 69.0663Z"/></svg>
      </a>
      <?php endif; ?>
      <?php if ($email) : ?>
      <a class="social" href="mailto:<?php echo esc_attr($email); ?>?subject=Здравствуйте!" aria-label="Email">
        <svg class="social__icon" fill="currentColor" viewBox="0 0 100 100"><path fill-rule="evenodd" clip-rule="evenodd" d="M50 100C77.6142 100 100 77.6142 100 50C100 22.3858 77.6142 0 50 0C22.3858 0 0 22.3858 0 50C0 77.6142 22.3858 100 50 100ZM51.8276 49.2076L74.191 33.6901C73.4347 32.6649 72.2183 32 70.8466 32H29.1534C27.8336 32 26.6576 32.6156 25.8968 33.5752L47.5881 49.172C48.8512 50.0802 50.5494 50.0945 51.8276 49.2076ZM75 63.6709V37.6286L53.4668 52.57C51.1883 54.151 48.1611 54.1256 45.9095 52.5066L25 37.4719V63.6709C25 65.9648 26.8595 67.8243 29.1534 67.8243H70.8466C73.1405 67.8243 75 65.9648 75 63.6709Z"/></svg>
      </a>
      <?php endif; ?>
      <?php if ($vk) : ?>
      <a class="social" href="<?php echo esc_url($vk); ?>" target="_blank" rel="noopener" aria-label="ВКонтакте">
        <svg class="social__icon" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 0C18.627 0 24 5.373 24 12C24 18.627 18.627 24 12 24C5.373 24 0 18.627 0 12C0 5.373 5.373 0 12 0ZM11.77 16.43h.94s.28-.03.43-.19c.14-.14.13-.4.13-.4s-.02-1.26.56-1.45c.57-.18 1.31 1.22 2.09 1.75.59.4 1.04.31 1.04.31l2.09-.03s1.09-.07.57-.93c-.04-.07-.3-.64-1.57-1.82-1.32-1.23-1.15-1.03.45-3.16.97-1.3 1.36-2.09 1.24-2.43-.11-.32-.83-.24-.83-.24l-2.35.02s-.17-.02-.3.06c-.13.08-.21.26-.21.26s-.38 1.01-.89 1.87c-1.07 1.82-1.5 1.91-1.67 1.8-.4-.26-.3-1.05-.3-1.61 0-1.75.27-2.48-.52-2.67-.26-.06-.45-.1-1.12-.11-.85-.01-1.58 0-1.99.2-.27.14-.48.44-.36.46.16.02.51.1.7.35.24.33.23 1.05.23 1.05s.14 2.01-.32 2.26c-.32.17-.75-.18-1.71-1.83-.49-.85-.86-1.78-.86-1.78s-.07-.17-.2-.27c-.15-.11-.37-.14-.37-.14l-2.23.02s-.34.01-.46.16c-.11.13-.01.4-.01.4s1.75 4.09 3.73 6.15c1.82 1.89 3.88 1.76 3.88 1.76z"/></svg>
      </a>
      <?php endif; ?>
      <?php if ($tg) : ?>
      <a class="social" href="<?php echo esc_url($tg); ?>" target="_blank" rel="noopener" aria-label="Telegram">
        <svg class="social__icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.05-.2-.06-.06-.14-.04-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/></svg>
      </a>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Модалка «Обратный звонок» -->
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

<!-- Модалка «Оставить заявку» -->
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
            <input class="site-modal__input" type="text" id="bk-date" name="date" placeholder="Например: 15-18 августа">
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