<?php
 $over      = get_post_meta(get_the_ID(), 'wg_over', true);
 $title     = get_post_meta(get_the_ID(), 'wg_title', true);
 $gold      = get_post_meta(get_the_ID(), 'wg_gold', true);
 $sub       = get_post_meta(get_the_ID(), 'wg_sub', true);
 $images    = get_post_meta(get_the_ID(), 'wg_images', true);
 $bg_image  = get_post_meta(get_the_ID(), 'wg_bg_image', true);
 $intro     = get_post_meta(get_the_ID(), 'wg_intro', true);
 $desc      = get_post_meta(get_the_ID(), 'wg_desc', true);
 $advs      = get_post_meta(get_the_ID(), 'wg_advantages', true);
 $feats     = get_post_meta(get_the_ID(), 'wg_features', true);

if (!is_array($images)) $images = array();
if (!is_array($advs))   $advs   = array();
if (!is_array($feats))  $feats  = array();

 $has_content = $intro || $desc || $advs || $feats;
 $has_right   = $advs || $feats;
 $has_bg      = !empty($bg_image);

if (!$over && !$title && !$gold && !$sub && !$images && !$has_content) return;
?>

<section class="section wg-section">

        <?php if ($has_bg) : ?>
    <div class="wg-bg">
        <img src="<?php echo esc_url($bg_image); ?>" alt="" aria-hidden="true">
    </div>
    <div class="wg-bg-overlay"></div>
    <?php endif; ?>

    <div class="container wg-section__inner">
        <div class="section-head reveal">
            <?php if ($over) : ?>
                <span class="section-over"><?php echo esc_html($over); ?></span>
            <?php endif; ?>

            <?php if ($title || $gold) : ?>
                <h2 class="section-title">
                    <?php echo esc_html($title); ?>
                    <?php if ($gold) : ?>
                        <br><em><?php echo esc_html($gold); ?></em>
                    <?php endif; ?>
                </h2>
            <?php endif; ?>

            <?php if ($sub) : ?>
                <p class="section-sub"><?php echo esc_html($sub); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Swiper-карусель -->
    <?php if ($images) : ?>
    <div class="wg-slider swiper wg-section__inner" data-wg-gallery>
        <div class="swiper-wrapper">
            <?php foreach ($images as $img) : ?>
                <div class="swiper-slide wg-slide">
                    <img src="<?php echo esc_url($img); ?>" alt="" loading="lazy">
                </div>
            <?php endforeach; ?>
        </div>
        <button class="wg-nav wg-nav--prev" type="button" aria-label="Предыдущее изображение">
            <svg class="icon"><use href="#icon-arrow-left"></use></svg>
        </button>
        <button class="wg-nav wg-nav--next" type="button" aria-label="Следующее изображение">
            <svg class="icon"><use href="#icon-arrow-right"></use></svg>
        </button>
    </div>
    <?php endif; ?>

    <!-- Двухколоночный контент -->
    <?php if ($has_content) : ?>
    <div class="container wg-section__inner">
        <div class="wg-content reveal<?php echo $has_right ? ' wg-content--cols' : ''; ?>">

            <div class="wg-content__left">
                <?php if ($intro) : ?>
                    <div class="wg-intro"><?php echo wp_kses_post($intro); ?></div>
                <?php endif; ?>

                <?php if ($desc) : ?>
                    <div class="wg-desc"><?php echo wp_kses_post($desc); ?></div>
                <?php endif; ?>
            </div>

                        <?php if ($has_right) : ?>
            <div class="wg-content__right">

                <?php if ($advs) : ?>
                <div class="wg-advantages">
                    <?php foreach ($advs as $adv) : ?>
                        <div class="wg-adv">
                            <span class="wg-adv-check"><i class="fa-solid fa-check"></i></span>
                            <span class="wg-adv-text"><?php echo esc_html($adv); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if ($feats) : ?>
                <div class="wg-features">
                    <?php foreach ($feats as $feat) : ?>
                        <div class="wg-feature"><?php echo wp_kses_post($feat); ?></div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

            </div>
            <?php endif; ?>

        </div>
    </div>
    <?php endif; ?>
</section>

<?php if ($images) : ?>
<script>
(function() {
    if (window.__wgGalleryReady) return;
    window.__wgGalleryReady = true;

    document.addEventListener('DOMContentLoaded', function() {
        var el = document.querySelector('[data-wg-gallery]');
        if (!el || typeof Swiper === 'undefined') return;

        new Swiper(el, {
            slidesPerView: 3,
            spaceBetween: 16,
            loop: false,
            navigation: {
                prevEl: '.wg-nav--prev',
                nextEl: '.wg-nav--next'
            },
            breakpoints: {
                0:   { slidesPerView: 1, spaceBetween: 12 },
                640: { slidesPerView: 2, spaceBetween: 14 },
                960: { slidesPerView: 3, spaceBetween: 16 }
            }
        });
    });
})();
</script>
<?php endif; ?>