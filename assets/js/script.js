/* ===== ЗАБЛОКИРОВАТЬ СКРОЛЛ ПРИ ОТКРЫТЫХ ПОПАПАХ ===== */
(function () {
  let scrollY = 0;
  const body = document.body;
  let lockCount = 0;

  window.__lockScroll = function () {
    scrollY = window.scrollY;
    body.style.position = 'fixed';
    body.style.top = '-' + scrollY + 'px';
    body.style.left = '0';
    body.style.right = '0';
    body.style.overflow = 'hidden';
    lockCount++;
  };

  window.__unlockScroll = function () {
    lockCount--;
    if (lockCount <= 0) {
      lockCount = 0;
      body.style.position = '';
      body.style.top = '';
      body.style.left = '';
      body.style.right = '';
      body.style.overflow = '';
      // Мгновенный возврат без анимации
      window.scrollTo(0, scrollY);
      scrollY = 0;
    }
  };
})();



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
  const nav = document.getElementById('nav');

  const setOpen = (open) => {
    burger.classList.toggle('is-open', open);
    burger.setAttribute('aria-expanded', String(open));
    menu.hidden = !open;
    // Открытое меню — красим шапку так же, как при скролле
    if (nav) nav.classList.toggle('is-menu-open', open);
  };

  burger.addEventListener('click', () => {
    setOpen(menu.hidden);
  });

  // Закрываем меню после клика по любой ссылке
  menu.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => setOpen(false));
  });
})();

// ─── Hero: смена заголовка каждые 2 секунды ───────────────
(() => {
  const title = document.getElementById('heroTitle');
  if (!title) return;

  const slides = Array.from(title.querySelectorAll('.hero__title-slide'));
  if (slides.length < 2) return;

  const INTERVAL = 3000;
  let current = slides.findIndex((s) => s.classList.contains('is-active'));
  if (current < 0) current = 0;

  const show = (index) => {
    slides.forEach((slide, i) => {
      const active = i === index;
      slide.classList.toggle('is-active', active);
      slide.setAttribute('aria-hidden', String(!active));
    });
  };

  show(current);

  let timer = null;
  const start = () => {
    if (timer) return;
    timer = setInterval(() => {
      current = (current + 1) % slides.length;
      show(current);
    }, INTERVAL);
  };

  start();

  // На скрытой вкладке смысла крутить нет
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
      clearInterval(timer);
      timer = null;
    } else {
      start();
    }
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
    summer: ['Рыбалка', 'Лодки и сапборды', 'Катамараны', 'Квадроциклы', 'Велосипеды', 'Детские самокаты', 'Волейбол', 'Баскетбол', 'Футбол', 'Бадминтон', 'Настольные игры'],
    winter: ['Лыжи', 'Тюбинги', 'Снегоходы', 'Прогулки по лесу', 'Коньки'],
    year: ['Мероприятия', 'Настольные игры', 'Прогулки', 'Уличные тренажеры', 'Детская площадка', 'Шахматы, шашки', 'Скандинавская ходьба', 'Настольный теннис', 'Мастер-классы'],
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
    'Рыбалка ': 'fish',
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
    summer: '/wp-content/themes/theme/assets/img/imgs/matthew-mcbrayer-mO-.jpg.jpeg',
    winter: '/wp-content/themes/theme/assets/img/imgs/IMG_0632-2.jpg.jpeg',
    year: '/wp-content/themes/theme/assets/img/imgs/photo.jpg_2.jpeg',
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
  const OFFSET_AFTER = 32; // «воздух» после последнего слайда до правого края экрана

  const sw = new Swiper(el, {
    slidesPerView: 'auto', // ширину карточек задаёт CSS → справа слайд «торчит»
    spaceBetween: 24,
    grabCursor: true, // курсор-«хват» + перетаскивание мышью/пальцем
    slidesOffsetBefore: offsetBefore(),
    slidesOffsetAfter: OFFSET_AFTER,
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

// ─── Карточка услуги: переключение фото внутри карточки ────
(() => {
  document.querySelectorAll('[data-ocard-slider]').forEach((card) => {
    const shots = Array.from(card.querySelectorAll('.ocard-shots__img'));
    if (shots.length < 2) return;

    const dotsEl = card.querySelector('[data-ocard-dots]');

    // Стартовое фото задаётся в разметке классом is-active
    let current = Math.max(
      0,
      shots.findIndex((s) => s.classList.contains('is-active'))
    );
    let dots = [];

    const show = (index) => {
      current = (index + shots.length) % shots.length;
      shots.forEach((s, i) => s.classList.toggle('is-active', i === current));
      dots.forEach((d, i) => {
        d.classList.toggle('is-active', i === current);
        d.setAttribute('aria-current', String(i === current));
      });
    };

    // Точки собираем из данных фото — их количество всегда совпадает
    dots = shots.map((shot, i) => {
      const dot = document.createElement('button');
      dot.type = 'button';
      dot.className = 'ocard-shots__dot';
      dot.setAttribute('aria-label', shot.dataset.label || `Фото ${i + 1}`);
      dot.addEventListener('click', () => show(i));
      if (dotsEl) dotsEl.appendChild(dot);
      return dot;
    });

    card.querySelectorAll('[data-ocard-prev]').forEach((b) => b.addEventListener('click', () => show(current - 1)));
    card.querySelectorAll('[data-ocard-next]').forEach((b) => b.addEventListener('click', () => show(current + 1)));

    show(current);
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
          img: '/wp-content/themes/theme/assets/img/imgs/pool_6x18.jpg',
          title: 'Бассейн 6×18',
          desc: 'Просторный бассейн с комфортной температурой воды, финской сауной и душевыми',
          // desc: 'Главная чаша спа-центра: просторная вода, финская сауна и зона отдыха рядом — можно провести здесь весь вечер.',
          details: [
            'Финская сауна',
            'Душевые',
            'Зона отдыха',
            'Посещение уличного бассейна в подарок (сезонно)',
            'Температура воды 29–33 °C',
          ],
        },
        {
          img: '/wp-content/themes/theme/assets/img/imgs/pool_outdoor.JPG',
          title: 'Уличный бассейн',
          desc: 'Подогреваемый бассейн под открытым небом. Работает в тёплое время года и входит в посещение бассейна 6×18.',
          details: [
            'Зона отдыха с шезлонгами и зонтиками',
            'Водопад',
            'Подводный массаж',
            'Подогрев',
            'Работает в тёплый сезон',
          ],
        },
        {
          img: '/wp-content/themes/theme/assets/img/imgs/pool_10x4.jpg',
          title: 'Бассейн 10×4',
          desc: 'Камерный бассейн с водопадом и турецкой парной — формат для спокойного восстановления без суеты.',
          details: ['Водопад', 'Турецкая паровая', 'Комната отдыха', 'Температура воды 29–33 °C', 'Противоток (доп. услуга)'],
        },
        {
          img: '/wp-content/themes/theme/assets/img/imgs/pool_afrodita.jpg',
          title: 'Бассейн 6×3 «Афродита»',
          desc: 'Самая насыщенная зона комплекса: джакузи, хамам и паровая в одном пространстве.',
          details: [
            'Джакузи на 75 форсунок',
            'Подводный массаж',
            'Турецкая паровая',
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
    document.window.__lockScroll();
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

// ─── Маска телефона ───────────────────────────────────────
// Вешается на input[data-phone-mask]. Формат +7 (999) 999-99-99:
// в значении храним только цифры, а вид собираем при каждом вводе.
(() => {
  const fields = document.querySelectorAll('[data-phone-mask]');
  if (!fields.length) return;

  const digitsOf = (value) => {
    let d = value.replace(/\D/g, '');
    if (d.startsWith('8')) d = '7' + d.slice(1); // 8·· → 7··
    if (!d.startsWith('7')) d = '7' + d;
    return d.slice(0, 11); // 7 + 10 цифр номера
  };

  const format = (digits) => {
    const n = digits.slice(1); // без кода страны
    let out = '+7';
    // Разделитель добавляем строго перед следующей цифрой, а не сразу
    // после предыдущей группы: иначе на конце висит «)» или «-»,
    // и Backspace приходится жать по три раза, чтобы стереть цифру
    if (n.length > 0) out += ' (' + n.slice(0, 3);
    if (n.length > 3) out += ') ' + n.slice(3, 6);
    if (n.length > 6) out += '-' + n.slice(6, 8);
    if (n.length > 8) out += '-' + n.slice(8, 10);
    return out;
  };

  fields.forEach((input) => {
    const render = () => {
      input.value = input.value.trim() ? format(digitsOf(input.value)) : '';
      // курсор всегда в конец: посимвольное позиционирование внутри
      // маски даёт больше проблем, чем пользы
      const end = input.value.length;
      input.setSelectionRange(end, end);
    };

    input.addEventListener('input', render);
    input.addEventListener('focus', () => {
      if (!input.value) input.value = '+7 (';
    });
    input.addEventListener('blur', () => {
      if (input.value === '+7 (' || input.value === '+7') input.value = '';
    });
  });
})();

// ─── Валидация форм ───────────────────────────────────────
// Включается атрибутом data-validate. Сообщение об ошибке кладём в
// [data-error-for="<id или name поля>"], поле помечаем классом.
(() => {
  const forms = document.querySelectorAll('form[data-validate]');
  if (!forms.length) return;

  const messageFor = (field) => {
    const value = (field.value || '').trim();
    if (field.hasAttribute('required') && !value) return 'Заполните это поле';
    if (field.type === 'tel' && value.replace(/\D/g, '').length !== 11) return 'Введите номер полностью';
    if (field.type === 'email' && value && !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(value)) return 'Проверьте адрес почты';
    const min = Number(field.getAttribute('minlength')) || 0;
    if (min && value.length < min) return 'Минимум ' + min + ' символов';
    return '';
  };

  forms.forEach((form) => {
    const setError = (key, text, field) => {
      const box = form.querySelector('[data-error-for="' + key + '"]');
      if (box) box.textContent = text;
      if (field) field.classList.toggle('is-invalid', Boolean(text));
    };

    const check = (field) => {
      const text = messageFor(field);
      setError(field.id || field.name, text, field);
      return !text;
    };

    // Показываем ошибку только после первой попытки отправки,
    // иначе поле краснеет ещё до того, как его начали заполнять
    let submitted = false;
    form.querySelectorAll('input, textarea, select').forEach((field) => {
      field.addEventListener('input', () => submitted && check(field));
      field.addEventListener('blur', () => submitted && check(field));
    });

    form.addEventListener(
      'submit',
      (event) => {
        submitted = true;
        let firstBad = null;

        form.querySelectorAll('input:not([type="radio"]), textarea, select').forEach((field) => {
          if (!check(field) && !firstBad) firstBad = field;
        });

        // Группы радиокнопок (оценка) проверяем отдельно — по имени
        const groups = new Set([...form.querySelectorAll('input[type="radio"][required]')].map((r) => r.name));
        groups.forEach((name) => {
          const chosen = form.querySelector('input[name="' + name + '"]:checked');
          setError(name, chosen ? '' : 'Поставьте оценку');
          if (!chosen && !firstBad) firstBad = form.querySelector('input[name="' + name + '"]');
        });

        if (firstBad) {
          event.preventDefault();
          event.stopImmediatePropagation(); // не пускаем к обработчику отправки
          firstBad.focus();
        }
      },
      true // capture: валидация должна отработать раньше отправки
    );
  });
})();

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
    document.window.__lockScroll();
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







(function() {
  'use strict';

  var modal = document.getElementById('site-modal-callback');
  if (!modal) return;

  /* — Открытие — */
  function open() {
    modal.removeAttribute('hidden');
    modal.classList.remove('is-closing');
    document.window.__lockScroll();
    /* Сбросить состояние */
    document.getElementById('callback-form-wrap').style.display = '';
    document.getElementById('callback-success').classList.remove('is-active');
    document.getElementById('site-callback-form').reset();
  }

  /* — Закрытие — */
  function close() {
    if (!modal || modal.hasAttribute('hidden')) return;
    modal.classList.add('is-closing');
    document.body.classList.remove('is-modal-open');

    function handler(e) {
      if (
        e.target.classList.contains('site-modal__dialog') ||
        e.target.classList.contains('site-modal__overlay')
      ) {
        modal.setAttribute('hidden', '');
        modal.classList.remove('is-closing');
        modal.removeEventListener('animationend', handler);
      }
    }

    modal.addEventListener('animationend', handler);
  }

  /* — Клик по кнопкам с классом — */
  document.addEventListener('click', function(e) {
    var trigger = e.target.closest('.js-open-modal-callback');
    if (trigger) {
      e.preventDefault();
      open();
      return;
    }
  });

  /* — Закрытие: оверлей / крестик — */
  modal.addEventListener('click', function(e) {
    var closeBtn = e.target.closest('.site-modal__close');
    if (closeBtn) { close(); return; }

    var overlay = e.target.closest('.site-modal__overlay');
    if (overlay) { close(); return; }
  });

  /* — Escape — */
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !modal.hasAttribute('hidden')) close();
  });

  /* — Отправка формы — */
  document.getElementById('site-callback-form').addEventListener('submit', function(e) {
    e.preventDefault();

    var name  = document.getElementById('cb-name').value.trim();
    var phone = document.getElementById('cb-phone').value.trim();

    if (!name || !phone) {
      if (!name)  document.getElementById('cb-name').style.borderColor = '#d44';
      if (!phone) document.getElementById('cb-phone').style.borderColor = '#d44';
      return;
    }

    /* Здесь можно добавить отправку на сервер */

    /* Показать успех */
    document.getElementById('callback-form-wrap').style.display = 'none';
    document.getElementById('callback-success').classList.add('is-active');
  });
})();


(function() {
  'use strict';

  var modal = document.getElementById('site-modal-booking');
  if (!modal) return;

  /* — Открытие — */
  function open() {
    modal.removeAttribute('hidden');
    modal.classList.remove('is-closing');
    document.window.__lockScroll();
    /* Сбросить состояние */
    document.getElementById('booking-form-wrap').style.display = '';
    document.getElementById('booking-success').classList.remove('is-active');
    document.getElementById('site-booking-form').reset();
  }

  /* — Закрытие — */
  function close() {
    if (!modal || modal.hasAttribute('hidden')) return;
    modal.classList.add('is-closing');
    document.body.classList.remove('is-modal-open');

    function handler(e) {
      if (
        e.target.classList.contains('site-modal__dialog') ||
        e.target.classList.contains('site-modal__overlay')
      ) {
        modal.setAttribute('hidden', '');
        modal.classList.remove('is-closing');
        modal.removeEventListener('animationend', handler);
      }
    }

    modal.addEventListener('animationend', handler);
  }

  /* — Клик по кнопкам с классом — */
  document.addEventListener('click', function(e) {
    var trigger = e.target.closest('.js-open-modal-booking');
    if (trigger) {
      e.preventDefault();
      open();
      return;
    }
  });

  /* — Закрытие: оверлей / крестик — */
  modal.addEventListener('click', function(e) {
    var closeBtn = e.target.closest('.site-modal__close');
    if (closeBtn) { close(); return; }

    var overlay = e.target.closest('.site-modal__overlay');
    if (overlay) { close(); return; }
  });

  /* — Escape — */
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !modal.hasAttribute('hidden')) close();
  });

  /* — Отправка формы — */
  document.getElementById('site-booking-form').addEventListener('submit', function(e) {
    e.preventDefault();

    var name  = document.getElementById('bk-name').value.trim();
    var phone = document.getElementById('bk-phone').value.trim();

    if (!name || !phone) {
      if (!name)  document.getElementById('bk-name').style.borderColor = '#d44';
      if (!phone) document.getElementById('bk-phone').style.borderColor = '#d44';
      return;
    }

    /* Здесь можно добавить отправку на сервер */
    /* Например: fetch('/api/booking', { method: 'POST', body: new FormData(this) }) */

    /* Показать успех */
    document.getElementById('booking-form-wrap').style.display = 'none';
    document.getElementById('booking-success').classList.add('is-active');
  });
})();



(function () {
        try {
          if (sessionStorage.getItem("kpLoaderShown")) {
            document.documentElement.classList.add("loader-skip");
            return;
          }
        } catch (e) {}
        document.documentElement.classList.add("is-loading");
      })();


      // Управление лоадером. Стоит сразу после разметки → стартует немедленно,
      // не дожидаясь Swiper с CDN и script2.js. Чистый ванильный JS.
      (function () {
        var root = document.documentElement;
        if (!root.classList.contains("is-loading")) return; // повторный визит — пропуск
        try {
          sessionStorage.setItem("kpLoaderShown", "1");
        } catch (e) {}

        var loader = document.getElementById("site-loader");
        if (!loader) {
          root.classList.remove("is-loading");
          return;
        }
        var fill = loader.querySelector(".loader__bar-fill");
        var start = Date.now();
        var MIN = 600; // минимум показа, чтобы не мигал на быстрой загрузке
        var MAX = 7000; // фолбэк, если ресурс завис
        var progress = 0;
        var raf;

        function tick() {
          // асимптотически подползаем к 90% — до 100% доходим на window.load
          progress += (90 - progress) * 0.02;
          if (fill) fill.style.width = progress.toFixed(1) + "%";
          if (progress < 89.5) raf = requestAnimationFrame(tick);
        }
        tick();

        var hidden = false;
        function hide() {
          if (hidden) return;
          hidden = true;
          if (raf) cancelAnimationFrame(raf);
          if (fill) {
            fill.style.transition = "width .3s ease";
            fill.style.width = "100%";
          }
          var wait = Math.max(0, MIN - (Date.now() - start));
          setTimeout(function () {
            loader.classList.add("is-done"); // плавное затухание (CSS)
            root.classList.remove("is-loading"); // разблокировать скролл
            setTimeout(function () {
              loader.style.display = "none";
            }, 650); // после CSS-перехода 0.6s
          }, wait);
        }

        window.addEventListener("load", hide);
        setTimeout(hide, MAX);
      })();






      // ─── Фильтр объектов размещения ─────────────────────────
      // Две независимые группы чипов: формат (эко/гостиница/дома)
      // и аудитория (семья/пара/компания). Карточка показывается,
      // если подходит под обе выбранные группы.
      (() => {
        const grid = document.querySelector("[data-acc-grid]");
        const groups = Array.from(document.querySelectorAll("[data-acc-filter]"));
        if (!grid || !groups.length) return;

        const cards = Array.from(grid.querySelectorAll(".acc-card"));
        const empty = document.querySelector("[data-acc-empty]");

        // Активный выбор в каждой группе: null — фильтр не задан
        const state = groups.map(() => null);

        const apply = () => {
          let shown = 0;

          cards.forEach((card) => {
            const type = card.dataset.type;
            const audience = (card.dataset.audience || "").split(" ");

            const ok = state.every((value) => value === null || value === "all" || value === type || audience.includes(value));

            card.hidden = !ok;
            if (ok) shown++;
          });

          if (empty) empty.hidden = shown > 0;
        };

        groups.forEach((group, index) => {
          const chips = Array.from(group.querySelectorAll("[data-filter]"));

          chips.forEach((chip) => {
            chip.addEventListener("click", () => {
              // Повторный клик по активному чипу снимает фильтр группы
              const off = state[index] === chip.dataset.filter;
              state[index] = off ? null : chip.dataset.filter;

              chips.forEach((c) => {
                const on = !off && c === chip;
                c.classList.toggle("is-active", on);
                c.setAttribute("aria-pressed", String(on));
              });

              apply();
            });
          });
        });

        apply();
      })();

      // ─── Подробности о доме в попапе ────────────────────────
      // Содержимое берём из скрытого [data-acc-details] карточки —
      // так же, как попап полного отзыва на главной.
/*      (() => {
        const modal = document.querySelector("[data-acc-modal]");
        if (!modal) return;

        const photo = modal.querySelector("[data-acc-modal-photo]");
        const badge = modal.querySelector("[data-acc-modal-badge]");
        const title = modal.querySelector("[data-acc-modal-title]");
        const meta = modal.querySelector("[data-acc-modal-meta]");
        const details = modal.querySelector("[data-acc-modal-details]");
        const price = modal.querySelector("[data-acc-modal-price]");
        const dialog = modal.querySelector(".acc-modal__dialog");

        const CLOSE_DUR = 260; // синхронно с анимацией в accommodation.css
        let closeTimer = null;
        let lastTrigger = null;

        const open = (card, trigger) => {
          if (closeTimer) {
            window.clearTimeout(closeTimer); // прервать незавершённое закрытие
            closeTimer = null;
          }
          modal.classList.remove("is-closing");

          const img = card.querySelector(".acc-card__media img");
          if (photo && img) {
            photo.src = img.getAttribute("src");
            photo.alt = img.getAttribute("alt") || "";
          }

          const pick = (sel) => card.querySelector(sel);
          if (badge) badge.textContent = pick(".acc-card__badge")?.textContent || "";
          if (title) title.textContent = pick(".acc-card__title")?.textContent || "";
          if (meta) meta.textContent = pick(".acc-card__meta")?.textContent || "";
          if (price) price.innerHTML = pick(".acc-card__price")?.innerHTML || "";
          // innerHTML — сохраняем списки и <strong>; контент наш, доверенный
          if (details) details.innerHTML = pick("[data-acc-details]")?.innerHTML || "";

          lastTrigger = trigger;
          modal.hidden = false;
          document.body.classList.add("is-modal-open");
          if (dialog) {
            dialog.scrollTop = 0;
            dialog.focus();
          }
        };

        const close = (restoreFocus = true) => {
          if (modal.hidden || modal.classList.contains("is-closing")) return;
          modal.classList.add("is-closing"); // запускает анимацию исчезновения
          closeTimer = window.setTimeout(() => {
            modal.hidden = true;
            modal.classList.remove("is-closing");
            document.body.classList.remove("is-modal-open");
            if (restoreFocus && lastTrigger) lastTrigger.focus(); // фокус обратно на кнопку
          }, CLOSE_DUR);
        };

        document.querySelectorAll("[data-acc-more]").forEach((btn) => {
          const card = btn.closest(".acc-card");
          if (!card) return;
          btn.addEventListener("click", () => open(card, btn));
        });

        modal.querySelectorAll("[data-acc-modal-close]").forEach((b) => b.addEventListener("click", () => close()));

        // «Забронировать» ведёт к форме: прокрутку разблокируем сразу,
        // иначе переход по якорю упрётся в body с overflow: hidden,
        // и фокус на кнопку не возвращаем — он уехал бы обратно к карточке
        modal.querySelector("[data-acc-modal-book]")?.addEventListener("click", () => {
          document.body.classList.remove("is-modal-open");
          close(false);
        });

        document.addEventListener("keydown", (e) => {
          if (e.key === "Escape" && !modal.hidden) close();
        });
      })();
*/


      /* ═══════════════════════════════════════════════
   ПОПАПЫ SPA-ПРОГРАММ
   ═══════════════════════════════════════════════ */
(function () {
  'use strict';

  var ESC = 'Escape';

  function openModal(id) {
    var modal = document.getElementById(id);
    if (!modal) return;
    modal.hidden = false;
    modal.classList.remove('is-closing');
    document.window.__lockScroll();
  }

  function closeModal(modal) {
    if (!modal || modal.hidden) return;
    modal.classList.add('is-closing');
    document.body.classList.remove('is-modal-open');
    modal.addEventListener('animationend', function handler(e) {
      if (e.target === modal.querySelector('.spa-prog-modal__dialog') ||
          e.target === modal.querySelector('.spa-prog-modal__overlay')) {
        modal.hidden = true;
        modal.classList.remove('is-closing');
        modal.removeEventListener('animationend', handler);
      }
    });
  }

  /* Клик по кнопкам «Подробнее» в SPA-программах */
  document.addEventListener('click', function (e) {
    var trigger = e.target.closest('[data-spa-modal]');
    if (!trigger) return;
    e.preventDefault();
    openModal(trigger.getAttribute('data-spa-modal'));
  });

  /* Клик по оверлею или кресту */
  document.addEventListener('click', function (e) {
    var closer = e.target.closest('[data-spa-modal-close]');
    if (!closer) return;
    var modal = closer.closest('.spa-prog-modal');
    if (modal) closeModal(modal);
  });

  /* Escape */
  document.addEventListener('keydown', function (e) {
    if (e.key !== ESC) return;
    var modal = document.querySelector('.spa-prog-modal:not([hidden])');
    if (modal) closeModal(modal);
  });
})();





/* ═══════════════════════════════════════════════
   ПОПАПЫ — Виды бань
   ═══════════════════════════════════════════════ */
(function () {
  'use strict';
  var ESC = 'Escape';

  function openModal(id) {
    var modal = document.getElementById(id);
    if (!modal) return;
    modal.hidden = false;
    modal.classList.remove('is-closing');
    document.window.__lockScroll();
  }

  function closeModal(modal) {
    if (!modal || modal.hidden) return;
    modal.classList.add('is-closing');
    document.body.classList.remove('is-modal-open');
    modal.addEventListener('animationend', function handler(e) {
      if (e.target === modal.querySelector('.bath-modal__dialog') ||
          e.target === modal.querySelector('.bath-modal__overlay')) {
        modal.hidden = true;
        modal.classList.remove('is-closing');
        modal.removeEventListener('animationend', handler);
      }
    });
  }

  document.addEventListener('click', function (e) {
    var trigger = e.target.closest('[data-bath-modal]');
    if (!trigger) return;
    e.preventDefault();
    openModal(trigger.getAttribute('data-bath-modal'));
  });

  document.addEventListener('click', function (e) {
    var closer = e.target.closest('[data-bath-modal-close]');
    if (!closer) return;
    var modal = closer.closest('.bath-modal');
    if (modal) closeModal(modal);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key !== ESC) return;
    var modal = document.querySelector('.bath-modal:not([hidden])');
    if (modal) closeModal(modal);
  });
})();

// Галереи в detail-секциях
    (function() {
      var galleries = document.querySelectorAll('[data-bath-gallery]');
      galleries.forEach(function(gal) {
        var track = gal.querySelector('.c-bath-detail__gallery-track');
        var slides = gal.querySelectorAll('.c-bath-detail__gallery-slide');
        var prevBtn = gal.querySelector('.c-bath-detail__gallery-prev');
        var nextBtn = gal.querySelector('.c-bath-detail__gallery-next');
        var current = 0;

        function showSlide(index) {
          if (index < 0) index = slides.length - 1;
          if (index >= slides.length) index = 0;
          current = index;
          track.style.transform = 'translateX(-' + (current * 100) + '%)';
        }

        if (prevBtn) prevBtn.addEventListener('click', function() { showSlide(current - 1); });
        if (nextBtn) nextBtn.addEventListener('click', function() { showSlide(current + 1); });
      });
    })();



    /* ═══════════════════════════════════════════════
   МОДАЛЬНЫЕ ОКНА — Dining page
   ═══════════════════════════════════════════════ */

(function() {
  'use strict';

  function openModal(id) {
    var modal = document.getElementById(id);
    if (!modal) return;
    modal.removeAttribute('hidden');
    modal.classList.remove('is-closing');
    document.window.__lockScroll();
  }

  function closeModal(modal) {
    if (!modal || modal.hasAttribute('hidden')) return;
    modal.classList.add('is-closing');
    document.body.classList.remove('is-modal-open');

    function handler(e) {
      if (
        e.target.classList.contains('dining-modal__dialog') ||
        e.target.classList.contains('dining-modal__overlay')
      ) {
        modal.setAttribute('hidden', '');
        modal.classList.remove('is-closing');
        modal.removeEventListener('animationend', handler);
      }
    }

    modal.addEventListener('animationend', handler);
  }

  document.addEventListener('click', function(e) {
    /* Открытие */
    var trigger = e.target.closest('[data-dining-modal]');
    if (trigger) {
      e.preventDefault();
      openModal(trigger.getAttribute('data-dining-modal'));
      return;
    }

    /* Закрытие — только оверлей или кнопка ✕ */
    var closeBtn = e.target.closest('.dining-modal__close');
    if (closeBtn) {
      var modal = closeBtn.closest('.dining-modal');
      if (modal) closeModal(modal);
      return;
    }

    var overlay = e.target.closest('.dining-modal__overlay');
    if (overlay) {
      var modal2 = overlay.closest('.dining-modal');
      if (modal2) closeModal(modal2);
      return;
    }
  });

  /* Escape */
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      var active = document.querySelector('.dining-modal:not([hidden])');
      if (active) closeModal(active);
    }
  });
})();


// Season chips filter (уникально для страницы кафе)
    (function() {
      var chips = document.querySelectorAll('.c-act-seasons__chip');
      var groups = document.querySelectorAll('[data-season-group]');
      chips.forEach(function(chip) {
        chip.addEventListener('click', function() {
          chips.forEach(function(c) { c.classList.remove('is-active'); });
          chip.classList.add('is-active');
          var season = chip.getAttribute('data-season');
          groups.forEach(function(g) {
            if (season === 'all') {
              g.style.display = '';
            } else {
              g.style.display = g.getAttribute('data-season-group') === season ? '' : 'none';
            }
          });
        });
      });
    })();




        // Season chips filter (уникально для страницы активный отдых)
    (function() {
      var chips = document.querySelectorAll('.c-act-seasons__chip');
      var groups = document.querySelectorAll('[data-season-group]');
      chips.forEach(function(chip) {
        chip.addEventListener('click', function() {
          chips.forEach(function(c) { c.classList.remove('is-active'); });
          chip.classList.add('is-active');
          var season = chip.getAttribute('data-season');
          groups.forEach(function(g) {
            if (season === 'all') {
              g.style.display = '';
            } else {
              g.style.display = g.getAttribute('data-season-group') === season ? '' : 'none';
            }
          });
        });
      });
    })();

    (function() {
      var items = document.querySelectorAll('.c-act-rules__item');
      items.forEach(function(item) {
        var btn = item.querySelector('.c-act-rules__q');
        btn.addEventListener('click', function() {
          var expanded = btn.getAttribute('aria-expanded') === 'true';
          items.forEach(function(i) { i.querySelector('.c-act-rules__q').setAttribute('aria-expanded', 'false'); });
          btn.setAttribute('aria-expanded', !expanded);
        });
      });
    })();

    /* ═══════════════════════════════════════════════
   ПОПАПЫ «ПОДРОБНЕЕ» — Активный отдых
   ═══════════════════════════════════════════════ */
(function () {
  'use strict';

  const ESC = 'Escape';

  /* Открыть попап по ID */
  function openModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.hidden = false;
    modal.classList.remove('is-closing');
    document.window.__lockScroll();
    /* Фокус на диалог для доступности */
    const dialog = modal.querySelector('.act-modal__dialog');
    if (dialog) dialog.focus();
  }

  /* Закрыть с анимацией */
  function closeModal(modal) {
    if (!modal || modal.hidden) return;
    modal.classList.add('is-closing');
    document.body.classList.remove('is-modal-open');
    modal.addEventListener('animationend', function handler(e) {
      if (e.target === modal.querySelector('.act-modal__dialog') ||
          e.target === modal.querySelector('.act-modal__overlay')) {
        modal.hidden = true;
        modal.classList.remove('is-closing');
        modal.removeEventListener('animationend', handler);
      }
    });
  }

  /* Клик по кнопкам «Подробнее» */
  document.addEventListener('click', function (e) {
    const trigger = e.target.closest('[data-modal]');
    if (!trigger) return;
    e.preventDefault();
    openModal(trigger.getAttribute('data-modal'));
  });

  /* Клик по оверлею или кнопке закрытия */
  document.addEventListener('click', function (e) {
    const closer = e.target.closest('[data-modal-close]');
    if (!closer) return;
    const modal = closer.closest('.act-modal');
    if (modal) closeModal(modal);
  });

  /* Escape */
  document.addEventListener('keydown', function (e) {
    if (e.key !== ESC) return;
    const modal = document.querySelector('.act-modal:not([hidden])');
    if (modal) closeModal(modal);
  });
})();




// Галереи в detail-секциях
    (function() {
      var galleries = document.querySelectorAll('[data-bath-gallery]');
      galleries.forEach(function(gal) {
        var track = gal.querySelector('.c-bath-detail__gallery-track');
        var slides = gal.querySelectorAll('.c-bath-detail__gallery-slide');
        var prevBtn = gal.querySelector('.c-bath-detail__gallery-prev');
        var nextBtn = gal.querySelector('.c-bath-detail__gallery-next');
        var current = 0;

        function showSlide(index) {
          if (index < 0) index = slides.length - 1;
          if (index >= slides.length) index = 0;
          current = index;
          track.style.transform = 'translateX(-' + (current * 100) + '%)';
        }

        if (prevBtn) prevBtn.addEventListener('click', function() { showSlide(current - 1); });
        if (nextBtn) nextBtn.addEventListener('click', function() { showSlide(current + 1); });
      });
    })();


    // Попап страница мероприятия
    (function() {
  'use strict';

  function openModal(id) {
    var modal = document.getElementById(id);
    if (!modal) return;
    modal.removeAttribute('hidden');
    modal.classList.remove('is-closing');
    document.window.__lockScroll();
  }

  function closeModal(modal) {
    if (!modal || modal.hasAttribute('hidden')) return;
    modal.classList.add('is-closing');
    document.body.classList.remove('is-modal-open');

    function handler(e) {
      if (
        e.target.classList.contains('events-modal__dialog') ||
        e.target.classList.contains('events-modal__overlay')
      ) {
        modal.setAttribute('hidden', '');
        modal.classList.remove('is-closing');
        modal.removeEventListener('animationend', handler);
      }
    }

    modal.addEventListener('animationend', handler);
  }

  document.addEventListener('click', function(e) {
    /* Открытие — клик на карточку */
    var trigger = e.target.closest('[data-events-modal]');
    if (trigger) {
      e.preventDefault();
      openModal(trigger.getAttribute('data-events-modal'));
      return;
    }

    /* Закрытие — только кнопка ✕ или оверлей */
    var closeBtn = e.target.closest('.events-modal__close');
    if (closeBtn) {
      var modal = closeBtn.closest('.events-modal');
      if (modal) closeModal(modal);
      return;
    }

    var overlay = e.target.closest('.events-modal__overlay');
    if (overlay) {
      var modal2 = overlay.closest('.events-modal');
      if (modal2) closeModal(modal2);
      return;
    }
  });

  /* Escape */
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      var active = document.querySelector('.events-modal:not([hidden])');
      if (active) closeModal(active);
    }
  });
})();






// Страница отзывы
(function(){
    const filters = document.getElementById('filters');
    const list = document.getElementById('reviewsList');
    const counter = document.getElementById('reviewsCount');
    const cards = list.querySelectorAll('.c-review');
    const buttons = filters.querySelectorAll('.c-reviews__filter');

    function plural(n){
      var m = n % 10, d = n % 100;
      if(d > 10 && d < 20) return 'отзывов';
      if(m > 1 && m < 5) return 'отзыва';
      if(m === 1) return 'отзыв';
      return 'отзывов';
    }

    buttons.forEach(function(btn){
      btn.addEventListener('click', function(){
        buttons.forEach(function(b){ b.classList.remove('is-active'); });
        btn.classList.add('is-active');
        var f = btn.dataset.filter;
        var visible = 0;
        cards.forEach(function(card){
          if(f === 'all' || card.dataset.category === f){
            card.style.display = '';
            visible++;
          } else {
            card.style.display = 'none';
          }
        });
        counter.textContent = visible;
        counter.nextElementSibling.textContent = plural(visible);
      });
    });

    /* Reveal on scroll */
    var reveals = document.querySelectorAll('.reveal');
    if('IntersectionObserver' in window){
      var io = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
          if(e.isIntersecting){
            e.target.classList.add('is-visible');
            io.unobserve(e.target);
          }
        });
      },{threshold:0.15});
      reveals.forEach(function(el){ io.observe(el); });
    } else {
      reveals.forEach(function(el){ el.classList.add('is-visible'); });
    }
  })();





  // --- Articles Filter Logic ---
  (function () {
    var filtersWrap = document.getElementById('articleFilters');
    if (!filtersWrap) return;

    var buttons = filtersWrap.querySelectorAll('.c-articles-cats__filter');
    var cards = document.querySelectorAll('.c-article-card[data-cat]');

    buttons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        // toggle active button
        buttons.forEach(function (b) { b.classList.remove('is-active'); });
        btn.classList.add('is-active');

        var filter = btn.dataset.filter;

        cards.forEach(function (card) {
          if (filter === 'all' || card.dataset.cat === filter) {
            card.classList.remove('is-hidden');
          } else {
            card.classList.add('is-hidden');
          }
        });
      });
    });
  })();


  // Страница акции
  (function() {
  'use strict';

  function openModal(id) {
    var modal = document.getElementById(id);
    if (!modal) return;
    modal.removeAttribute('hidden');
    modal.classList.remove('is-closing');
    document.window.__lockScroll();
  }

  function closeModal(modal) {
    if (!modal || modal.hasAttribute('hidden')) return;
    modal.classList.add('is-closing');
    document.body.classList.remove('is-modal-open');

    function handler(e) {
      if (
        e.target.classList.contains('offers-modal__dialog') ||
        e.target.classList.contains('offers-modal__overlay')
      ) {
        modal.setAttribute('hidden', '');
        modal.classList.remove('is-closing');
        modal.removeEventListener('animationend', handler);
      }
    }

    modal.addEventListener('animationend', handler);
  }

  document.addEventListener('click', function(e) {
    var trigger = e.target.closest('[data-offers-modal]');
    if (trigger) {
      e.preventDefault();
      openModal(trigger.getAttribute('data-offers-modal'));
      return;
    }

    var closeBtn = e.target.closest('.offers-modal__close');
    if (closeBtn) {
      var modal = closeBtn.closest('.offers-modal');
      if (modal) closeModal(modal);
      return;
    }

    var overlay = e.target.closest('.offers-modal__overlay');
    if (overlay) {
      var modal2 = overlay.closest('.offers-modal');
      if (modal2) closeModal(modal2);
      return;
    }
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      var active = document.querySelector('.offers-modal:not([hidden])');
      if (active) closeModal(active);
    }
  });
})();







/* ─── Fishing format modals (offers-style logic) ─────── */
(function () {
  'use strict';

  var scrollY = 0;

  function lockScroll() {
    scrollY = window.pageYOffset || document.documentElement.scrollTop;
    document.body.style.top = '-' + scrollY + 'px';
    document.window.__lockScroll();
  }

  function unlockScroll() {
    document.body.classList.remove('is-modal-open');
    document.body.style.top = '';
    window.scrollTo(0, scrollY);
  }

  function openModal(id) {
    var modal = document.getElementById(id);
    if (!modal) return;
    modal.removeAttribute('hidden');
    modal.classList.remove('is-closing');
    lockScroll();
  }

  function closeModal(modal) {
    if (!modal || modal.hasAttribute('hidden')) return;
    modal.classList.add('is-closing');

    function handler(e) {
      if (
        e.target.classList.contains('fishing-modal__dialog') ||
        e.target.classList.contains('fishing-modal__overlay')
      ) {
        modal.setAttribute('hidden', '');
        modal.classList.remove('is-closing');
        unlockScroll();
        modal.removeEventListener('animationend', handler);
      }
    }

    modal.addEventListener('animationend', handler);
  }

  /* Клик по карточке */
  document.addEventListener('click', function (e) {
    var trigger = e.target.closest('.c-fishing-formats [data-fishing-format-modal]');
    if (trigger) {
      e.preventDefault();
      openModal(trigger.getAttribute('data-fishing-format-modal'));
      return;
    }

    var closeBtn = e.target.closest('.fishing-modal__close');
    if (closeBtn) {
      var modal = closeBtn.closest('.fishing-modal');
      if (modal) closeModal(modal);
      return;
    }

    var overlay = e.target.closest('.fishing-modal__overlay');
    if (overlay) {
      var modal2 = overlay.closest('.fishing-modal');
      if (modal2) closeModal(modal2);
      return;
    }

    var bookingBtn = e.target.closest('.fishing-modal [data-fishing-modal-close]');
    if (bookingBtn) {
      var modal3 = bookingBtn.closest('.fishing-modal');
      if (modal3) closeModal(modal3);
      return;
    }
  });

  /* Escape */
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      var active = document.querySelector('.fishing-modal:not([hidden])');
      if (active) closeModal(active);
    }
  });
})();










// Лайтбокс для галереи
(function() {
  var lightbox = document.getElementById('spaLightbox');
  if (!lightbox) return;

  var img = lightbox.querySelector('.spa-lightbox__img');
  var overlay = lightbox.querySelector('.spa-lightbox__overlay');
  var closeBtn = lightbox.querySelector('.spa-lightbox__close');
  var prevBtn = lightbox.querySelector('.spa-lightbox__arrow--prev');
  var nextBtn = lightbox.querySelector('.spa-lightbox__arrow--next');
  var counter = lightbox.querySelector('.spa-lightbox__counter');

  var galleryItems = document.querySelectorAll('.c-spa-gallery__item');
  var currentIndex = 0;

  function open(index) {
    currentIndex = index;
    var src = galleryItems[index].querySelector('img').src;
    img.src = src;
    counter.textContent = (index + 1) + ' / ' + galleryItems.length;
    lightbox.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function close() {
    lightbox.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  function prev() {
    currentIndex = (currentIndex - 1 + galleryItems.length) % galleryItems.length;
    open(currentIndex);
  }

  function next() {
    currentIndex = (currentIndex + 1) % galleryItems.length;
    open(currentIndex);
  }

  // Клик по картинкам в галерее
  galleryItems.forEach(function(item, i) {
    item.style.cursor = 'pointer';
    item.addEventListener('click', function() {
      open(i);
    });
  });

  closeBtn.addEventListener('click', close);
  overlay.addEventListener('click', close);
  prevBtn.addEventListener('click', prev);
  nextBtn.addEventListener('click', next);

  // Клавиатура
  document.addEventListener('keydown', function(e) {
    if (!lightbox.classList.contains('is-open')) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowLeft') prev();
    if (e.key === 'ArrowRight') next();
  });
})();