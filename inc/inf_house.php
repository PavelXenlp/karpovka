<?php
/* ===== Секция: Информация о доме ===== */

add_action('add_meta_boxes', function () {
    add_meta_box('house_info_box', 'Информация о доме', 'house_info_mb', 'page', 'normal', 'high');
});

function house_info_mb($post) {
    wp_nonce_field('house_info_nonce', 'house_info_nonce_f');
    $d = get_post_meta($post->ID, 'house_info', true);
    if (!is_array($d)) $d = [];
    ?>
    <style>
        .hi-feat{display:flex;gap:6px;margin-bottom:4px;align-items:center;}
        .hi-feat input{flex:1;}
        .hi-gallery-item{display:flex;align-items:center;gap:8px;margin-bottom:6px;}
        .hi-gallery-item input{flex:1;}
    </style>

    <p><label>Заголовок (до gold-текста):</label><br>
<input type="text" name="house_info[title]" value="<?php echo esc_attr($d['title'] ?? ''); ?>" class="widefat" placeholder="Эко-дом"></p>

<p><label>Gold-текст (обёрнется в &lt;em&gt;):</label><br>
<input type="text" name="house_info[gold_text]" value="<?php echo esc_attr($d['gold_text'] ?? ''); ?>" class="widefat" placeholder="«Лесной»"></p>

    <p><label>Описание:</label><br>
    <textarea name="house_info[desc]" rows="5" class="widefat"><?php echo esc_textarea($d['desc'] ?? ''); ?></textarea></p>

    <p><label>Выделенный блок (особенность):</label><br>
<textarea name="house_info[highlight]" rows="3" class="widefat" placeholder="Дом построен в 2023 году из соснового бревна диаметром 28 см. Общая площадь — 85 м² с открытой террасой 20 м²."></textarea></p>

    <p><label>Главное изображение (URL):</label><br>
    <input type="text" name="house_info[image]" value="<?php echo esc_url($d['image'] ?? ''); ?>" class="widefat hi-img-input">
    <button type="button" class="button hi-upload">Выбрать изображение</button></p>

    <hr>
    <p><strong>Галерея изображений:</strong></p>
    <div id="hi-gallery-wrap">
    <?php if (!empty($d['gallery']) && is_array($d['gallery'])) : foreach ($d['gallery'] as $gi => $gv) : ?>
        <div class="hi-gallery-item">
            <input type="text" name="house_info[gallery][<?php echo $gi; ?>]" value="<?php echo esc_url($gv); ?>" class="widefat hi-gallery-input" placeholder="URL изображения">
            <button type="button" class="button hi-gallery-upload">Выбрать</button>
            <button type="button" class="button hi-gallery-del">−</button>
        </div>
    <?php endforeach; endif; ?>
    </div>
    <p><button type="button" class="button" id="hi-gallery-add">+ Добавить фото</button></p>

    <hr>
<p><strong>Преимущества (золотые галочки):</strong></p>
<div id="hi-adv-wrap">
<?php if (!empty($d['advantages']) && is_array($d['advantages'])) : foreach ($d['advantages'] as $ai => $av) : ?>
<div class="hi-adv-row">
    <input type="text" name="house_info[advantages][<?php echo $ai; ?>]" value="<?php echo esc_attr($av); ?>" class="widefat" placeholder="Например: Собственная баня на участке">
    <button type="button" class="button hi-adv-del">−</button>
</div>
<?php endforeach; endif; ?>
</div>
<p><button type="button" class="button" id="hi-adv-add">+ Добавить преимущество</button>

    <hr>
    <p><strong>Характеристики (иконка + текст):</strong></p>
    <div id="hi-feats-wrap">
    <?php if (!empty($d['features']) && is_array($d['features'])) : foreach ($d['features'] as $fi => $fv) : ?>
        <div class="hi-feat">
            <input type="text" name="house_info[features][<?php echo $fi; ?>][icon]" value="<?php echo esc_attr($fv['icon'] ?? ''); ?>" placeholder="fa-solid fa-users" style="max-width:220px">
            <input type="text" name="house_info[features][<?php echo $fi; ?>][text]" value="<?php echo esc_attr($fv['text'] ?? ''); ?>" placeholder="До 6 гостей">
            <button type="button" class="button hi-feat-del">−</button>
        </div>
    <?php endforeach; endif; ?>
    </div>
    <p><button type="button" class="button" id="hi-feat-add">+ Добавить характеристику</button></p>

    <hr>
    <p><label>Текст кнопки CTA:</label><br>
    <input type="text" name="house_info[cta_text]" value="<?php echo esc_attr($d['cta_text'] ?? 'Забронировать'); ?>" class="widefat"></p>

    <p><label>Ссылка кнопки CTA:</label><br>
    <input type="text" name="house_info[cta_link]" value="<?php echo esc_attr($d['cta_link'] ?? '#cta'); ?>" class="widefat" placeholder="#cta"></p>

    <script>
(function($){

    /* ─── Главное изображение ─── */
    $('.hi-upload').on('click', function(){
        var btn = this;
        var frame = wp.media({title:'Выбрать изображение', multiple:false, library:{type:'image'}});
        frame.on('select', function(){
            $(btn).prev('.hi-img-input').val(frame.state().get('selection').first().toJSON().url);
        });
        frame.open();
    });

    /* ─── Галерея ─── */
    var gi = $('#hi-gallery-wrap .hi-gallery-item').length;
    $('#hi-gallery-add').on('click', function(){
        var h = '<div class="hi-gallery-item">'
            +'<input type="text" name="house_info[gallery]['+gi+']" class="widefat hi-gallery-input" placeholder="URL изображения">'
            +' <button type="button" class="button hi-gallery-upload">Выбрать</button>'
            +' <button type="button" class="button hi-gallery-del">\u2212</button></div>';
        $('#hi-gallery-wrap').append(h);
        gi++;
    });

    $(document).on('click', '.hi-gallery-del', function(){ $(this).closest('.hi-gallery-item').remove(); });

    $(document).on('click', '.hi-gallery-upload', function(){
        var btn = this;
        var frame = wp.media({title:'Выбрать изображение', multiple:false, library:{type:'image'}});
        frame.on('select', function(){
            $(btn).prev('.hi-gallery-input').val(frame.state().get('selection').first().toJSON().url);
        });
        frame.open();
    });

    /* ─── Преимущества ─── */
    var ai = $('#hi-adv-wrap .hi-adv-row').length;
    $('#hi-adv-add').on('click', function(){
        var h = '<div class="hi-adv-row">'
            +'<input type="text" name="house_info[advantages]['+ai+']" class="widefat" placeholder="Например: Собственная баня на участке">'
            +' <button type="button" class="button hi-adv-del">\u2212</button></div>';
        $('#hi-adv-wrap').append(h);
        ai++;
    });
    $(document).on('click', '.hi-adv-del', function(){ $(this).closest('.hi-adv-row').remove(); });

    /* ─── Характеристики ─── */
    var fi = $('#hi-feats-wrap .hi-feat').length;
    $('#hi-feat-add').on('click', function(){
        var h = '<div class="hi-feat">'
            +'<input type="text" name="house_info[features]['+fi+'][icon]" placeholder="fa-solid fa-users" style="max-width:220px">'
            +'<input type="text" name="house_info[features]['+fi+'][text]" placeholder="До 6 гостей">'
            +' <button type="button" class="button hi-feat-del">\u2212</button></div>';
        $('#hi-feats-wrap').append(h);
        fi++;
    });

    $(document).on('click', '.hi-feat-del', function(){ $(this).closest('.hi-feat').remove(); });

})(jQuery);
</script>
    <?php
}

add_action('save_post', function ($pid) {
    if (!isset($_POST['house_info_nonce_f'])) return;
    if (!wp_verify_nonce($_POST['house_info_nonce_f'], 'house_info_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $pid)) return;

    $raw = $_POST['house_info'] ?? [];

    $gallery = [];
    if (!empty($raw['gallery']) && is_array($raw['gallery'])) {
        foreach ($raw['gallery'] as $url) {
            $u = esc_url_raw($url);
            if ($u) $gallery[] = $u;
        }
    }

    $features = [];
    if (!empty($raw['features']) && is_array($raw['features'])) {
        foreach ($raw['features'] as $f) {
            $t = sanitize_text_field($f['text'] ?? '');
            if ($t) {
                $features[] = [
                    'icon' => sanitize_text_field($f['icon'] ?? ''),
                    'text' => $t,
                ];
            }
        }
    }

     $advantages = [];
if (!empty($raw['advantages']) && is_array($raw['advantages'])) {
    foreach ($raw['advantages'] as $a) {
        $t = sanitize_text_field(is_array($a) ? ($a ?? '') : $a);
        if ($t) $advantages[] = $t;
    }
}

    update_post_meta($pid, 'house_info', [
        'title'    => sanitize_text_field($raw['title'] ?? ''),
        'desc'     => sanitize_textarea_field($raw['desc'] ?? ''),
        'image'    => esc_url_raw($raw['image'] ?? ''),
        'gallery'  => $gallery,
        'features' => $features,
        'cta_text' => sanitize_text_field($raw['cta_text'] ?? ''),
        'cta_link' => sanitize_text_field($raw['cta_link'] ?? ''),
        'gold_text' => sanitize_text_field($raw['gold_text'] ?? ''),
        'advantages' => $advantages,
        'highlight' => sanitize_textarea_field($raw['highlight'] ?? ''),
    ]);
});