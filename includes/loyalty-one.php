<?php
/**
 * Секция: Лояльность — введение (изображение + текст)
 * Подключить: include get_template_directory() . '/includes/loyalty-one.php';
 *
 * Классы BEM: .l-intro, .l-intro__grid, .l-intro__media, .l-intro__content,
 * .l-intro__title, .l-intro__text
 *
 * Заголовок и текст выводятся через wp_kses_post() — HTML-теги <em>,
 * <strong>, <br> сохраняются. В CSS есть правила для .l-intro__title em
 * и .l-intro__text em — они делают текст золотым курсивом.
 */

 $defaults = array(
    'image' => '',
    'title' => '',
    'text'  => '',
);

 $data = get_post_meta(get_the_ID(), 'kp_loyalty_one', true);
if (!is_array($data)) $data = array();
 $d = array_merge($defaults, $data);
?>

<section class="l-intro section" id="loyalty-one">
  <div class="container">
    <div class="l-intro__grid">
      <div class="l-intro__media reveal">
        <?php if (!empty($d['image'])) : ?>
          <img class="l-intro__img" src="<?php echo esc_url($d['image']); ?>" alt="">
        <?php endif; ?>
      </div>
      <div class="l-intro__content reveal">
        <?php if (!empty($d['title'])) : ?>
          <h2 class="l-intro__title section-title"><?php echo wp_kses_post($d['title']); ?></h2>
        <?php endif; ?>
        <?php if (!empty($d['text'])) : ?>
          <div class="l-intro__text section-sub"><?php echo wp_kses_post($d['text']); ?></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>