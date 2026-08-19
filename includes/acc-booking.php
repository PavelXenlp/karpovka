<?php
// Template: Бронирование (подбор проживания)
 $v = get_post_meta( get_the_ID(), 'acc_booking', true );
if ( empty( $v ) ) return;

 $eyebrow      = $v['eyebrow'] ?? '';
 $title        = $v['title'] ?? '';
 $lead         = $v['lead'] ?? '';
 $points       = $v['points'] ?? [];
 $phones       = $v['phones'] ?? [];
 $fields_raw   = $v['fields'] ?? [];
 $format_opts  = $v['format_opts'] ?? [];
 $submit_text  = $v['submit_text'] ?? 'Отправить заявку';
 $privacy_text = $v['privacy_text'] ?? '';
 $privacy_link = $v['privacy_link'] ?? '#';

// Индексируем поля по ключу для быстрого доступа
 $fields_idx = [];
foreach ( $fields_raw as $f ) {
    $fields_idx[ $f['key'] ?? '' ] = $f;
}
 $field = function( $key, $prop, $fallback = '' ) use ( $fields_idx ) {
    return $fields_idx[ $key ][ $prop ] ?? $fallback;
};
?>

<section class="acc-booking" id="booking">
    <div class="acc-booking__inner">
        <div class="acc-booking__copy reveal">
            <?php if ( $eyebrow ) : ?>
            <p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
            <?php endif; ?>

            <?php if ( $title ) : ?>
            <h2 class="section-title acc-booking__title"><?php echo wp_kses_post( $title ); ?></h2>
            <?php endif; ?>

            <?php if ( $lead ) : ?>
            <p class="section-sub acc-booking__lead"><?php echo esc_html( $lead ); ?></p>
            <?php endif; ?>

            <?php if ( ! empty( $points ) ) : ?>
            <ul class="acc-booking__points">
                <?php foreach ( $points as $pt ) :
                    $t = $pt['text'] ?? '';
                    if ( $t === '' ) continue;
                ?>
                <li><svg class="icon" aria-hidden="true"><use href="#icon-check"></use></svg><?php echo esc_html( $t ); ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>

            <?php if ( ! empty( $phones ) ) : ?>
            <div class="acc-booking__contacts">
                <?php foreach ( $phones as $ph ) :
                    $num  = $ph['number'] ?? '';
                    $disp = $ph['display'] ?? $num;
                    if ( $num === '' ) continue;
                ?>
                <a class="acc-booking__phone" href="tel:<?php echo esc_attr( $num ); ?>"><?php echo esc_html( $disp ); ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="acc-form form-background reveal">
            <form data-callback-form>
                <div class="acc-form__row">
                    <div class="acc-form__field">
                        <label for="af-name"><?php echo esc_html( $field( 'name', 'label', 'Ваше имя' ) ); ?></label>
                        <input type="text" id="af-name" name="name" placeholder="<?php echo esc_attr( $field( 'name', 'placeholder', '' ) ); ?>" required />
                    </div>
                    <div class="acc-form__field">
                        <label for="af-phone"><?php echo esc_html( $field( 'phone', 'label', 'Телефон' ) ); ?></label>
                        <input type="tel" id="af-phone" name="phone" placeholder="<?php echo esc_attr( $field( 'phone', 'placeholder', '' ) ); ?>" required />
                    </div>
                </div>

                <div class="acc-form__row">
                    <div class="acc-form__field">
                        <label for="af-checkin"><?php echo esc_html( $field( 'checkin', 'label', 'Заезд' ) ); ?></label>
                        <input type="date" id="af-checkin" name="checkin" />
                    </div>
                    <div class="acc-form__field">
                        <label for="af-checkout"><?php echo esc_html( $field( 'checkout', 'label', 'Выезд' ) ); ?></label>
                        <input type="date" id="af-checkout" name="checkout" />
                    </div>
                </div>

                <div class="acc-form__row">
                    <div class="acc-form__field">
                        <label for="af-guests"><?php echo esc_html( $field( 'guests', 'label', 'Гостей' ) ); ?></label>
                        <input type="number" id="af-guests" name="guests" min="1" max="103" placeholder="<?php echo esc_attr( $field( 'guests', 'placeholder', '' ) ); ?>" />
                    </div>
                    <div class="acc-form__field">
                        <label for="af-format"><?php echo esc_html( $field( 'format', 'label', 'Формат' ) ); ?></label>
                        <select id="af-format" name="format">
                            <?php foreach ( $format_opts as $opt ) : ?>
                            <option value="<?php echo esc_attr( $opt['value'] ?? '' ); ?>"><?php echo esc_html( $opt['text'] ?? '' ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="acc-form__row acc-form__row--full">
                    <div class="acc-form__field">
                        <label for="af-comment"><?php echo esc_html( $field( 'comment', 'label', 'Комментарий' ) ); ?></label>
                        <textarea id="af-comment" name="comment" rows="3" placeholder="<?php echo esc_attr( $field( 'comment', 'placeholder', '' ) ); ?>"></textarea>
                    </div>
                </div>

                <button class="btn btn--gold acc-form__submit" type="submit"><?php echo esc_html( $submit_text ); ?></button>
                <?php if ( $privacy_text ) : ?>
                <p class="acc-form__note"><?php echo esc_html( $privacy_text ); ?> <a href="<?php echo esc_url( $privacy_link ); ?>">политикой конфиденциальности</a></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</section>