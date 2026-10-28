<?php
/**
 * Секция: FAQ (категории + группы вопросов)
 * Подключить: include get_template_directory() . '/includes/faq.php';
 *
 * Берёт данные из мета-поля kp_faq_data, заполненного метабоксом
 * /inc/faq_section.php.
 */

 $data = get_post_meta(get_the_ID(), 'kp_faq_data', true);
if (!is_array($data)) $data = array();

 $active = !empty($data['active']) ? $data['active'] : 'booking';
 $cats   = !empty($data['cats']) && is_array($data['cats']) ? $data['cats'] : array();
 $groups = !empty($data['groups']) && is_array($data['groups']) ? $data['groups'] : array();

 $total_items = 0;
 $main_items  = 0;
 $per_cat_counts = array();
foreach ($groups as $g) {
    $gslug = !empty($g['slug']) ? $g['slug'] : '';
    $gitems = !empty($g['items']) && is_array($g['items']) ? $g['items'] : array();
    $per_cat_counts[$gslug] = count($gitems);
    $total_items += count($gitems);
    foreach ($gitems as $item) {
        if (!empty($item['is_main'])) $main_items++;
    }
}

function kp_faq_group_is_hidden($active, $group_slug) {
    if ($active === 'all' || $active === 'main') return false;
    return ($active !== $group_slug);
}
function kp_faq_item_is_hidden($active, $group_slug, $is_main) {
    if ($active === 'all') return false;
    if ($active === 'main') return !$is_main;
    return ($active !== $group_slug);
}
?>

<section class="faq f-faq" id="questions">
  <div class="f-cats" id="categories">
    <div class="container f-cats__inner" data-faq-cats="" role="group" aria-label="Категории вопросов">
      <button class="f-cat<?php echo ($active === 'all') ? ' is-active' : ''; ?>" type="button" data-cat="all" aria-pressed="<?php echo ($active === 'all') ? 'true' : 'false'; ?>">Все вопросы<span class="f-cat__count"><?php echo (int)$total_items; ?></span></button>
      <button class="f-cat<?php echo ($active === 'main') ? ' is-active' : ''; ?>" type="button" data-cat="main" aria-pressed="<?php echo ($active === 'main') ? 'true' : 'false'; ?>">Основные<span class="f-cat__count"><?php echo (int)$main_items; ?></span></button>
      <?php foreach ($cats as $c) :
          $cslug  = !empty($c['slug']) ? $c['slug'] : '';
          $clabel = !empty($c['label']) ? $c['label'] : $cslug;
          if (!$cslug) continue;
          $is_active_cat = ($active === $cslug);
          $count = isset($per_cat_counts[$cslug]) ? (int)$per_cat_counts[$cslug] : 0;
      ?>
      <button class="f-cat<?php echo $is_active_cat ? ' is-active' : ''; ?>" type="button" data-cat="<?php echo esc_attr($cslug); ?>" aria-pressed="<?php echo $is_active_cat ? 'true' : 'false'; ?>"><?php echo esc_html($clabel); ?><span class="f-cat__count"><?php echo $count; ?></span></button>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="container faq__inner faq__inner_page">
    <div class="faq__list f-faq__list" data-faq="">
      <?php
      $first_visible_done = false;
      foreach ($groups as $g) :
          $gslug  = !empty($g['slug']) ? $g['slug'] : '';
          $gtitle = !empty($g['title']) ? $g['title'] : '';
          $gitems = !empty($g['items']) && is_array($g['items']) ? $g['items'] : array();
          $group_hidden = kp_faq_group_is_hidden($active, $gslug);
      ?>
      <div class="f-group" data-group="<?php echo esc_attr($gslug); ?>"<?php echo $group_hidden ? ' hidden=""' : ''; ?>>
        <div class="f-group__head">
          <h2 class="f-group__title"><?php echo esc_html($gtitle); ?></h2>
        </div>

        <?php foreach ($gitems as $item) :
            $q = !empty($item['q']) ? $item['q'] : '';
            $a = !empty($item['a']) ? $item['a'] : '';
            $is_main = !empty($item['is_main']);
            $item_hidden = kp_faq_item_is_hidden($active, $gslug, $is_main);

            $first_visible_class = '';
            if (!$item_hidden && !$first_visible_done) {
                $first_visible_class = ' is-first-visible';
                $first_visible_done = true;
            }
        ?>
        <div class="faq-item<?php echo $first_visible_class; ?>" data-faq-item=""<?php echo $is_main ? ' data-main=""' : ''; ?><?php echo $item_hidden ? ' hidden=""' : ''; ?>>
          <button class="faq-item__q" type="button" data-faq-toggle="" aria-expanded="false">
            <span><?php echo esc_html($q); ?></span>
            <span class="faq-item__icon" aria-hidden="true"></span>
          </button>
          <div class="faq-item__a" data-faq-answer="" style="max-height: 0px;">
            <?php echo wpautop(wp_kses_post($a)); ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<script>
(function () {
  'use strict';

  function initFaqFilter() {
    var catButtons = document.querySelectorAll('.f-cat[data-cat]');
    var groups = document.querySelectorAll('.f-group');
    var items = document.querySelectorAll('.faq-item');

    if (!catButtons.length || !groups.length) return;

    function closeAllAnswers() {
      items.forEach(function (item) {
        var btn = item.querySelector('.faq-item__q');
        var ans = item.querySelector('.faq-item__a');
        if (btn && ans && btn.getAttribute('aria-expanded') === 'true') {
          btn.setAttribute('aria-expanded', 'false');
          ans.style.maxHeight = '0px';
          item.classList.remove('is-open');
        }
      });
    }

    function recomputeFirstVisible() {
      // Убираем старый класс is-first-visible
      items.forEach(function (item) {
        item.classList.remove('is-first-visible');
      });
      // Находим первую видимую карточку и вешаем класс
      var done = false;
      items.forEach(function (item) {
        if (done) return;
        if (!item.hasAttribute('hidden') && !item.closest('.f-group')?.hasAttribute('hidden')) {
          item.classList.add('is-first-visible');
          done = true;
        }
      });
    }

    function filterByCat(cat) {
      // 1) Обновляем активную кнопку
      catButtons.forEach(function (btn) {
        var isActive = btn.getAttribute('data-cat') === cat;
        btn.classList.toggle('is-active', isActive);
        btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
      });

      // 2) Закрываем все открытые ответы (как на старом сайте)
      closeAllAnswers();

      // 3) Фильтруем группы и элементы
      groups.forEach(function (group) {
        var groupSlug = group.getAttribute('data-group') || '';
        var groupItems = group.querySelectorAll('.faq-item');
        var anyVisibleInGroup = false;

        groupItems.forEach(function (item) {
          var isMain = item.hasAttribute('data-main');
          var show = false;

          if (cat === 'all') {
            show = true;
          } else if (cat === 'main') {
            show = isMain;
          } else {
            show = (cat === groupSlug);
          }

          if (show) {
            item.removeAttribute('hidden');
            anyVisibleInGroup = true;
          } else {
            item.setAttribute('hidden', '');
          }
        });

        // Группу скрываем, только если выбрана конкретная категория
        // (для "all" и "main" показываем все группы — элементы внутри отфильтруются)
        if (cat === 'all' || cat === 'main') {
          group.removeAttribute('hidden');
        } else {
          if (anyVisibleInGroup) {
            group.removeAttribute('hidden');
          } else {
            group.setAttribute('hidden', '');
          }
        }
      });

      // 4) Пересчитываем is-first-visible
      recomputeFirstVisible();
    }

    // Клик по категории
    catButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var cat = btn.getAttribute('data-cat');
        if (!cat) return;
        filterByCat(cat);

        
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFaqFilter);
  } else {
    initFaqFilter();
  }
})();
</script>