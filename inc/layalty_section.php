<?php
/**
 * Admin Panel: Loyalty Section Fields
 */

function loyalty_admin_cb( $post ) {
    // Защита
    wp_nonce_field( 'loyalty_save_action', 'loyalty_nonce' );

    // Получаем данные
    $data = get_post_meta( $post->ID, 'loyalty_data', true );
    
    $intro           = $data['intro'] ?? '';
    $steps_heading   = $data['steps_heading'] ?? 'Как это работает'; // Заголовок этапов (по умолчанию)
    $levels_heading  = $data['levels_heading'] ?? 'Уровни программы'; // Заголовок уровней (по умолчанию)
    $steps           = $data['steps'] ?? [];
    $levels          = $data['levels'] ?? [];
    
    // Стили админки
    ?>
    <style>
        .loyalty-wrapper { background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 8px; }
        .loyalty-section-title { font-size: 16px; font-weight: 700; color: #1d2327; margin: 30px 0 15px; border-bottom: 2px solid #eee; padding-bottom: 5px; }
        
        .loyalty-item { border: 1px solid #dcdcde; background: #fafafa; margin-bottom: 15px; padding: 15px; border-radius: 6px; position: relative; }
        .loyalty-row-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; gap: 10px; }
        .loyalty-title-input { flex-grow: 1; font-size: 1.05em; font-weight: 600; border: 1px solid #ddd; padding: 6px; }
        .loyalty-body { display: grid; gap: 15px; margin-bottom: 12px; }
        .loyalty-field label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; color: #444; }
        .loyalty-field textarea { width: 100%; min-height: 100px; padding: 8px; border: 1px solid #8c8f94; border-radius: 4px; }
        
        .image-uploader-box { border: 1px dashed #b4b9be; padding: 10px; background: #f0f0f1; text-align: center; border-radius: 4px; }
        .image-uploader-box img { max-width: 100%; max-height: 120px; display: block; margin: 10px auto; border: 1px solid #ddd; }
        .loyalty-delete-btn { color: #a00; background: #fff; border: 1px solid #a00; padding: 4px 10px; border-radius: 4px; cursor: pointer; font-size: 12px; text-decoration: none;}
        .loyalty-delete-btn:hover { background: #a00; color: #fff; }
    </style>
    
    <div class="loyalty-wrapper">
        
        <!-- 1. Вступление -->
        <div class="loyalty-field" style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
            <label>Вступительный текст:</label>
            <?php wp_editor( $intro, 'loyalty_intro', array( 'textarea_name' => 'loyalty_data[intro]', 'media_buttons' => false, 'textarea_rows' => 4, 'teeny' => true ) ); ?>
        </div>

        <!-- Добавьте этот код СЮДА для картинки -->
        <div class="loyalty-field">
            <label>Иллюстрация (справа):</label>
            <div class="image-uploader-box">
                <input type="hidden" name="loyalty_data[intro_image]" value="<?php echo esc_attr( $data['intro_image'] ?? '' ); ?>" class="img-url-input">
                <button type="button" class="button upload-btn-small">Выбрать фото</button>
                <div class="img-preview">
                    <?php if ( ! empty( $data['intro_image'] ) ) : ?>
                        <img src="<?php echo esc_url( $data['intro_image'] ); ?>" style="max-width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- 2. ЗАГОЛОВОК И ЭТАПЫ -->
        <div class="loyalty-section-title">Секция: Этапы участия</div>
        <div class="loyalty-field">
            <label>Заголовок над этапами:</label>
            <input type="text" name="loyalty_data[steps_heading]" value="<?php echo esc_attr( $steps_heading ); ?>" placeholder="Например: Как это работает">
        </div>
        <div id="steps-repeater-container">
            <?php foreach ( $steps as $index => $step ) : ?>
                <?php echo render_loyalty_step_row( $index, $step ); ?>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button" id="add-step-row" style="margin-bottom: 30px;">+ Добавить этап</button>

        <!-- 3. ЗАГОЛОВОК И УРОВНИ -->
        <div class="loyalty-section-title">Секция: Уровни программы</div>
        <div class="loyalty-field">
            <label>Заголовок над уровнями:</label>
            <input type="text" name="loyalty_data[levels_heading]" value="<?php echo esc_attr( $levels_heading ); ?>" placeholder="Например: Уровни программы">
        </div>
        <div id="levels-repeater-container">
            <?php foreach ( $levels as $index => $level ) : ?>
                <?php echo render_loyalty_level_row( $index, $level ); ?>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button button-primary" id="add-level-row">+ Добавить уровень</button>
    </div>

    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // --- Логика добавления Этапов ---
        $('#add-step-row').click(function() {
            var index = $('#steps-repeater-container .loyalty-item').length;
            $('#steps-repeater-container').append(loyaltyGetStepHtml(index));
        });

        // --- Логика добавления Уровней ---
        $('#add-level-row').click(function() {
            var index = $('#levels-repeater-container .loyalty-item').length;
            $('#levels-repeater-container').append(loyaltyGetLevelHtml(index));
        });

        // --- Загрузчик картинок ---
        $(document).on('click', '.upload-btn-small', function(e) {
            e.preventDefault();
            var btn = $(this);
            var wrapper = btn.closest('.image-uploader-box');
            var input = wrapper.find('input[type="hidden"]');
            var preview = wrapper.find('div');
            var uploader = wp.media({ title: 'Выбрать картинку', button: { text: 'OK' }, multiple: false })
            .on('select', function() {
                var attachment = uploader.state().get('selection').first().toJSON();
                input.val(attachment.url);
                preview.html('<img src="' + attachment.url + '">');
                btn.text('Изменить');
            }).open();
        });
    });

    // HTML шаблоны для JS
    window.loyaltyGetStepHtml = function(i) {
        return `<div class="loyalty-item">
            <div class="loyalty-row-header">
                <input type="text" name="loyalty_data[steps][${i}][title]" class="loyalty-title-input" placeholder="Название этапа (напр. Регистрация)">
                <button type="button" class="loyalty-delete-btn" onclick="jQuery(this).closest('.loyalty-item').remove()">Удалить</button>
            </div>
            <div class="loyalty-body loyalty-full-width">
                <textarea name="loyalty_data[steps][${i}][desc]" placeholder="Описание этапа..."></textarea>
            </div>
        </div>`;
    };

    window.loyaltyGetLevelHtml = function(i) {
        return `<div class="loyalty-item">
            <div class="loyalty-row-header">
                <input type="text" name="loyalty_data[levels][${i}][title]" class="loyalty-title-input" placeholder="Название уровня">
                <div style="display:flex; align-items:center; gap:10px;">
                    <select name="loyalty_data[levels][${i}][modifier]" style="padding:4px;">
                        <option value="standard">Обычный</option>
                        <option value="gold">Золотой</option>
                    </select>
                    <button type="button" class="loyalty-delete-btn" onclick="jQuery(this).closest('.loyalty-item').remove()">Удалить</button>
                </div>
            </div>
            <div class="loyalty-body">
                <div class="loyalty-field loyalty-full-width">
                    <label>Описание и бонусы:</label>
                    <textarea name="loyalty_data[levels][${i}][benefits]" rows="5"></textarea>
                </div>
                <div class="loyalty-field">
                    <label>Картинка:</label>
                    <div class="image-uploader-box">
                        <input type="hidden" name="loyalty_data[levels][${i}][bg]" value="" class="img-url-input">
                        <button type="button" class="button upload-btn-small">Выбрать</button>
                        <div></div>
                    </div>
                </div>
            </div>
            <div class="loyalty-body">
                <div class="loyalty-field">
                    <label>Текст кнопки:</label>
                    <input type="text" name="loyalty_data[levels][${i}][btn_text]" value="Подробнее">
                </div>
                <div class="loyalty-field">
                    <label>Ссылка:</label>
                    <input type="text" name="loyalty_data[levels][${i}][btn_link]" value="#">
                </div>
            </div>
        </div>`;
    };
    </script>
    <?php
}

// Вспомогательные функции PHP для рендера существующих строк в админке
function render_loyalty_step_row( $index, $step ) {
    ?>
    <div class="loyalty-item">
        <div class="loyalty-row-header">
            <input type="text" name="loyalty_data[steps][<?php echo $index; ?>][title]" class="loyalty-title-input" value="<?php echo esc_attr( $step['title'] ?? '' ); ?>">
            <button type="button" class="loyalty-delete-btn" onclick="jQuery(this).closest('.loyalty-item').remove()">Удалить</button>
        </div>
        <div class="loyalty-body loyalty-full-width">
            <textarea name="loyalty_data[steps][<?php echo $index; ?>][desc]" rows="3"><?php echo esc_textarea( $step['desc'] ?? '' ); ?></textarea>
        </div>
    </div>
    <?php
}

function render_loyalty_level_row( $index, $level ) {
    $bg = $level['bg'] ?? '';
    ?>
    <div class="loyalty-item">
        <div class="loyalty-row-header">
            <input type="text" name="loyalty_data[levels][<?php echo $index; ?>][title]" class="loyalty-title-input" value="<?php echo esc_attr( $level['title'] ?? '' ); ?>">
            <div style="display:flex; align-items:center; gap:10px;">
                <select name="loyalty_data[levels][<?php echo $index; ?>][modifier]" style="padding:4px;">
                    <option value="standard" <?php selected( $level['modifier'] ?? '', 'standard' ); ?>>Обычный</option>
                    <option value="gold" <?php selected( $level['modifier'] ?? '', 'gold' ); ?>>Золотой</option>
                </select>
                <button type="button" class="loyalty-delete-btn" onclick="jQuery(this).closest('.loyalty-item').remove()">Удалить</button>
            </div>
        </div>
        <div class="loyalty-body">
            <div class="loyalty-field loyalty-full-width">
                <label>Описание и бонусы:</label>
                <textarea name="loyalty_data[levels][<?php echo $index; ?>][benefits]" rows="5"><?php echo esc_textarea( $level['benefits'] ?? '' ); ?></textarea>
            </div>
            <div class="loyalty-field">
                <label>Картинка:</label>
                <div class="image-uploader-box">
                    <input type="hidden" name="loyalty_data[levels][<?php echo $index; ?>][bg]" value="<?php echo esc_url( $bg ); ?>" class="img-url-input">
                    <button type="button" class="button upload-btn-small"><?php echo $bg ? 'Изменить' : 'Выбрать'; ?></button>
                    <div><?php if ( $bg ) : ?><img src="<?php echo esc_url( $bg ); ?>"><?php endif; ?></div>
                </div>
            </div>
        </div>
        <div class="loyalty-body">
            <div class="loyalty-field">
                <label>Текст кнопки:</label>
                <input type="text" name="loyalty_data[levels][<?php echo $index; ?>][btn_text]" value="<?php echo esc_attr( $level['btn_text'] ?? 'Подробнее' ); ?>">
            </div>
            <div class="loyalty-field">
                <label>Ссылка:</label>
                <input type="text" name="loyalty_data[levels][<?php echo $index; ?>][btn_link]" value="<?php echo esc_url( $level['btn_link'] ?? '#' ); ?>">
            </div>
        </div>
    </div>
    <?php
}

// Сохранение
add_action( 'save_post_page', function( $post_id ) {
    if ( ! isset( $_POST['loyalty_nonce'] ) || ! wp_verify_nonce( $_POST['loyalty_nonce'], 'loyalty_save_action' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( isset( $_POST['loyalty_data'] ) ) {
        update_post_meta( $post_id, 'loyalty_data', $_POST['loyalty_data'] );
    }
});