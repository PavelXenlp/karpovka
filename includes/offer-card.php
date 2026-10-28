<?php

function karpovka_offer_card($card, $modal_id)
{
    $badge_class = 'c-offers-card__badge' . (! empty($card['badge_hot']) ? ' c-offers-card__badge--hot' : '');
    ?>
    <article class="c-offers-card" role="button" tabindex="0" data-offers-modal="<?php echo esc_attr($modal_id); ?>" aria-haspopup="dialog" aria-label="Подробнее: <?php echo esc_attr($card['title'] ?? ''); ?>">
        <div class="c-offers-card__img">
            <?php if (! empty($card['image'])) : ?><img src="<?php echo esc_url($card['image']); ?>" alt="" loading="lazy"><?php endif; ?>
            <?php if (! empty($card['badge_text'])) : ?><span class="<?php echo esc_attr($badge_class); ?>"><?php echo esc_html($card['badge_text']); ?></span><?php endif; ?>
        </div>
        <div class="c-offers-card__body">
            <h3 class="c-offers-card__title"><?php echo esc_html($card['title'] ?? ''); ?></h3>
            <?php if (! empty($card['desc'])) : ?><p class="c-offers-card__desc"><?php echo esc_html($card['desc']); ?></p><?php endif; ?>
            <?php if (! empty($card['conditions']) && is_array($card['conditions'])) : ?>
                <div class="c-offers-card__conditions">
                    <?php foreach ($card['conditions'] as $condition) : ?>
                        <span class="c-offers-card__cond">
                            <?php if (! empty($condition['icon'])) : ?><i class="<?php echo esc_attr($condition['icon']); ?>" aria-hidden="true"></i><?php endif; ?>
                            <?php echo esc_html($condition['text'] ?? ''); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <span class="c-offers-card__link">Подробнее <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
        </div>
    </article>
    <?php
}

function karpovka_offer_modal($card, $modal_id)
{
    $details = ! empty($card['modal_text']) ? $card['modal_text'] : ($card['desc'] ?? '');
    $link = $card['link'] ?? '';
    ?>
    <div class="offers-modal" id="<?php echo esc_attr($modal_id); ?>" hidden>
        <div class="offers-modal__overlay" data-offers-modal-close></div>
        <div class="offers-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr($modal_id . '-title'); ?>" tabindex="-1">
            <button class="offers-modal__close" type="button" data-offers-modal-close aria-label="Закрыть">&times;</button>
            <?php if (! empty($card['image'])) : ?><img class="offers-modal__photo" src="<?php echo esc_url($card['image']); ?>" alt="" loading="lazy"><?php endif; ?>
            <div class="offers-modal__body">
                <?php if (! empty($card['badge_text'])) : ?><span class="offers-modal__badge"><?php echo esc_html($card['badge_text']); ?></span><?php endif; ?>
                <h2 class="offers-modal__title" id="<?php echo esc_attr($modal_id . '-title'); ?>"><?php echo esc_html($card['title'] ?? ''); ?></h2>
                <?php if ($details) : ?><div class="offers-modal__desc"><?php echo wpautop(esc_html($details)); ?></div><?php endif; ?>
                <?php if (! empty($card['conditions']) && is_array($card['conditions'])) : ?>
                    <div class="offers-modal__conditions">
                        <?php foreach ($card['conditions'] as $condition) : ?>
                            <span class="offers-modal__cond">
                                <?php if (! empty($condition['icon'])) : ?><i class="<?php echo esc_attr($condition['icon']); ?>" aria-hidden="true"></i><?php endif; ?>
                                <?php echo esc_html($condition['text'] ?? ''); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="offers-modal__foot">
                    <?php if ($link && '#' !== $link[0]) : ?>
                        <a class="btn btn--gold" href="<?php echo esc_url($link); ?>">Перейти к предложению</a>
                    <?php else : ?>
                        <button class="btn btn--gold js-open-modal-booking" type="button" data-offers-modal-book>Забронировать</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
