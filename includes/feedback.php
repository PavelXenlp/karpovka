<?php
 $v = get_post_meta( get_the_ID(), 'feedback_form', true );

if ( empty( $v ) ) return;

 $label       = esc_html( $v['label'] ?? '' );
 $title       = wp_kses_post( $v['title'] ?? '' );
 $desc        = esc_html( $v['desc'] ?? '' );
 $contacts    = $v['contacts'] ?? [];
 $name_label  = esc_html( $v['name_label'] ?? 'Ваше имя' );
 $name_ph     = esc_attr( $v['name_ph'] ?? '' );
 $phone_label = esc_html( $v['phone_label'] ?? 'Телефон' );
 $phone_ph    = esc_attr( $v['phone_ph'] ?? '' );
 $email_label = esc_html( $v['email_label'] ?? 'E-mail' );
 $email_opt   = esc_html( $v['email_opt'] ?? '' );
 $email_ph    = esc_attr( $v['email_ph'] ?? '' );
 $topic_label = esc_html( $v['topic_label'] ?? 'Тема обращения' );
 $topic_opts  = $v['topic_opts'] ?? [];
 $msg_label   = esc_html( $v['msg_label'] ?? 'Сообщение' );
 $msg_ph      = esc_attr( $v['msg_ph'] ?? '' );
 $submit_text = esc_html( $v['submit_text'] ?? 'Отправить' );
 $priv_text   = $v['priv_text'] ?? '';
 $priv_link   = esc_html( $v['priv_link'] ?? '' );
 $priv_url    = esc_url( $v['priv_url'] ?? '#' );
 $ok_title    = esc_html( $v['ok_title'] ?? '' );
 $ok_text     = esc_html( $v['ok_text'] ?? '' );
 $section_id  = esc_attr( $v['section_id'] ?? 'feedback' );

if ( empty( $title ) && empty( $desc ) && empty( $contacts ) ) return;
?>

<section class="c-form-section" id="<?php echo $section_id; ?>">
    <div class="c-form-section__bg"></div>
    <div class="container">
        <div class="c-form-layout reveal">

            <!-- Левая колонка -->
            <div class="c-form-layout__text">
                <?php if ( $label ) : ?>
                    <div class="c-form-layout__label"><?php echo $label; ?></div>
                <?php endif; ?>
                <?php if ( $title ) : ?>
                    <h2 class="c-form-layout__title"><?php echo $title; ?></h2>
                <?php endif; ?>
                <?php if ( $desc ) : ?>
                    <p class="c-form-layout__desc"><?php echo $desc; ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $contacts ) ) : ?>
                    <ul class="c-form-layout__contacts">
                        <?php foreach ( $contacts as $c ) :
                            $href = esc_url( $c['href'] ?? '' );
                            $text = esc_html( $c['text'] ?? '' );
                            $is_email = ( strpos( $href, 'mailto:' ) === 0 );
                        ?>
                            <li>
                                <?php if ( $is_email ) : ?>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                <?php else : ?>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.11 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                <?php endif; ?>
                                <a href="<?php echo $href; ?>"><?php echo $text; ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Правая колонка — форма -->
            <div class="c-form-layout__form-wrap">
                <form class="c-form" data-callback-form>
                    <div class="c-form__row c-form__row--2col">
                        <div class="c-form__field">
                            <label for="cf-name"><?php echo $name_label; ?></label>
                            <input type="text" id="cf-name" name="name" placeholder="<?php echo $name_ph; ?>" required>
                        </div>
                        <div class="c-form__field">
                            <label for="cf-phone"><?php echo $phone_label; ?></label>
                            <input type="tel" id="cf-phone" name="phone" placeholder="<?php echo $phone_ph; ?>" required>
                        </div>
                    </div>
                    <div class="c-form__row">
                        <div class="c-form__field">
                            <label for="cf-email"><?php echo $email_label; ?><?php if ( $email_opt ) : ?> <span class="c-form__optional"><?php echo $email_opt; ?></span><?php endif; ?></label>
                            <input type="email" id="cf-email" name="email" placeholder="<?php echo $email_ph; ?>">
                        </div>
                    </div>
                    <?php if ( ! empty( $topic_opts ) ) : ?>
                        <div class="c-form__row">
                            <div class="c-form__field">
                                <label for="cf-topic"><?php echo $topic_label; ?></label>
                                <div class="c-form__select-wrap">
                                    <select id="cf-topic" name="topic">
                                        <?php foreach ( $topic_opts as $opt ) : ?>
                                            <option value="<?php echo esc_attr( $opt['value'] ?? '' ); ?>"><?php echo esc_html( $opt['text'] ?? '' ); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <svg class="c-form__select-arrow" width="14" height="8" viewBox="0 0 14 8" fill="none"><path d="M1 1.5L7 6.5L13 1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="c-form__row">
                        <div class="c-form__field">
                            <label for="cf-message"><?php echo $msg_label; ?></label>
                            <textarea id="cf-message" name="message" placeholder="<?php echo $msg_ph; ?>" rows="4"></textarea>
                        </div>
                    </div>
                    <button class="btn btn--gold c-form__submit" type="submit"><?php echo $submit_text; ?></button>
                    <?php if ( $priv_text || $priv_link ) : ?>
                        <p class="c-form__note reveal"><?php echo $priv_text; ?> <a href="<?php echo $priv_url; ?>"><?php echo $priv_link; ?></a></p>
                    <?php endif; ?>
                </form>

                <div class="c-form__success" id="formSuccess">
                    <div class="c-form__success-icon">
                        <svg><use href="#i-check"></use></svg>
                    </div>
                    <?php if ( $ok_title ) : ?><h3><?php echo $ok_title; ?></h3><?php endif; ?>
                    <?php if ( $ok_text ) : ?><p><?php echo $ok_text; ?></p><?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>