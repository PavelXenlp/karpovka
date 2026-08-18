<section class="acc-booking" id="booking">
        <div class="acc-booking__inner">
          <div class="acc-booking__copy reveal">
            <p class="eyebrow">Подбор и бронирование</p>
            <h2 class="section-title acc-booking__title">Подобрать <em>проживание</em></h2>
            <p class="section-sub acc-booking__lead">
              Расскажите про даты и число гостей — подберём дом или номер, посчитаем стоимость с учётом бань, СПА и питания и забронируем.
            </p>

            <ul class="acc-booking__points">
              <li><svg class="icon" aria-hidden="true"><use href="#icon-check"></use></svg>Ответим и поможем выбрать формат</li>
              <li><svg class="icon" aria-hidden="true"><use href="#icon-check"></use></svg>Подскажем свободные даты</li>
              <li><svg class="icon" aria-hidden="true"><use href="#icon-check"></use></svg>Забронируем дополнительные услуги</li>
            </ul>

            <div class="acc-booking__contacts">
              <a class="acc-booking__phone" href="tel:+79850200203">+7 (985) 020-02-03</a>
              <a class="acc-booking__phone" href="tel:+79127813933">+7 (912) 781-39-33</a>
            </div>
          </div>

          <div class="acc-form form-background reveal">
            <form data-callback-form>
              <div class="acc-form__row">
                <div class="acc-form__field">
                  <label for="af-name">Ваше имя</label>
                  <input type="text" id="af-name" name="name" placeholder="Как к вам обращаться" required />
                </div>
                <div class="acc-form__field">
                  <label for="af-phone">Телефон</label>
                  <input type="tel" id="af-phone" name="phone" placeholder="+7 (___) ___-__-__" required />
                </div>
              </div>

              <div class="acc-form__row">
                <div class="acc-form__field">
                  <label for="af-checkin">Заезд</label>
                  <input type="date" id="af-checkin" name="checkin" />
                </div>
                <div class="acc-form__field">
                  <label for="af-checkout">Выезд</label>
                  <input type="date" id="af-checkout" name="checkout" />
                </div>
              </div>

              <div class="acc-form__row">
                <div class="acc-form__field">
                  <label for="af-guests">Гостей</label>
                  <input type="number" id="af-guests" name="guests" min="1" max="103" placeholder="2" />
                </div>
                <div class="acc-form__field">
                  <label for="af-format">Формат</label>
                  <select id="af-format" name="format">
                    <option value="any">Подберите на ваш выбор</option>
                    <option value="eco">Эко-дом</option>
                    <option value="hotel">Номер в гостинице</option>
                    <option value="guest">Гостевой дом</option>
                    <option value="vip">VIP-дом</option>
                  </select>
                </div>
              </div>

              <div class="acc-form__row acc-form__row--full">
                <div class="acc-form__field">
                  <label for="af-comment">Комментарий</label>
                  <textarea id="af-comment" name="comment" rows="3" placeholder="Питомец, детская кроватка, баня или СПА..."></textarea>
                </div>
              </div>

              <button class="btn btn--gold acc-form__submit" type="submit">Отправить заявку</button>
              <p class="acc-form__note">Нажимая кнопку, вы соглашаетесь с <a href="#">политикой конфиденциальности</a></p>
            </form>
          </div>
        </div>
      </section>