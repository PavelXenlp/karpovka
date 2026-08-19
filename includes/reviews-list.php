<?php
 $v = get_post_meta( get_the_ID(), 'reviews_list', true );

if ( empty( $v ) || empty( $v['reviews'] ) ) return;

 $eyebrow    = esc_html( $v['eyebrow'] ?? '' );
 $title      = $v['title'] ?? ''; // wp_kses_post already in template
 $section_id = esc_attr( $v['section_id'] ?? 'reviews-list' );
 $filters    = $v['filters'] ?? [];
 $reviews    = $v['reviews'] ?? '';
?>

<section class="section c-reviews-section" id="<?php echo $section_id; ?>">
    <div class="container">
        <div class="section-head reveal">
            <?php if ( $eyebrow ) : ?>
                <p class="eyebrow"><?php echo $eyebrow; ?></p>
            <?php endif; ?>
            <?php if ( $title ) : ?>
                <h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2>
            <?php endif; ?>
        </div>

        <?php if ( ! empty( $filters ) ) : ?>
            <div class="c-reviews__filters reveal" id="filters">
                <?php foreach ( $filters as $f ) :
                    $slug  = esc_attr( $f['slug'] ?? '' );
                    $text  = esc_html( $f['text'] ?? '' );
                    $class = 'f-cat c-reviews__filter';
                    if ( ! empty( $f['is_active'] ) ) $class .= ' is-active';
                ?>
                    <button class="<?php echo $class; ?>" data-filter="<?php echo $slug; ?>"><?php echo $text; ?></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="c-reviews__list" id="reviewsList">

            <?php foreach ( $reviews as $r ) :
                $category    = esc_attr( $r['category'] ?? '' );
                $tag         = esc_html( $r['tag'] ?? '' );
                $star_count  = intval( $r['star_count'] ?? 5 );
                $has_partial = ! empty( $r['has_partial'] );
                $fill        = esc_attr( $r['partial_fill'] ?? '80%' );
                $text        = esc_html( $r['text'] ?? '' );
                $name        = esc_html( $r['name'] ?? '' );
                $avatar      = esc_html( $r['avatar'] ?? '' );
                $date        = esc_html( $r['date'] ?? '' );
                $source      = esc_html( $r['source'] ?? '' );

                // Генерация инициалов из имени если не указаны
                if ( empty( $avatar ) && ! empty( $name ) ) {
                    $parts = preg_split( '/\s+/', $name );
                    $init  = '';
                    if ( ! empty( $parts[0] ) ) $init .= mb_substr( $parts[0], 0, 1 );
                    if ( ! empty( $parts[1] ) ) $init .= mb_substr( $parts[1], 0, 1 );
                    $avatar = mb_strtoupper( $init );
                }
            ?>
                <article class="c-review reveal" data-category="<?php echo $category; ?>">
                    <div class="c-review__top">
                        <div class="c-review__stars">
                            <?php
                            $full = $has_partial ? ( $star_count - 1 ) : $star_count;
                            for ( $s = 1; $s <= $full; $s++ ) :
                            ?>
                                <svg class="c-rating__star" viewBox="0 0 24 24"><use href="#i-star"></use></svg>
                            <?php endfor; ?>
                            <?php if ( $has_partial ) : ?>
                                <svg class="c-rating__star c-rating__star--partial" viewBox="0 0 24 24" style="--fill:<?php echo $fill; ?>"><use href="#i-star"></use></svg>
                            <?php endif; ?>
                        </div>
                        <?php if ( $tag ) : ?>
                            <span class="c-review__tag"><?php echo $tag; ?></span>
                        <?php endif; ?>
                    </div>
                    <blockquote class="c-review__text">
                        <svg class="c-review__quote-icon" aria-hidden="true"><use href="#i-quote"></use></svg>
                        <?php echo $text; ?>
                    </blockquote>
                    <div class="c-review__footer">
                        <div class="c-review__author">
                            <div class="c-review__avatar"><?php echo $avatar; ?></div>
                            <div>
                                <div class="c-review__name"><?php echo $name; ?></div>
                                <?php if ( $date ) : ?>
                                    <div class="c-review__date"><?php echo $date; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ( $source ) : ?>
                            <span class="c-review__source"><?php echo $source; ?></span>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>

        </div><!-- /c-reviews__list -->
    </div>
</section>