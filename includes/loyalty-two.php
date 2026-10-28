<?php
/**
 * Секция: Лояльность — система статусов (заголовок + карточки)
 * Подключить: include get_template_directory() . '/includes/loyalty-two.php';
 *
 * Классы BEM: .l-statuses, .l-statuses__grid, .l-status, .l-status__icon,
 * .l-status__title, .l-status__descr
 *
 * ИЗМЕНЕНИЯ:
 *   1) Добавлен вывод eyebrow (надзаголовка) над основным заголовком
 *   2) section_title выводится через wp_kses_post (HTML-теги <em> работают)
 */

 $defaults = array(
    'eyebrow'       => '',
    'section_title' => 'Система статусов',
    'cards'         => array(),
);

 $data = get_post_meta(get_the_ID(), 'kp_loyalty_two', true);
if (!is_array($data)) $data = array();
 $d = array_merge($defaults, $data);
if (!is_array($d['cards'] ?? array())) $d['cards'] = array();
?>

<section class="l-statuses section section--alt" id="loyalty-two">
  <div class="container">

    <?php if (!empty($d['eyebrow']) || !empty($d['section_title'])) : ?>
    <div class="section-head reveal">

      <?php /* НОВОЕ: надзаголовок (eyebrow) над основным заголовком */ ?>
      <?php if (!empty($d['eyebrow'])) : ?>
        <p class="eyebrow"><?php echo esc_html($d['eyebrow']); ?></p>
      <?php endif; ?>

      <?php /* ИЗМЕНЕНО: wp_kses_post вместо esc_html — чтобы работали <em>, <strong> */ ?>
      <?php if (!empty($d['section_title'])) : ?>
        <h2 class="section-title"><?php echo wp_kses_post($d['section_title']); ?></h2>
      <?php endif; ?>

    </div>
    <?php endif; ?>

    <?php if (!empty($d['cards'])) : ?>
    <ul class="l-statuses__grid reveal">
      <?php foreach ($d['cards'] as $c) :
          $img   = !empty($c['image']) ? $c['image'] : '';
          $title = !empty($c['title']) ? $c['title'] : '';
          $descr = !empty($c['descr']) ? $c['descr'] : '';
      ?>
      <li class="l-status">
        <?php if ($img) : ?>
          <div class="l-status__icon">
            <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($title); ?>">
          </div>
        <?php endif; ?>
        <?php if ($title) : ?>
          <h3 class="l-status__title"><?php echo esc_html($title); ?></h3>
        <?php endif; ?>
        <?php if ($descr) : ?>
          <p class="l-status__descr"><?php echo esc_html($descr); ?></p>
        <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
</section>