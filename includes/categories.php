<section class="c-articles-cats section" id="categories">
    <div class="container">
      <div class="section-head section-head--center reveal">
        <p class="eyebrow">Разделы</p>
        <h2 class="section-title"><span class="gold-header"><em>Темы</em></span> статей</h2>
        <p class="section-sub">Выберите интересующую категорию</p>
      </div>
      <div class="c-articles-cats__filters reveal" id="articleFilters">
  <button class="c-articles-cats__filter is-active" data-filter="all">Все статьи</button>
  <button class="c-articles-cats__filter" data-filter="stay">Проживание</button>
  <button class="c-articles-cats__filter" data-filter="spa">SPA и восстановление</button>
  <button class="c-articles-cats__filter" data-filter="family">Отдых с детьми</button>
  <button class="c-articles-cats__filter" data-filter="active">Активный отдых</button>
  <button class="c-articles-cats__filter" data-filter="events">Мероприятия</button>
  <button class="c-articles-cats__filter" data-filter="gifts">Подарки</button>
</div>
    </div>
  </section>


  <script>
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
  </script>
  