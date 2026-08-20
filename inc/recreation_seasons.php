<?php
/* ===== Секция: Активности — Сезоны (фильтр-чипы) ===== */

add_action('add_meta_boxes', function () {
    add_meta_box('recreation_seasons', 'Активности — Сезоны', 'recreation_seasons_mb', 'page', 'normal', 'high');
});

function recreation_seasons_mb($post) {
    wp_nonce_field('rec_seasons_nonce', 'rec_seasons_nonce_f');
    $e  = get_post_meta($post->ID, 'rec_seasons_eyebrow', true);
    $tb = get_post_meta($post->ID, 'rec_seasons_title_before', true);
    $te = get_post_meta($post->ID, 'rec_seasons_title_em', true);
    $su = get_post_meta($post->ID, 'rec_seasons_sub', true);
    $ch = get_post_meta($post->ID, 'rec_seasons_chips', true);
    if (!is_array($ch)) $ch = [];
    ?>
    <p><label><strong>Надзаголовок</strong></label><br>
    <input type="text" name="rec_seasons_eyebrow" value="<?php echo esc_attr($e); ?>" class="widefat"></p>

    <p><label><strong>Заголовок <em>:</strong></label><br>
    <input type="text" name="rec_seasons_title_before" value="<?php echo esc_attr($tb); ?>" class="widefat"></p>

    <p><label><strong>Золотой текст заголовка <em>:</strong></label><br>
    <input type="text" name="rec_seasons_title_em" value="<?php echo esc_attr($te); ?>" class="widefat"></p>

    <p><label><strong>Подзаголовок:</strong></label><br>
    <textarea name="rec_seasons_sub" rows="2" class="widefat"><?php echo esc_textarea($su); ?></textarea></p>

    <hr>
    <p><strong>Чипы-фильтры:</strong></p>
    <div id="rec-seasons-chips-wrap">
    <?php if (!empty($ch)) : foreach ($ch as $i => $c) : ?>
        <div class="rec-sc-row" style="margin-bottom:10px;padding:10px;border:1px solid #ccc;background:#fafafa;">
            <p><label>Текст:</label><br>
            <input type="text" name="rec_seasons_chips[<?php echo $i; ?>][text]" value="<?php echo esc_attr($c['text'] ?? ''); ?>" class="widefat"></p>
            <p><label>data-season:</label><br>
            <select name="rec_seasons_chips[<?php echo $i; ?>][value]">
                <?php foreach (['all','summer','winter','allyear'] as $v) : ?>
                    <option value="<?php echo $v; ?>" <?php selected($c['value'] ?? '', $v); ?>><?php echo $v; ?></option>
                <?php endforeach; ?>
            </select></p>
            <p><label><input type="checkbox" name="rec_seasons_chips[<?php echo $i; ?>][active]" value="1" <?php checked($c['active'] ?? '', '1'); ?>> is-active по умолчанию</label></p>
            <p><button type="button" class="button rec-sc-del">Удалить</button></p>
        </div>
    <?php endforeach; endif; ?>
    </div>
    <p><button type="button" class="button" id="rec-sc-add">+ Добавить чип</button></p>

    <script>
    (function($){
        var n = $('#rec-seasons-chips-wrap .rec-sc-row').length;
        var opts = '<option value="all">all</option><option value="summer">summer</option><option value="winter">winter</option><option value="allyear">allyear</option>';
        $('#rec-sc-add').on('click', function(){
            var h = '<div class="rec-sc-row" style="margin-bottom:10px;padding:10px;border:1px solid #ccc;background:#fafafa;">'
                +'<p><label>Текст:</label><br><input type="text" name="rec_seasons_chips['+n+'][text]" class="widefat"></p>'
                +'<p><label>data-season:</label><br><select name="rec_seasons_chips['+n+'][value]">'+opts+'</select></p>'
                +'<p><label><input type="checkbox" name="rec_seasons_chips['+n+'][active]" value="1"> is-active</label></p>'
                +'<p><button type="button" class="button rec-sc-del">Удалить</button></p></div>';
            $('#rec-seasons-chips-wrap').append(h);
            n++;
        });
        $(document).on('click', '.rec-sc-del', function(){ $(this).closest('.rec-sc-row').remove(); });
    })(jQuery);
    </script>
    <?php
}

add_action('save_post', function ($pid) {
    if (!isset($_POST['rec_seasons_nonce_f'])) return;
    if (!wp_verify_nonce($_POST['rec_seasons_nonce_f'], 'rec_seasons_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $pid)) return;

    foreach (['rec_seasons_eyebrow','rec_seasons_title_before','rec_seasons_title_em','rec_seasons_sub'] as $f) {
        update_post_meta($pid, $f, sanitize_text_field($_POST[$f] ?? ''));
    }

    $ch = [];
    if (!empty($_POST['rec_seasons_chips']) && is_array($_POST['rec_seasons_chips'])) {
        foreach ($_POST['rec_seasons_chips'] as $c) {
            $val = in_array($c['value'] ?? '', ['all','summer','winter','allyear']) ? $c['value'] : 'all';
            $ch[] = [
                'text'   => sanitize_text_field($c['text'] ?? ''),
                'value'  => $val,
                'active' => !empty($c['active']) ? '1' : '',
            ];
        }
    }
    update_post_meta($pid, 'rec_seasons_chips', $ch);
});