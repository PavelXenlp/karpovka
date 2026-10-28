<?php
// Logic: Страница сертификатов

function karpovka_render_certificates_page() {
    // Получаем данные из произвольных полей (ACF или мета-полей)
    // Предполагаем, что поле называется 'certificates_list' (повторитель)
    $v = get_post_meta( get_the_ID(), 'certificates_list', true );
    $certs = $v ?? [];
    
    // Заголовок страницы (H1)
    $page_title = get_the_title();
    $page_desc = get_post_meta( get_the_ID(), 'page_description', true ) ?: ''; // Опциональное описание

    if ( empty( $certs ) ) {
        // Если сертификатов нет, можно вывести заглушки или текст (опционально)
        // echo '<p>Сертификаты скоро появятся.</p>';
        return;
    }
?>

<section class="certificates-section">
    <div class="container">
        <header class="certificates__header text-center">
            <h1><?php echo esc_html( $page_title ); ?></h1>
            <?php if ( $page_desc ) : ?>
                <p class="certificates__desc"><?php echo esc_html( $page_desc ); ?></p>
            <?php endif; ?>
        </header>

        <div class="certificates__grid">
            <?php foreach ( $certs as $cert ) :
                $title    = $cert['title'] ?? '';
                $price    = $cert['price'] ?? '';
                $bg       = $cert['image'] ?? '';
                $btn_text = $cert['btn_text'] ?? 'Купить';
                $btn_link = $cert['btn_link'] ?? '#';
            ?>
            <article class="certificate-card reveal">
                <?php if ( $bg ) : ?>
                <div class="certificate-card__img-wrapper">
                    <img class="certificate-card__img" src="<?php echo esc_url( $bg ); ?>" alt="<?php echo esc_attr( $title ); ?>" />
                </div>
                <?php endif; ?>
                
                <div class="certificate-card__content">
                    <?php if ( $title ) : ?>
                    <h3 class="certificate-card__title"><?php echo esc_html( $title ); ?></h3>
                    <?php endif; ?>
                    
                    <?php if ( $price ) : ?>
                    <p class="certificate-card__price"><?php echo esc_html( $price ); ?></p>
                    <?php endif; ?>
                    
                    <?php if ( $btn_text ) : ?>
                    <a class="btn btn--gold btn--full-width" href="<?php echo esc_url( $btn_link ); ?>">
                        <?php echo esc_html( $btn_text ); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
}