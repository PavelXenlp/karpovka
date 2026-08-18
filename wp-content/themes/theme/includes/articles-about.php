<section class="c-articles-seo" id="about">
    <img class="c-articles-seo__bg" src="/wp-content/themes/theme/assets/img/imgs/1.jpg_1.jpeg" alt="" aria-hidden="true">
    <div class="c-articles-seo__overlay"></div>
    <div class="container">
      <div class="c-articles-seo__layout reveal">
        <div class="c-articles-seo__text">
          <div class="section-head">
            <p class="eyebrow">Карповка</p>
            <h2 class="section-title">О загородном отдыхе в <span class="gold-header"><em>Карповке</em></span></h2>
          </div>
          <p>Парк-отель «Карповка» расположен в живописном уголке Пермского края, на берегу Карпова пруда, в 90 километрах от Перми. Это место, где городская суета сменяется тишиной соснового леса, а свежий воздух и кристально чистая вода создают идеальные условия для восстановления сил и душевного равновесия.</p>
          <p>На территории парк-отеля guests найдут эко-дома с панорамными окнами, комфортабельный гостиничный комплекс и уютные гостевые дома. Каждый вариант размещения продуман до мелочей: просторные комнаты, стильный интерьер в духе скандинавского минимализма и вид на воду или лес. СПА-центр с бассейном, хаммамом и русская баня дополняют картину полноценного отдыха.</p>
          <p>Для активных гостей — велосипеды, лодки, байдарки, конные прогулки и рыбалка. Для семей — детская площадка, анимационные программы и безопасная территория. Для компаний — банкетный зал с видом на пруд и программа тимбилдинга. В кафе готовят блюда из локальных продуктов, а на террасе можно насладиться вечерним закатом с напитком в руке.</p>
        </div>
        <div class="c-articles-seo__media">
          <img src="/wp-content/themes/theme/assets/img/imgs/1.jpg_1.jpeg" alt="Парк-отель Карповка" loading="lazy">
          <div class="c-articles-seo__cta-box">
            <h3>Подберите свой формат отдыха</h3>
            <p>Менеджеры помогут выбрать программу и размещение под ваши пожелания</p>
            <div class="c-articles-seo__cta-actions">
              <a href="tel:+79850200203" class="btn btn--gold">Связатся с нами</a>
              <button type="button" class="btn btn--ghost js-open-modal-booking">Оставить заявку</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

    <!-- ═══════════ ПОПАП «ОСТАВИТЬ ЗАЯВКУ» ═══════════
     ═══════════════════════════════════════════════ -->

<div class="site-modal" id="site-modal-booking" hidden>
  <div class="site-modal__overlay" data-site-modal-close></div>
  <div class="site-modal__dialog">
    <button class="site-modal__close" data-site-modal-close aria-label="Закрыть">✕</button>
    <div class="site-modal__body">

      <!-- Форма -->
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

      <!-- Успех -->
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