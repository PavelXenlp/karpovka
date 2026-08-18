<?php
/**
 * Template: FAQ Section
 */
 $v = get_post_meta( get_the_ID(), 'spa_faq', true ) ?: [];

 $eyebrow    = $v['eyebrow']    ?? '';
 $title      = $v['title']      ?? '';
 $gold_text  = $v['gold_text']  ?? '';
 $subtitle   = $v['subtitle']   ?? '';
 $bg_image   = $v['bg_image']   ?? '';
 $section_id = $v['section_id'] ?? 'faq';
 $btn_text   = $v['btn_text']   ?? '';
 $btn_link   = $v['btn_link']   ?? '';
 $items      = $v['items']      ?? [];

if ( empty( $items ) && empty( $eyebrow ) && empty( $title ) ) return;
?>

<section class="faq" id="<?php echo esc_attr( $section_id ); ?>">
    <?php if ( $bg_image ) : ?>
    <img class="faq__bg" src="<?php echo esc_url( $bg_image ); ?>" alt="" aria-hidden="true" />
    <?php endif; ?>
    <div class="faq__overlay"></div>

    <div class="container faq__inner">
        <div class="faq__head reveal">
            <?php if ( $eyebrow ) : ?>
            <p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
            <?php endif; ?>

            <?php if ( $title || $gold_text ) : ?>
            <h2 class="section-title">
                <?php echo esc_html( $title ); ?><?php if ( $title && $gold_text ) : ?><br /><?php endif; ?>
                <?php if ( $gold_text ) : ?><em><?php echo esc_html( $gold_text ); ?></em><?php endif; ?>
            </h2>
            <?php endif; ?>

            <?php if ( $subtitle ) : ?>
            <p class="faq__sub"><?php echo esc_html( $subtitle ); ?></p>
            <?php endif; ?>
        </div>

        <?php if ( ! empty( $items ) && is_array( $items ) ) : ?>
        <div class="faq__list reveal" data-faq>
            <?php foreach ( $items as $item ) :
                $question = $item['question'] ?? '';
                $answer   = $item['answer']   ?? '';
                if ( ! $question ) continue;
            ?>
            <div class="faq-item" data-faq-item>
                <button class="faq-item__q" type="button" data-faq-toggle aria-expanded="false">
                    <span><?php echo esc_html( $question ); ?></span>
                    <span class="faq-item__icon" aria-hidden="true"></span>
                </button>
                <div class="faq-item__a" data-faq-answer>
                    <p><?php echo wp_kses_post( $answer ); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ( $btn_text && $btn_link ) : ?>
        <div class="faq__more reveal">
            <a class="btn btn--ghost" href="<?php echo esc_url( $btn_link ); ?>"><?php echo esc_html( $btn_text ); ?></a>
        </div>
        <?php endif; ?>
    </div>
</section>