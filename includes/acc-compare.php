<?php
// Template: Сравнение форматов (таблица)
 $v = get_post_meta( get_the_ID(), 'acc_compare', true );
if ( empty( $v ) ) return;

 $eyebrow     = $v['eyebrow'] ?? '';
 $title       = $v['title'] ?? '';
 $col_headers = $v['col_headers'] ?? [];
 $rows        = $v['rows'] ?? [];
 $note        = $v['note'] ?? '';

if ( empty( $col_headers ) || empty( $rows ) ) return;
?>

<section class="section acc-compare" id="compare">
    <div class="section-head reveal">
        <?php if ( $eyebrow ) : ?>
            <p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
        <?php endif; ?>
        <?php if ( $title ) : ?>
            <h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2>
        <?php endif; ?>
    </div>

    <div class="acc-table-wrap reveal">
        <table class="acc-table">
            <caption class="visually-hidden">Сравнение форматов проживания в парк-отеле «Карповка»</caption>
            <thead>
                <tr>
                    <th scope="col">Параметр</th>
                    <?php foreach ( $col_headers as $ch ) : ?>
                    <th scope="col"><?php echo esc_html( $ch ); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $rows as $row ) :
                    $param = $row['param'] ?? '';
                    $cells = $row['cells'] ?? [];
                    if ( $param === '' ) continue;
                ?>
                <tr>
                    <th scope="row"><?php echo esc_html( $param ); ?></th>
                    <?php for ( $i = 0; $i < count( $col_headers ); $i++ ) : ?>
                    <td><?php echo esc_html( $cells[ $i ] ?? '' ); ?></td>
                    <?php endfor; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ( $note ) : ?>
    <p class="acc-compare__note reveal"><?php echo esc_html( $note ); ?></p>
    <?php endif; ?>
</section>