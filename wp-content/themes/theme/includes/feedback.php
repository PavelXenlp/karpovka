<section class="c-form-section" id="feedback">
  <div class="c-form-section__bg"></div>
  <div class="container">
    <div class="c-form-layout reveal">

      <!-- Левая колонка — редакторский текст -->
      <div class="c-form-layout__text">
        <div class="c-form-layout__label">Напишите нам</div>
        <h2 class="c-form-layout__title">
          Остались <em>вопросы</em>?
        </h2>
        <p class="c-form-layout__desc">
          Оставьте заявку — мы перезвоним и поможем с бронированием, выбором номера или организацией мероприятия
        </p>
        <ul class="c-form-layout__contacts">
          <li>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.11 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            <a href="tel:+79850200203">+7 (985) 020-02-03</a>
          </li>
          <li>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.11 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            <a href="tel:+79127813933">+7 (912) 781-39-33</a>
          </li>
          <li>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <a href="mailto:karpovka59@yandex.ru">karpovka59@yandex.ru</a>
          </li>
        </ul>
      </div>

      <!-- Правая колонка — форма -->
      <div class="c-form-layout__form-wrap">
        <form class="c-form" data-callback-form>
          <div class="c-form__row c-form__row--2col">
            <div class="c-form__field">
              <label for="cf-name">Ваше имя</label>
              <input type="text" id="cf-name" name="name" placeholder="Как к вам обращаться" required>
            </div>
            <div class="c-form__field">
              <label for="cf-phone">Телефон</label>
              <input type="tel" id="cf-phone" name="phone" placeholder="+7 (___) ___-__-__" required>
            </div>
          </div>
          <div class="c-form__row">
            <div class="c-form__field">
              <label for="cf-email">E-mail <span class="c-form__optional">необязательно</span></label>
              <input type="email" id="cf-email" name="email" placeholder="your@email.com">
            </div>
          </div>
          <div class="c-form__row">
            <div class="c-form__field">
              <label for="cf-topic">Тема обращения</label>
              <div class="c-form__select-wrap">
                <select id="cf-topic" name="topic">
                  <option value="booking">Бронирование проживания</option>
                  <option value="spa">СПА-услуги и баня</option>
                  <option value="event">Организация мероприятия</option>
                  <option value="cert">Подарочный сертификат</option>
                  <option value="other">Другой вопрос</option>
                </select>
                <svg class="c-form__select-arrow" width="14" height="8" viewBox="0 0 14 8" fill="none"><path d="M1 1.5L7 6.5L13 1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </div>
            </div>
          </div>
          <div class="c-form__row">
            <div class="c-form__field">
              <label for="cf-message">Сообщение</label>
              <textarea id="cf-message" name="message" placeholder="Расскажите, чем можем помочь..." rows="4"></textarea>
            </div>
          </div>
          <button class="btn btn--gold c-form__submit" type="submit">Отправить заявку</button>
          <p class="c-form__note reveal">Нажимая кнопку, вы соглашаетесь с <a href="#">политикой конфиденциальности</a></p>
        </form>

        <div class="c-form__success" id="formSuccess">
          <div class="c-form__success-icon">
            <svg><use href="#i-check"></use></svg>
          </div>
          <h3>Спасибо за обращение!</h3>
          <p>Мы свяжемся с вами в ближайшее время</p>
        </div>
      </div>

    </div>

    
  </div>
</section>