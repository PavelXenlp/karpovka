const header = document.querySelector("[data-header]");
const menuButton = document.querySelector("[data-menu]");
const nav = document.querySelector("[data-nav]");
const activityList = document.querySelector("[data-activity-list]");

const activities = {
  summer: ["Рыбалка летом и осенью", "Лодки и сапборды", "Катамараны", "Квадроциклы", "Велосипеды", "Волейбол", "Баскетбол", "Футбол", "Бадминтон", "Настольные игры"],
  winter: ["Лыжи", "Тюбинги", "Снегоходы", "Прогулки по лесу", "Баня после прогулки", "Коньки"],
  year: ["Мероприятия", "Настольные игры", "Прогулки", "Уличные тренажеры", "Детская площадка", "Тир", "Дартс", "Шахматы, шашки", "Скандинавская ходьба"],
};

const activityIcons = {
  SPA: "waves",
  Баня: "flame",
  "Баня после прогулки": "flame",
  Кафе: "utensils",
  Катамараны: "catamaran",
  Квадроциклы: "atv",
  Лыжи: "ski",
  "Лодки и сапборды": "boat",
  Мероприятия: "calendar",
  "Настольные игры": "dice",
  "Прогулки": "stroll",
  "Прогулки по лесу": "walking",
  "Рыбалка летом и осенью": "fish",
  Снегоходы: "snowmobile",
  Тюбинги: "tube",
  Велосипеды: "bike",
  Волейбол: "volleyball",
  Баскетбол: "basketball",
  Футбол: "soccer",
  Бадминтон: "badminton",
  "Детские самокаты": "scooter",
  Коньки: "skate",
  "Уличные тренажеры": "dumbbell",
  "Детская площадка": "playground",
  Тир: "target",
  Дартс: "darts",
  "Скандинавская ходьба": "walking",
  "Шахматы, шашки": "chess",
  "Горячий чай": "utensils",
};

function activityItem(item) {
  const icon = activityIcons[item] || "check";
  return `<li><svg class="icon"><use href="#icon-${icon}"></use></svg>${item}</li>`;
}

function updateHeader() {
  header.classList.toggle("scrolled", window.scrollY > 18);
}

updateHeader();
window.addEventListener("scroll", updateHeader, { passive: true });

menuButton.addEventListener("click", () => {
  header.classList.toggle("menu-open");
  document.body.classList.toggle("menu-open");
});

nav.addEventListener("click", (event) => {
  if (event.target.tagName === "A") {
    header.classList.remove("menu-open");
    document.body.classList.remove("menu-open");
  }
});

document.querySelectorAll("form").forEach((form) => {
  form.addEventListener("submit", (event) => {
    event.preventDefault();
    const button = form.querySelector("button[type='submit']");
    if (button) {
      button.textContent = form.id === "booking" ? "Откроем TravelLine" : "Заявка подготовлена";
      button.disabled = true;
    }
  });
});

document.querySelectorAll("[data-tab]").forEach((button) => {
  button.addEventListener("click", () => {
    document.querySelectorAll("[data-tab]").forEach((item) => item.classList.toggle("active", item === button));
    const items = activities[button.dataset.tab] || activities.summer;
    activityList.innerHTML = items.map(activityItem).join("");
  });
});

document.querySelectorAll(".faq-list button").forEach((button) => {
  button.addEventListener("click", () => {
    button.classList.toggle("open");
  });
});

document.querySelectorAll("[data-story-slider]").forEach((slider) => {
  const stage = slider.querySelector("[data-story-stage]");
  const cards = Array.from(stage.querySelectorAll(".story-card"));
  const dotsBox = slider.querySelector("[data-story-dots]");
  const prevBtn = slider.querySelector("[data-story-prev]");
  const nextBtn = slider.querySelector("[data-story-next]");
  if (cards.length === 0) return;

  let active = Math.floor(cards.length / 2);

  const dots = cards.map((_, index) => {
    const dot = document.createElement("button");
    dot.type = "button";
    dot.setAttribute("aria-label", `Слайд ${index + 1}`);
    dot.addEventListener("click", () => goTo(index));
    dotsBox.appendChild(dot);
    return dot;
  });

  function render() {
    cards.forEach((card, index) => {
      const offset = index - active;
      // Позиция -2..2 задаёт трансформацию через CSS, остальные скрыты
      card.dataset.pos = Math.abs(offset) <= 2 ? String(offset) : "hidden";
    });
    dots.forEach((dot, index) => dot.classList.toggle("active", index === active));
  }

  function goTo(index) {
    active = (index + cards.length) % cards.length;
    render();
  }

  prevBtn.addEventListener("click", () => goTo(active - 1));
  nextBtn.addEventListener("click", () => goTo(active + 1));

  cards.forEach((card, index) => {
    card.addEventListener("click", (event) => {
      // Клик по соседней карточке — переводим её в центр, а не переходим по ссылке
      if (index !== active) {
        event.preventDefault();
        goTo(index);
      }
    });
  });

  // Свайп на тач-устройствах
  let startX = null;
  stage.addEventListener("touchstart", (event) => {
    startX = event.touches[0].clientX;
  }, { passive: true });
  stage.addEventListener("touchend", (event) => {
    if (startX === null) return;
    const dx = event.changedTouches[0].clientX - startX;
    if (Math.abs(dx) > 40) goTo(active + (dx < 0 ? 1 : -1));
    startX = null;
  });

  render();
});

document.querySelectorAll("[data-spa-slider]").forEach((slider) => {
  const track = slider.querySelector("[data-spa-track]");
  const slides = Array.from(track.children);
  const dotsBox = slider.querySelector("[data-spa-dots]");
  const prevBtn = slider.querySelector("[data-spa-prev]");
  const nextBtn = slider.querySelector("[data-spa-next]");
  if (slides.length === 0) return;

  let active = 0;

  const dots = slides.map((_, index) => {
    const dot = document.createElement("button");
    dot.type = "button";
    dot.setAttribute("aria-label", `Бассейн ${index + 1}`);
    dot.addEventListener("click", () => goTo(index));
    dotsBox.appendChild(dot);
    return dot;
  });

  function render() {
    track.style.transform = `translateX(${-active * 100}%)`;
    dots.forEach((dot, index) => dot.classList.toggle("active", index === active));
  }

  function goTo(index) {
    active = (index + slides.length) % slides.length;
    render();
  }

  prevBtn.addEventListener("click", () => goTo(active - 1));
  nextBtn.addEventListener("click", () => goTo(active + 1));

  // Свайп на тач-устройствах
  let startX = null;
  track.addEventListener("touchstart", (event) => {
    startX = event.touches[0].clientX;
  }, { passive: true });
  track.addEventListener("touchend", (event) => {
    if (startX === null) return;
    const dx = event.changedTouches[0].clientX - startX;
    if (Math.abs(dx) > 40) goTo(active + (dx < 0 ? 1 : -1));
    startX = null;
  });

  render();
});

// Вкладки SPA-центра: переключение панелей с услугами
document.querySelectorAll("[data-spa-tab]").forEach((button) => {
  button.addEventListener("click", () => {
    const key = button.dataset.spaTab;
    document.querySelectorAll("[data-spa-tab]").forEach((item) => item.classList.toggle("active", item === button));
    document.querySelectorAll("[data-spa-panel]").forEach((panel) => {
      panel.classList.toggle("active", panel.dataset.spaPanel === key);
    });
  });
});

// Отзывы: обрезаем длинные до 270px и раскрываем по клику
function setupReviews() {
  document.querySelectorAll(".review").forEach((review) => {
    const clamped = review.scrollHeight > review.clientHeight + 4;
    review.classList.toggle("is-clamped", clamped && !review.classList.contains("expanded"));
  });
}

document.querySelectorAll(".review").forEach((review) => {
  review.addEventListener("click", () => {
    // Реагируем только на отзывы, которые были обрезаны
    if (!review.classList.contains("is-clamped") && !review.classList.contains("expanded")) return;
    const expanding = !review.classList.contains("expanded");
    review.classList.toggle("expanded", expanding);
    // Свёрнутый обрезанный отзыв снова показывает градиент
    review.classList.toggle("is-clamped", !expanding);
  });
});

setupReviews();
window.addEventListener("resize", setupReviews);

const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
        observer.unobserve(entry.target);
      }
    });
  },
  { threshold: 0.14 }
);

document.querySelectorAll(".reveal").forEach((item) => observer.observe(item));
