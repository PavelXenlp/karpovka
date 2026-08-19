<!doctype html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php wp_head(); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <!-- ─── Глобальный лоадер: критический CSS (в WP → wp_head) ── -->
    <style>
        /* Цвета захардкожены: этот <style> рендерится до styles2.css,
         где определены CSS-переменные (--navy-950 / --gold). */
        #site-loader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 34px;
            background: #0a2822;
            opacity: 1;
            visibility: visible;
            transition:
                opacity 0.6s ease,
                visibility 0s linear 0.6s;
        }

        #site-loader.is-done {
            opacity: 0;
            visibility: hidden;
        }

        #site-loader .loader__logo {
            width: clamp(150px, 26vw, 240px);
            height: auto;
            display: block;
            animation: loaderPulse 1.8s ease-in-out infinite;
        }

        #site-loader .loader__bar {
            position: relative;
            width: clamp(180px, 40vw, 280px);
            height: 3px;
            background: rgba(200, 164, 108, 0.18);
            border-radius: 2px;
            overflow: hidden;
        }

        #site-loader .loader__bar-fill {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: #c8a46c;
            border-radius: 2px;
        }

        @keyframes loaderPulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.55;
            }
        }

        /* Блокируем прокрутку, пока лоадер активен */
        html.is-loading {
            overflow: hidden;
        }

        /* Повторный визит в рамках сессии — лоадер не показываем */
        html.loader-skip #site-loader {
            display: none;
        }

        @media (prefers-reduced-motion: reduce) {
            #site-loader .loader__logo {
                animation: none;
            }
        }
    </style>

    <!-- Решение показать/пропустить — до отрисовки, чтобы не было мигания -->
    <script>
        (function() {
            try {
                if (sessionStorage.getItem("kpLoaderShown")) {
                    document.documentElement.classList.add("loader-skip");
                    return;
                }
            } catch (e) {}
            document.documentElement.classList.add("is-loading");
        })();
    </script>

    <!-- Если JS выключен — лоадер не должен «запереть» сайт -->
    <noscript>
        <style>
            #site-loader {
                display: none !important;
            }

            html.is-loading {
                overflow: auto !important;
            }
        </style>
    </noscript>
</head>

<body>
    <!-- ─── Глобальный лоадер (в WP → после wp_body_open) ───────── -->
    <div id="site-loader" role="status" aria-label="Загрузка сайта">
        <img class="loader__logo" src="./assets/imgs/logo.png" alt="Карповка" />
        <div class="loader__bar">
            <div class="loader__bar-fill"></div>
        </div>
    </div>

    <script>
        // Управление лоадером. Стоит сразу после разметки → стартует немедленно,
        // не дожидаясь Swiper с CDN и script2.js. Чистый ванильный JS.
        (function() {
            var root = document.documentElement;
            if (!root.classList.contains("is-loading")) return; // повторный визит — пропуск
            try {
                sessionStorage.setItem("kpLoaderShown", "1");
            } catch (e) {}

            var loader = document.getElementById("site-loader");
            if (!loader) {
                root.classList.remove("is-loading");
                return;
            }
            var fill = loader.querySelector(".loader__bar-fill");
            var start = Date.now();
            var MIN = 600; // минимум показа, чтобы не мигал на быстрой загрузке
            var MAX = 7000; // фолбэк, если ресурс завис
            var progress = 0;
            var raf;

            function tick() {
                // асимптотически подползаем к 90% — до 100% доходим на window.load
                progress += (90 - progress) * 0.02;
                if (fill) fill.style.width = progress.toFixed(1) + "%";
                if (progress < 89.5) raf = requestAnimationFrame(tick);
            }
            tick();

            var hidden = false;

            function hide() {
                if (hidden) return;
                hidden = true;
                if (raf) cancelAnimationFrame(raf);
                if (fill) {
                    fill.style.transition = "width .3s ease";
                    fill.style.width = "100%";
                }
                var wait = Math.max(0, MIN - (Date.now() - start));
                setTimeout(function() {
                    loader.classList.add("is-done"); // плавное затухание (CSS)
                    root.classList.remove("is-loading"); // разблокировать скролл
                    setTimeout(function() {
                        loader.style.display = "none";
                    }, 650); // после CSS-перехода 0.6s
                }, wait);
            }

            window.addEventListener("load", hide);
            setTimeout(hide, MAX);
        })();
    </script>

    <!-- ─── Icon sprite (иконки активностей из основного сайта) ─ -->
    <svg class="icon-sprite" aria-hidden="true" width="0" height="0">
        <symbol id="icon-fish" viewBox="0 0 24 24">
            <path d="M6.5 12c1.8-4 6.4-6 11-3l4-3v12l-4-3c-4.6 3-9.2 1-11-3Z" />
            <path d="M2 12h5" />
            <circle cx="15" cy="11" r="1" />
        </symbol>
        <symbol id="icon-boat" viewBox="0 0 24 24">
            <path d="M3 15h18l-1.6 4A2 2 0 0 1 17.5 20h-11a2 2 0 0 1-1.9-1L3 15Z" />
            <path d="m10 3 5 12" />
            <path d="M9 3h2l-.5 3" />
        </symbol>
        <symbol id="icon-catamaran" viewBox="0 0 24 24">
            <path d="M2 18c1.5 1.5 3 2 5 2" />
            <path d="M22 18c-1.5 1.5-3 2-5 2" />
            <path d="M7 20h10" />
            <path d="M12 20V4l6 9h-6" />
        </symbol>
        <symbol id="icon-atv" viewBox="0 0 24 24">
            <circle cx="6" cy="17" r="3" />
            <circle cx="18" cy="17" r="3" />
            <path d="M9 17h6" />
            <path d="M6 14c0-2 1.2-4 3.5-4H13l3 4" />
            <path d="M9.5 10V7H13" />
        </symbol>
        <symbol id="icon-bike" viewBox="0 0 24 24">
            <circle cx="5.5" cy="17.5" r="3.5" />
            <circle cx="18.5" cy="17.5" r="3.5" />
            <path d="M15 6h3" />
            <path d="m9 6 3 6h4l-3-6" />
            <path d="M5.5 17.5 9 12h3" />
        </symbol>
        <symbol id="icon-scooter" viewBox="0 0 24 24">
            <circle cx="5" cy="18" r="2" />
            <circle cx="19" cy="18" r="2" />
            <path d="M7 18h9" />
            <path d="m17 18 1-12" />
            <path d="M16 5h4" />
        </symbol>
        <symbol id="icon-volleyball" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9" />
            <path d="M12 3a20 20 0 0 0-6 17" />
            <path d="M21 9a20 20 0 0 0-16 3" />
            <path d="M14.5 21A20 20 0 0 0 20 9" />
        </symbol>
        <symbol id="icon-basketball" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9" />
            <path d="M12 3v18" />
            <path d="M3 12h18" />
            <path d="M5.64 5.64c3.5 3 3.5 9.72 0 12.72" />
            <path d="M18.36 5.64c-3.5 3-3.5 9.72 0 12.72" />
        </symbol>
        <symbol id="icon-soccer" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9" />
            <path d="m12 7.5 3.4 2.5-1.3 4h-4.2l-1.3-4L12 7.5Z" />
            <path d="M12 7.5V3.2" />
            <path d="m15.4 10 3.9-1.7" />
            <path d="m14.1 14 2.5 3.4" />
            <path d="m9.9 14-2.5 3.4" />
            <path d="M8.6 10 4.7 8.3" />
        </symbol>
        <symbol id="icon-badminton" viewBox="0 0 24 24">
            <circle cx="12" cy="18" r="2.5" />
            <path d="M10 16 7 6" />
            <path d="M14 16 17 6" />
            <path d="M12 15.5V5" />
            <path d="M7 6c3-2 7-2 10 0" />
            <path d="m9.4 15.7.8-9" />
            <path d="m14.6 15.7-.8-9" />
        </symbol>
        <symbol id="icon-dice" viewBox="0 0 24 24">
            <rect x="4" y="4" width="16" height="16" rx="3" />
            <circle cx="8.5" cy="8.5" r="1" />
            <circle cx="15.5" cy="8.5" r="1" />
            <circle cx="12" cy="12" r="1" />
            <circle cx="8.5" cy="15.5" r="1" />
            <circle cx="15.5" cy="15.5" r="1" />
        </symbol>
        <symbol id="icon-ski" viewBox="0 0 24 24">
            <path d="M18 4 7 17" />
            <path d="M21 7 10 20" />
            <path d="M7 17c-1.5 1.8-3.5 1.3-3.5-.8" />
            <path d="M10 20c-1.5 1.8-3.5 1.3-3.5-.8" />
            <path d="M14.5 5 9 19" />
            <path d="M13.5 4.2h2.8" />
        </symbol>
        <symbol id="icon-tube" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9" />
            <circle cx="12" cy="12" r="3.5" />
        </symbol>
        <symbol id="icon-snowmobile" viewBox="0 0 24 24">
            <path d="M3 17h9l3-4h3a2 2 0 0 1 2 2v2" />
            <path d="M3 17a2 2 0 0 1 0-4h5" />
            <path d="M14 13l1.5-3H19" />
            <path d="M16 19h5" />
            <path d="M21 19c1.3 0 1.6-1.6.5-2.3" />
        </symbol>
        <symbol id="icon-skate" viewBox="0 0 24 24">
            <path d="M7 3v8h6a4 4 0 0 1 4 4" />
            <path d="M4 18h15" />
            <path d="M7.5 15v3" />
            <path d="M14 15v3" />
            <path d="M4 18c-1.2 0-2-.7-2-1.8" />
        </symbol>
        <symbol id="icon-walking" viewBox="0 0 24 24">
            <circle cx="13" cy="4" r="1.8" />
            <path d="M13 5.8c-1.2 1.8-1.2 3.4 0 5.2" />
            <path d="m13 11-3 5" />
            <path d="m13 11 3 4" />
            <path d="M11 8 7 21" />
            <path d="M15 8.5 19 21" />
        </symbol>
        <symbol id="icon-stroll" viewBox="0 0 24 24">
            <circle cx="12" cy="4" r="1.8" />
            <path d="M12 6v6" />
            <path d="M12 12 9 21" />
            <path d="M12 12l3 4-1 5" />
            <path d="M12 8 8.5 10" />
            <path d="M12 8l3.5 1.5" />
        </symbol>
        <symbol id="icon-dumbbell" viewBox="0 0 24 24">
            <path d="m6.5 6.5 11 11" />
            <path d="m21 14-7 7" />
            <path d="m3 10 7-7" />
            <path d="m18 11 3 3" />
            <path d="m3 10 3 3" />
        </symbol>
        <symbol id="icon-playground" viewBox="0 0 24 24">
            <path d="M6 20 9.5 5" />
            <path d="M18 20 14.5 5" />
            <path d="M8 5h8" />
            <path d="M10.3 5v9" />
            <path d="M13.7 5v9" />
            <path d="M9 14h6" />
        </symbol>
        <symbol id="icon-chess" viewBox="0 0 24 24">
            <circle cx="12" cy="5" r="2.5" />
            <path
                d="M9.7 9.5c-.3 2.3-1.7 4.3-3.2 5.5h11c-1.5-1.2-2.9-3.2-3.2-5.5" />
            <path d="M9.5 9.5h5" />
            <path d="M6.5 15 5.5 20" />
            <path d="M17.5 15l1 5" />
            <path d="M5 20h14" />
        </symbol>
        <symbol id="icon-calendar" viewBox="0 0 24 24">
            <path d="M8 2v4" />
            <path d="M16 2v4" />
            <rect x="3" y="4" width="18" height="18" rx="2" />
            <path d="M3 10h18" />
        </symbol>
        <symbol id="icon-check" viewBox="0 0 24 24">
            <path d="m20 6-11 11-5-5" />
        </symbol>
        <symbol id="icon-chevron-left" viewBox="0 0 24 24">
            <path d="m15 18-6-6 6-6" />
        </symbol>
        <symbol id="icon-chevron-right" viewBox="0 0 24 24">
            <path d="m9 18 6-6-6-6" />
        </symbol>
        <symbol id="icon-arrow-left" viewBox="0 0 24 24">
            <path d="M19 12H5" />
            <path d="m11 18-6-6 6-6" />
        </symbol>
        <symbol id="icon-arrow-right" viewBox="0 0 24 24">
            <path d="M5 12h14" />
            <path d="m13 6 6 6-6 6" />
        </symbol>
        <symbol id="icon-arrow-ur" viewBox="0 0 24 24">
            <path d="M7 17 17 7" />
            <path d="M8 7h9v9" />
        </symbol>
        <symbol id="icon-close" viewBox="0 0 24 24">
            <path d="M6 6 18 18" />
            <path d="M18 6 6 18" />
        </symbol>
        <!-- Бренд-иконки (заливка, а не обводка) — для соцссылок -->
        <symbol id="icon-vk" viewBox="0 0 24 24">
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M12 0C18.627 0 24 5.373 24 12C24 18.627 18.627 24 12 24C5.373 24 0 18.627 0 12C0 5.373 5.373 0 12 0ZM11.77 16.43h.94s.28-.03.43-.19c.14-.14.13-.4.13-.4s-.02-1.26.56-1.45c.57-.18 1.31 1.22 2.09 1.75.59.4 1.04.31 1.04.31l2.09-.03s1.09-.07.57-.93c-.04-.07-.3-.64-1.57-1.82-1.32-1.23-1.15-1.03.45-3.16.97-1.3 1.36-2.09 1.24-2.43-.11-.32-.83-.24-.83-.24l-2.35.02s-.17-.02-.3.06c-.13.08-.21.26-.21.26s-.38 1.01-.89 1.87c-1.07 1.82-1.5 1.91-1.67 1.8-.4-.26-.3-1.05-.3-1.61 0-1.75.27-2.48-.52-2.67-.26-.06-.45-.1-1.12-.11-.85-.01-1.58 0-1.99.2-.27.14-.48.44-.36.46.16.02.51.1.7.35.24.33.23 1.05.23 1.05s.14 2.01-.32 2.26c-.32.17-.75-.18-1.71-1.83-.49-.85-.86-1.78-.86-1.78s-.07-.17-.2-.27c-.15-.11-.37-.14-.37-.14l-2.23.02s-.34.01-.46.16c-.11.13-.01.4-.01.4s1.75 4.09 3.73 6.15c1.82 1.89 3.88 1.76 3.88 1.76z" />
        </symbol>
        <symbol id="icon-tg" viewBox="0 0 24 24">
            <path
                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.05-.2-.06-.06-.14-.04-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z" />
        </symbol>
        <symbol id="icon-phone" viewBox="0 0 100 100">
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M50 100C77.6142 100 100 77.6142 100 50C100 22.3858 77.6142 0 50 0C22.3858 0 0 22.3858 0 50C0 77.6142 22.3858 100 50 100ZM50.0089 29H51.618C56.4915 29.0061 61.1633 30.9461 64.6073 34.3938C68.0512 37.8415 69.9856 42.5151 69.9856 47.3879V48.9968C69.9338 49.5699 69.6689 50.1027 69.2433 50.49C68.8177 50.8772 68.2623 51.0908 67.6868 51.0884H67.5029C66.8966 51.0358 66.3359 50.745 65.9437 50.2796C65.5516 49.8143 65.36 49.2124 65.4109 48.6061V47.3879C65.4109 43.7303 63.9578 40.2225 61.3711 37.6362C58.7844 35.0499 55.2761 33.597 51.618 33.597H50.3997C49.79 33.6488 49.1847 33.4563 48.7169 33.0619C48.2492 32.6675 47.9573 32.1035 47.9054 31.4939C47.8536 30.8843 48.0461 30.279 48.4406 29.8114C48.835 29.3437 49.3992 29.0518 50.0089 29ZM56.889 49.0132C56.4579 48.5821 56.2157 47.9975 56.2157 47.3879C56.2157 46.1687 55.7313 44.9994 54.869 44.1373C54.0068 43.2752 52.8374 42.7909 51.618 42.7909C51.0083 42.7909 50.4236 42.5488 49.9925 42.1177C49.5614 41.6867 49.3192 41.102 49.3192 40.4924C49.3192 39.8828 49.5614 39.2982 49.9925 38.8672C50.4236 38.4361 51.0083 38.1939 51.618 38.1939C54.0568 38.1939 56.3956 39.1626 58.1201 40.8868C59.8445 42.611 60.8133 44.9495 60.8133 47.3879C60.8133 47.9975 60.5711 48.5821 60.14 49.0132C59.7089 49.4442 59.1242 49.6864 58.5145 49.6864C57.9048 49.6864 57.3201 49.4442 56.889 49.0132ZM66.4011 69.0663L66.401 69.0846C66.3999 69.5725 66.2967 70.0547 66.0981 70.5003C65.8998 70.9451 65.611 71.3435 65.2499 71.67C64.8674 72.0182 64.4123 72.2771 63.9176 72.428C63.4516 72.5702 62.9613 72.6132 62.4782 72.5546C58.2475 72.53 53.4102 70.5344 49.1802 68.1761C44.8871 65.7827 41.0444 62.915 38.8019 60.9903L38.7681 60.9613L38.7367 60.9299C32.3303 54.5198 28.2175 46.1735 27.0362 37.186C26.9623 36.6765 27.0018 36.157 27.1519 35.6645C27.3027 35.1695 27.5615 34.7142 27.9094 34.3314C28.2397 33.9658 28.6436 33.6742 29.0944 33.4757C29.5447 33.2775 30.0316 33.1766 30.5234 33.1796H37.4967C38.299 33.1636 39.0826 33.4244 39.7156 33.9184C40.3527 34.4156 40.7979 35.1184 40.9754 35.9071L41.0038 36.0335V36.1631C41.0038 36.4901 41.0787 36.795 41.1847 37.2268C41.2275 37.4012 41.2755 37.5965 41.3256 37.8221L41.326 37.8238C41.583 38.9896 41.925 40.1351 42.3491 41.251L42.7322 42.259L38.4899 44.26L38.4846 44.2625C38.204 44.3914 37.986 44.6263 37.8784 44.9157L37.8716 44.934L37.8642 44.952C37.7476 45.236 37.7476 45.5545 37.8642 45.8385L37.9144 45.9608L37.9359 46.0912C38.0802 46.9648 38.5603 48.0981 39.4062 49.4169C40.243 50.7215 41.3964 52.1437 42.808 53.5872C45.6206 56.4634 49.3981 59.3625 53.5798 61.5387C53.8533 61.6395 54.1552 61.6343 54.4257 61.5231L54.4437 61.5157L54.462 61.5089C54.7501 61.4016 54.9842 61.1848 55.1133 60.9057L55.1148 60.9023L57.0232 56.6591L58.0397 57.03C59.1934 57.4509 60.3737 57.7947 61.5729 58.0592L61.5785 58.0605L61.5841 58.0618C62.152 58.1929 62.7727 58.3042 63.3802 58.3942L63.4231 58.4006L63.4654 58.4101C64.2537 58.5877 64.956 59.0332 65.453 59.6706C65.9429 60.2991 66.2033 61.0758 66.1916 61.8721L66.4011 69.0663Z" />
        </symbol>
        <symbol id="icon-email" viewBox="0 0 100 100">
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M50 100C77.6142 100 100 77.6142 100 50C100 22.3858 77.6142 0 50 0C22.3858 0 0 22.3858 0 50C0 77.6142 22.3858 100 50 100ZM51.8276 49.2076L74.191 33.6901C73.4347 32.6649 72.2183 32 70.8466 32H29.1534C27.8336 32 26.6576 32.6156 25.8968 33.5752L47.5881 49.172C48.8512 50.0802 50.5494 50.0945 51.8276 49.2076ZM75 63.6709V37.6286L53.4668 52.57C51.1883 54.151 48.1611 54.1256 45.9095 52.5066L25 37.4719V63.6709C25 65.9648 26.8595 67.8243 29.1534 67.8243H70.8466C73.1405 67.8243 75 65.9648 75 63.6709Z" />
        </symbol>
        <symbol id="icon-max" viewBox="0 0 48 48">
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M24 0C37.2548 0 48 10.7452 48 24C48 37.2548 37.2548 48 24 48C10.7452 48 0 37.2548 0 24C0 10.7452 10.7452 0 24 0ZM24.2314 12.5C17.8663 12.5 12.4942 17.4255 12.4941 23.9727C12.4941 26.714 13.0015 28.6059 13.4482 30.3047C13.8233 31.6836 14.1543 32.9467 14.1543 34.4414C14.3143 36.4326 17.9823 35.2685 19.1406 33.7793C20.9718 35.1031 22.0251 35.4346 24.292 35.4346C30.5586 35.4011 35.6151 30.2999 35.5938 24.0332C35.5937 17.6682 30.602 12.5 24.2314 12.5ZM24.3857 18.1592V18.165C27.5981 18.349 30.0709 21.0719 29.9453 24.2871C29.7296 27.4955 26.9854 29.9406 23.7734 29.7861C22.768 29.7055 21.8016 29.3614 20.9717 28.7881C20.4699 29.2899 19.6648 29.9402 19.3447 29.8633C18.6774 29.6868 17.8938 26.2951 18.335 23.5098C18.87 20.1452 21.2859 17.9993 24.3857 18.1592Z" />
        </symbol>
    </svg>

    <!-- ─── Nav ─────────────────────────────────────────────── -->
    <header class="nav" id="nav">
        <nav class="nav__inner">
            <a href="/index" class="logo" aria-label="Карповка — парк-отель">
                <img class="logo__img" src="/wp-content/themes/theme/assets/img/logo.png" alt="Карповка" />
            </a>
            <ul class="nav__links hidden-mobile">
                <li><a href="/prozhivanie">Проживание</a></li>
                <li><a href="/spa">СПА</a></li>
                <li><a href="/bannyy-kompleks">Баня</a></li>
                <li><a href="/kafe">Кафе</a></li>
                <li><a href="/aktivnyy-otdyh">Активный отдых</a></li>
                <li><a href="/meropriyatiya">Мероприятия</a></li>
                <li><a href="/contacts">Контакты</a></li>
                <li><a href="/review">Отзывы</a></li>
                <li><a href="/articles">Статьи</a></li>
                <li><a href="/stock">Акции</a></li>
            </ul>
            <div class="nav__tels">
                <a href="tel:+79850200203" class="nav__cta hidden-mobile">+7 (985) 020-02-03</a>
                <a href="tel:+79127813933" class="nav__cta hidden-mobile">+7 (912) 781-39-33</a>
            </div>
            <button
                class="nav__burger show-mobile"
                id="burger"
                aria-label="Меню"
                aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </nav>
        <nav class="nav__mobile" id="mobileMenu" hidden>
            <a href="/prozhivanie" class="nav__mobile-link">Проживание</a>
            <a href="/spa" class="nav__mobile-link">СПА</a>
            <a href="/bannyy-kompleks" class="nav__mobile-link">Баня</a>
            <a href="/" class="nav__mobile-link">Кафе</a>
            <a href="/" class="nav__mobile-link">Активный отдых</a>
            <a href="/" class="nav__mobile-link">Мероприятия</a>
            <a href="/" class="nav__mobile-link">Контакты</a>
            <a href="/" class="nav__mobile-link">Отзывы</a>
            <a href="/" class="nav__mobile-link">Статьи</a>
            <a href="/" class="nav__mobile-link">Акции</a>
            <a href="tel:+79850200203" class="nav__mobile-cta">+7 (985) 020-02-03</a>
            <a href="tel:+79127813933" class="nav__mobile-cta">+7 (912) 781-39-33</a>
            <div class="socials nav__mobile-socials">
                <a class="social" href="tel:+79127813933" aria-label="Телефон"><svg class="social__icon" fill="currentColor" aria-hidden="true">
                        <use href="#icon-phone"></use>
                    </svg></a>
                <a
                    class="social"
                    href="mailto:karpovka59ru@yandex.ru?subject=Здравствуйте!"
                    target="_blank"
                    rel="nofollow"
                    aria-label="Электронная почта"><svg class="social__icon" fill="currentColor" aria-hidden="true">
                        <use href="#icon-email"></use>
                    </svg></a>
                <a
                    class="social"
                    href="https://vk.com/baza_otdyha_spa_perm"
                    target="_blank"
                    rel="noopener"
                    aria-label="ВКонтакте"><svg class="social__icon" fill="currentColor" aria-hidden="true">
                        <use href="#icon-vk"></use>
                    </svg></a>
                <a
                    class="social"
                    href="https://t.me/karpovka59"
                    target="_blank"
                    rel="noopener"
                    aria-label="Telegram"><svg class="social__icon" fill="currentColor" aria-hidden="true">
                        <use href="#icon-tg"></use>
                    </svg></a>
            </div>
        </nav>
    </header>