<?php
/**
 * Template: Contacts Section
 */
 karpovka_initialize_home_contacts( get_the_ID() );
 $v = get_post_meta( get_the_ID(), 'contacts_section', true ) ?: [];

 $eyebrow    = $v['eyebrow']    ?? '';
 $title      = $v['title']      ?? '';
 $section_id = $v['section_id'] ?? 'contacts';
 $address    = $v['address']    ?? '';
 $phones     = $v['phones']     ?? [];
 $email      = $v['email']      ?? '';
 $form_title = $v['form_title'] ?? '';
 $form_sub   = $v['form_sub']   ?? '';
 $form_btn   = $v['form_btn']   ?? '';

if ( empty( $eyebrow ) && empty( $title ) && empty( $address ) ) return;
?>

<section id="<?php echo esc_attr( $section_id ); ?>" class="section contacts-section">

  <div class="section-head reveal">
    <?php if ( $eyebrow ) : ?>
    <p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
    <?php endif; ?>

    <?php if ( $title ) : ?>
    <h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
    <?php endif; ?>
  </div>

  <div class="contacts2 reveal">

    <div class="contacts2__aside">
      <div class="contacts2__rows">

        <?php if ( $address ) : ?>
        <div class="contact-item">
          <div class="contact-item__label">Адрес</div>
          <div class="contact-item__value"><?php echo esc_html( $address ); ?></div>
        </div>
        <?php endif; ?>

        <?php if ( ! empty( $phones ) ) : ?>
        <div class="contact-item">
          <div class="contact-item__label">Телефон</div>
          <div class="contact-item__phones">
            <?php foreach ( $phones as $ph ) : ?>
            <a class="contact-item__value" href="<?php echo esc_attr( $ph['href'] ?? '' ); ?>"><?php echo esc_html( $ph['label'] ?? '' ); ?></a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <?php if ( $email ) : ?>
        <a class="contact-item contact-item--link" href="mailto:<?php echo esc_attr( $email ); ?>">
          <div class="contact-item__label">Email</div>
          <div class="contact-item__value"><?php echo esc_html( $email ); ?></div>
        </a>
        <?php endif; ?>

      </div>

      <form class="contacts2__form" data-callback-form>
        <?php if ( $form_title ) : ?>
        <h3 class="contacts2__form-title"><?php echo esc_html( $form_title ); ?></h3>
        <?php endif; ?>
        <?php if ( $form_sub ) : ?>
        <p class="contacts2__form-sub"><?php echo esc_html( $form_sub ); ?></p>
        <?php endif; ?>
        <div class="contacts2__fields">
          <input type="text" name="name" placeholder="Ваше имя" aria-label="Ваше имя" required />
          <input type="tel" name="phone" placeholder="Ваш телефон" aria-label="Ваш телефон" required />
        </div>
        <button class="btn btn--gold" type="submit"><?php echo esc_html( $form_btn ?: 'Перезвоните мне' ); ?></button>
      </form>
    </div>

    <div class="contacts2__map">
      <iframe
        class="contacts2__map-frame"
        src="https://yandex.ru/map-widget/v1/?ll=54.869726%2C58.309505&mode=search&oid=1716447826&ol=biz&z=10.53"
        title="Карта — Парк-отель «Карповка»"
        allowfullscreen
        loading="lazy"
      ></iframe>
    </div>

  </div>
</section>
