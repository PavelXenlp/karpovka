<section class="section offers-section" id="services-grid">
      <!-- <div class="container"> -->
      <div class="section-head section-head--row reveal">
        <h2 class="section-title">Всё для насыщенного <em>отдыха</em></h2>
        <div class="offers-nav">
          <button
            class="offers-nav__btn"
            type="button"
            data-offers-prev
            aria-label="Предыдущие"
          >
            <svg class="icon"><use href="#icon-chevron-left"></use></svg>
          </button>
          <button
            class="offers-nav__btn"
            type="button"
            data-offers-next
            aria-label="Следующие"
          >
            <svg class="icon"><use href="#icon-chevron-right"></use></svg>
          </button>
        </div>
      </div>
      <!-- </div> -->

      <div class="swiper offers-swiper reveal" data-offers>
        <div class="swiper-wrapper">
          <!-- Банный комплекс -->
          <article class="swiper-slide ocard" id="bath">
            <div class="ocard__media">
              <img src="/wp-content/themes/theme/assets/img/imgs/banya.png" alt="Банный комплекс" />
            </div>
            <div class="ocard__body">
              <h3 class="ocard__title">Банный комплекс</h3>
              <div class="ocard__foot">
                <p class="ocard__desc">
                  Русская баня на дровах, парные с системой «второе дыхание».
                </p>
                <a
                  class="ocard__arrow"
                  href="#contacts"
                  aria-label="Подробнее — Банный комплекс"
                >
                  <svg class="icon"><use href="#icon-arrow-ur"></use></svg>
                </a>
              </div>
            </div>
          </article>

          <!-- Кафе и банкетный зал -->
          <article class="swiper-slide ocard" id="dining">
            <div class="ocard__media">
              <img
                src="/wp-content/themes/theme/assets/img/imgs/photo.png"
                alt="Кафе и банкетный зал"
              />
            </div>
            <div class="ocard__body">
              <h3 class="ocard__title">Кафе и банкетный зал</h3>
              <div class="ocard__foot">
                <p class="ocard__desc">
                  Домашняя кухня, сезонные блюда и уютная атмосфера для событий.
                </p>
                <a class="ocard__arrow" href="/cafe" aria-label="Смотреть меню">
                  <svg class="icon"><use href="#icon-arrow-ur"></use></svg>
                </a>
              </div>
            </div>
          </article>

          <!-- Мероприятия -->
          <article class="swiper-slide ocard" id="events">
            <div class="ocard__media">
              <img
                src="/wp-content/themes/theme/assets/img/imgs/events.JPG"
                alt="Мероприятия в Карповке"
              />
            </div>
            <div class="ocard__body">
              <h3 class="ocard__title">Мероприятия</h3>
              <div class="ocard__foot">
                <ul class="ocard__list">
                  <li>Свадьбы</li>
                  <li>Банкеты</li>
                  <li>Выпускные</li>
                  <li>Дни рождения</li>
                  <li>Ретриты</li>
                  <li>Корпоративы</li>
                  <li>Лагеря</li>
                  <li>Новый год</li>
                </ul>
                <a
                  class="ocard__arrow"
                  href="#contacts"
                  aria-label="Подробнее о мероприятиях"
                >
                  <svg class="icon"><use href="#icon-arrow-ur"></use></svg>
                </a>
              </div>
            </div>
          </article>

          <!-- Доставка еды и напитков -->
          <article class="swiper-slide ocard">
            <div class="ocard__media">
              <img
                src="/wp-content/themes/theme/assets/img/imgs/food_delivery.jpg"
                alt="Доставка еды и напитков"
              />
            </div>
            <div class="ocard__body">
              <h3 class="ocard__title">Доставка еды и напитков</h3>
              <div class="ocard__foot">
                <p class="ocard__desc">
                  Закажите любимые блюда и напитки — доставим в любое место на
                  территории парк-отеля.
                </p>
                <a
                  class="ocard__arrow"
                  href="https://shop.hotbot.ai/karpovka/eda-i-napitki-v-nomer/"
                  target="_blank"
                  rel="noopener"
                  aria-label="Заказать онлайн"
                >
                  <svg class="icon"><use href="#icon-arrow-ur"></use></svg>
                </a>
              </div>
            </div>
          </article>

          <!-- Беседки: открытая и тёплая — переключаются внутри карточки -->
          <article class="swiper-slide ocard" data-ocard-slider>
            <div class="ocard__media">
              <div class="ocard-shots">
                <img
                  class="ocard-shots__img"
                  src="/wp-content/themes/theme/assets/img/imgs/besedka_warm.jpg"
                  alt="Тёплая крытая беседка"
                  data-label="Тёплая крытая беседка"
                  loading="lazy"
                />
                <img
                  class="ocard-shots__img is-active"
                  src="/wp-content/themes/theme/assets/img/imgs/besedka_outdoor.jpg"
                  alt="Открытая беседка на берегу пруда"
                  data-label="Открытая беседка"
                />
              </div>

              <button
                class="ocard-shots__nav ocard-shots__nav_prev swiper-no-swiping"
                type="button"
                data-ocard-prev
                aria-label="Предыдущее фото беседки"
              >
                <svg class="icon"><use href="#icon-chevron-left"></use></svg>
              </button>
              <button
                class="ocard-shots__nav ocard-shots__nav_next swiper-no-swiping"
                type="button"
                data-ocard-next
                aria-label="Следующее фото беседки"
              >
                <svg class="icon"><use href="#icon-chevron-right"></use></svg>
              </button>

              <div
                class="ocard-shots__dots swiper-no-swiping"
                data-ocard-dots
              ></div>
            </div>
            <div class="ocard__body">
              <h3 class="ocard__title">Беседки</h3>
              <div class="ocard__foot">
                <p class="ocard__desc">
                  Просторная открытая беседка на берегу живописного пруда и
                  тёплый банкетный зал для мероприятий в любое время года.
                </p>
                <a
                  class="ocard__arrow"
                  href="https://shop.hotbot.ai/karpovka/eda-i-napitki-v-nomer/"
                  target="_blank"
                  rel="noopener"
                  aria-label="Заказать онлайн"
                >
                  <svg class="icon"><use href="#icon-arrow-ur"></use></svg>
                </a>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>