<?php
/**
 * Template: Dining Event Formats.
 *
 * Берёт данные из мета-поля dining_formats, заполненного метабоксом
 * /inc/dining_formats.php.
 *
 * ИЗМЕНЕНИЕ: модалки вынесены ИЗ блока .reveal в отдельный блок-сосед.
 * Так position: fixed работает корректно (нет transform у предка).
 */
 $v = get_post_meta( get_the_ID(), 'dining_formats', true ) ?: [];

 $eyebrow    = $v['eyebrow']    ?? '';
 $title      = $v['title']      ?? '';
 $gold_text  = $v['gold_text']  ?? '';
 $subtitle   = $v['subtitle']   ?? '';
 $section_id = $v['section_id'] ?? 'formats';
 $cards      = is_array( $v['cards'] ?? null ) ? $v['cards'] : [];

if ( empty( $cards ) && empty( $eyebrow ) && empty( $title ) ) return;

 $check_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c8a96e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';

/* Соберём данные для модалок в массив — выведем ОТДЕЛЬНО от секции */
 $modals_data = [];
foreach ( $cards as $format_index => $card ) {
    $modals_data[] = [
        'image'       => $card['card_image'] ?? '',
        'tag'         => $card['card_tag'] ?? '',
        'name'        => $card['card_title'] ?? '',
        'desc'        => $card['card_desc'] ?? '',
        'modal_image' => $card['modal_image'] ?? ( $card['card_image'] ?? '' ),
        'modal_badge' => $card['modal_badge'] ?? ( $card['card_tag'] ?? '' ),
        'modal_title' => $card['modal_title'] ?: ( $card['card_title'] ?? '' ),
        'modal_meta'  => $card['modal_meta'] ?? '',
        'modal_desc'  => $card['modal_desc'] ?? '',
        'modal_items' => is_array( $card['modal_items'] ?? null ) ? $card['modal_items'] : [],
        'cta_text'    => $card['cta_text'] ?? 'Обсудить формат',
        'cta_link'    => $card['cta_link'] ?: '#booking',
        'modal_id'    => 'modal-dining-format-' . ( $format_index + 1 ),
    ];
}
?>

<section class="c-dining-formats" id="<?php echo esc_attr( $section_id ); ?>">
  <div class="container">
    <div class="section-head section-head--center reveal">
      <?php if ( $eyebrow ) : ?><p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p><?php endif; ?>
      <?php if ( $title || $gold_text ) : ?>
      <h2 class="section-title">
        <?php echo esc_html( $title ); ?>
        <?php if ( $gold_text ) : ?><span class="gold-header"><em><?php echo esc_html( $gold_text ); ?></em></span><?php endif; ?>
      </h2>
      <?php endif; ?>
      <?php if ( $subtitle ) : ?><p class="section-sub"><?php echo esc_html( $subtitle ); ?></p><?php endif; ?>
    </div>

    <?php if ( $cards ) : ?>
    <div class="c-dining-formats__grid reveal">
      <?php foreach ( $modals_data as $m ) : ?>
      <article class="c-dining-formats__card" data-dining-modal="<?php echo esc_attr( $m['modal_id'] ); ?>" role="button" tabindex="0">
        <div class="c-dining-formats__card-link-wrap">
          <div class="c-dining-formats__card-img">
            <?php if ( $m['image'] ) : ?><img src="<?php echo esc_url( $m['image'] ); ?>" alt="<?php echo esc_attr( $m['name'] ); ?>" loading="lazy"><?php endif; ?>
          </div>
          <div class="c-dining-formats__card-body">
            <?php if ( $m['tag'] ) : ?><span class="c-dining-formats__card-tag"><?php echo esc_html( $m['tag'] ); ?></span><?php endif; ?>
            <?php if ( $m['name'] ) : ?><h3 class="c-dining-formats__card-title"><?php echo esc_html( $m['name'] ); ?></h3><?php endif; ?>
            <?php if ( $m['desc'] ) : ?><p class="c-dining-formats__card-desc"><?php echo esc_html( $m['desc'] ); ?></p><?php endif; ?>
            <button type="button" class="c-dining-formats__card-link">Подробнее <i class="icon-arrow-right"></i></button>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- ═══ Модалки — ВЫНЕСЕНЫ ИЗ СЕКЦИИ (как в spa_programs) ═══ -->
<!-- Так position: fixed работает относительно viewport, а не относительно .reveal -->
<?php foreach ( $modals_data as $m ) : ?>
<div class="dining-modal" id="<?php echo esc_attr( $m['modal_id'] ); ?>" hidden data-dining-modal-close>
  <div class="dining-modal__overlay" data-dining-modal-close></div>
  <div class="dining-modal__dialog">
    <button class="dining-modal__close" type="button" data-dining-modal-close aria-label="Закрыть">&#10005;</button>
    <?php if ( $m['modal_image'] ) : ?>
    <img class="dining-modal__photo" src="<?php echo esc_url( $m['modal_image'] ); ?>" alt="<?php echo esc_attr( $m['modal_title'] ); ?>" loading="lazy">
    <?php endif; ?>
    <div class="dining-modal__body">
      <?php if ( $m['modal_badge'] ) : ?><p class="dining-modal__badge"><?php echo esc_html( $m['modal_badge'] ); ?></p><?php endif; ?>
      <?php if ( $m['modal_title'] ) : ?><h3 class="dining-modal__title"><?php echo esc_html( $m['modal_title'] ); ?></h3><?php endif; ?>
      <?php if ( $m['modal_meta'] ) : ?><p class="dining-modal__meta"><?php echo esc_html( $m['modal_meta'] ); ?></p><?php endif; ?>
      <?php if ( $m['modal_desc'] ) : ?><div class="dining-modal__desc"><?php echo wp_kses_post( $m['modal_desc'] ); ?></div><?php endif; ?>

      <?php if ( ! empty( $m['modal_items'] ) ) : ?>
      <div class="dining-modal__includes">
        <h4 class="dining-modal__includes-title">Что входит</h4>
        <ul class="dining-modal__includes-list">
          <?php foreach ( $m['modal_items'] as $it ) :
              $t = is_array( $it ) ? ( $it['text'] ?? '' ) : $it;
              if ( ! $t ) continue;
          ?>
          <li><?php echo $check_svg; ?> <?php echo esc_html( $t ); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <div class="dining-modal__foot">
        <a href="<?php echo esc_attr( $m['cta_link'] ); ?>" class="dining-modal__cta"><?php echo esc_html( $m['cta_text'] ); ?> <i class="icon-arrow-right"></i></a>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>