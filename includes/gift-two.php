<?php
/**
 * Секция: Бонусная карта UDS
 * Подключить: include get_template_directory() . '/includes/gift-two.php';
 *
 * Берёт данные из мета-поля kp_gift_two, заполненного метабоксом
 * /inc/gift_two.php.
 *
 * Классы: .uds-section, .uds-card, .uds-copy, .uds-steps, .uds-qr,
 * .uds-logo, .uds-qr-code
 */

 $defaults = array(
    'eyebrow' => '',
    'title'   => '',
    'text'    => '',
    'steps'   => array(),
    'logo'    => '',
    'qr'      => '',
);

 $data = get_post_meta(get_the_ID(), 'kp_gift_two', true);
if (!is_array($data)) $data = array();
 $d = array_merge($defaults, $data);
if (!is_array($d['steps'] ?? array())) $d['steps'] = array();
?>

<section class="section uds-section" id="gift-two">
  <div class="container uds-card reveal">

    <div class="uds-copy">

      <?php if (!empty($d['eyebrow'])) : ?>
        <p class="eyebrow"><?php echo esc_html($d['eyebrow']); ?></p>
      <?php endif; ?>

      <?php if (!empty($d['title'])) : ?>
        <h2><?php echo wp_kses_post($d['title']); ?></h2>
      <?php endif; ?>

      <?php if (!empty($d['text'])) : ?>
        <p><?php echo wp_kses_post($d['text']); ?></p>
      <?php endif; ?>

      <?php if (!empty($d['steps'])) : ?>
      <ul class="uds-steps">
        <?php foreach ($d['steps'] as $s) :
            $text = !empty($s['text']) ? $s['text'] : '';
        ?>
        <li><svg class="icon"><use href="#icon-check"></use></svg><?php echo esc_html($text); ?></li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </div>

    <div class="uds-qr">
      <?php if (!empty($d['logo'])) : ?>
        <img class="uds-logo" src="<?php echo esc_url($d['logo']); ?>" alt="Логотип UDS">
      <?php endif; ?>
      <?php if (!empty($d['qr'])) : ?>
        <div class="uds-qr-code">
          <img src="<?php echo esc_url($d['qr']); ?>" alt="QR-код для активации бонусной карты в приложении UDS">
        </div>
      <?php endif; ?>
    </div>

  </div>
</section>