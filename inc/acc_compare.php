<?php
// Meta box: Сравнение форматов (таблица)
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'acc_compare_section', 'Сравнение форматов (таблица)', 'acc_compare_section_cb', 'page', 'normal', 'high' );
} );

function acc_compare_section_cb( $post ) {
    wp_nonce_field( 'acc_compare_section_nonce', 'acc_compare_section_nonce_field' );
    $v = get_post_meta( $post->ID, 'acc_compare', true );

    $eyebrow     = $v['eyebrow'] ?? '';
    $title       = $v['title'] ?? '';
    $col_headers = $v['col_headers'] ?? [ 'Эко-дома', 'Гостиница', 'Гостевые и VIP-дома' ];
    $rows        = $v['rows'] ?? [];
    $note        = $v['note'] ?? '';
    ?>

    <p>
        <label><b>Бровь (надзаголовок)</b></label><br>
        <input type="text" name="acc_compare[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>" style="width:100%;max-width:500px;">
    </p>
    <p>
        <label><b>Заголовок</b> (<code>&lt;em&gt;</code>)</label><br>
        <input type="text" name="acc_compare[title]" value="<?php echo esc_attr( $title ); ?>" style="width:100%;">
    </p>

    <hr>
    <h4>Заголовки колонок таблицы</h4>
    <p class="description">Первый столбец всегда «Параметр» — он задаётся автоматически. Укажите заголовки для остальных колонок. Можно добавлять и удалять.</p>
    <div id="cmp-cols-wrap">
        <?php if ( ! empty( $col_headers ) ) : foreach ( $col_headers as $ci => $ch ) : ?>
        <div class="cmp-col-row" style="display:flex;gap:6px;align-items:center;margin-bottom:4px;">
            <span style="min-width:120px;color:#666;">Колонка <?php echo $ci + 1; ?>:</span>
            <input type="text" name="acc_compare[col_headers][<?php echo $ci; ?>]" value="<?php echo esc_attr( $ch ); ?>" style="flex:1;">
            <button type="button" class="button cmp-rm-col">✕</button>
        </div>
        <?php endforeach; endif; ?>
    </div>
    <button type="button" class="button" id="cmp-add-col">+ Добавить колонку</button>

    <hr>
    <h4>Строки таблицы</h4>
    <div id="cmp-rows-wrap">
        <?php if ( ! empty( $rows ) ) : foreach ( $rows as $ri => $row ) : ?>
        <div class="cmp-row-block" style="border:1px solid #ccc;padding:10px;margin-bottom:10px;background:#fafafa;">
            <p>
                <label><b>Параметр</b> (название строки)</label><br>
                <input type="text" name="acc_compare[rows][<?php echo $ri; ?>][param]" value="<?php echo esc_attr( $row['param'] ?? '' ); ?>" style="width:100%;max-width:400px;">
            </p>
            <div class="cmp-cells-wrap">
                <?php $cells = $row['cells'] ?? []; foreach ( $cells as $ci => $cell ) : ?>
                <p>
                    <label>Колонка <?php echo $ci + 1; ?>:</label><br>
                    <input type="text" name="acc_compare[rows][<?php echo $ri; ?>][cells][<?php echo $ci; ?>]" value="<?php echo esc_attr( $cell ); ?>" style="width:100%;">
                </p>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button cmp-rm-row" style="color:#a00;">Удалить строку</button>
        </div>
        <?php endforeach; endif; ?>
    </div>
    <button type="button" class="button" id="cmp-add-row">+ Добавить строку</button>

    <hr>
    <p>
        <label><b>Примечание под таблицей</b></label><br>
        <input type="text" name="acc_compare[note]" value="<?php echo esc_attr( $note ); ?>" style="width:100%;">
    </p>

    <script>
    (function($){
        // Колонки
        $('#cmp-add-col').on('click', function(){
            var n = $('#cmp-cols-wrap .cmp-col-row').length;
            $('#cmp-cols-wrap').append(
                '<div class="cmp-col-row" style="display:flex;gap:6px;align-items:center;margin-bottom:4px;">'
                + '<span style="min-width:120px;color:#666;">Колонка ' + (n + 1) + ':</span>'
                + '<input type="text" name="acc_compare[col_headers][' + n + ']" style="flex:1;">'
                + '<button type="button" class="button cmp-rm-col">✕</button></div>'
            );
        });
        $(document).on('click', '.cmp-rm-col', function(){ $(this).closest('.cmp-col-row').remove(); });

        // Строки
        $('#cmp-add-row').on('click', function(){
            var ri = $('#cmp-rows-wrap .cmp-row-block').length;
            // Читаем текущее количество колонок
            var colCount = $('#cmp-cols-wrap .cmp-col-row').length || 3;
            var cellsHtml = '';
            for (var c = 0; c < colCount; c++) {
                cellsHtml += '<p><label>Колонка ' + (c + 1) + ':</label><br>'
                    + '<input type="text" name="acc_compare[rows][' + ri + '][cells][' + c + ']" style="width:100%;"></p>';
            }
            var h = '<div class="cmp-row-block" style="border:1px solid #ccc;padding:10px;margin-bottom:10px;background:#fafafa;">'
                + '<p><label><b>Параметр</b></label><br>'
                + '<input type="text" name="acc_compare[rows][' + ri + '][param]" style="width:100%;max-width:400px;"></p>'
                + cellsHtml
                + '<button type="button" class="button cmp-rm-row" style="color:#a00;">Удалить строку</button></div>';
            $('#cmp-rows-wrap').append(h);
        });
        $(document).on('click', '.cmp-rm-row', function(){
            if( confirm('Удалить строку?') ) $(this).closest('.cmp-row-block').remove();
        });
    })(jQuery);
    </script>
    <?php
}

// Сохранение
add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['acc_compare_section_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['acc_compare_section_nonce_field'], 'acc_compare_section_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['acc_compare'] ?? [];

    // Колонки
    $col_headers = [];
    if ( ! empty( $raw['col_headers'] ) && is_array( $raw['col_headers'] ) ) {
        foreach ( $raw['col_headers'] as $ch ) {
            $t = sanitize_text_field( $ch );
            if ( $t !== '' ) $col_headers[] = $t;
        }
    }

    // Строки
    $rows = [];
    if ( ! empty( $raw['rows'] ) && is_array( $raw['rows'] ) ) {
        foreach ( $raw['rows'] as $row ) {
            $param = sanitize_text_field( $row['param'] ?? '' );
            if ( $param === '' ) continue;
            $cells = [];
            if ( ! empty( $row['cells'] ) && is_array( $row['cells'] ) ) {
                foreach ( $row['cells'] as $cell ) {
                    $cells[] = sanitize_text_field( $cell );
                }
            }
            $rows[] = [ 'param' => $param, 'cells' => $cells ];
        }
    }

    $data = [
        'eyebrow'     => sanitize_text_field( $raw['eyebrow'] ?? '' ),
        'title'       => wp_kses_post( $raw['title'] ?? '' ),
        'col_headers' => $col_headers,
        'rows'        => $rows,
        'note'        => sanitize_text_field( $raw['note'] ?? '' ),
    ];

    update_post_meta( $post_id, 'acc_compare', $data );
} );