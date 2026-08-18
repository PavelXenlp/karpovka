<?php
// ─── Шаблон секции: Массаж (без плагинов) ────────────────

 $eyebrow   = get_post_meta(get_the_ID(), 'spa_massage_eyebrow', true);
 $title     = get_post_meta(get_the_ID(), 'spa_massage_title', true);
 $gold      = get_post_meta(get_the_ID(), 'spa_massage_gold', true);
 $subtitle  = get_post_meta(get_the_ID(), 'spa_massage_subtitle', true);
 $bg_img    = get_post_meta(get_the_ID(), 'spa_massage_bg_img', true);
 $btn_text  = get_post_meta(get_the_ID(), 'spa_massage_btn_text', true);
 $btn_link  = get_post_meta(get_the_ID(), 'spa_massage_btn_link', true);
 $sec_id    = get_post_meta(get_the_ID(), 'spa_massage_id', true);
 $items     = get_post_meta(get_the_ID(), 'spa_massage_items', true);

 $eyebrow   = !empty($eyebrow)    ? $eyebrow    : 'Массаж';
 $title     = !empty($title)      ? $title      : 'Виды массажа';
 $gold      = !empty($gold)       ? $gold       : 'массажа';
 $subtitle  = !empty($subtitle)   ? $subtitle   : 'От классического до авторских программ — выберите процедуру под ваши задачи';
 $btn_text  = !empty($btn_text)   ? $btn_text   : 'Полный прайс-лист';
 $btn_link  = !empty($btn_link)   ? $btn_link   : '#price';
 $sec_id    = !empty($sec_id)     ? $sec_id     : 'massage';
 $items     = !empty($items) && is_array($items) ? $items : array();
?>

<section class="c-spa-massage" id="<?php echo esc_attr($sec_id); ?>">
  <div class="c-spa-massage__bg">
    <?php if ($bg_img) : ?>
      <img src="<?php echo esc_url($bg_img); ?>" alt="" aria-hidden="true">
    <?php endif; ?>
  </div>
  <div class="container">
    <div class="c-spa-massage__layout reveal">
      <div class="c-spa-massage__visual"></div>
      <div class="c-spa-massage__content">
        <div class="section-head">
          <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
          <h2 class="section-title">
            <?php echo esc_html($title); ?>
            <?php if ($gold) : ?>
              <span class="gold-header"><em><?php echo esc_html($gold); ?></em></span>
            <?php endif; ?>
          </h2>
          <?php if ($subtitle) : ?>
            <p class="section-sub c-spa-massage__sub"><?php echo esc_html($subtitle); ?></p>
          <?php endif; ?>
        </div>

        <?php if ($items) : ?>
        <ul class="c-spa-massage__list">
          <?php foreach ($items as $item) :
            $name  = !empty($item['name'])  ? esc_html($item['name'])  : '';
            $desc  = !empty($item['desc'])  ? esc_html($item['desc'])  : '';
            $dur   = !empty($item['dur'])   ? esc_html($item['dur'])   : '';
            $price = !empty($item['price']) ? esc_html($item['price']) : '';
          ?>
            <li class="c-spa-massage__item">
              <div class="c-spa-massage__item-info">
                <?php if ($name) : ?>
                  <h3 class="c-spa-massage__item-name"><?php echo $name; ?></h3>
                <?php endif; ?>
                <?php if ($desc) : ?>
                  <p class="c-spa-massage__item-desc"><?php echo $desc; ?></p>
                <?php endif; ?>
              </div>
              <?php if ($dur || $price) : ?>
              <div class="c-spa-massage__item-meta">
                <?php if ($dur) : ?>
                  <span class="c-spa-massage__item-dur"><i class="fa-regular fa-clock"></i> <?php echo $dur; ?></span>
                <?php endif; ?>
                <?php if ($price) : ?>
                  <span class="c-spa-massage__item-price"><?php echo $price; ?></span>
                <?php endif; ?>
              </div>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <?php if ($btn_text && $btn_link) : ?>
        <a href="<?php echo esc_url($btn_link); ?>" class="btn btn--ghost c-spa-massage__btn">
          <?php echo esc_html($btn_text); ?> <i class="fa-solid fa-arrow-right"></i>
        </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>