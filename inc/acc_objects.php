<?php

add_action( 'add_meta_boxes', function () {
    add_meta_box( 'acc_objects_section', 'Варианты проживания', 'acc_objects_section_cb', 'page', 'normal', 'high' );
} );

function acc_objects_section_cb( $post ) {
    wp_nonce_field( 'acc_objects_section_nonce', 'acc_objects_section_nonce_field' );
    $v = get_post_meta( $post->ID, 'acc_objects', true );
    $v = is_array( $v ) ? $v : [];
    $eyebrow = $v['eyebrow'] ?? '';
    $title = $v['title'] ?? '';
    $filters = isset( $v['filters'] ) && is_array( $v['filters'] ) ? $v['filters'] : [];
    $mode = 'selected' === ( $v['selection_mode'] ?? '' ) ? 'selected' : 'all';
    $selected_ids = array_map( 'intval', (array) ( $v['house_ids'] ?? [] ) );
    $houses = get_posts( [
        'post_type'      => 'karpovka_house',
        'post_status'    => [ 'publish', 'draft' ],
        'posts_per_page' => -1,
        'orderby'        => [ 'menu_order' => 'ASC', 'title' => 'ASC' ],
    ] );
    ?>
    <div class="kp-acc-objects">
        <div class="kp-acc-grid">
            <p><label><b>Аннотация</b></label><input type="text" name="acc_objects[eyebrow]" value="<?php echo esc_attr( $eyebrow ); ?>"></p>
            <p><label><b>Заголовок</b> <span class="description">Можно использовать &lt;em&gt;</span></label><input type="text" name="acc_objects[title]" value="<?php echo esc_attr( $title ); ?>"></p>
        </div>

        <section class="kp-acc-panel">
            <h4>Какие дома показывать</h4>
            <label class="kp-acc-choice"><input type="radio" name="acc_objects[selection_mode]" value="all" <?php checked( $mode, 'all' ); ?>> <span><b>Все дома</b><small>Список обновляется автоматически при добавлении новых домов.</small></span></label>
            <label class="kp-acc-choice"><input type="radio" name="acc_objects[selection_mode]" value="selected" <?php checked( $mode, 'selected' ); ?>> <span><b>Выбранные дома</b><small>Показываются только отмеченные ниже записи.</small></span></label>

            <div class="kp-house-selection" data-house-selection <?php echo 'selected' === $mode ? '' : 'hidden'; ?>>
                <?php if ( $houses ) : foreach ( $houses as $house ) : ?>
                    <label><input type="checkbox" name="acc_objects[house_ids][]" value="<?php echo (int) $house->ID; ?>" <?php checked( in_array( (int) $house->ID, $selected_ids, true ) ); ?>> <?php echo esc_html( $house->post_title ); ?><?php echo 'publish' !== $house->post_status ? ' — черновик' : ''; ?></label>
                <?php endforeach; else : ?>
                    <p class="description">Домов пока нет. Добавьте их в разделе «Дома» в левом меню.</p>
                <?php endif; ?>
            </div>
            <p><a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=karpovka_house' ) ); ?>">Открыть список домов</a> <a class="button button-primary" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=karpovka_house' ) ); ?>">Добавить дом</a></p>
        </section>

        <details class="kp-acc-panel">
            <summary><b>Фильтры на странице</b></summary>
            <p class="description">Фильтры относятся к блоку страницы. Тип и аудитория каждого дома настраиваются в самой записи дома.</p>
            <div data-filter-groups>
                <?php foreach ( $filters as $fi => $group ) : ?>
                    <div class="kp-filter-group" data-filter-group>
                        <div class="kp-filter-head"><input type="text" name="acc_objects[filters][<?php echo $fi; ?>][label]" value="<?php echo esc_attr( $group['label'] ?? '' ); ?>" placeholder="Название группы"><button type="button" class="button-link-delete" data-remove-filter>Удалить группу</button></div>
                        <div data-filter-options>
                            <?php foreach ( (array) ( $group['options'] ?? [] ) as $oi => $option ) : ?>
                                <div class="kp-filter-option">
                                    <input type="text" name="acc_objects[filters][<?php echo $fi; ?>][options][<?php echo $oi; ?>][text]" value="<?php echo esc_attr( $option['text'] ?? '' ); ?>" placeholder="Подпись">
                                    <input type="text" name="acc_objects[filters][<?php echo $fi; ?>][options][<?php echo $oi; ?>][slug]" value="<?php echo esc_attr( $option['slug'] ?? '' ); ?>" placeholder="all, eco, family...">
                                    <label><input type="checkbox" name="acc_objects[filters][<?php echo $fi; ?>][options][<?php echo $oi; ?>][is_active]" value="1" <?php checked( ! empty( $option['is_active'] ) ); ?>> По умолчанию</label>
                                    <button type="button" class="button" data-remove-option>×</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="button" data-add-option>Добавить вариант</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button" data-add-filter>Добавить группу фильтров</button>
        </details>
    </div>
    <style>
        .kp-acc-objects label{display:block;margin-bottom:6px}.kp-acc-objects input[type="text"]{width:100%}.kp-acc-grid{display:grid;grid-template-columns:1fr 2fr;gap:16px}.kp-acc-panel{margin-top:18px;padding:16px;border:1px solid #dcdcde;background:#fff}.kp-acc-panel h4{margin-top:0}.kp-acc-choice{display:flex!important;align-items:flex-start;gap:8px;padding:10px;border:1px solid #dcdcde;margin-bottom:8px!important}.kp-acc-choice small{display:block;color:#646970;margin-top:3px}.kp-house-selection{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:4px 18px;padding:14px;margin:10px 0;background:#f6f7f7}.kp-house-selection[hidden]{display:none}.kp-filter-group{padding:12px;border:1px solid #dcdcde;margin:12px 0}.kp-filter-head,.kp-filter-option{display:grid;grid-template-columns:1fr auto;gap:8px;align-items:center}.kp-filter-option{grid-template-columns:1fr 1fr 150px 34px;margin-top:8px}.kp-filter-option label{margin:0}@media(max-width:782px){.kp-acc-grid,.kp-house-selection,.kp-filter-option{grid-template-columns:1fr}}
    </style>
    <script>
    jQuery(function($){
        var $root = $('.kp-acc-objects');
        $root.on('change', 'input[name="acc_objects[selection_mode]"]', function(){
            $root.find('[data-house-selection]').prop('hidden', this.value !== 'selected');
        });
        function nextIndex($items, attr){ var max=-1; $items.each(function(){ var m=($(this).find('input').first().attr('name')||'').match(attr); if(m) max=Math.max(max,parseInt(m[1],10)); }); return max+1; }
        $root.on('click','[data-add-filter]',function(){
            var $wrap=$root.find('[data-filter-groups]'), fi=nextIndex($wrap.find('[data-filter-group]'), /filters\]\[(\d+)\]/);
            $wrap.append('<div class="kp-filter-group" data-filter-group><div class="kp-filter-head"><input type="text" name="acc_objects[filters]['+fi+'][label]" placeholder="Название группы"><button type="button" class="button-link-delete" data-remove-filter>Удалить группу</button></div><div data-filter-options></div><button type="button" class="button" data-add-option>Добавить вариант</button></div>');
        });
        $root.on('click','[data-remove-filter]',function(){ $(this).closest('[data-filter-group]').remove(); });
        $root.on('click','[data-add-option]',function(){
            var $group=$(this).closest('[data-filter-group]'), $options=$group.find('[data-filter-options]'), name=$group.find('input').first().attr('name')||'', m=name.match(/filters\]\[(\d+)\]/), fi=m?m[1]:0, oi=nextIndex($options.find('.kp-filter-option'), /options\]\[(\d+)\]/);
            $options.append('<div class="kp-filter-option"><input type="text" name="acc_objects[filters]['+fi+'][options]['+oi+'][text]" placeholder="Подпись"><input type="text" name="acc_objects[filters]['+fi+'][options]['+oi+'][slug]" placeholder="all, eco, family..."><label><input type="checkbox" name="acc_objects[filters]['+fi+'][options]['+oi+'][is_active]" value="1"> По умолчанию</label><button type="button" class="button" data-remove-option>×</button></div>');
        });
        $root.on('click','[data-remove-option]',function(){ $(this).closest('.kp-filter-option').remove(); });
    });
    </script>
    <?php
}

add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['acc_objects_section_nonce_field'] ) || ! wp_verify_nonce( $_POST['acc_objects_section_nonce_field'], 'acc_objects_section_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $raw = $_POST['acc_objects'] ?? [];
    $mode = 'selected' === ( $raw['selection_mode'] ?? '' ) ? 'selected' : 'all';
    $house_ids = array_values( array_unique( array_filter( array_map( 'absint', (array) ( $raw['house_ids'] ?? [] ) ) ) ) );
    $filters = [];
    foreach ( (array) ( $raw['filters'] ?? [] ) as $group ) {
        $label = sanitize_text_field( $group['label'] ?? '' );
        $options = [];
        foreach ( (array) ( $group['options'] ?? [] ) as $option ) {
            $text = sanitize_text_field( $option['text'] ?? '' );
            $slug = sanitize_key( $option['slug'] ?? '' );
            if ( '' === $text || '' === $slug ) continue;
            $options[] = [ 'text' => $text, 'slug' => $slug, 'is_active' => ! empty( $option['is_active'] ) ];
        }
        if ( '' !== $label && $options ) $filters[] = [ 'label' => $label, 'options' => $options ];
    }
    update_post_meta( $post_id, 'acc_objects', [
        'eyebrow'       => sanitize_text_field( $raw['eyebrow'] ?? '' ),
        'title'         => wp_kses_post( $raw['title'] ?? '' ),
        'filters'       => $filters,
        'selection_mode'=> $mode,
        'house_ids'     => $house_ids,
    ] );
} );
