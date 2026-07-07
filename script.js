const header = document.querySelector("[data-header]");
const menuButton = document.querySelector("[data-menu]");
const nav = document.querySelector("[data-nav]");
const activityList = document.querySelector("[data-activity-list]");

const activities = {
  summer: ["Рыбалка летом и осенью", "Лодки и сапборды", "Катамараны", "Квадроциклы", "Велосипеды", "Волейбол"],
  winter: ["Лыжи", "Тюбинги", "Снегоходы", "Прогулки по лесу", "Баня после прогулки", "Горячий чай"],
  year: ["SPA", "Баня", "Кафе", "Мероприятия", "Настольные игры", "Прогулки"],
};

const activityIcons = {
  SPA: "waves",
  Баня: "flame",
  "Баня после прогулки": "flame",
  Кафе: "utensils",
  Катамараны: "waves",
  Квадроциклы: "bike",
  Лыжи: "trees",
  "Лодки и сапборды": "waves",
  Мероприятия: "calendar",
  "Настольные игры": "users",
  "Прогулки": "trees",
  "Прогулки по лесу": "trees",
  "Рыбалка летом и осенью": "fish",
  Снегоходы: "bike",
  Тюбинги: "waves",
  Велосипеды: "bike",
  Волейбол: "users",
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
