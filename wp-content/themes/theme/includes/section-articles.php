<?php
 $eyebrow    = get_post_meta(get_the_ID(), 'spa_articles_eyebrow', true);
 $title      = get_post_meta(get_the_ID(), 'spa_articles_title', true);
 $gold       = get_post_meta(get_the_ID(), 'spa_articles_gold', true);
 $sec_id     = get_post_meta(get_the_ID(), 'spa_articles_id', true);
 $feat_tag   = get_post_meta(get_the_ID(), 'spa_articles_feat_tag', true);
 $feat_title = get_post_meta(get_the_ID(), 'spa_articles_feat_title', true);
 $feat_text  = get_post_meta(get_the_ID(), 'spa_articles_feat_text', true);
 $feat_link  = get_post_meta(get_the_ID(), 'spa_articles_feat_link', true);
 $feat_img   = get_post_meta(get_the_ID(), 'spa_articles_feat_img', true);
 $rows       = get_post_meta(get_the_ID(), 'spa_articles_rows', true);

 $eyebrow    = !empty($eyebrow)    ? $eyebrow    : 'Читателям';
 $title      = !empty($title)      ? $title      : 'Полезные статьи';
 $gold       = !empty($gold)       ? $gold       : 'статьи';
 $sec_id     = !empty($sec_id)     ? $sec_id     : 'articles';
 $feat_tag   = !empty($feat_tag)   ? $feat_tag   : 'Гид по выбору';
 $rows       = !empty($rows) && is_array($rows) ? $rows : array();
?>

<section class="section acc-articles" id="<?php echo esc_attr($sec_id); ?>">
  <div class="section-head reveal">
    <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
    <h2 class="section-title">
      <?php echo esc_html($title); ?>
      <?php if ($gold) : ?>
        <em><?php echo esc_html($gold); ?></em>
      <?php endif; ?>
    </h2>
  </div>

  <?php if ($feat_img || $feat_title) : ?>
  <div class="acc-articles__layout reveal">
    <article class="acc-feature">
      <?php if ($feat_img) : ?>
        <img class="acc-feature__bg" src="<?php echo esc_url($feat_img); ?>" alt="<?php echo esc_attr($feat_title); ?>" loading="lazy">
      <?php endif; ?>
      <div class="acc-feature__overlay"></div>
      <div class="acc-feature__content">
        <?php if ($feat_tag) : ?>
          <p class="acc-feature__tag"><?php echo esc_html($feat_tag); ?></p>
        <?php endif; ?>
        <?php if ($feat_title) : ?>
          <h3 class="acc-feature__title"><?php echo esc_html($feat_title); ?></h3>
        <?php endif; ?>
        <?php if ($feat_text) : ?>
          <p class="acc-feature__text"><?php echo esc_html($feat_text); ?></p>
        <?php endif; ?>
        <?php if ($feat_link) : ?>
        <a class="acc-feature__link" href="/article/<?php echo esc_attr($feat_link); ?>">
          Читать статью
          <svg class="icon" aria-hidden="true"><use href="#icon-arrow-ur"></use></svg>
        </a>
        <?php endif; ?>
      </div>
    </article>

    <?php if ($rows) : ?>
    <div class="acc-articles__rows">
      <?php foreach ($rows as $r) :
        $num   = !empty($r['num'])   ? esc_html($r['num'])   : '';
        $rtitle = !empty($r['title']) ? esc_html($r['title']) : '';
        $text  = !empty($r['text'])  ? esc_html($r['text'])  : '';
        $link  = !empty($r['link'])  ? esc_attr($r['link'])  : '';
        $href  = $link ? '/article/' . $link : '#';
      ?>
        <article class="acc-row">
          <?php if ($num) : ?>
            <span class="acc-row__num" aria-hidden="true"><?php echo $num; ?></span>
          <?php endif; ?>
          <div class="acc-row__body">
            <?php if ($rtitle) : ?>
              <h3 class="acc-row__title"><a href="<?php echo esc_url($href); ?>"><?php echo $rtitle; ?></a></h3>
            <?php endif; ?>
            <?php if ($text) : ?>
              <p class="acc-row__text"><?php echo $text; ?></p>
            <?php endif; ?>
          </div>
          <svg class="acc-row__arrow icon" aria-hidden="true"><use href="#icon-arrow-ur"></use></svg>
        </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</section>