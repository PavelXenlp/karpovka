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

// ─── Слайдер «Выберите формат отдыха» (бесконечный) ───────
document.querySelectorAll('[data-stories]').forEach((slider) => {
  const track = slider.querySelector('[data-stories-track]');
  const prevBtn = slider.querySelector('[data-stories-prev]');
  const nextBtn = slider.querySelector('[data-stories-next]');
  if (!track) return;

  const originals = Array.from(track.children);
  const count = originals.length;
  if (count === 0) return;

  // Для бесконечной прокрутки дублируем набор до и после оригиналов:
  // [клоны][оригиналы][клоны] — и незаметно возвращаемся в середину.
  const tail = document.createDocumentFragment();
  const head = document.createDocumentFragment();
  originals.forEach((node) => tail.appendChild(node.cloneNode(true)));
  originals.forEach((node) => head.appendChild(node.cloneNode(true)));
  track.appendChild(tail);
  track.insertBefore(head, track.firstChild);

  const slides = Array.from(track.children);
  let index = count; // первый оригинал
  let animating = false;

  function stepWidth() {
    const styles = getComputedStyle(track);
    const gap = parseFloat(styles.columnGap || styles.gap) || 0;
    return slides[0].getBoundingClientRect().width + gap;
  }

  function layout(animate) {
    // is-jumping отключает анимации и трека, и размера карточек:
    // перескок к оригиналам должен быть незаметным
    if (!animate) track.classList.add('is-jumping');
    const cardWidth = slides[0].getBoundingClientRect().width;
    const offset = (slider.clientWidth - cardWidth) / 2;
    track.style.transform = `translateX(${offset - index * stepWidth()}px)`;
    slides.forEach((slide, i) => slide.classList.toggle('is-active', i === index));
    if (!animate) {
      void track.offsetWidth; // применяем позицию без анимации
      track.classList.remove('is-jumping');
    }
  }

  function goTo(target) {
    if (animating) return;
    animating = true;
    index = target;
    layout(true);
  }

  track.addEventListener('transitionend', (event) => {
    if (event.target !== track || event.propertyName !== 'transform') return;
    animating = false;
    // Ушли в зону клонов — бесшовно возвращаемся к оригиналам
    if (index >= count * 2) {
      index -= count;
      layout(false);
    } else if (index < count) {
      index += count;
      layout(false);
    }
  });

  if (prevBtn) prevBtn.addEventListener('click', () => goTo(index - 1));
  if (nextBtn) nextBtn.addEventListener('click', () => goTo(index + 1));

  // Клик по неактивной карточке — центрируем её, а не переходим по ссылке
  slides.forEach((slide, i) => {
    slide.addEventListener('click', (event) => {
      if (i !== index) {
        event.preventDefault();
        goTo(i);
      }
    });
  });

  // Свайп на тач-устройствах
  let startX = null;
  track.addEventListener('touchstart', (event) => {
    startX = event.touches[0].clientX;
  }, { passive: true });
  track.addEventListener('touchend', (event) => {
    if (startX === null) return;
    const dx = event.changedTouches[0].clientX - startX;
    if (Math.abs(dx) > 40) goTo(index + (dx < 0 ? 1 : -1));
    startX = null;
  });

  window.addEventListener('resize', () => layout(false));
  layout(false);
});

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

// ─── Отзывы: бесконечный слайдер во всю ширину ────────────
// Идёт ДО блока раскрытия отзывов: клоны создаются здесь, поэтому
// кнопка «Читать полностью» навешивается и на них тоже.
document.querySelectorAll('[data-reviews]').forEach((slider) => {
  const track = slider.querySelector('[data-reviews-track]');
  const prevBtn = slider.querySelector('[data-reviews-prev]');
  const nextBtn = slider.querySelector('[data-reviews-next]');
  if (!track) return;

  const originals = Array.from(track.children);
  const count = originals.length;
  if (count === 0) return;

  // [клоны][оригиналы][клоны] — и незаметный возврат в середину
  const head = document.createDocumentFragment();
  const tail = document.createDocumentFragment();
  originals.forEach((node) => head.appendChild(node.cloneNode(true)));
  originals.forEach((node) => tail.appendChild(node.cloneNode(true)));
  track.appendChild(tail);
  track.insertBefore(head, track.firstChild);

  const slides = Array.from(track.children);
  let index = count; // первый оригинал
  let animating = false;

  function stepWidth() {
    const styles = getComputedStyle(track);
    const gap = parseFloat(styles.columnGap || styles.gap) || 0;
    return slides[0].getBoundingClientRect().width + gap;
  }

  function layout(animate) {
    if (!animate) track.classList.add('is-jumping');
    track.style.transform = `translateX(${-index * stepWidth()}px)`;
    if (!animate) {
      void track.offsetWidth; // применяем позицию без анимации
      track.classList.remove('is-jumping');
    }
  }

  function goTo(target) {
    if (animating) return;
    animating = true;
    index = target;
    layout(true);
  }

  track.addEventListener('transitionend', (event) => {
    // у карточек тоже есть переходы (max-height) — они всплывают сюда
    if (event.target !== track || event.propertyName !== 'transform') return;
    animating = false;
    if (index >= count * 2) {
      index -= count;
      layout(false);
    } else if (index < count) {
      index += count;
      layout(false);
    }
  });

  if (prevBtn) prevBtn.addEventListener('click', () => goTo(index - 1));
  if (nextBtn) nextBtn.addEventListener('click', () => goTo(index + 1));

  // Свайп на тач-устройствах
  let startX = null;
  track.addEventListener('touchstart', (event) => {
    startX = event.touches[0].clientX;
  }, { passive: true });
  track.addEventListener('touchend', (event) => {
    if (startX === null) return;
    const dx = event.changedTouches[0].clientX - startX;
    if (Math.abs(dx) > 40) goTo(index + (dx < 0 ? 1 : -1));
    startX = null;
  });

  window.addEventListener('resize', () => layout(false));
  layout(false);
});

// ─── Отзывы: раскрытие полного текста ─────────────────────
(() => {
  const COLLAPSED = 250; // синхронизировано с max-height в styles2.css

  document.querySelectorAll('[data-review]').forEach((review) => {
    const toggle = review.querySelector('[data-review-toggle]');
    if (!toggle) return;

    // Свёрнутое состояние: ограничиваем высоту и показываем кнопку,
    // только если текст реально не помещается
    function syncCollapsed() {
      review.style.maxHeight = COLLAPSED + 'px';
      review.classList.toggle('is-clamped', review.scrollHeight > COLLAPSED + 4);
    }

    function expand() {
      review.classList.add('is-expanded');
      review.classList.remove('is-clamped');
      // scrollHeight отдаёт полную высоту контента даже при overflow: hidden
      review.style.maxHeight = review.scrollHeight + 'px';
      toggle.textContent = 'Свернуть';
    }

    function collapse() {
      review.classList.remove('is-expanded');
      syncCollapsed();
      toggle.textContent = 'Читать полностью';
    }

    toggle.addEventListener('click', () => {
      if (review.classList.contains('is-expanded')) collapse();
      else expand();
    });

    window.addEventListener('resize', () => {
      if (review.classList.contains('is-expanded')) {
        // при смене ширины колонки фиксированная высота может обрезать текст
        review.style.maxHeight = 'none';
      } else {
        syncCollapsed();
      }
    });

    syncCollapsed();
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