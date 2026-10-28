<?php
require_once __DIR__ . '/offer-card.php';
 $data = get_post_meta(get_the_ID(), 'acc_offers', true);
if (!is_array($data) || empty($data)) return;

 $eyebrow      = $data['eyebrow'] ?? '';
 $title_before = $data['title_before'] ?? '';
 $title_em     = $data['title_em'] ?? '';
 $sub          = $data['sub'] ?? '';
 $cards        = !empty($data['cards']) && is_array($data['cards']) ? $data['cards'] : [];
?>
<section class="c-offers-acc" id="acc-offers">
    <div class="container">
        <div class="section-head section-head--center reveal">
            <?php if (!empty($eyebrow)) : ?>
                <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
            <?php endif; ?>
            <?php if (!empty($title_before) || !empty($title_em)) : ?>
                <h2 class="section-title">
                    <?php echo esc_html($title_before); ?>
                    <?php if (!empty($title_em)) : ?>
                        <span class="gold-header"><em><?php echo esc_html($title_em); ?></em></span>
                    <?php endif; ?>
                </h2>
            <?php endif; ?>
            <?php if (!empty($sub)) : ?>
                <p class="section-sub"><?php echo esc_html($sub); ?></p>
            <?php endif; ?>
        </div>
        <div class="c-offers-grid reveal">
            <?php foreach ($cards as $index => $card) :
                $modal_id = 'offer-' . get_the_ID() . '-acc-' . $index;
                karpovka_offer_card($card, $modal_id);
            endforeach; ?>
        </div>
    </div>
</section>
<?php foreach ($cards as $index => $card) :
    karpovka_offer_modal($card, 'offer-' . get_the_ID() . '-acc-' . $index);
endforeach; ?>
