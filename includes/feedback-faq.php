<?php
/**
 * Секция: форма обратной связи (f-form-section)
 * Подключить: include get_template_directory() . '/includes/f-form-section.php';
 *
 * Берёт данные из мета-поля kp_faq_form, заполненного метабоксом
 * /inc/faq_form_section.php.
 *
 * ИЗМЕНЕНИЕ: если задано bg_img — он перекрывает дефолтный background-image
 * из CSS (с сохранением тёмного градиентного overlay).
 */

 $defaults = array(
    'bg_img'              => '',
    'eyebrow'             => 'Обратная связь',
    'title'               => 'Остались <em>вопросы</em>?',
    'subtitle'            => '',
    'name_label'          => 'Ваше имя', 'name_placeholder' => 'Как к вам обращаться',
    'phone_label'         => 'Телефон', 'phone_placeholder' => '+7 (___) ___-__-__',
    'topic_label'         => 'Тема вопроса',
    'message_label'       => 'Вопрос',
    'message_placeholder' => 'Расскажите, что хотите узнать...',
    'submit_text'         => 'Отправить вопрос',
    'note'                => 'Нажимая кнопку, вы соглашаетесь с <a href="#">политикой конфиденциальности</a>',
    'topics'              => array(),
);

 $data = get_post_meta(get_the_ID(), 'kp_faq_form', true);
if (!is_array($data)) $data = array();
 $d = array_merge($defaults, $data);
if (!is_array($d['topics'] ?? array())) $d['topics'] = array();

/* Готовим инлайн-стиль для фоновой картинки, перекрывая CSS-фон
   и сохраняя тёмный градиент 0.88 → 0.8. */
 $section_style = '';
if (!empty($d['bg_img'])) {
    $url = esc_url($d['bg_img']);
    $section_style = ' style="background-image: linear-gradient(180deg, rgba(10, 40, 34, 0.88) 0%, rgba(10, 40, 34, 0.8) 100%), url(\'' . $url . '\');"';
}
?>

<section class="f-form-section" id="feedback"<?php echo $section_style; ?>>
  <div class="container">
    <div class="f-form-head reveal is-visible">
      <p class="eyebrow"><?php echo esc_html($d['eyebrow']); ?></p>
      <h2 class="section-title"><?php echo wp_kses_post($d['title']); ?></h2>
      <p class="section-sub"><?php echo esc_html($d['subtitle']); ?></p>
    </div>

    <div class="f-form form-background reveal is-visible">
      <form data-callback-form="">
        <div class="f-form__row">
          <div class="f-form__field">
            <label for="ff-name"><?php echo esc_html($d['name_label']); ?></label>
            <input type="text" id="ff-name" name="name" placeholder="<?php echo esc_attr($d['name_placeholder']); ?>" required="">
          </div>
          <div class="f-form__field">
            <label for="ff-phone"><?php echo esc_html($d['phone_label']); ?></label>
            <input type="tel" id="ff-phone" name="phone" placeholder="<?php echo esc_attr($d['phone_placeholder']); ?>" required="">
          </div>
        </div>

        <div class="f-form__row f-form__row--full">
          <div class="f-form__field">
            <label for="ff-topic"><?php echo esc_html($d['topic_label']); ?></label>
            <select id="ff-topic" name="topic">
              <?php foreach ($d['topics'] as $t) :
                  $tval = !empty($t['value']) ? $t['value'] : '';
                  $tlbl = !empty($t['label']) ? $t['label'] : $tval;
                  if (!$tval) continue;
              ?>
              <option value="<?php echo esc_attr($tval); ?>"><?php echo esc_html($tlbl); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="f-form__row f-form__row--full">
          <div class="f-form__field">
            <label for="ff-message"><?php echo esc_html($d['message_label']); ?></label>
            <textarea id="ff-message" name="message" placeholder="<?php echo esc_attr($d['message_placeholder']); ?>" rows="4"></textarea>
          </div>
        </div>

        <button class="btn btn--gold f-form__submit" type="submit"><?php echo esc_html($d['submit_text']); ?></button>
      </form>
    </div>

    <p class="f-form__note reveal is-visible"><?php echo wp_kses_post($d['note']); ?></p>
  </div>
</section>