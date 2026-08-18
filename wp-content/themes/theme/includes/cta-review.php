<section class="c-cta-review" id="cta">
    <img class="c-hero__photo" src="/wp-content/themes/theme/assets/img/imgs/map_no_wm.png" alt="Природа парк-отеля Карповка" loading="eager">
    <div class="c-hero__overlay"></div>
    <div class="container">
      <div class="c-cta-review__head reveal">
        <p class="eyebrow">Поделитесь впечатлениями</p>
        <h2 class="section-title">Оставьте свой отзыв</h2>
      </div>

      <!-- Горизонтальная раскладка: слева текст и фото, справа форма -->
      <div class="c-cta-review__layout">
        <div class="c-cta-review__aside reveal">
          <p class="c-cta-review__text">
            Ваше мнение помогает нам становиться лучше. Расскажите о своём опыте пребывания в Карповке — мы ценим каждую обратную связь.
          </p>

          <img
            class="c-cta-review__photo"
            src="/wp-content/themes/theme/assets/img/imgs/1.jpg_1.jpeg"
            alt="Эко-дом с террасой на территории парк-отеля"
            loading="lazy"
          />
        </div>

        <div class="c-cta-review__main">
          <!-- Форма отзыва: та же логика отправки, что у форм на других
               страницах (data-callback-form в script2.js) -->
          <form class="r-form form-background reveal" data-callback-form data-validate novalidate>
            <p class="r-form__legend">Напишите нам напрямую</p>

            <div class="r-form__row r-form__row--full">
              <div class="r-form__field">
                <span class="r-form__label">Оценка</span>
                <!-- Радиокнопки в обратном порядке: подсветку соседей слева
                     даёт селектор ~, поэтому в разметке 5 идёт первой -->
                <div class="r-rating" data-rating>
                  <input class="r-rating__input" type="radio" id="rf-star5" name="rating" value="5" required />
                  <label class="r-rating__star" for="rf-star5" title="Отлично" aria-label="5 из 5">★</label>
                  <input class="r-rating__input" type="radio" id="rf-star4" name="rating" value="4" />
                  <label class="r-rating__star" for="rf-star4" title="Хорошо" aria-label="4 из 5">★</label>
                  <input class="r-rating__input" type="radio" id="rf-star3" name="rating" value="3" />
                  <label class="r-rating__star" for="rf-star3" title="Нормально" aria-label="3 из 5">★</label>
                  <input class="r-rating__input" type="radio" id="rf-star2" name="rating" value="2" />
                  <label class="r-rating__star" for="rf-star2" title="Плохо" aria-label="2 из 5">★</label>
                  <input class="r-rating__input" type="radio" id="rf-star1" name="rating" value="1" />
                  <label class="r-rating__star" for="rf-star1" title="Ужасно" aria-label="1 из 5">★</label>
                </div>
                <span class="r-form__error" data-error-for="rating"></span>
              </div>
            </div>

            <div class="r-form__row">
              <div class="r-form__field">
                <label for="rf-name">Ваше имя</label>
                <input type="text" id="rf-name" name="name" placeholder="Как к вам обращаться" required />
                <span class="r-form__error" data-error-for="rf-name"></span>
              </div>

              <div class="r-form__field">
                <label for="rf-phone">Телефон</label>
                <input
                  type="tel"
                  id="rf-phone"
                  name="phone"
                  placeholder="+7 (___) ___-__-__"
                  inputmode="tel"
                  autocomplete="tel"
                  data-phone-mask
                  required
                />
                <span class="r-form__error" data-error-for="rf-phone"></span>
              </div>
            </div>

            

            <div class="r-form__row r-form__row--full">
              <div class="r-form__field">
                <label for="rf-text">Отзыв</label>
                <textarea id="rf-text" name="text" rows="4" placeholder="Что понравилось, а что можно улучшить" required minlength="10"></textarea>
                <span class="r-form__error" data-error-for="rf-text"></span>
              </div>
            </div>

            <button class="btn btn--gold r-form__submit" type="submit">Отправить отзыв</button>
            <p class="r-form__note">Нажимая кнопку, вы соглашаетесь с <a href="#">политикой конфиденциальности</a></p>
          </form>

          <p class="c-cta-review__or reveal"><span>или</span></p>

          <div class="c-cta-review__actions reveal">
            <a href="https://yandex.ru/maps/org/karpovka/..." class="btn btn--gold" target="_blank" rel="noopener">Написать на Яндекс Картах</a>
            <a href="https://vk.com/baza_otdyha_spa_perm" class="btn btn--ghost" target="_blank" rel="noopener">Написать в ВКонтакте</a>
          </div>
        </div>
      </div>
    </div>
  </section>