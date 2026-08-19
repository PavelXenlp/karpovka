<?php
 $v = get_post_meta( get_the_ID(), 'requisites_section', true );

if ( empty( $v ) || empty( $v['groups'] ) ) return;

 $eyebrow    = esc_html( $v['eyebrow'] ?? '' );
 $title      = esc_html( $v['title'] ?? '' );
 $section_id = esc_attr( $v['section_id'] ?? 'requisites' );
 $groups     = $v['groups'];
?>

<section class="c-requisites" id="<?php echo $section_id; ?>">
    <div class="container">
        <div class="section-head section-head--center reveal">
            <?php if ( $eyebrow ) : ?>
                <p class="eyebrow"><?php echo $eyebrow; ?></p>
            <?php endif; ?>
            <?php if ( $title ) : ?>
                <h2 class="section-title"><?php echo $title; ?></h2>
            <?php endif; ?>
        </div>

        <div class="c-req-layout reveal">
            <?php
            $total = count( $groups );
            foreach ( $groups as $gi => $group ) :
                $group_title = esc_html( $group['group_title'] ?? '' );
                $rows        = $group['rows'] ?? [];
                if ( empty( $group_title ) && empty( $rows ) ) continue;
            ?>
                <!-- <?php echo $group_title ?: 'Группа ' . ( $gi + 1 ); ?> -->
                <div class="c-req-group">
                    <?php if ( $group_title ) : ?>
                        <h3 class="c-req-group__title"><?php echo $group_title; ?></h3>
                    <?php endif; ?>

                    <?php foreach ( $rows as $row ) :
                        $key       = esc_html( $row['key'] ?? '' );
                        $value     = $row['value'] ?? '';
                        $is_accent = ! empty( $row['is_accent'] );
                        $is_sub    = ! empty( $row['is_sub'] );

                        $row_classes = 'c-req-row';
                        if ( $is_sub )    $row_classes .= ' c-req-row--sub';
                        $val_classes   = 'c-req-row__val';
                        if ( $is_accent ) $val_classes .= ' c-req-row__val--accent';
                    ?>
                        <div class="<?php echo $row_classes; ?>">
                            <?php if ( $key ) : ?>
                                <span class="c-req-row__key"><?php echo $key; ?></span>
                            <?php endif; ?>
                            <span class="<?php echo $val_classes; ?>"><?php echo wp_kses_post( $value ); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ( $gi < $total - 1 ) : ?>
                    <div class="c-req-divider" aria-hidden="true"></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>