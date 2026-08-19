<?php
$v = get_post_meta(get_the_ID(), 'combined_section', true);

if (empty($v) || (empty($v['stay_options']) && empty($v['rec_title']))) return;

$section_id   = esc_attr($v['section_id'] ?? 'accommodation');
// Проживание
$stay_eyebrow = esc_html($v['stay_eyebrow'] ?? '');
$stay_title   = $v['stay_title'] ?? '';
$stay_sub     = esc_html($v['stay_sub'] ?? '');
$stay_options = $v['stay_options'] ?? [];
$stay_btn_txt = esc_html($v['stay_btn_text'] ?? '');
$stay_btn_lnk = esc_url($v['stay_btn_link'] ?? '#');
// Активный отдых
$rec_eyebrow  = esc_html($v['rec_eyebrow'] ?? '');
$rec_title    = $v['rec_title'] ?? '';
$rec_sub      = esc_html($v['rec_sub'] ?? '');
$rec_img      = esc_url($v['rec_img'] ?? '');
$seasons      = $v['seasons'] ?? [];
$activities   = $v['activities'] ?? '';

// Определяем активный вариант проживания (для начального отображения)
$active_stay = null;
foreach ($stay_options as $o) {
  if (! empty($o['is_active'])) {
    $active_stay = $o;
    break;
  }
}
if (! $active_stay && ! empty($stay_options)) $active_stay = $stay_options[0];
?>

<section id="<?php echo $section_id; ?>" class="combined">

  <!-- ═══ Проживание ═══ -->
  <?php if (! empty($stay_options)) : ?>
    <div class="combined-row">
      <div class="combined-row__text reveal">
        <?php if ($stay_eyebrow) : ?>
          <p class="eyebrow"><?php echo $stay_eyebrow; ?></p>
        <?php endif; ?>
        <?php if ($stay_title) : ?>
          <h2 class="section-title"><?php echo wp_kses_post($stay_title); ?></h2>
        <?php endif; ?>
        <?php if ($stay_sub) : ?>
          <p class="section-sub combined-row__sub"><?php echo $stay_sub; ?></p>
        <?php endif; ?>

        <ul class="stay-list" id="stayList">
          <?php foreach ($stay_options as $o) :
            $cls = 'stay-option';
            if (! empty($o['is_active'])) $cls .= ' is-active';
          ?>
            <li
              class="<?php echo $cls; ?>"
              data-img="<?php echo esc_url($o['img'] ?? ''); ?>"
              data-title="<?php echo esc_attr($o['card_title'] ?? ''); ?>"
              data-caption="<?php echo esc_attr($o['caption'] ?? ''); ?>"
              data-desc="<?php echo esc_attr($o['desc'] ?? ''); ?>"
              data-price="<?php echo esc_attr($o['card_price'] ?? ''); ?>">
              <span class="stay-option__name"><?php echo esc_html($o['name'] ?? ''); ?></span>
              <span class="stay-option__price"><?php echo esc_html($o['price'] ?? ''); ?></span>
            </li>
          <?php endforeach; ?>
        </ul>

        <?php if ($stay_btn_txt) : ?>
          <a class="btn btn--ghost stay-list__more" href="<?php echo $stay_btn_lnk; ?>"><?php echo $stay_btn_txt; ?></a>
        <?php endif; ?>
      </div>

      <div class="combined-row__media stay-media reveal">
        <?php if ($active_stay) : ?>
          <img class="stay-media__img" id="stayImg" src="<?php echo esc_url($active_stay['img'] ?? ''); ?>" alt="<?php echo esc_attr($active_stay['card_title'] ?? ''); ?>" />
          <div class="stay-media__card" id="stayCard">
            <h3 class="stay-media__title"><?php echo esc_html($active_stay['card_title'] ?? ''); ?></h3>
            <?php if (! empty($active_stay['caption'])) : ?>
              <p class="stay-media__caption"><?php echo esc_html($active_stay['caption']); ?></p>
            <?php endif; ?>
            <?php if (! empty($active_stay['desc'])) : ?>
              <p class="stay-media__desc"><?php echo esc_html($active_stay['desc']); ?></p>
            <?php endif; ?>
            <?php if (! empty($active_stay['card_price'])) : ?>
              <strong class="stay-media__price"><?php echo esc_html($active_stay['card_price']); ?></strong>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>

  <!-- ═══ Активный отдых ═══ -->
  <?php if ($rec_title || ! empty($seasons) || ! empty($activities)) : ?>
    <div class="combined-row" id="recreation">
      <div class="combined-row__media season-media reveal">
        <?php if ($rec_img) : ?>
          <img class="season-media__img" id="seasonImg" src="<?php echo $rec_img; ?>" alt="" />
        <?php endif; ?>
      </div>
      <div class="combined-row__text combined-row__text-active-rest reveal">
        <?php if ($rec_eyebrow) : ?>
          <p class="eyebrow"><?php echo $rec_eyebrow; ?></p>
        <?php endif; ?>
        <?php if ($rec_title) : ?>
          <h2 class="section-title"><?php echo wp_kses_post($rec_title); ?></h2>
        <?php endif; ?>
        <?php if ($rec_sub) : ?>
          <p class="section-sub combined-row__sub"><?php echo $rec_sub; ?></p>
        <?php endif; ?>

        <?php if (! empty($seasons)) : ?>
          <div class="season-toggle season-toggle--left">
            <div class="season-toggle__group">
              <?php foreach ($seasons as $s) :
                $s_cls = 'season-btn';
                if (! empty($s['is_active'])) $s_cls .= ' is-active';
              ?>
                <button class="<?php echo $s_cls; ?>" type="button" data-season="<?php echo esc_attr($s['slug'] ?? ''); ?>">
                  <?php echo esc_html($s['text'] ?? ''); ?>
                </button>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

                    <?php if ( ! empty( $activities ) ) : ?>
                <ul class="activity-list" id="activityList"></ul>

                <script>
                window.wpSeasonActivities = <?php
                    $json = [];
                    foreach ( $activities as $a ) {
                        $json[] = [
                            'text'   => $a['text'] ?? '',
                            'season' => $a['season'] ?? '',
                        ];
                    }
                    echo wp_json_encode( $json, JSON_UNESCAPED_UNICODE );
                ?>;
                </script>
            <?php else : ?>
                <ul class="activity-list" id="activityList"></ul>
            <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>

</section>