<section class="map-section" id="map">
      <div class="map" data-map>
        <h2 class="map__title">Интерактивная карта <em>Карповки</em></h2>

        <!-- Слой, который двигаем/масштабируем: только изображение -->
        <div class="map__canvas" data-map-canvas>
          <img
            class="map__img"
            src="/wp-content/themes/theme/assets/img/map_no_wm.png"
            alt="Карта территории парк-отеля «Карповка»"
            draggable="false"
          />
        </div>

        <!-- Метки лежат в отдельном слое и позиционируются из JS,
             поэтому не «уезжают» и не меняют размер при зуме/прокрутке.
             data-x/data-y — доля (0..1) от размеров изображения. -->
        <div class="map__markers" data-map-markers>
          <button
            class="map__marker"
            type="button"
            data-map-marker
            data-x="0.4"
            data-y="0.20"
            data-title="Эко-дома"
            data-img="/wp-content/themes/theme/assets/img/imgs/1.jpg_1.jpeg"
            data-desc="Отдельный дом, терраса, кухня, мангальная зона. Для пар, семей и спокойных выходных на природе."
            data-price="от 10 500 ₽ / ночь"
            aria-label="Эко-дома"
          >
            <span class="map__pin"></span>
            <span class="map__marker-label">Эко-дома</span>
          </button>

          <button
            class="map__marker"
            type="button"
            data-map-marker
            data-x="0.32"
            data-y="0.60"
            data-title="Гостиничный комплекс"
            data-img="/wp-content/themes/theme/assets/img/imgs/IMG_9846.jpg.jpeg"
            data-desc="Номера для короткого отдыха, СПА-визита или события. Мангальная зона, Wi-Fi, удобный доступ к услугам комплекса."
            data-price="от 6 000 ₽ / ночь"
            aria-label="Гостиничный комплекс"
          >
            <span class="map__pin"></span>
            <span class="map__marker-label">Гостиничный комплекс</span>
          </button>

          <button
            class="map__marker"
            type="button"
            data-map-marker
            data-x="0.5"
            data-y="0.4"
            data-title="Гостевые дома"
            data-img="/wp-content/themes/theme/assets/img/imgs/1.jpg.jpeg"
            data-desc="4 спальни, большая гостиная, кухня, вид на лес"
            data-price="от 14 500 ₽ / ночь"
            aria-label="Гостевые дома"
          >
            <span class="map__pin"></span>
            <span class="map__marker-label">Гостевые дома</span>
          </button>
        </div>

        <!-- Полупрозрачное затемнение поверх карты — уходит при активации -->
        <div class="map__veil" aria-hidden="true"></div>

        <!-- Стартовое состояние: большая кнопка по центру -->
        <button class="map__activate" type="button" data-map-activate>
          Активировать карту
        </button>
        <!-- Активное состояние: компактная кнопка справа сверху -->
        <button class="map__deactivate" type="button" data-map-deactivate>
          Отключить карту
        </button>
      </div>
    </section>


    <!-- ─── Попап метки на карте (место проживания) ─────────── -->
    <div class="map-modal" data-map-modal hidden>
      <div class="map-modal__overlay" data-map-modal-close></div>
      <div
        class="map-modal__dialog"
        role="dialog"
        aria-modal="true"
        aria-label="Место проживания"
      >
        <button
          class="map-modal__close"
          type="button"
          data-map-modal-close
          aria-label="Закрыть"
        >
          <svg class="icon"><use href="#icon-close"></use></svg>
        </button>
        <div class="map-modal__media">
          <img class="map-modal__img" data-map-modal-img src="" alt="" />
        </div>
        <div class="map-modal__body">
          <h3 class="map-modal__title" data-map-modal-title></h3>
          <p class="map-modal__desc" data-map-modal-desc></p>
          <strong class="map-modal__price" data-map-modal-price></strong>
        </div>
      </div>
    </div>