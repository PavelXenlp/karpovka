<section id="accommodation" class="combined">
      <!-- Проживание -->
      <div class="combined-row">
        <div class="combined-row__text reveal">
          <p class="eyebrow">Проживание</p>
          <h2 class="section-title">
            Ваш <em>комфорт</em> среди живописной природы
          </h2>
          <p class="section-sub combined-row__sub">
            От гостиничного номера до отдельного дома — каждый найдёт здесь свой
            идеальный сценарий отдыха
          </p>

          <ul class="stay-list" id="stayList">
            <li
              class="stay-option"
              data-img="/wp-content/themes/theme/assets/img/imgs/IMG_9846.JPG.jpeg"
              data-title="Гостиничный комплекс"
              data-caption="Номера для короткого отдыха, СПА-визита или события"
              data-desc="Мангальная зона, Wi-Fi, удобный доступ к услугам комплекса"
              data-price="от 6 000 ₽ / ночь"
            >
              <span class="stay-option__name">Гостиничный комплекс</span>
              <span class="stay-option__price">от 3 300 ₽</span>
            </li>
            <li
              class="stay-option is-active"
              data-img="/wp-content/themes/theme/assets/img/imgs/1.jpg_1.jpeg"
              data-title="Эко-дома"
              data-caption="Для пар, семей и спокойных выходных на природе"
              data-desc="Отдельный дом, терраса, кухня, мангальная зона"
              data-price="от 10 500 ₽ / ночь"
            >
              <span class="stay-option__name">Эко-дома</span>
              <span class="stay-option__price">от 13 500 ₽</span>
            </li>
            <li
              class="stay-option"
              data-img="/wp-content/themes/theme/assets/img/imgs/1.jpg.jpeg"
              data-title="Гостевые дома"
              data-caption="Для компаний, семейных встреч и выездных мероприятий"
              data-desc="4 спальни, большая гостиная, кухня, вид на лес"
              data-price="от 14 500 ₽ / ночь"
            >
              <span class="stay-option__name">Гостевые дома</span>
              <span class="stay-option__price">от 32 000 ₽</span>
            </li>
          </ul>

          <a class="btn btn--ghost stay-list__more" href="accommodation.html"
            >Все варианты размещения</a
          >
        </div>

        <div class="combined-row__media stay-media reveal">
          <img
            class="stay-media__img"
            id="stayImg"
            src="/wp-content/themes/theme/assets/img/imgs/1.jpg_1.jpeg"
            alt="Эко-дома"
          />
          <div class="stay-media__card" id="stayCard">
            <h3 class="stay-media__title">Эко-дома</h3>
            <p class="stay-media__caption">
              Для пар, семей и спокойных выходных на природе
            </p>
            <p class="stay-media__desc">
              Отдельный дом, терраса, кухня, мангальная зона
            </p>
            <strong class="stay-media__price">от 10 500 ₽ / ночь</strong>
          </div>
        </div>
      </div>

      <!-- Активный отдых -->
      <div class="combined-row" id="recreation">
        <!-- Картинки сезонов задаются в script2.js (seasonImages). Пока стоят заглушки — замените на присланные фото. -->
        <div class="combined-row__media season-media reveal">
          <img
            class="season-media__img"
            id="seasonImg"
            src="/wp-content/themes/theme/assets/img/imgs/matthew-mcbrayer-mO-.jpg.jpeg"
            alt="Активный отдых"
          />
        </div>
        <div class="combined-row__text combined-row__text-active-rest reveal">
          <p class="eyebrow">Активный отдых</p>
          <h2 class="section-title"><em>Активный</em> отдых</h2>
          <p class="section-sub combined-row__sub">
            На воде, в лесу и на свежем воздухе — в любой сезон
          </p>

          <div class="season-toggle season-toggle--left">
            <div class="season-toggle__group">
              <button
                class="season-btn is-active"
                type="button"
                data-season="summer"
              >
                Лето-осень
              </button>
              <button class="season-btn" type="button" data-season="winter">
                Зима
              </button>
              <button class="season-btn" type="button" data-season="year">
                Круглый год
              </button>
            </div>
          </div>

          <ul class="activity-list" id="activityList"></ul>
        </div>
      </div>
    </section>