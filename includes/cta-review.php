<?php
 $v = get_post_meta( get_the_ID(), 'cta_review_section', true );

if ( empty( $v ) ) return;

 $eyebrow     = esc_html( $v['eyebrow'] ?? '' );
 $title       = esc_html( $v['title'] ?? '' );
 $section_id  = esc_attr( $v['section_id'] ?? 'cta' );
 $bg_img      = esc_url( $v['bg_img'] ?? '' );
 $aside_text  = esc_html( $v['aside_text'] ?? '' );
 $aside_img   = esc_url( $v['aside_img'] ?? '' );
 $form_legend = esc_html( $v['form_legend'] ?? '' );
 $name_label  = esc_html( $v['name_label'] ?? 'Ваше имя' );
 $name_ph     = esc_attr( $v['name_ph'] ?? '' );
 $phone_label = esc_html( $v['phone_label'] ?? 'Телефон' );
 $phone_ph    = esc_attr( $v['phone_ph'] ?? '' );
 $rev_label   = esc_html( $v['review_label'] ?? 'Отзыв' );
 $rev_ph      = esc_attr( $v['review_ph'] ?? '' );
 $rev_rows    = intval( $v['review_rows'] ?? 4 );
 $submit_text = esc_html( $v['submit_text'] ?? 'Отправить' );
 $priv_text   = $v['priv_text'] ?? '';
 $priv_link   = esc_html( $v['priv_link'] ?? '' );
 $priv_url    = esc_url( $v['priv_url'] ?? '#' );
 $or_text     = esc_html( $v['or_text'] ?? '' );
 $actions     = $v['actions'] ?? '';
?>

<section class="c-cta-review" id="<?php echo $section_id; ?>">
    <?php if ( $bg_img ) : ?>
        <img class="c-hero__photo" src="<?php echo $bg_img; ?>" alt="" loading="eager">
    <?php endif; ?>
    <div class="c-hero__overlay"></div>
    <div class="container">
        <div class="c-cta-review__head reveal">
            <?php if ( $eyebrow ) : ?>
                <p class="eyebrow"><?php echo $eyebrow; ?></p>
            <?php endif; ?>
            <?php if ( $title ) : ?>
                <h2 class="section-title"><?php echo $title; ?></h2>
            <?php endif; ?>
        </div>

        <div class="c-cta-review__layout">
            <div class="c-cta-review__aside reveal">
                <?php if ( $aside_text ) : ?>
                    <p class="c-cta-review__text"><?php echo $aside_text; ?></p>
                <?php endif; ?>

                <?php if ( $aside_img ) : ?>
                    <img class="c-cta-review__photo" src="<?php echo $aside_img; ?>" alt="" loading="lazy" />
                <?php endif; ?>
            </div>

            <div class="c-cta-review__main">
                <form class="r-form form-background reveal" data-callback-form data-validate novalidate>
                    <?php if ( $form_legend ) : ?>
                        <p class="r-form__legend"><?php echo $form_legend; ?></p>
                    <?php endif; ?>

                    <div class="r-form__row r-form__row--full">
                        <div class="r-form__field">
                            <span class="r-form__label">Оценка</span>
                            <div class="r-rating" data-rating>
                                <input class="r-rating__input" type="radio" id="rf-star5" name="rating" value="5" required />
                                <label class="r-rating__star" for="rf-star5" title="Отлично" aria-label="5 из 5">★</label>
                                <input class="r-rating__input" type="radio" id="rf-star4" name="rating" value="4" />
                                <label class="r-rating__star" for="rf-star4" title="Хорошо" aria-label="4 из 5">★</label>
                                <input class="r-rating__input" type="radio" id="rf-star3" name="rating" value="3" />
                                <label class="r-rating__star" for="rf-star3" title="Нормально" aria-label="3 из 5">★</label>
                                <input class="r-rating__input" type="radio" id="rf-star2" name="rating" value="2" />
                                <label class="r-rating__star" for="rf-star2" title="Плохо" aria-label="2 из 5">★</label>
                                <input class="r-rating__input" type="radio" id="rf-star1" name="rating" value="1" />
                                <label class="r-rating__star" for="rf-star1" title="Ужасно" aria-label="1 из 5">★</label>
                            </div>
                            <span class="r-form__error" data-error-for="rating"></span>
                        </div>
                    </div>

                    <div class="r-form__row">
                        <div class="r-form__field">
                            <label for="rf-name"><?php echo $name_label; ?></label>
                            <input type="text" id="rf-name" name="name" placeholder="<?php echo $name_ph; ?>" required />
                            <span class="r-form__error" data-error-for="rf-name"></span>
                        </div>
                        <div class="r-form__field">
                            <label for="rf-phone"><?php echo $phone_label; ?></label>
                            <input type="tel" id="rf-phone" name="phone" placeholder="<?php echo $phone_ph; ?>" inputmode="tel" autocomplete="tel" data-phone-mask required />
                            <span class="r-form__error" data-error-for="rf-phone"></span>
                        </div>
                    </div>

                    <div class="r-form__row r-form__row--full">
                        <div class="r-form__field">
                            <label for="rf-text"><?php echo $rev_label; ?></label>
                            <textarea id="rf-text" name="text" rows="<?php echo $rev_rows; ?>" placeholder="<?php echo $rev_ph; ?>" required minlength="10"></textarea>
                            <span class="r-form__error" data-error-for="rf-text"></span>
                        </div>
                    </div>

                    <button class="btn btn--gold r-form__submit" type="submit"><?php echo $submit_text; ?></button>
                    <?php if ( $priv_text || $priv_link ) : ?>
                        <p class="r-form__note"><?php echo $priv_text; ?> <a href="<?php echo $priv_url; ?>"><?php echo $priv_link; ?></a></p>
                    <?php endif; ?>
                </form>

                <?php if ( $or_text || ! empty( $actions ) ) : ?>
                    <p class="c-cta-review__or reveal"><span><?php echo $or_text ?: 'или'; ?></span></p>
                <?php endif; ?>

                <?php if ( ! empty( $actions ) ) : ?>
                    <div class="c-cta-review__actions reveal">
                        <?php foreach ( $actions as $a ) :
                            $a_text  = esc_html( $a['text'] ?? '' );
                            $a_link  = esc_url( $a['link'] ?? '#' );
                            $a_style = ( $a['style'] ?? 'gold' ) === 'ghost' ? 'btn--ghost' : 'btn--gold';
                        ?>
                            <a href="<?php echo $a_link; ?>" class="btn <?php echo $a_style; ?>" target="_blank" rel="noopener"><?php echo $a_text; ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>