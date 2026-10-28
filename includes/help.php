<?php
/**
 * Секция: «Не нашли ответ?» (f-help)
 * Подключить: include get_template_directory() . '/includes/f-help.php';
 *
 * Берёт данные из мета-поля kp_faq_help, заполненного метабоксом
 * /inc/faq_help_section.php.
 */

 $defaults = array(
    'eyebrow'      => 'Не нашли ответ?',
    'title'        => 'Спросите нас <em>напрямую</em>',
    'lead'         => '',
    'phone_label'  => 'Позвонить', 'phone_value' => '', 'phone_url' => '#',
    'tg_label'     => 'Написать в Telegram', 'tg_value' => '', 'tg_url' => '#',
    'email_label'  => 'Почта', 'email_value' => '', 'email_url' => '#',
    'btn1_text'    => 'Задать вопрос в форме', 'btn1_link' => '#feedback',
    'btn2_text'    => 'Все контакты', 'btn2_link' => '/contacts.html',
);

 $data = get_post_meta(get_the_ID(), 'kp_faq_help', true);
if (!is_array($data)) $data = array();
 $d = array_merge($defaults, $data);
?>

<section class="f-help" id="help">
  <div class="f-help__content">
    <p class="eyebrow"><?php echo esc_html($d['eyebrow']); ?></p>
    <h2 class="section-title f-help__title"><?php echo wp_kses_post($d['title']); ?></h2>
    <p class="section-sub f-help__lead"><?php echo esc_html($d['lead']); ?></p>

    <div class="f-help__ways">
      <a class="f-way" href="<?php echo esc_url($d['phone_url']); ?>">
        <svg class="f-way__icon" fill="currentColor" aria-hidden="true"><use href="#icon-phone"></use></svg>
        <span>
          <span class="f-way__label"><?php echo esc_html($d['phone_label']); ?></span>
          <span class="f-way__value"><?php echo esc_html($d['phone_value']); ?></span>
        </span>
      </a>
      <a class="f-way" href="<?php echo esc_url($d['tg_url']); ?>" target="_blank" rel="noopener">
        <svg class="f-way__icon" fill="currentColor" aria-hidden="true"><use href="#icon-tg"></use></svg>
        <span>
          <span class="f-way__label"><?php echo esc_html($d['tg_label']); ?></span>
          <span class="f-way__value"><?php echo esc_html($d['tg_value']); ?></span>
        </span>
      </a>
      <a class="f-way" href="<?php echo esc_url($d['email_url']); ?>">
        <svg class="f-way__icon" fill="currentColor" aria-hidden="true"><use href="#icon-email"></use></svg>
        <span>
          <span class="f-way__label"><?php echo esc_html($d['email_label']); ?></span>
          <span class="f-way__value"><?php echo esc_html($d['email_value']); ?></span>
        </span>
      </a>
    </div>

    <div class="f-help__actions">
      <a class="btn btn--gold" href="<?php echo esc_url($d['btn1_link']); ?>"><?php echo esc_html($d['btn1_text']); ?></a>
      <a class="btn btn--ghost" href="<?php echo esc_url($d['btn2_link']); ?>"><?php echo esc_html($d['btn2_text']); ?></a>
    </div>
  </div>
</section>