<section class="section reviews-section" id="reviews">
        <div class="container">
          <div class="section-head reveal">
            <p class="eyebrow">Отзывы гостей</p>
            <h2 class="section-title">Что говорят <em>о домах</em></h2>
          </div>
        </div>

        <div class="reviews-slider swiper reveal" data-reviews>
          <div class="swiper-wrapper">
            <blockquote class="review swiper-slide" data-review>
              <div class="review__stars">★★★★★</div>
              <p class="review__text">
                Очень душевное место среди красоты природы. Жили в VIP доме, всё очень достойно, удобно, красиво. Прекрасный релакс в теплом бассейне
                под открытым небом, сауне. Для ребенка было чем заняться на детской площадке.
              </p>
              <div class="review__author">
                <div class="review__name">Larisa.Kurbatova.73</div>
                <div class="review__date">31 мая 2026</div>
              </div>
              <div class="review__fade" aria-hidden="true"></div>
              <button class="review__toggle" type="button" data-review-toggle>Читать полностью</button>
            </blockquote>

            <blockquote class="review swiper-slide" data-review>
              <div class="review__stars">★★★★★</div>
              <p class="review__text">
                Прекрасное удалённое место для отдыха, уютные стильные обустроенные домики. Детская площадка. Возможность половить рыбу в местном
                пруду. Мангальные зоны, можно заранее заказать питание из большого выбора блюд. Бассейн крытый и тёплый на улице. Сауна. Вежливый
                персонал. Приезжайте — не пожалеете.
              </p>
              <div class="review__author">
                <div class="review__name">Константин Марков</div>
                <div class="review__date">12 октября 2025</div>
              </div>
              <div class="review__fade" aria-hidden="true"></div>
              <button class="review__toggle" type="button" data-review-toggle>Читать полностью</button>
            </blockquote>

            <blockquote class="review swiper-slide" data-review>
              <div class="review__stars">★★★★★</div>
              <p class="review__text">
                Бываю здесь летом, и зимой — всегда есть чем заняться. Сервис на высоте, ремонт и интерьер сделаны с настоящей заботой о клиентах. В
                домиках очень красиво, уютно и кристально чисто. Сауны, бассейн, природа — всё, что душе угодно. Съездила уже больше 15 раз и снова
                хочется вернуться!
              </p>
              <div class="review__author">
                <div class="review__name">Marina</div>
                <div class="review__date">27 апреля 2026</div>
              </div>
              <div class="review__fade" aria-hidden="true"></div>
              <button class="review__toggle" type="button" data-review-toggle>Читать полностью</button>
            </blockquote>

            <blockquote class="review swiper-slide" data-review>
              <div class="review__stars">★★★★★</div>
              <p class="review__text">
                Всей душой и сердцем любим это место, невероятная сказка зимой, весной наслаждаешься тишиной, летом есть куча активностей и все на
                одной территории, осенью наслаждаешься золотым лесом. Сюда хочется возвращаться и советовать всем своим знакомым. Удовольствие
                получаешь с самого начала, от того как вежливо тебя встречает персонал, как уютно в каждом домике и номере. Вкусная еда, большая
                территория, потрясающие бассейны. Это место влюбляет в себя с первых секунд ❤
              </p>
              <div class="review__author">
                <div class="review__name">Дарья Носкова</div>
                <div class="review__date">10 апреля 2026</div>
              </div>
              <div class="review__fade" aria-hidden="true"></div>
              <button class="review__toggle" type="button" data-review-toggle>Читать полностью</button>
            </blockquote>

            <blockquote class="review swiper-slide" data-review>
              <div class="review__stars">★★★★★</div>
              <p class="review__text">
                Спа-отель «Карповка» — место, куда всегда хочется вернуться!<br /><br />
                Мы с семьёй отдыхаем в «Карповке» уже много лет. Несколько раз пытались сменить место, но всё равно возвращаемся сюда снова и снова.
                Чистые и уютные номера — всегда идеальная чистота и комфорт. Гостеприимство — встречают всегда с улыбкой, чувствуешь себя как дома.
              </p>
              <div class="review__author">
                <div class="review__name">Дмитрий Власов</div>
                <div class="review__date">19 апреля 2026</div>
              </div>
              <div class="review__fade" aria-hidden="true"></div>
              <button class="review__toggle" type="button" data-review-toggle>Читать полностью</button>
            </blockquote>
          </div>

          <button class="reviews-nav reviews-nav--prev" type="button" data-reviews-prev aria-label="Предыдущий отзыв">
            <svg class="icon"><use href="#icon-arrow-left"></use></svg>
          </button>
          <button class="reviews-nav reviews-nav--next" type="button" data-reviews-next aria-label="Следующий отзыв">
            <svg class="icon"><use href="#icon-arrow-right"></use></svg>
          </button>
        </div>
      </section>



      <div class="review-modal" data-review-modal hidden>
      <div class="review-modal__overlay" data-review-modal-close></div>
      <div class="review-modal__dialog" role="dialog" aria-modal="true" aria-label="Отзыв гостя">
        <button class="review-modal__close" type="button" data-review-modal-close aria-label="Закрыть">
          <svg class="icon"><use href="#icon-close"></use></svg>
        </button>
        <div class="review-modal__stars" data-review-modal-stars></div>
        <div class="review-modal__text" data-review-modal-text></div>
        <div class="review-modal__author">
          <div class="review-modal__name" data-review-modal-name></div>
          <div class="review-modal__date" data-review-modal-date></div>
        </div>
      </div>
    </div>