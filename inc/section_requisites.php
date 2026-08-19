<?php
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'requisites_section', 'Секция: Реквизиты', 'requisites_section_box', 'page', 'normal', 'high' );
} );

function requisites_section_box( $post ) {
    wp_nonce_field( 'requisites_section_nonce', 'requisites_section_nonce_field' );
    $v = get_post_meta( $post->ID, 'requisites_section', true );

    $eyebrow       = $v['eyebrow'] ?? '';
    $title         = $v['title'] ?? '';
    $section_id    = $v['section_id'] ?? '';
    $groups        = $v['groups'] ?? [];
    ?>
    <style>
        .req-field{margin-bottom:12px}
        .req-field label{display:block;font-weight:600;margin-bottom:4px}
        .req-field input[type="text"],.req-field textarea{width:100%;max-width:600px}
        .req-group-wrap{border:1px solid #ccc;margin:16px 0;padding:16px;background:#f9f9f9;border-radius:4px}
        .req-group-wrap h4{margin-top:0}
        .req-row-wrap{border:1px solid #e0e0e0;margin:10px 0;padding:12px;background:#fff;border-radius:4px}
        .req-row-wrap label{display:block;font-weight:600;margin-bottom:4px;font-size:13px}
        .req-row-wrap input[type="text"],.req-row-wrap textarea{width:100%}
        .req-row-wrap .req-inline{display:inline-block;margin-right:16px}
        .req-row-wrap .req-inline label{display:inline;font-weight:normal;margin-right:4px}
        .req-add-row,.req-add-group,.req-remove{margin:4px 2px;padding:4px 12px;cursor:pointer}
        .req-remove{color:#a00;border-color:#a00}
    </style>

    <div class="req-field">
        <label>Надсекция (eyebrow)</label>
        <input type="text" name="requisites_section[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>">
    </div>
    <div class="req-field">
        <label>Заголовок</label>
        <input type="text" name="requisites_section[title]" value="<?php echo esc_attr( $title ); ?>">
    </div>
    <div class="req-field">
        <label>Section ID</label>
        <input type="text" name="requisites_section[section_id]" value="<?php echo esc_attr( $section_id ); ?>">
    </div>

    <h3>Группы</h3>
    <div id="req-groups-container">
        <?php if ( ! empty( $groups ) ) : ?>
            <?php foreach ( $groups as $gi => $group ) : ?>
                <div class="req-group-wrap">
                    <h4>Группа</h4>
                    <div class="req-field">
                        <label>Название группы</label>
                        <input type="text" name="requisites_section[groups][<?php echo $gi; ?>][group_title]" value="<?php echo esc_attr( $group['group_title'] ?? '' ); ?>">
                    </div>
                    <div class="req-remove" onclick="this.closest('.req-group-wrap').remove()">Удалить группу</div>

                    <h4 style="margin-top:16px">Строки</h4>
                    <div class="req-rows-container">
                        <?php if ( ! empty( $group['rows'] ) ) : ?>
                            <?php foreach ( $group['rows'] as $ri => $row ) : ?>
                                <div class="req-row-wrap">
                                    <div class="req-field">
                                        <label>Ключ</label>
                                        <input type="text" name="requisites_section[groups][<?php echo $gi; ?>][rows][<?php echo $ri; ?>][key]" value="<?php echo esc_attr( $row['key'] ?? '' ); ?>">
                                    </div>
                                    <div class="req-field">
                                        <label>Значение (можно HTML: &lt;a&gt;, &lt;br&gt;, &lt;strong&gt;)</label>
                                        <textarea name="requisites_section[groups][<?php echo $gi; ?>][rows][<?php echo $ri; ?>][value]" rows="3"><?php echo esc_textarea( $row['value'] ?? '' ); ?></textarea>
                                    </div>
                                    <div class="req-inline">
                                        <input type="checkbox" id="req_accent_<?php echo $gi; ?>_<?php echo $ri; ?>" name="requisites_section[groups][<?php echo $gi; ?>][rows][<?php echo $ri; ?>][is_accent]" value="1" <?php checked( $row['is_accent'] ?? 0, 1 ); ?>>
                                        <label for="req_accent_<?php echo $gi; ?>_<?php echo $ri; ?>">Акцент (золотой)</label>
                                    </div>
                                    <div class="req-inline">
                                        <input type="checkbox" id="req_sub_<?php echo $gi; ?>_<?php echo $ri; ?>" name="requisites_section[groups][<?php echo $gi; ?>][rows][<?php echo $ri; ?>][is_sub]" value="1" <?php checked( $row['is_sub'] ?? 0, 1 ); ?>>
                                        <label for="req_sub_<?php echo $gi; ?>_<?php echo $ri; ?>">Подстрока (без ключа)</label>
                                    </div>
                                    <div class="req-remove" onclick="this.closest('.req-row-wrap').remove()">Удалить строку</div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="req-add-row" data-group="<?php echo $gi; ?>">+ Добавить строку</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button type="button" class="req-add-group">+ Добавить группу</button>

    <script>
jQuery(function($){

    /* Добавить строку */
    $('#req-groups-container').on('click', '.req-add-row', function(){
        var gi = $(this).data('group');
        var rowsBox = $(this).prev('.req-rows-container');
        var idx = rowsBox.find('.req-row-wrap').length;
        var html = '<div class="req-row-wrap">' +
            '<div class="req-field"><label>Ключ</label><input type="text" name="requisites_section[groups]['+gi+'][rows]['+idx+'][key]"></div>' +
            '<div class="req-field"><label>Значение (можно HTML: &lt;a&gt;, &lt;br&gt;, &lt;strong&gt;)</label><textarea name="requisites_section[groups]['+gi+'][rows]['+idx+'][value]" rows="3"></textarea></div>' +
            '<div class="req-inline"><input type="checkbox" id="req_accent_'+gi+'_'+idx+'" name="requisites_section[groups]['+gi+'][rows]['+idx+'][is_accent]" value="1"> <label for="req_accent_'+gi+'_'+idx+'">Акцент (золотой)</label></div>' +
            '<div class="req-inline"><input type="checkbox" id="req_sub_'+gi+'_'+idx+'" name="requisites_section[groups]['+gi+'][rows]['+idx+'][is_sub]" value="1"> <label for="req_sub_'+gi+'_'+idx+'">Подстрока (без ключа)</label></div>' +
            '<div class="req-remove" onclick="this.closest(\'.req-row-wrap\').remove()">Удалить строку</div>' +
            '</div>';
        rowsBox.append(html);
    });

    /* Добавить группу — привязка к document, т.к. кнопка вне контейнера */
    $(document).on('click', '.req-add-group', function(e){
        e.preventDefault();
        var container = $('#req-groups-container');
        var idx = container.find('.req-group-wrap').length;
        var html = '<div class="req-group-wrap">' +
            '<h4>Группа</h4>' +
            '<div class="req-field"><label>Название группы</label><input type="text" name="requisites_section[groups]['+idx+'][group_title]"></div>' +
            '<div class="req-remove" onclick="this.closest(\'.req-group-wrap\').remove()">Удалить группу</div>' +
            '<h4 style="margin-top:16px">Строки</h4>' +
            '<div class="req-rows-container"></div>' +
            '<button type="button" class="req-add-row" data-group="'+idx+'">+ Добавить строку</button>' +
            '</div>';
        $(html).insertBefore(this);
    });

    /* Переназначаем data-group при удалении */
    $('#req-groups-container').on('click', '.req-remove', function(){
        $('#req-groups-container .req-group-wrap').each(function(gi){
            $(this).find('.req-add-row').attr('data-group', gi);
        });
    });

});
</script>
    <?php
}

add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['requisites_section_nonce_field'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['requisites_section_nonce_field'], 'requisites_section_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['requisites_section'] ?? [];

    $data = [
        'eyebrow'    => sanitize_text_field( $raw['eyebrow'] ?? '' ),
        'title'      => sanitize_text_field( $raw['title'] ?? '' ),
        'section_id' => sanitize_text_field( $raw['section_id'] ?? '' ),
        'groups'     => [],
    ];

    if ( ! empty( $raw['groups'] ) && is_array( $raw['groups'] ) ) {
        foreach ( $raw['groups'] as $gi => $group ) {
            $g = [
                'group_title' => sanitize_text_field( $group['group_title'] ?? '' ),
                'rows'        => [],
            ];
            if ( ! empty( $group['rows'] ) && is_array( $group['rows'] ) ) {
                foreach ( $group['rows'] as $ri => $row ) {
                    $g['rows'][] = [
                        'key'      => sanitize_text_field( $row['key'] ?? '' ),
                        'value'    => wp_kses_post( $row['value'] ?? '' ),
                        'is_accent' => ! empty( $row['is_accent'] ) ? 1 : 0,
                        'is_sub'   => ! empty( $row['is_sub'] ) ? 1 : 0,
                    ];
                }
            }
            $data['groups'][] = $g;
        }
    }

    update_post_meta( $post_id, 'requisites_section', $data );
} );