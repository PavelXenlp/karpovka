// ─── Nav: фон при прокрутке ───────────────────────────────
(() => {
  const nav = document.getElementById('nav');
  const THRESHOLD = 80;

  const onScroll = () => {
    nav.classList.toggle('is-scrolled', window.scrollY > THRESHOLD);
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
})();

// ─── Бургер-меню ──────────────────────────────────────────
(() => {
  const burger = document.getElementById('burger');
  const menu = document.getElementById('mobileMenu');

  const setOpen = (open) => {
    burger.classList.toggle('is-open', open);
    burger.setAttribute('aria-expanded', String(open));
    menu.hidden = !open;
  };

  burger.addEventListener('click', () => {
    setOpen(menu.hidden);
  });

  // Закрываем меню после клика по любой ссылке
  menu.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => setOpen(false));
  });
})();

// ─── Проживание: выбор варианта + смена фото и карточки ────
(() => {
  const list = document.getElementById('stayList');
  const img = document.getElementById('stayImg');
  const card = document.getElementById('stayCard');
  if (!list || !img || !card) return;

  const options = Array.from(list.querySelectorAll('.stay-option'));

  const select = (option) => {
    options.forEach((o) => o.classList.toggle('is-active', o === option));

    // Плавная смена изображения с предзагрузкой
    const src = option.dataset.img;
    if (src && img.getAttribute('src') !== src) {
      img.style.opacity = '0';
      const pre = new Image();
      pre.onload = () => {
        img.src = src;
        img.alt = option.dataset.title || '';
        img.style.opacity = '1';
      };
      pre.src = src;
    }

    // Обновляем всплывающую карточку с информацией
    card.querySelector('.stay-media__title').textContent = option.dataset.title || '';
    card.querySelector('.stay-media__caption').textContent = option.dataset.caption || '';
    card.querySelector('.stay-media__desc').textContent = option.dataset.desc || '';
    card.querySelector('.stay-media__price').textContent = option.dataset.price || '';

    // Перезапуск анимации появления карточки
    card.classList.remove('is-in');
    void card.offsetWidth;
    card.classList.add('is-in');
  };

  options.forEach((o) => o.addEventListener('click', () => select(o)));
})();

// ─── Активный отдых: сезоны, иконки и смена фото ───────────
(() => {
  const buttons = document.querySelectorAll('.season-btn');
  const listEl = document.getElementById('activityList');
  const img = document.getElementById('seasonImg');
  if (!buttons.length || !listEl) return;

  // Активности по сезонам (как на основном сайте)
  const activities = {
    summer: ['Рыбалка летом и осенью', 'Лодки и сапборды', 'Катамараны', 'Квадроциклы', 'Велосипеды', 'Детские самокаты', 'Волейбол', 'Баскетбол', 'Футбол', 'Бадминтон', 'Настольные игры'],
    winter: ['Лыжи', 'Тюбинги', 'Снегоходы', 'Прогулки по лесу', 'Коньки'],
    year: ['Мероприятия', 'Настольные игры', 'Прогулки', 'Уличные тренажеры', 'Детская площадка', 'Шахматы, шашки', 'Скандинавская ходьба', 'Настольный теннис'],
  };

  // Иконки активностей (совпадают со спрайтом из index.html)
  const activityIcons = {
    'Катамараны': 'catamaran',
    'Квадроциклы': 'atv',
    'Лыжи': 'ski',
    'Лодки и сапборды': 'boat',
    'Мероприятия': 'calendar',
    'Настольные игры': 'dice',
    'Прогулки': 'stroll',
    'Прогулки по лесу': 'walking',
    'Рыбалка летом и осенью': 'fish',
    'Снегоходы': 'snowmobile',
    'Тюбинги': 'tube',
    'Велосипеды': 'bike',
    'Волейбол': 'volleyball',
    'Баскетбол': 'basketball',
    'Футбол': 'soccer',
    'Бадминтон': 'badminton',
    'Детские самокаты': 'scooter',
    'Коньки': 'skate',
    'Уличные тренажеры': 'dumbbell',
    'Детская площадка': 'playground',
    'Скандинавская ходьба': 'walking',
    'Шахматы, шашки': 'chess',
  };

  // Фото по сезонам. ЗАГЛУШКИ — замените на присланные картинки:
  // зима — снег, лето — зелень и солнце, круглый год — лето с закатом.
  const seasonImages = {
    summer: './assets/imgs/imgs/matthew-mcbrayer-mO-.jpg.jpeg',
    winter: './assets/imgs/imgs/IMG_0632-2.jpg.jpeg',
    year: './assets/imgs/imgs/photo.jpg_2.jpeg',
  };

  const renderList = (season) => {
    const items = activities[season] || activities.summer;
    listEl.innerHTML = items
      .map((item) => {
        const icon = activityIcons[item] || 'check';
        return `<li><svg class="icon"><use href="#icon-${icon}"></use></svg>${item}</li>`;
      })
      .join('');
  };

  const swapImage = (season) => {
    const src = seasonImages[season];
    if (!img || !src || img.getAttribute('src') === src) return;
    img.style.opacity = '0';
    const pre = new Image();
    pre.onload = () => {
      img.src = src;
      img.style.opacity = '1';
    };
    pre.src = src;
  };

  buttons.forEach((btn) => {
    btn.addEventListener('click', () => {
      const season = btn.dataset.season;
      buttons.forEach((b) => b.classList.toggle('is-active', b === btn));
      renderList(season);
      swapImage(season);
    });
  });

  // Стартовое состояние — лето-осень
  renderList('summer');
})();

// ─── Слайдер услуг «Всё для насыщенного отдыха» (Swiper) ──
(() => {
  const el = document.querySelector('[data-offers]');
  if (!el || typeof Swiper === 'undefined') return;

  // Первый слайд выравниваем по левому краю заголовка секции: отступ слева
  // = позиция заголовка относительно окна (пересчитываем при ресайзе/загрузке).
  const section = el.closest('.offers-section');
  const titleEl = section ? section.querySelector('.section-title') : null;
  const offsetBefore = () => (titleEl ? Math.max(0, Math.round(titleEl.getBoundingClientRect().left)) : 0);

  const sw = new Swiper(el, {
    slidesPerView: 'auto', // ширину карточек задаёт CSS → справа слайд «торчит»
    spaceBetween: 24,
    grabCursor: true, // курсор-«хват» + перетаскивание мышью/пальцем
    slidesOffsetBefore: offsetBefore(),
    navigation: {
      prevEl: '[data-offers-prev]',
      nextEl: '[data-offers-next]',
    },
    on: {
      resize(s) {
        s.params.slidesOffsetBefore = offsetBefore();
        s.update();
      },
    },
  });

  // Позиция заголовка зависит от подгруженного шрифта — пересчитываем после load
  window.addEventListener('load', () => {
    sw.params.slidesOffsetBefore = offsetBefore();
    sw.update();
  });
})();

// ─── Слайдер «Выберите формат отдыха» (Swiper, центрированный) ─
(() => {
  const el = document.querySelector('[data-stories]');
  if (!el || typeof Swiper === 'undefined') return;

  new Swiper(el, {
    slidesPerView: 'auto', // ширину карточек задаёт CSS (var(--story-card-w))
    centeredSlides: true, // активная карточка — по центру
    loop: true, // бесконечная прокрутка
    speed: 600, // синхронно с --story-dur в CSS
    grabCursor: true, // перетаскивание мышью/пальцем
    slideToClickedSlide: true, // клик по соседней карточке центрирует её
    navigation: {
      prevEl: '[data-stories-prev]',
      nextEl: '[data-stories-next]',
    },
    breakpoints: {
      0: { spaceBetween: 14 },
      720: { spaceBetween: 24 },
    },
  });

  // Активна только центральная карточка: переход по ссылке у остальных
  // отменяем (slideToClickedSlide просто центрирует кликнутую).
  el.addEventListener('click', (event) => {
    const card = event.target.closest('.story-slide__card');
    if (!card) return;
    const slide = card.closest('.swiper-slide');
    if (slide && !slide.classList.contains('swiper-slide-active')) {
      event.preventDefault();
    }
  });
})();

// ─── СПА-центр: вкладки + слайдер ─────────────────────────
(() => {
  const root = document.querySelector('[data-spa]');
  if (!root) return;

  const SPA_TABS = [
    {
      id: 'pools',
      label: 'Бассейны',
      slides: [
        {
          img: './assets/imgs/imgs/pool_6x18.jpg',
          title: 'Бассейн 6×18',
          desc: 'Главная чаша спа-центра: просторная вода, финская сауна и зона отдыха рядом — можно провести здесь весь вечер.',
          details: [
            'Финская сауна',
            'Душевые',
            'Зона отдыха',
            'Посещение уличного бассейна в подарок (сезонно)',
            'Температура воды 29–33 °C',
          ],
        },
        {
          img: './assets/imgs/imgs/pool_outdoor.JPG',
          title: 'Уличный бассейн',
          desc: 'Открытый подогреваемый бассейн под небом. Работает в тёплое время года и входит в посещение чаши 6×18.',
          details: [
            'Входит в посещение бассейна 6×18',
            'Водопад',
            'Подводный массаж',
            'Подогрев, температура от 35 °C',
            'Работает в тёплый сезон',
          ],
        },
        {
          img: './assets/imgs/imgs/pool_10x4.jpg',
          title: 'Бассейн 10×4',
          desc: 'Камерная чаша с водопадом и турецкой парной — формат для спокойного восстановления без суеты.',
          details: ['Водопад', 'Турецкая паровая', 'Комната отдыха', 'Температура воды 29–33 °C'],
        },
        {
          img: './assets/imgs/imgs/pool_afrodita.jpg',
          title: 'Бассейн «Афродита»',
          desc: 'Самая насыщенная зона комплекса: джакузи, хамам и паровая в одном пространстве.',
          details: [
            'Джакузи на 75 форсунок',
            'Подводный массаж',
            'Паровая',
            'Хамам',
            'Комната отдыха',
            'Температура воды 29–33 °C',
          ],
        },
      ],
    },
    {
      id: 'massage',
      label: 'Массаж',
      slides: [
        {
          img: './assets/imgs/imgs/massage_spine.jpg',
          title: 'Массаж спины',
          desc: 'Средство для улучшения общего состояния и профилактики воздействия вредных факторов среды. Повышает сопротивляемость организма инфекциям и стрессам.',
          details: [
            'Нормализует работу сальных и потовых желёз',
            'Улучшает состояние подкожно-жировой клетчатки',
            'Кожа становится гладкой, плотной и эластичной',
          ],
        },
        {
          img: './assets/imgs/imgs/massage_method.jpg',
          title: 'Массаж по авторской методике',
          desc: 'Процедура охватывает все основные группы мышц: разминание, поглаживание и растирание помогают расслабиться и снять болевые ощущения.',
          details: [
            'Улучшить кровообращение',
            'Стимулировать лимфатическую систему',
            'Способствовать эмоциональному расслаблению',
            'Снизить уровень стресса и улучшить настроение',
          ],
        },
        {
          img: './assets/imgs/imgs/massage_childern.jpg',
          title: 'Детский массаж',
          desc: 'Тело подростка непрерывно меняется, и эти изменения часто становятся причиной болей в мышцах, головных болей и травм.',
          details: [
            'Восстанавливает повреждённые мышцы',
            'Снимает боль после спортивных нагрузок',
            'Улучшает качество сна',
            'Положительно влияет на учебную продуктивность',
          ],
        },
        {
          img: './assets/imgs/imgs/massage_lpg.jpg',
          title: 'LPG-массаж',
          desc: 'Аппаратная методика для улучшения внешнего вида и эластичности кожи, обменных процессов и уменьшения жировых клеток.',
          details: [
            'Стимулирует выработку коллагена и эластина',
            'Укрепляет и уплотняет кожу',
            'Тонизирует мышцы, борется с вялостью кожи',
          ],
        },
        {
          img: './assets/imgs/imgs/massage_vibro.jpg',
          title: 'Вибромассаж',
          desc: 'Вибрационный массаж всего тела — эффективная и приятная процедура с множеством преимуществ для организма.',
          details: [
            'Улучшение кровообращения и питания тканей',
            'Расслабление мышц, снятие спазмов и усталости',
            'Снятие стресса за счёт выделения эндорфинов',
          ],
        },
        {
          img: './assets/imgs/imgs/massage_press.jpg',
          title: 'Прессотерапия',
          desc: 'Вид аппаратного массажа: механическое воздействие на тело сжатым воздухом. Активизирует ток лимфы по сосудам.',
          details: [
            'Ускоряет выход жидкости из межклеточного пространства',
            'Уменьшает отёчность',
            'Понадобится удобная одежда с длинным рукавом',
          ],
        },
      ],
    },
    {
      id: 'programs',
      label: 'СПА-программы',
      slides: [
        {
          img: './assets/imgs/imgs/spa_krasivoe-telo.jpg',
          title: 'СПА-программа «Красивое тело»',
          desc: 'Комплексный уход на основе мёда. Эффект: подтянутая, сияющая кожа, ощущение лёгкости и прилив энергии.',
          details: [
            'Медовый скраб — нежное очищение и питание кожи',
            'Медовое обёртывание — глубокое увлажнение',
            'Лимфодренажный массаж — меньше отёков, лучше тонус',
            'Травяной чай — расслабляющее завершение',
          ],
        },
        {
          img: './assets/imgs/imgs/spa_chocolate-heaven.jpg',
          title: 'СПА-программа «Шоколадный рай»',
          desc: 'Ритуал с ароматом какао. Эффект: бархатистая кожа, приподнятое настроение и полное восстановление сил.',
          details: [
            'Шоколадное скрабирование — мягкое отшелушивание',
            'Шоколадное обёртывание — питание и увлажнение',
            'Расслабляющий массаж — снятие мышечных зажимов',
            'Травяной чай — тёплое завершение ритуала',
          ],
        },
      ],
    },
    {
      id: 'salt',
      label: 'Соляная комната',
      slides: [
        {
          img: './assets/imgs/imgs/spa_salt-room.jpg',
          title: 'Соляная комната',
          desc: 'Соляная пещера в «Карповке» — отличный способ снять накопившуюся усталость. Сеанс проходит в сопровождении расслабляющей музыки (по желанию).',
          details: [
            'Солегенератор насыщает воздух микрочастицами соли',
            'Помогает справляться с простудными заболеваниями',
            'Укрепляет иммунитет',
            'Снимает стресс',
          ],
        },
      ],
    },
  ];

  const tabsBox = root.querySelector('[data-spa-tabs]');
  const imgEl = root.querySelector('[data-spa-img]');
  const counterEl = root.querySelector('[data-spa-counter]');
  const textWrap = root.querySelector('[data-spa-textwrap]');
  const titleEl = root.querySelector('[data-spa-title]');
  const descEl = root.querySelector('[data-spa-desc]');
  const detailsEl = root.querySelector('[data-spa-details]');
  const dotsBox = root.querySelector('[data-spa-dots]');
  const arrowsBox = root.querySelector('[data-spa-arrows]');
  const stripBox = root.querySelector('[data-spa-strip]');
  const prevBtn = root.querySelector('[data-spa-prev]');
  const nextBtn = root.querySelector('[data-spa-next]');

  let activeTab = 0;
  let activeSlide = 0;

  // Вкладки создаём один раз
  SPA_TABS.forEach((tab, i) => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'spa__tab';
    btn.textContent = tab.label;
    btn.addEventListener('click', () => {
      activeTab = i;
      activeSlide = 0;
      render();
    });
    tabsBox.appendChild(btn);
  });
  const tabBtns = Array.from(tabsBox.children);

  // Перезапуск CSS-анимации появления
  function replay(el, cls) {
    el.classList.remove(cls);
    void el.offsetWidth;
    el.classList.add(cls);
  }

  function goSlide(i) {
    const total = SPA_TABS[activeTab].slides.length;
    activeSlide = (i + total) % total;
    render();
  }

  function render() {
    const tab = SPA_TABS[activeTab];
    const slide = tab.slides[activeSlide];
    const total = tab.slides.length;

    tabBtns.forEach((b, i) => b.classList.toggle('is-active', i === activeTab));

    imgEl.src = slide.img;
    imgEl.alt = slide.title;
    titleEl.textContent = slide.title;
    descEl.textContent = slide.desc;

    detailsEl.innerHTML = '';
    slide.details.forEach((d) => {
      const row = document.createElement('div');
      row.className = 'spa__detail';
      const span = document.createElement('span');
      span.textContent = d;
      row.appendChild(span);
      detailsEl.appendChild(row);
    });

    // Счётчик слайдов поверх картинки
    counterEl.hidden = total < 2;
    if (total > 1) {
      counterEl.innerHTML =
        '<b>' + String(activeSlide + 1).padStart(2, '0') + '</b><i>/</i>' + String(total).padStart(2, '0');
    }

    // Точки
    dotsBox.innerHTML = '';
    tab.slides.forEach((_, i) => {
      const dot = document.createElement('button');
      dot.type = 'button';
      dot.className = 'spa__dot' + (i === activeSlide ? ' is-active' : '');
      dot.setAttribute('aria-label', `Слайд ${i + 1}`);
      dot.addEventListener('click', () => goSlide(i));
      dotsBox.appendChild(dot);
    });

    // Стрелки прячем, если во вкладке один слайд
    arrowsBox.hidden = total < 2;

    // Нижняя полоска-индикатор. Пустой контейнер оставляем —
    // он держит нижний отступ секции
    stripBox.innerHTML = '';
    if (total > 1) {
      tab.slides.forEach((s, i) => {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'spa__strip-item' + (i === activeSlide ? ' is-active' : '');
        item.setAttribute('aria-label', s.title);
        item.addEventListener('click', () => goSlide(i));
        stripBox.appendChild(item);
      });
    }

    replay(imgEl, 'is-in');
    replay(textWrap, 'is-in');
  }

  prevBtn.addEventListener('click', () => goSlide(activeSlide - 1));
  nextBtn.addEventListener('click', () => goSlide(activeSlide + 1));

  render();
})();

// ─── Услуги: горизонтальный скролл с пиннингом секции ─────
(() => {
  const section = document.querySelector('[data-hscroll]');
  if (!section) return;
  const scroller = section.querySelector('[data-hscroll-scroller]');
  const track = section.querySelector('[data-hscroll-track]');
  if (!scroller || !track) return;

  const isMobile = () => window.matchMedia('(max-width: 900px)').matches;
  let maxScroll = 0;
  let enabled = false;

  function setup() {
    if (isMobile()) {
      // Мобильный режим: обычный горизонтальный свайп, пиннинг выключен
      enabled = false;
      section.style.height = '';
      track.style.transform = '';
      return;
    }
    enabled = true;
    // Насколько трек шире видимой области — столько вертикального
    // скролла «съест» секция, пока листает карточки горизонтально
    maxScroll = Math.max(0, track.scrollWidth - scroller.clientWidth);
    section.style.height = window.innerHeight + maxScroll + 'px';
    update();
  }

  function update() {
    if (!enabled) return;
    if (maxScroll === 0) {
      track.style.transform = 'translateX(0)';
      return;
    }
    // Пока секция запинена, её top уходит от 0 до -maxScroll
    const top = section.getBoundingClientRect().top;
    const progress = Math.min(1, Math.max(0, -top / maxScroll));
    track.style.transform = 'translateX(' + -progress * maxScroll + 'px)';
  }

  let ticking = false;
  window.addEventListener(
    'scroll',
    () => {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(() => {
        update();
        ticking = false;
      });
    },
    { passive: true }
  );

  window.addEventListener('resize', setup);
  // Картинки могут дозагрузиться и повлиять на размеры — пересчитываем
  window.addEventListener('load', setup);
  setup();
})();

// ─── Мини-слайдер «Беседки» ───────────────────────────────
document.querySelectorAll('[data-gz]').forEach((slider) => {
  const track = slider.querySelector('[data-gz-track]');
  const slides = Array.from(track.children);
  const dotsBox = slider.querySelector('[data-gz-dots]');
  const prevBtn = slider.querySelector('[data-gz-prev]');
  const nextBtn = slider.querySelector('[data-gz-next]');
  if (slides.length === 0) return;

  let active = 0;

  const dots = slides.map((_, index) => {
    const dot = document.createElement('button');
    dot.type = 'button';
    dot.setAttribute('aria-label', `Беседка ${index + 1}`);
    dot.addEventListener('click', () => goTo(index));
    dotsBox.appendChild(dot);
    return dot;
  });

  function render() {
    track.style.transform = `translateX(${-active * 100}%)`;
    dots.forEach((dot, index) => dot.classList.toggle('is-active', index === active));
  }

  function goTo(index) {
    active = (index + slides.length) % slides.length;
    render();
  }

  prevBtn.addEventListener('click', () => goTo(active - 1));
  nextBtn.addEventListener('click', () => goTo(active + 1));

  // Свайп на тач-устройствах
  let startX = null;
  track.addEventListener(
    'touchstart',
    (event) => {
      startX = event.touches[0].clientX;
    },
    { passive: true }
  );
  track.addEventListener('touchend', (event) => {
    if (startX === null) return;
    const dx = event.changedTouches[0].clientX - startX;
    if (Math.abs(dx) > 40) goTo(active + (dx < 0 ? 1 : -1));
    startX = null;
  });

  render();
});

// ─── Бегущие строки преимуществ ───────────────────────────
document.querySelectorAll('[data-marquee]').forEach((track) => {
  const set = track.querySelector('[data-marquee-set]');
  if (!set) return;
  const speed = parseFloat(track.dataset.marqueeSpeed) || 50; // пикселей в секунду

  function build() {
    // Пересобираем: убираем прежние клоны, оставляя исходный набор
    track.querySelectorAll('[data-marquee-clone]').forEach((node) => node.remove());

    const setWidth = set.getBoundingClientRect().width;
    if (!setWidth) return;

    // Половина дорожки должна быть шире экрана, иначе в цикле появится пустота
    const perHalf = Math.max(1, Math.ceil(window.innerWidth / setWidth));
    const total = perHalf * 2;

    for (let i = 1; i < total; i++) {
      const clone = set.cloneNode(true);
      clone.removeAttribute('data-marquee-set');
      clone.setAttribute('data-marquee-clone', '');
      clone.setAttribute('aria-hidden', 'true');
      track.appendChild(clone);
    }

    // Скорость держим постоянной, сколько бы копий ни получилось
    track.style.animationDuration = (setWidth * perHalf) / speed + 's';
  }

  build();
  // Ширина набора зависит от подгруженного шрифта — пересчитываем
  window.addEventListener('load', build);
  window.addEventListener('resize', build);
});

// ─── Отзывы: слайдер (Swiper, бесконечный) + попап полного текста ─
(() => {
  const el = document.querySelector('[data-reviews]');
  if (!el || typeof Swiper === 'undefined') return;

  new Swiper(el, {
    slidesPerView: 'auto', // ширину карточек задаёт CSS (var(--review-card-w))
    loop: true, // бесконечная прокрутка
    grabCursor: true, // перетаскивание мышью/пальцем
    navigation: {
      prevEl: '[data-reviews-prev]',
      nextEl: '[data-reviews-next]',
    },
    breakpoints: {
      0: { spaceBetween: 14 },
      720: { spaceBetween: 24 },
    },
  });

  // ─ Обрезка карточки ─
  // Карточка фиксированной высоты; кнопку «Читать полностью» и затенение
  // показываем только если текст реально не помещается (класс is-clamped).
  const COLLAPSED = 250; // синхронизировано с max-height в styles2.css
  const syncCollapsed = (review) => {
    review.classList.toggle('is-clamped', review.scrollHeight > COLLAPSED + 4);
  };
  const syncAll = () => el.querySelectorAll('[data-review]').forEach(syncCollapsed);
  syncAll();
  window.addEventListener('load', syncAll);
  window.addEventListener('resize', syncAll);

  // ─ Попап с полным текстом ─
  const modal = document.querySelector('[data-review-modal]');
  if (!modal) return;
  const mStars = modal.querySelector('[data-review-modal-stars]');
  const mText = modal.querySelector('[data-review-modal-text]');
  const mName = modal.querySelector('[data-review-modal-name]');
  const mDate = modal.querySelector('[data-review-modal-date]');

  const CLOSE_DUR = 260; // синхронно с анимацией reviewModalOut в styles2.css
  let closeTimer = null;

  const openModal = (review) => {
    if (closeTimer) {
      window.clearTimeout(closeTimer); // прервать незавершённое закрытие
      closeTimer = null;
    }
    modal.classList.remove('is-closing');
    const stars = review.querySelector('.review__stars');
    const text = review.querySelector('.review__text');
    const name = review.querySelector('.review__name');
    const date = review.querySelector('.review__date');
    if (mStars) mStars.textContent = stars ? stars.textContent : '';
    // innerHTML — сохраняем переносы <br>; контент наш, доверенный
    if (mText) mText.innerHTML = text ? text.innerHTML : '';
    if (mName) mName.textContent = name ? name.textContent : '';
    if (mDate) mDate.textContent = date ? date.textContent : '';
    modal.hidden = false;
    document.body.classList.add('is-modal-open');
  };

  const closeModal = () => {
    if (modal.hidden || modal.classList.contains('is-closing')) return;
    modal.classList.add('is-closing'); // запускает анимацию исчезновения
    closeTimer = window.setTimeout(() => {
      modal.hidden = true;
      modal.classList.remove('is-closing');
      document.body.classList.remove('is-modal-open');
    }, CLOSE_DUR);
  };

  // Клик по «Читать полностью» — делегирование, работает и на клонах Swiper
  el.addEventListener('click', (event) => {
    const toggle = event.target.closest('[data-review-toggle]');
    if (!toggle) return;
    const review = toggle.closest('[data-review]');
    if (review) openModal(review);
  });

  // Закрытие: оверлей, крестик, Esc
  modal.querySelectorAll('[data-review-modal-close]').forEach((btn) => btn.addEventListener('click', closeModal));
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !modal.hidden) closeModal();
  });
})();

// ─── FAQ: аккордеон ───────────────────────────────────────
document.querySelectorAll('[data-faq]').forEach((faq) => {
  const items = Array.from(faq.querySelectorAll('[data-faq-item]'));

  function close(item) {
    const answer = item.querySelector('[data-faq-answer]');
    const btn = item.querySelector('[data-faq-toggle]');
    item.classList.remove('is-open');
    if (btn) btn.setAttribute('aria-expanded', 'false');
    if (answer) answer.style.maxHeight = '0px';
  }

  function open(item) {
    const answer = item.querySelector('[data-faq-answer]');
    const btn = item.querySelector('[data-faq-toggle]');
    item.classList.add('is-open');
    if (btn) btn.setAttribute('aria-expanded', 'true');
    // scrollHeight отдаёт полную высоту ответа даже при overflow: hidden
    if (answer) answer.style.maxHeight = answer.scrollHeight + 'px';
  }

  items.forEach((item) => {
    const btn = item.querySelector('[data-faq-toggle]');
    if (!btn) return;
    btn.addEventListener('click', () => {
      const isOpen = item.classList.contains('is-open');
      // Один открытый пункт за раз
      items.forEach(close);
      if (!isOpen) open(item);
    });
  });

  // Открытый пункт мог поменять высоту при смене ширины — пересчитываем
  window.addEventListener('resize', () => {
    const openItem = items.find((i) => i.classList.contains('is-open'));
    if (!openItem) return;
    const answer = openItem.querySelector('[data-faq-answer]');
    if (answer) answer.style.maxHeight = answer.scrollHeight + 'px';
  });
});

// ─── Форма быстрого бронирования ──────────────────────────
document.querySelectorAll('[data-booking-form]').forEach((form) => {
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const btn = form.querySelector('button[type="submit"]');
    if (!btn) return;
    btn.disabled = true;
    btn.textContent = 'Проверяем даты…';
    // Здесь позже — переход в модуль бронирования (TravelLine / Bnovo и т.п.)
  });
});

// ─── Форма обратного звонка ───────────────────────────────
document.querySelectorAll('[data-callback-form]').forEach((form) => {
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const btn = form.querySelector('button[type="submit"]');
    if (!btn) return;
    btn.disabled = true;
    btn.textContent = 'Заявка отправлена';
    // Здесь позже — отправка на бэкенд/CRM
  });
});

// ─── Reveal при появлении в зоне видимости ────────────────
(() => {
  const targets = document.querySelectorAll('.reveal');

  // Fallback: если IntersectionObserver недоступен — показываем сразу
  if (!('IntersectionObserver' in window)) {
    targets.forEach((el) => el.classList.add('is-visible'));
    return;
  }

  const observer = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          obs.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.15 }
  );

  targets.forEach((el) => observer.observe(el));
})();

// ─── Интерактивная карта территории ───────────────────────
// Картинка-карта с перетаскиванием и зумом. Взаимодействие включается
// кнопкой «Активировать карту». Метки привязаны к точкам изображения
// (доля 0..1 в data-x/data-y) и позиционируются из JS — не «уезжают».
(() => {
  const view = document.querySelector('[data-map]');
  if (!view) return;
  const canvas = view.querySelector('[data-map-canvas]');
  const img = view.querySelector('.map__img');
  const activateBtn = view.querySelector('[data-map-activate]');
  const deactivateBtn = view.querySelector('[data-map-deactivate]');
  const markers = Array.from(view.querySelectorAll('[data-map-marker]'));
  if (!canvas || !img) return;

  let imgW = 0;
  let imgH = 0;
  let vpW = 0;
  let vpH = 0;
  let scale = 1;
  let minScale = 1;
  let maxScale = 1;
  let tx = 0;
  let ty = 0;
  let active = false;

  const positionMarkers = () => {
    markers.forEach((m) => {
      const fx = parseFloat(m.dataset.x) || 0;
      const fy = parseFloat(m.dataset.y) || 0;
      m.style.left = tx + fx * imgW * scale + 'px';
      m.style.top = ty + fy * imgH * scale + 'px';
    });
  };

  const apply = () => {
    canvas.style.transform = `translate(${tx}px, ${ty}px) scale(${scale})`;
    positionMarkers();
  };

  const clampTranslate = () => {
    const w = imgW * scale;
    const h = imgH * scale;
    tx = w <= vpW ? (vpW - w) / 2 : Math.min(0, Math.max(vpW - w, tx));
    ty = h <= vpH ? (vpH - h) / 2 : Math.min(0, Math.max(vpH - h, ty));
  };

  const measure = () => {
    if (!img.naturalWidth || !img.naturalHeight) return;
    imgW = img.naturalWidth;
    imgH = img.naturalHeight;
    vpW = view.clientWidth;
    vpH = view.clientHeight;
    canvas.style.width = imgW + 'px';
    canvas.style.height = imgH + 'px';
    minScale = Math.max(vpW / imgW, vpH / imgH); // «cover» — карта всегда закрывает вьюпорт
    maxScale = minScale * 4;
    scale = minScale;
    tx = (vpW - imgW * scale) / 2;
    ty = (vpH - imgH * scale) / 2;
    clampTranslate();
    apply();
  };

  const zoomAt = (cx, cy, factor) => {
    const next = Math.min(maxScale, Math.max(minScale, scale * factor));
    if (next === scale) return;
    // точка под курсором остаётся на месте
    tx = cx - ((cx - tx) / scale) * next;
    ty = cy - ((cy - ty) / scale) * next;
    scale = next;
    clampTranslate();
    apply();
  };

  const setActive = (v) => {
    active = v;
    view.classList.toggle('is-active', v);
  };
  if (activateBtn) activateBtn.addEventListener('click', () => setActive(true));
  if (deactivateBtn) deactivateBtn.addEventListener('click', () => setActive(false));

  // Зум колесом — только при активной карте (иначе не мешаем скроллу страницы)
  view.addEventListener(
    'wheel',
    (e) => {
      if (!active) return;
      e.preventDefault();
      const rect = view.getBoundingClientRect();
      const factor = e.deltaY < 0 ? 1.15 : 1 / 1.15;
      zoomAt(e.clientX - rect.left, e.clientY - rect.top, factor);
    },
    { passive: false }
  );

  // Перетаскивание (мышь/палец) и пинч — на Pointer Events
  const pointers = new Map();
  let last = null;
  let pinchDist = 0;

  view.addEventListener('pointerdown', (e) => {
    if (!active) return;
    // клик по метке или кнопке не должен начинать перетаскивание:
    // захват указателя на .map перебил бы их click (и кнопка не срабатывала бы)
    if (e.target.closest('[data-map-marker], [data-map-activate], [data-map-deactivate]')) return;
    view.setPointerCapture(e.pointerId);
    pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });
    view.classList.add('is-grabbing');
    if (pointers.size === 1) {
      last = { x: e.clientX, y: e.clientY };
    } else if (pointers.size === 2) {
      const p = [...pointers.values()];
      pinchDist = Math.hypot(p[0].x - p[1].x, p[0].y - p[1].y);
    }
  });

  view.addEventListener('pointermove', (e) => {
    if (!active || !pointers.has(e.pointerId)) return;
    pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });
    const rect = view.getBoundingClientRect();
    if (pointers.size === 1 && last) {
      tx += e.clientX - last.x;
      ty += e.clientY - last.y;
      last = { x: e.clientX, y: e.clientY };
      clampTranslate();
      apply();
    } else if (pointers.size === 2) {
      const p = [...pointers.values()];
      const dist = Math.hypot(p[0].x - p[1].x, p[0].y - p[1].y);
      const midX = (p[0].x + p[1].x) / 2 - rect.left;
      const midY = (p[0].y + p[1].y) / 2 - rect.top;
      if (pinchDist > 0) zoomAt(midX, midY, dist / pinchDist);
      pinchDist = dist;
    }
  });

  const endPointer = (e) => {
    pointers.delete(e.pointerId);
    if (pointers.size < 2) pinchDist = 0;
    if (pointers.size === 1) {
      const p = [...pointers.values()][0];
      last = { x: p.x, y: p.y };
    }
    if (pointers.size === 0) {
      last = null;
      view.classList.remove('is-grabbing');
    }
  };
  view.addEventListener('pointerup', endPointer);
  view.addEventListener('pointercancel', endPointer);

  // ─ Попап метки ─
  const modal = document.querySelector('[data-map-modal]');
  let mMapCloseTimer = null;
  const openMarker = (marker) => {
    if (!modal) return;
    const mImg = modal.querySelector('[data-map-modal-img]');
    const mTitle = modal.querySelector('[data-map-modal-title]');
    const mDesc = modal.querySelector('[data-map-modal-desc]');
    const mPrice = modal.querySelector('[data-map-modal-price]');
    if (mMapCloseTimer) {
      clearTimeout(mMapCloseTimer);
      mMapCloseTimer = null;
    }
    modal.classList.remove('is-closing');
    if (mImg) {
      mImg.src = marker.dataset.img || '';
      mImg.alt = marker.dataset.title || '';
    }
    if (mTitle) mTitle.textContent = marker.dataset.title || '';
    if (mDesc) mDesc.textContent = marker.dataset.desc || '';
    if (mPrice) mPrice.textContent = marker.dataset.price || '';
    modal.hidden = false;
    document.body.classList.add('is-modal-open');
  };

  const closeMarker = () => {
    if (!modal || modal.hidden || modal.classList.contains('is-closing')) return;
    modal.classList.add('is-closing');
    mMapCloseTimer = setTimeout(() => {
      modal.hidden = true;
      modal.classList.remove('is-closing');
      document.body.classList.remove('is-modal-open');
    }, 260);
  };

  markers.forEach((m) => {
    m.addEventListener('click', (e) => {
      e.preventDefault();
      openMarker(m);
    });
  });

  if (modal) {
    modal.querySelectorAll('[data-map-modal-close]').forEach((b) => b.addEventListener('click', closeMarker));
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !modal.hidden) closeMarker();
    });
  }

  // Инициализация размеров карты
  if (img.complete && img.naturalWidth) measure();
  else img.addEventListener('load', measure);
  window.addEventListener('load', measure);
  window.addEventListener('resize', measure);
})();