<?php
/* ===== Секция: Акции — многократные блоки ===== */

add_action('add_meta_boxes', function () {
    add_meta_box('offers_sections', 'Акции — Секции', 'offers_sections_mb', 'page', 'normal', 'high');
});

function offers_sections_mb($post) {
    wp_nonce_field('offers_sections_nonce', 'offers_sections_nonce_f');
    $sections = get_post_meta($post->ID, 'offers_sections', true);
    if (!is_array($sections)) $sections = [];
    ?>
    <style>
        .ofs-sec{margin-bottom:16px;padding:16px;border:2px solid #c3c4c7;background:#fff;}
        .ofs-sec>h4{margin:0 0 10px;padding:6px 10px;background:#f0f0f1;display:flex;justify-content:space-between;align-items:center;}
        .ofs-card{margin-bottom:10px;padding:10px;border:1px solid #dcdcde;background:#fafafa;}
        .ofs-card>h5{margin:0 0 6px;color:#2271b1;}
        .ofs-cond-row{display:flex;gap:6px;margin-bottom:4px;align-items:center;}
        .ofs-cond-row input{flex:1;}
    </style>

    <p><strong>Секции акций:</strong> каждая секция = один блок на странице. ID и Класс — уникальны для каждой.</p>

    <div id="ofs-wrap">
    <?php foreach ($sections as $si => $sec) : ?>
        <div class="ofs-sec">
            <h4>Секция: <span class="ofs-sec-label"><?php echo esc_html($sec['section_id'] ?? 'sec_'.$si); ?></span>
                <button type="button" class="button ofs-sec-del">Удалить секцию</button></h4>

            <p><label>ID секции (для вызова в шаблоне):</label><br>
            <input type="text" name="offers_sections[<?php echo $si; ?>][section_id]" value="<?php echo esc_attr($sec['section_id'] ?? ''); ?>" class="widefat" placeholder="current"></p>

            <p><label>Класс секции (CSS):</label><br>
            <input type="text" name="offers_sections[<?php echo $si; ?>][section_class]" value="<?php echo esc_attr($sec['section_class'] ?? ''); ?>" class="widefat" placeholder="c-offers-current"></p>

            <p><label>Надзаголовок:</label><br>
            <input type="text" name="offers_sections[<?php echo $si; ?>][eyebrow]" value="<?php echo esc_attr($sec['eyebrow'] ?? ''); ?>" class="widefat"></p>

            <p><label>Заголовок <em>:</label><br>
            <input type="text" name="offers_sections[<?php echo $si; ?>][title_before]" value="<?php echo esc_attr($sec['title_before'] ?? ''); ?>" class="widefat"></p>

            <p><label>Золотой текст <em>:</label><br>
            <input type="text" name="offers_sections[<?php echo $si; ?>][title_em]" value="<?php echo esc_attr($sec['title_em'] ?? ''); ?>" class="widefat"></p>

            <p><label>Подзаголовок:</label><br>
            <textarea name="offers_sections[<?php echo $si; ?>][sub]" rows="2" class="widefat"><?php echo esc_textarea($sec['sub'] ?? ''); ?></textarea></p>

            <hr>
            <p><strong>Карточки:</strong></p>
            <div class="ofs-cards-wrap">
            <?php if (!empty($sec['cards']) && is_array($sec['cards'])) : foreach ($sec['cards'] as $ci => $c) : ?>
                <div class="ofs-card">
                    <h5>Карточка <?php echo $ci + 1; ?></h5>
                    <p><label>Изображение:</label><br>
                    <input type="text" name="offers_sections[<?php echo $si; ?>][cards][<?php echo $ci; ?>][image]" value="<?php echo esc_url($c['image'] ?? ''); ?>" class="widefat ofs-card-img">
                    <button type="button" class="button ofs-card-upload">Выбрать</button></p>

                    <p><label>Текст бейджа:</label><br>
                    <input type="text" name="offers_sections[<?php echo $si; ?>][cards][<?php echo $ci; ?>][badge_text]" value="<?php echo esc_attr($c['badge_text'] ?? ''); ?>" class="widefat" placeholder="Скидка 20%"></p>

                    <p><label><input type="checkbox" name="offers_sections[<?php echo $si; ?>][cards][<?php echo $ci; ?>][badge_hot]" value="1" <?php checked($c['badge_hot'] ?? '', '1'); ?>> Бейдж «Хит» (красный)</label></p>

                    <p><label>Название:</label><br>
                    <input type="text" name="offers_sections[<?php echo $si; ?>][cards][<?php echo $ci; ?>][title]" value="<?php echo esc_attr($c['title'] ?? ''); ?>" class="widefat"></p>

                    <p><label>Описание:</label><br>
                    <textarea name="offers_sections[<?php echo $si; ?>][cards][<?php echo $ci; ?>][desc]" rows="3" class="widefat"><?php echo esc_textarea($c['desc'] ?? ''); ?></textarea></p>

                    <p><label>Ссылка «Подробнее»:</label><br>
                    <input type="text" name="offers_sections[<?php echo $si; ?>][cards][<?php echo $ci; ?>][link]" value="<?php echo esc_attr($c['link'] ?? ''); ?>" class="widefat" placeholder="/offers/weekday"></p>

                    <p><strong>Условия (иконка + текст):</strong></p>
                    <div class="ofs-conds-wrap">
                    <?php if (!empty($c['conditions']) && is_array($c['conditions'])) : foreach ($c['conditions'] as $kni => $cn) : ?>
                        <div class="ofs-cond-row">
                            <input type="text" name="offers_sections[<?php echo $si; ?>][cards][<?php echo $ci; ?>][conditions][<?php echo $kni; ?>][icon]" value="<?php echo esc_attr($cn['icon'] ?? ''); ?>" placeholder="fa-regular fa-calendar">
                            <input type="text" name="offers_sections[<?php echo $si; ?>][cards][<?php echo $ci; ?>][conditions][<?php echo $kni; ?>][text]" value="<?php echo esc_attr($cn['text'] ?? ''); ?>" placeholder="Пн — Чт">
                            <button type="button" class="button ofs-cond-del">−</button>
                        </div>
                    <?php endforeach; endif; ?>
                    </div>
                    <p><button type="button" class="button ofs-cond-add">+ Условие</button></p>

                    <p><button type="button" class="button ofs-card-del">Удалить карточку</button></p>
                </div>
            <?php endforeach; endif; ?>
            </div>
            <p><button type="button" class="button ofs-card-add">+ Добавить карточку</button></p>
        </div>
    <?php endforeach; ?>
    </div>
    <p><button type="button" class="button button-primary" id="ofs-sec-add">+ Добавить секцию</button></p>

    <script>
    (function($){
        var sn = $('#ofs-wrap .ofs-sec').length;

        function mkCond(si, ci, ni){
            return '<div class="ofs-cond-row">'
                +'<input type="text" name="offers_sections['+si+'][cards]['+ci+'][conditions]['+ni+'][icon]" placeholder="fa-regular fa-calendar">'
                +'<input type="text" name="offers_sections['+si+'][cards]['+ci+'][conditions]['+ni+'][text]" placeholder="Пн \u2014 Чт">'
                +'<button type="button" class="button ofs-cond-del">\u2212</button></div>';
        }

        function mkCard(si, ci){
            return '<div class="ofs-card"><h5>Карточка '+(ci+1)+'</h5>'
                +'<p><label>Изображение:</label><br>'
                +'<input type="text" name="offers_sections['+si+'][cards]['+ci+'][image]" class="widefat ofs-card-img">'
                +' <button type="button" class="button ofs-card-upload">Выбрать</button></p>'
                +'<p><label>Текст бейджа:</label><br>'
                +'<input type="text" name="offers_sections['+si+'][cards]['+ci+'][badge_text]" class="widefat" placeholder="Скидка 20%"></p>'
                +'<p><label><input type="checkbox" name="offers_sections['+si+'][cards]['+ci+'][badge_hot]" value="1"> Бейдж «Хит» (красный)</label></p>'
                +'<p><label>Название:</label><br>'
                +'<input type="text" name="offers_sections['+si+'][cards]['+ci+'][title]" class="widefat"></p>'
                +'<p><label>Описание:</label><br>'
                +'<textarea name="offers_sections['+si+'][cards]['+ci+'][desc]" rows="3" class="widefat"></textarea></p>'
                +'<p><label>Ссылка «Подробнее»:</label><br>'
                +'<input type="text" name="offers_sections['+si+'][cards]['+ci+'][link]" class="widefat" placeholder="/offers/weekday"></p>'
                +'<p><strong>Условия:</strong></p>'
                +'<div class="ofs-conds-wrap"></div>'
                +'<p><button type="button" class="button ofs-cond-add">+ Условие</button></p>'
                +'<p><button type="button" class="button ofs-card-del">Удалить карточку</button></p></div>';
        }

        function mkSection(){
            var si = sn;
            var h = '<div class="ofs-sec"><h4>Секция: <span class="ofs-sec-label">sec_'+si+'</span>'
                +' <button type="button" class="button ofs-sec-del">Удалить секцию</button></h4>'
                +'<p><label>ID секции:</label><br>'
                +'<input type="text" name="offers_sections['+si+'][section_id]" class="widefat" placeholder="current"></p>'
                +'<p><label>Класс секции:</label><br>'
                +'<input type="text" name="offers_sections['+si+'][section_class]" class="widefat" placeholder="c-offers-current"></p>'
                +'<p><label>Надзаголовок:</label><br>'
                +'<input type="text" name="offers_sections['+si+'][eyebrow]" class="widefat"></p>'
                +'<p><label>Заголовок <em>:</label><br>'
                +'<input type="text" name="offers_sections['+si+'][title_before]" class="widefat"></p>'
                +'<p><label>Золотой текст <em>:</label><br>'
                +'<input type="text" name="offers_sections['+si+'][title_em]" class="widefat"></p>'
                +'<p><label>Подзаголовок:</label><br>'
                +'<textarea name="offers_sections['+si+'][sub]" rows="2" class="widefat"></textarea></p>'
                +'<hr><p><strong>Карточки:</strong></p>'
                +'<div class="ofs-cards-wrap"></div>'
                +'<p><button type="button" class="button ofs-card-add">+ Добавить карточку</button></p></div>';
            $('#ofs-wrap').append(h);
            sn++;
        }

        $('#ofs-sec-add').on('click', function(){ mkSection(); });
        $(document).on('click', '.ofs-sec-del', function(){ $(this).closest('.ofs-sec').remove(); });

        $(document).on('click', '.ofs-card-add', function(){
            var wrap = $(this).closest('p').prev('.ofs-cards-wrap');
            var si = wrap.closest('.ofs-sec').index('#ofs-wrap .ofs-sec');
            var ci = wrap.find('.ofs-card').length;
            wrap.append(mkCard(si, ci));
        });
        $(document).on('click', '.ofs-card-del', function(){ $(this).closest('.ofs-card').remove(); });

        $(document).on('click', '.ofs-cond-add', function(){
            var wrap = $(this).closest('p').prev('.ofs-conds-wrap');
            var si = wrap.closest('.ofs-sec').index('#ofs-wrap .ofs-sec');
            var ci = wrap.closest('.ofs-card').index('.ofs-cards-wrap .ofs-card');
            var ni = wrap.find('.ofs-cond-row').length;
            wrap.append(mkCond(si, ci, ni));
        });
        $(document).on('click', '.ofs-cond-del', function(){ $(this).closest('.ofs-cond-row').remove(); });

        $(document).on('click', '.ofs-card-upload', function(){
            var btn = this;
            var frame = wp.media({title:'Выбрать изображение', multiple:false, library:{type:'image'}});
            frame.on('select', function(){
                var url = frame.state().get('selection').first().toJSON().url;
                $(btn).prev('.ofs-card-img').val(url);
            });
            frame.open();
        });
    })(jQuery);
    </script>
    <?php
}

add_action('save_post', function ($pid) {
    if (!isset($_POST['offers_sections_nonce_f'])) return;
    if (!wp_verify_nonce($_POST['offers_sections_nonce_f'], 'offers_sections_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $pid)) return;

    $sections = [];
    if (!empty($_POST['offers_sections']) && is_array($_POST['offers_sections'])) {
        foreach ($_POST['offers_sections'] as $sec) {
            $cards = [];
            if (!empty($sec['cards']) && is_array($sec['cards'])) {
                foreach ($sec['cards'] as $c) {
                    $conditions = [];
                    if (!empty($c['conditions']) && is_array($c['conditions'])) {
                        foreach ($c['conditions'] as $cn) {
                            $conditions[] = [
                                'icon' => sanitize_text_field($cn['icon'] ?? ''),
                                'text' => sanitize_text_field($cn['text'] ?? ''),
                            ];
                        }
                    }
                    $cards[] = [
                        'image'      => esc_url_raw($c['image'] ?? ''),
                        'badge_text' => sanitize_text_field($c['badge_text'] ?? ''),
                        'badge_hot'  => !empty($c['badge_hot']) ? '1' : '',
                        'title'      => sanitize_text_field($c['title'] ?? ''),
                        'desc'       => sanitize_textarea_field($c['desc'] ?? ''),
                        'link'       => esc_url_raw($c['link'] ?? ''),
                        'conditions' => $conditions,
                    ];
                }
            }
            $sections[] = [
                'section_id'   => sanitize_text_field($sec['section_id'] ?? ''),
                'section_class'=> sanitize_text_field($sec['section_class'] ?? ''),
                'eyebrow'      => sanitize_text_field($sec['eyebrow'] ?? ''),
                'title_before' => sanitize_text_field($sec['title_before'] ?? ''),
                'title_em'     => sanitize_text_field($sec['title_em'] ?? ''),
                'sub'          => sanitize_textarea_field($sec['sub'] ?? ''),
                'cards'        => $cards,
            ];
        }
    }
    update_post_meta($pid, 'offers_sections', $sections);
});