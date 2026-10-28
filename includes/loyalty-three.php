<?php
/**
 * Секция: Лояльность — правила программы (карточки)
 * Подключить: include get_template_directory() . '/includes/loyalty-three.php';
 *
 * Берёт данные из мета-поля kp_loyalty_three, заполненного метабоксом
 * /inc/loyalty_three.php.
 *
 * Классы BEM: .l-rules, .l-rules__grid, .l-rule, .l-rule__title,
 * .l-rule__divider, .l-rule__text
 */

 $defaults = array(
    'cards' => array(),
);

 $data = get_post_meta(get_the_ID(), 'kp_loyalty_three', true);
if (!is_array($data)) $data = array();
 $d = array_merge($defaults, $data);
if (!is_array($d['cards'] ?? array())) $d['cards'] = array();
?>

<section class="l-rules section" id="loyalty-three">
  <div class="container">
    <?php if (!empty($d['cards'])) : ?>
    <ul class="l-rules__grid reveal">
      <?php foreach ($d['cards'] as $c) :
          $title = !empty($c['title']) ? $c['title'] : '';
          $text  = !empty($c['text'])  ? $c['text']  : '';
      ?>
      <li class="l-rule">
        <?php if ($title) : ?>
          <h3 class="l-rule__title"><?php echo wp_kses_post($title); ?></h3>
        <?php endif; ?>
        <div class="l-rule__divider" aria-hidden="true"></div>
        <?php if ($text) : ?>
          <div class="l-rule__text"><?php echo wp_kses_post($text); ?></div>
        <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
</section>