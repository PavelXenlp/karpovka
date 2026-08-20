<?php
/* ===== Секция: Пакеты размещения (акции проживания) ===== */

add_action('add_meta_boxes', function () {
    add_meta_box('acc_offers_box', 'Пакеты размещения', 'acc_offers_mb', 'page', 'normal', 'high');
});

function acc_offers_mb($post) {
    wp_nonce_field('acc_offers_nonce', 'acc_offers_nonce_f');
    $data = get_post_meta($post->ID, 'acc_offers', true);
    if (!is_array($data)) $data = [];
    $eyebrow      = $data['eyebrow'] ?? '';
    $title_before = $data['title_before'] ?? '';
    $title_em     = $data['title_em'] ?? '';
    $sub          = $data['sub'] ?? '';
    $cards        = $data['cards'] ?? [];
    ?>
    <style>
        .ao-card{margin-bottom:12px;padding:12px;border:1px dashed #c3c4c7;background:#fafafa;position:relative;}
        .ao-card>h5{margin:0 0 8px;color:#2271b1;font-size:13px;}
        .ao-card .ao-card-del{position:absolute;top:8px;right:10px;color:#a00;cursor:pointer;text-decoration:underline;font-size:12px;}
        .ao-card .ao-card-del:hover{color:#f00;}
        .ao-cond-row{display:flex;gap:6px;margin-bottom:4px;align-items:center;}
        .ao-cond-row input{flex:1;}
    </style>

    <p><label>Надзаголовок:</label><br>
    <input type="text" name="acc_offers[eyebrow]" value="<?php echo esc_attr($eyebrow); ?>" class="widefat" placeholder="Пакеты размещения"></p>

    <p><label>Заголовок — часть до выделения:</label><br>
    <input type="text" name="acc_offers[title_before]" value="<?php echo esc_attr($title_before); ?>" class="widefat" placeholder="Предложения для"></p>

    <p><label>Золотой текст (выделение):</label><br>
    <input type="text" name="acc_offers[title_em]" value="<?php echo esc_attr($title_em); ?>" class="widefat" placeholder="проживания"></p>

    <p><label>Подзаголовок:</label><br>
    <textarea name="acc_offers[sub]" rows="2" class="widefat"><?php echo esc_textarea($sub); ?></textarea></p>

    <hr>
    <p><strong>Карточки:</strong></p>

    <div id="ao-cards-wrap">
    <?php foreach ($cards as $ci => $c) : ?>
        <div class="ao-card">
            <span class="ao-card-del">Удалить карточку</span>
            <h5>Карточка <?php echo $ci + 1; ?></h5>

            <p><label>Изображение:</label><br>
            <input type="text" name="acc_offers[cards][<?php echo $ci; ?>][image]" value="<?php echo esc_url($c['image'] ?? ''); ?>" class="widefat ao-card-img">
            <button type="button" class="button ao-card-upload">Выбрать</button></p>

            <p><label>Текст бейджа:</label><br>
            <input type="text" name="acc_offers[cards][<?php echo $ci; ?>][badge_text]" value="<?php echo esc_attr($c['badge_text'] ?? ''); ?>" class="widefat" placeholder="−10%"></p>

            <p><label><input type="checkbox" name="acc_offers[cards][<?php echo $ci; ?>][badge_hot]" value="1" <?php checked($c['badge_hot'] ?? '', '1'); ?>> Бейдж «Хит» (красный)</label></p>

            <p><label>Название:</label><br>
            <input type="text" name="acc_offers[cards][<?php echo $ci; ?>][title]" value="<?php echo esc_attr($c['title'] ?? ''); ?>" class="widefat"></p>

            <p><label>Описание:</label><br>
            <textarea name="acc_offers[cards][<?php echo $ci; ?>][desc]" rows="3" class="widefat"><?php echo esc_textarea($c['desc'] ?? ''); ?></textarea></p>

            <p><label>Ссылка «Подробнее»:</label><br>
            <input type="text" name="acc_offers[cards][<?php echo $ci; ?>][link]" value="<?php echo esc_attr($c['link'] ?? ''); ?>" class="widefat" placeholder="/offers/weekday"></p>

            <p><strong>Условия (иконка + текст):</strong></p>
            <div class="ao-conds-wrap">
            <?php if (!empty($c['conditions']) && is_array($c['conditions'])) : foreach ($c['conditions'] as $ki => $cn) : ?>
                <div class="ao-cond-row">
                    <input type="text" name="acc_offers[cards][<?php echo $ci; ?>][conditions][<?php echo $ki; ?>][icon]" value="<?php echo esc_attr($cn['icon'] ?? ''); ?>" placeholder="fa-solid fa-moon">
                    <input type="text" name="acc_offers[cards][<?php echo $ci; ?>][conditions][<?php echo $ki; ?>][text]" value="<?php echo esc_attr($cn['text'] ?? ''); ?>" placeholder="3+ ночи">
                    <button type="button" class="button ao-cond-del">−</button>
                </div>
            <?php endforeach; endif; ?>
            </div>
            <p><button type="button" class="button ao-cond-add">+ Условие</button></p>
        </div>
    <?php endforeach; ?>
    </div>
    <p><button type="button" class="button button-primary" id="ao-card-add">+ Добавить карточку</button></p>

    <script>
    (function($){
        function mkCond(ci, ni){
            return '<div class="ao-cond-row">'
                +'<input type="text" name="acc_offers[cards]['+ci+'][conditions]['+ni+'][icon]" placeholder="fa-solid fa-moon">'
                +'<input type="text" name="acc_offers[cards]['+ci+'][conditions]['+ni+'][text]" placeholder="3+ ночи">'
                +'<button type="button" class="button ao-cond-del">\u2212</button></div>';
        }

        function mkCard(ci){
            return '<div class="ao-card"><span class="ao-card-del">Удалить карточку</span>'
                +'<h5>Карточка '+(ci+1)+'</h5>'
                +'<p><label>Изображение:</label><br>'
                +'<input type="text" name="acc_offers[cards]['+ci+'][image]" class="widefat ao-card-img">'
                +' <button type="button" class="button ao-card-upload">Выбрать</button></p>'
                +'<p><label>Текст бейджа:</label><br>'
                +'<input type="text" name="acc_offers[cards]['+ci+'][badge_text]" class="widefat" placeholder="\u221210%"></p>'
                +'<p><label><input type="checkbox" name="acc_offers[cards]['+ci+'][badge_hot]" value="1"> Бейдж \u00ABХит\u00BB (красный)</label></p>'
                +'<p><label>Название:</label><br>'
                +'<input type="text" name="acc_offers[cards]['+ci+'][title]" class="widefat"></p>'
                +'<p><label>Описание:</label><br>'
                +'<textarea name="acc_offers[cards]['+ci+'][desc]" rows="3" class="widefat"></textarea></p>'
                +'<p><label>Ссылка \u00ABПодробнее\u00BB:</label><br>'
                +'<input type="text" name="acc_offers[cards]['+ci+'][link]" class="widefat" placeholder="/offers/weekday"></p>'
                +'<p><strong>Условия:</strong></p>'
                +'<div class="ao-conds-wrap"></div>'
                +'<p><button type="button" class="button ao-cond-add">+ Условие</button></p></div>';
        }

        var cn = $('#ao-cards-wrap .ao-card').length;

        $('#ao-card-add').on('click', function(){
            $('#ao-cards-wrap').append(mkCard(cn));
            cn++;
        });

        $(document).on('click', '.ao-card-del', function(){ $(this).closest('.ao-card').remove(); });

        $(document).on('click', '.ao-cond-add', function(){
            var $card = $(this).closest('.ao-card');
            var ci = $card.index('#ao-cards-wrap .ao-card');
            var ni = $card.find('.ao-cond-row').length;
            $card.find('.ao-conds-wrap').append(mkCond(ci, ni));
        });

        $(document).on('click', '.ao-cond-del', function(){ $(this).closest('.ao-cond-row').remove(); });

        $(document).on('click', '.ao-card-upload', function(){
            var btn = this;
            var frame = wp.media({title:'Выбрать изображение', multiple:false, library:{type:'image'}});
            frame.on('select', function(){
                var url = frame.state().get('selection').first().toJSON().url;
                $(btn).prev('.ao-card-img').val(url);
            });
            frame.open();
        });
    })(jQuery);
    </script>
    <?php
}

add_action('save_post', function ($pid) {
    if (!isset($_POST['acc_offers_nonce_f'])) return;
    if (!wp_verify_nonce($_POST['acc_offers_nonce_f'], 'acc_offers_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $pid)) return;

    $cards = [];
    if (!empty($_POST['acc_offers']['cards']) && is_array($_POST['acc_offers']['cards'])) {
        foreach ($_POST['acc_offers']['cards'] as $c) {
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
                'link'       => sanitize_text_field($c['link'] ?? ''),
                'conditions' => $conditions,
            ];
        }
    }

    update_post_meta($pid, 'acc_offers', [
        'eyebrow'      => sanitize_text_field($_POST['acc_offers']['eyebrow'] ?? ''),
        'title_before' => sanitize_text_field($_POST['acc_offers']['title_before'] ?? ''),
        'title_em'     => sanitize_text_field($_POST['acc_offers']['title_em'] ?? ''),
        'sub'          => sanitize_textarea_field($_POST['acc_offers']['sub'] ?? ''),
        'cards'        => $cards,
    ]);
});