<?php
/**
 * Секция: Подарочные сертификаты — слайдер
 * Подключить: include get_template_directory() . '/includes/gift-one.php';
 *
 * Классы: .certificates-section, .certificates-intro, .certificates-kicker,
 * .center-title, .certificates-lead, .cert-slider, .cert-card,
 * .cert-card--pine, .cert-card--light, .certificates-cta, .certificates-note
 *
 * ИЗМЕНЕНИЯ:
 *   1) Убран блок .story-dots (точки не нужны)
 *   2) JS инициализируется на window.load (не DOMContentLoaded) — layout готов
 *   3) Расчёт позиции через getBoundingClientRect — надёжнее, чем offsetLeft
 *   4) Слайд прилипает к левому краю stage (а не центрируется)
 *   5) isAnimating защищает от ложных срабатываний scroll-обработчика
 */

 $defaults = array(
    'eyebrow'  => '',
    'title'    => '',
    'lead'     => '',
    'cards'    => array(),
    'cta_text' => '',
    'cta_link' => '#',
    'note'     => '',
);

 $data = get_post_meta(get_the_ID(), 'kp_gift_one', true);
if (!is_array($data)) $data = array();
 $d = array_merge($defaults, $data);
if (!is_array($d['cards'] ?? array())) $d['cards'] = array();
?>

<section class="section certificates-section" id="gift-one">
  <div class="container certificates-container">

    <?php if (!empty($d['eyebrow']) || !empty($d['title']) || !empty($d['lead'])) : ?>
    <div class="certificates-intro reveal">

      <?php if (!empty($d['eyebrow'])) : ?>
        <p class="certificates-kicker">
          <svg class="icon"><use href="#icon-gift"></use></svg><?php echo esc_html($d['eyebrow']); ?>
        </p>
      <?php endif; ?>

      <?php if (!empty($d['title'])) : ?>
        <h1 class="center-title"><?php echo wp_kses_post($d['title']); ?></h1>
      <?php endif; ?>

      <?php if (!empty($d['lead'])) : ?>
        <p class="certificates-lead"><?php echo wp_kses_post($d['lead']); ?></p>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($d['cards'])) : ?>
    <div class="story-slider cert-slider reveal" data-story-slider="">
      <button class="story-nav story-nav--prev" type="button" data-story-prev="" aria-label="Предыдущий сертификат">
        <svg class="icon"><use href="#icon-chevron-left"></use></svg>
      </button>
      <div class="story-stage" data-story-stage="">
        <?php foreach ($d['cards'] as $c) :
            $variant = !empty($c['variant']) ? $c['variant'] : 'pine';
        ?>
        <article class="story-card cert-card cert-card--<?php echo esc_attr($variant); ?>">
          <div class="cert-inner">
            <div class="cert-head">
              <?php if (!empty($c['brand'])) : ?>
                <span class="cert-brand"><?php echo esc_html($c['brand']); ?></span>
              <?php endif; ?>
              <?php if (!empty($c['brand_sub'])) : ?>
                <span class="cert-brand-sub"><?php echo wp_kses_post($c['brand_sub']); ?></span>
              <?php endif; ?>
            </div>
            <div class="cert-body">
              <span class="cert-label">Подарочный сертификат</span>
              <?php if (!empty($c['value'])) : ?>
                <span class="cert-value"><?php echo esc_html($c['value']); ?></span>
              <?php endif; ?>
              <?php if (!empty($c['theme'])) : ?>
                <span class="cert-theme"><?php echo esc_html($c['theme']); ?></span>
              <?php endif; ?>
            </div>
            <div class="cert-foot">
              <?php if (!empty($c['validity'])) : ?>
                <span><?php echo esc_html($c['validity']); ?></span>
              <?php endif; ?>
              <?php if (!empty($c['code'])) : ?>
                <span class="cert-code"><?php echo esc_html($c['code']); ?></span>
              <?php endif; ?>
            </div>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
      <button class="story-nav story-nav--next" type="button" data-story-next="" aria-label="Следующий сертификат">
        <svg class="icon"><use href="#icon-chevron-right"></use></svg>
      </button>
      <!-- УБРАНО: <div class="story-dots" data-story-dots=""></div> -->
    </div>
    <?php endif; ?>

    <?php if (!empty($d['cta_text'])) : ?>
    <div class="certificates-cta reveal">
      <a class="btn btn-sand" href="<?php echo esc_url($d['cta_link']); ?>"><?php echo esc_html($d['cta_text']); ?></a>
      <?php if (!empty($d['note'])) : ?>
        <p class="certificates-note"><?php echo wp_kses_post($d['note']); ?></p>
      <?php endif; ?>
    </div>
    <?php endif; ?>

  </div>
</section>

<script>
(function () {
  'use strict';

  /* ИСправлено: 'load' вместо 'DOMContentLoaded' — ждём шрифты и картинки,
     layout к этому моменту финальный, расчёты будут корректными. */
  window.addEventListener('load', function () {
    var sliders = document.querySelectorAll('[data-story-slider]');
    if (!sliders.length) return;
    sliders.forEach(initSlider);
  });

  /* Если страница уже загрузилась к моменту подключения скрипта — запускаем сразу */
  if (document.readyState === 'complete') {
    var sliders0 = document.querySelectorAll('[data-story-slider]');
    sliders0.forEach(function (s) { initSlider(s); });
  }

  function initSlider(slider) {
    var stage = slider.querySelector('[data-story-stage]');
    var prevBtn = slider.querySelector('[data-story-prev]');
    var nextBtn = slider.querySelector('[data-story-next]');

    if (!stage) return;

    var slides = stage.querySelectorAll('.story-card');
    if (!slides.length) return;

    var currentIndex = 0;
    var isAnimating = false;

    if (prevBtn) {
      prevBtn.addEventListener('click', function () { goTo(currentIndex - 1); });
    }
    if (nextBtn) {
      nextBtn.addEventListener('click', function () { goTo(currentIndex + 1); });
    }

    /* ─── Расчёт позиции слайда относительно stage ─── */
    /* getBoundingClientRect() — надёжнее, чем offsetLeft, не зависит от
       position: relative у stage, работает даже если layout ещё пересчитывается. */
    function getSlideOffset(index) {
      var slide = slides[index];
      var stageRect = stage.getBoundingClientRect();
      var slideRect = slide.getBoundingClientRect();
      /* Смещение левого края слайда относительно левого края stage + текущий скролл */
      return (slideRect.left - stageRect.left) + stage.scrollLeft;
    }

    function goTo(index) {
      var clamped = Math.max(0, Math.min(slides.length - 1, index));
      if (clamped === currentIndex) return;

      isAnimating = true;
      currentIndex = clamped;

      /* Если все слайды влезают в stage — скроллить не нужно */
      if (stage.scrollWidth <= stage.offsetWidth) {
        isAnimating = false;
        updateNavButtons();
        return;
      }

      /* Слайд прилипает к левому краю stage */
      var offset = getSlideOffset(currentIndex);

      /* Ограничиваем offset */
      offset = Math.max(0, Math.min(stage.scrollWidth - stage.offsetWidth, offset));

      stage.scrollTo({ left: offset, behavior: 'smooth' });

      updateNavButtons();

      /* Снимаем блокировку после завершения анимации */
      setTimeout(function () { isAnimating = false; }, 500);
    }

    function updateNavButtons() {
      if (prevBtn) prevBtn.disabled = (currentIndex === 0);
      if (nextBtn) nextBtn.disabled = (currentIndex === slides.length - 1);
    }

    /* ─── Синхронизация при ручном скролле (drag/свайп) ─── */
    var scrollTimeout;
    stage.addEventListener('scroll', function () {
      if (isAnimating) return; /* игнорируем скролл во время программной анимации */
      clearTimeout(scrollTimeout);
      scrollTimeout = setTimeout(function () {
        var stageRect = stage.getBoundingClientRect();
        var nearest = 0;
        var minDist = Infinity;
        slides.forEach(function (slide, i) {
          var slideRect = slide.getBoundingClientRect();
          var dist = Math.abs(slideRect.left - stageRect.left);
          if (dist < minDist) {
            minDist = dist;
            nearest = i;
          }
        });
        if (nearest !== currentIndex) {
          currentIndex = nearest;
          updateNavButtons();
        }
      }, 150);
    });

    /* ─── Адаптив: при ресайзе пересчитываем ─── */
    var resizeTimeout;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(function () {
        /* Просто обновляем состояние кнопок — не двигаем слайдер */
        updateNavButtons();
      }, 200);
    });

    /* ─── Начальное состояние ─── */
    updateNavButtons();

    /* ─── Клавиатура ←/→, если фокус на слайдере ─── */
    slider.setAttribute('tabindex', '0');
    slider.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowLeft') {
        e.preventDefault();
        goTo(currentIndex - 1);
      } else if (e.key === 'ArrowRight') {
        e.preventDefault();
        goTo(currentIndex + 1);
      }
    });
  }
})();
</script>