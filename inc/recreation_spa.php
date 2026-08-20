<?php
/* ===== Секция: Активности — Группы по сезонам ===== */

add_action('add_meta_boxes', function () {
    add_meta_box('recreation_groups', 'Активности — Сезонные группы', 'recreation_groups_mb', 'page', 'normal', 'high');
});

function recreation_groups_mb($post) {
    wp_nonce_field('rec_groups_nonce', 'rec_groups_nonce_f');
    $groups = get_post_meta($post->ID, 'rec_groups', true);
    if (!is_array($groups)) $groups = [];
    ?>
    <style>
        .rg-group{margin-bottom:16px;padding:16px;border:2px solid #c3c4c7;background:#fff;}
        .rg-group h4{margin:0 0 10px;padding:6px 10px;background:#f0f0f1;display:flex;justify-content:space-between;align-items:center;}
        .rg-card{margin-bottom:10px;padding:10px;border:1px solid #dcdcde;background:#fafafa;}
        .rg-card h5{margin:0 0 6px;color:#2271b1;}
    </style>

    <p><strong>Сезонные группы:</strong> классы секций и фона определяются автоматически по сезону.</p>
    <div id="rg-groups-wrap">
    <?php foreach ($groups as $gi => $g) : ?>
        <div class="rg-group">
            <h4>Группа: <span class="rg-g-label"><?php echo esc_html($g['section_id'] ?? 'group_'.$gi); ?></span>
                <button type="button" class="button rg-group-del">Удалить группу</button>
            </h4>

            <p><label>Ключ сезона (data-season-group):</label><br>
            <select name="rec_groups[<?php echo $gi; ?>][group_key]">
                <?php foreach (['summer','winter','allyear'] as $v) : ?>
                    <option value="<?php echo $v; ?>" <?php selected($g['group_key'] ?? '', $v); ?>><?php echo $v; ?></option>
                <?php endforeach; ?>
            </select></p>

            <p><label>ID секции:</label><br>
            <input type="text" name="rec_groups[<?php echo $gi; ?>][section_id]" value="<?php echo esc_attr($g['section_id'] ?? ''); ?>" class="widefat" placeholder="summer"></p>

            <p><label>Фон — изображение:</label><br>
            <input type="text" name="rec_groups[<?php echo $gi; ?>][bg_image]" value="<?php echo esc_url($g['bg_image'] ?? ''); ?>" class="widefat rg-bg-img">
            <button type="button" class="button rg-bg-upload">Выбрать</button></p>

            <p><label>Надзаголовок:</label><br>
            <input type="text" name="rec_groups[<?php echo $gi; ?>][eyebrow]" value="<?php echo esc_attr($g['eyebrow'] ?? ''); ?>" class="widefat"></p>

            <p><label>Заголовок <em>:</label><br>
            <input type="text" name="rec_groups[<?php echo $gi; ?>][title_before]" value="<?php echo esc_attr($g['title_before'] ?? ''); ?>" class="widefat"></p>

            <p><label>Золотой текст <em>:</label><br>
            <input type="text" name="rec_groups[<?php echo $gi; ?>][title_em]" value="<?php echo esc_attr($g['title_em'] ?? ''); ?>" class="widefat"></p>

            <p><label>Подзаголовок:</label><br>
            <textarea name="rec_groups[<?php echo $gi; ?>][sub]" rows="2" class="widefat"><?php echo esc_textarea($g['sub'] ?? ''); ?></textarea></p>

            <hr>
            <p><strong>Карточки:</strong></p>
            <div class="rg-cards-wrap">
            <?php if (!empty($g['cards']) && is_array($g['cards'])) : foreach ($g['cards'] as $ci => $c) : ?>
                <div class="rg-card">
                    <h5>Карточка <?php echo $ci + 1; ?></h5>
                    <p><label>Изображение:</label><br>
                    <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][image]" value="<?php echo esc_url($c['image'] ?? ''); ?>" class="widefat rg-card-img">
                    <button type="button" class="button rg-card-upload">Выбрать</button></p>

                    <p><label>Иконка тега (FA class):</label><br>
                    <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][tag_icon]" value="<?php echo esc_attr($c['tag_icon'] ?? ''); ?>" class="widefat" placeholder="fa-solid fa-sun"></p>

                    <p><label>Текст тега:</label><br>
                    <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][tag_text]" value="<?php echo esc_attr($c['tag_text'] ?? ''); ?>" class="widefat" placeholder="Май — Октябрь"></p>

                    <p><label>Название:</label><br>
                    <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][title]" value="<?php echo esc_attr($c['title'] ?? ''); ?>" class="widefat"></p>

                    <p><label>Описание:</label><br>
                    <textarea name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][desc]" rows="3" class="widefat"><?php echo esc_textarea($c['desc'] ?? ''); ?></textarea></p>

                    <p><label>Мета 1 — иконка:</label>
                    <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][meta1_icon]" value="<?php echo esc_attr($c['meta1_icon'] ?? ''); ?>" placeholder="fa-regular fa-clock" style="width:220px">
                    <label> текст:</label>
                    <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][meta1_text]" value="<?php echo esc_attr($c['meta1_text'] ?? ''); ?>" placeholder="от 2 часов" style="width:300px"></p>

                    <p><label>Мета 2 — иконка:</label>
                    <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][meta2_icon]" value="<?php echo esc_attr($c['meta2_icon'] ?? ''); ?>" placeholder="fa-solid fa-child" style="width:220px">
                    <label> текст:</label>
                    <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][meta2_text]" value="<?php echo esc_attr($c['meta2_text'] ?? ''); ?>" placeholder="от 6 лет" style="width:300px"></p>

                    <p><label>Ссылка «Подробнее»:</label><br>
                    <input type="text" name="rec_groups[<?php echo $gi; ?>][cards][<?php echo $ci; ?>][link]" value="<?php echo esc_attr($c['link'] ?? ''); ?>" class="widefat" placeholder="/activity-page"></p>

                    <p><button type="button" class="button rg-card-del">Удалить карточку</button></p>
                </div>
            <?php endforeach; endif; ?>
            </div>
            <p><button type="button" class="button rg-card-add">+ Добавить карточку</button></p>
        </div>
    <?php endforeach; ?>
    </div>
    <p><button type="button" class="button button-primary" id="rg-group-add">+ Добавить сезонную группу</button></p>

    <script>
(function($){
    var gn = $('#rg-groups-wrap .rg-group').length;

    function mkCard(gi, ci){
        return '<div class="rg-card"><h5>Карточка '+(ci+1)+'</h5>'
            +'<p><label>Изображение:</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][image]" class="widefat rg-card-img">'
            +' <button type="button" class="button rg-card-upload">Выбрать</button></p>'
            +'<p><label>Иконка тега (FA class):</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][tag_icon]" class="widefat" placeholder="fa-solid fa-sun"></p>'
            +'<p><label>Текст тега:</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][tag_text]" class="widefat" placeholder="Май — Октябрь"></p>'
            +'<p><label>Название:</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][title]" class="widefat"></p>'
            +'<p><label>Описание:</label><br>'
            +'<textarea name="rec_groups['+gi+'][cards]['+ci+'][desc]" rows="3" class="widefat"></textarea></p>'
            +'<p><label>Мета 1 — иконка:</label>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][meta1_icon]" placeholder="fa-regular fa-clock" style="width:220px">'
            +' <label> текст:</label>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][meta1_text]" placeholder="от 2 часов" style="width:300px"></p>'
            +'<p><label>Мета 2 — иконка:</label>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][meta2_icon]" placeholder="fa-solid fa-child" style="width:220px">'
            +' <label> текст:</label>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][meta2_text]" placeholder="от 6 лет" style="width:300px"></p>'
            +'<p><label>Ссылка «Подробнее»:</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][cards]['+ci+'][link]" class="widefat" placeholder="/activity-page"></p>'
            +'<p><button type="button" class="button rg-card-del">Удалить карточку</button></p></div>';
    }

    function mkGroup(){
        var gi = gn;
        var h = '<div class="rg-group"><h4>Группа: <span class="rg-g-label">group_'+gi+'</span>'
            +' <button type="button" class="button rg-group-del">Удалить группу</button></h4>'
            +'<p><label>Ключ сезона:</label><br>'
            +'<select name="rec_groups['+gi+'][group_key]">'
            +'<option value="summer">summer</option><option value="winter">winter</option><option value="allyear">allyear</option>'
            +'</select></p>'
            +'<p><label>ID секции:</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][section_id]" class="widefat" placeholder="summer"></p>'
            +'<p><label>Фон — изображение:</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][bg_image]" class="widefat rg-bg-img">'
            +' <button type="button" class="button rg-bg-upload">Выбрать</button></p>'
            +'<p><label>Надзаголовок:</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][eyebrow]" class="widefat"></p>'
            +'<p><label>Заголовок <em>:</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][title_before]" class="widefat"></p>'
            +'<p><label>Золотой текст <em>:</label><br>'
            +'<input type="text" name="rec_groups['+gi+'][title_em]" class="widefat"></p>'
            +'<p><label>Подзаголовок:</label><br>'
            +'<textarea name="rec_groups['+gi+'][sub]" rows="2" class="widefat"></textarea></p>'
            +'<hr><p><strong>Карточки:</strong></p>'
            +'<div class="rg-cards-wrap"></div>'
            +'<p><button type="button" class="button rg-card-add">+ Добавить карточку</button></p></div>';
        $('#rg-groups-wrap').append(h);
        gn++;
    }

    $('#rg-group-add').on('click', function(){ mkGroup(); });
    $(document).on('click', '.rg-group-del', function(){ $(this).closest('.rg-group').remove(); });

    $(document).on('click', '.rg-card-add', function(){
        var wrap = $(this).closest('p').prev('.rg-cards-wrap');
        var gi = wrap.closest('.rg-group').index('#rg-groups-wrap .rg-group');
        var ci = wrap.find('.rg-card').length;
        wrap.append(mkCard(gi, ci));
    });

    $(document).on('click', '.rg-card-del', function(){ $(this).closest('.rg-card').remove(); });

    $(document).on('click', '.rg-bg-upload', function(){
        var btn = this;
        var frame = wp.media({title:'Выбрать фоновое изображение', multiple:false, library:{type:'image'}});
        frame.on('select', function(){
            var url = frame.state().get('selection').first().toJSON().url;
            $(btn).prev('.rg-bg-img').val(url);
        });
        frame.open();
    });
    $(document).on('click', '.rg-card-upload', function(){
        var btn = this;
        var frame = wp.media({title:'Выбрать изображение карточки', multiple:false, library:{type:'image'}});
        frame.on('select', function(){
            var url = frame.state().get('selection').first().toJSON().url;
            $(btn).prev('.rg-card-img').val(url);
        });
        frame.open();
    });
})(jQuery);
</script>
    <?php
}

add_action('save_post', function ($pid) {
    if (!isset($_POST['rec_groups_nonce_f'])) return;
    if (!wp_verify_nonce($_POST['rec_groups_nonce_f'], 'rec_groups_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $pid)) return;

    $groups = [];
    if (!empty($_POST['rec_groups']) && is_array($_POST['rec_groups'])) {
        foreach ($_POST['rec_groups'] as $g) {
            $cards = [];
            if (!empty($g['cards']) && is_array($g['cards'])) {
                foreach ($g['cards'] as $c) {
                    $cards[] = [
                        'image'     => esc_url_raw($c['image'] ?? ''),
                        'tag_icon'  => sanitize_text_field($c['tag_icon'] ?? ''),
                        'tag_text'  => sanitize_text_field($c['tag_text'] ?? ''),
                        'title'     => sanitize_text_field($c['title'] ?? ''),
                        'desc'      => sanitize_textarea_field($c['desc'] ?? ''),
                        'meta1_icon'=> sanitize_text_field($c['meta1_icon'] ?? ''),
                        'meta1_text'=> sanitize_text_field($c['meta1_text'] ?? ''),
                        'meta2_icon'=> sanitize_text_field($c['meta2_icon'] ?? ''),
                        'meta2_text'=> sanitize_text_field($c['meta2_text'] ?? ''),
                        'link'      => esc_url_raw($c['link'] ?? ''),
                    ];
                }
            }
            $gk = in_array($g['group_key'] ?? '', ['summer','winter','allyear']) ? $g['group_key'] : 'summer';
            $groups[] = [
                'group_key'  => $gk,
                'section_id' => sanitize_text_field($g['section_id'] ?? ''),
                'bg_image'   => esc_url_raw($g['bg_image'] ?? ''),
                'eyebrow'    => sanitize_text_field($g['eyebrow'] ?? ''),
                'title_before'=> sanitize_text_field($g['title_before'] ?? ''),
                'title_em'   => sanitize_text_field($g['title_em'] ?? ''),
                'sub'        => sanitize_textarea_field($g['sub'] ?? ''),
                'cards'      => $cards,
            ];
        }
    }
    update_post_meta($pid, 'rec_groups', $groups);
});