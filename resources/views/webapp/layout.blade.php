<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <title>{{ $title ?? 'lenses' }}</title>

    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <script src="https://telegram.org/js/telegram-web-app.js?1"></script>

    <style>
        @font-face {
            font-family: "Gotham";
            src: url("/fonts/gotham/gotham_book.otf") format("opentype");
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: "Gotham";
            src: url("/fonts/gotham/gotham_bookitalic.otf") format("opentype");
            font-weight: 400;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: "Gotham";
            src: url("/fonts/gotham/gotham_medium.otf") format("opentype");
            font-weight: 500;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: "Gotham";
            src: url("/fonts/gotham/gotham_mediumitalic.otf") format("opentype");
            font-weight: 500;
            font-style: italic;
            font-display: swap;
        }

        @font-face {
            font-family: "Gotham";
            src: url("/fonts/gotham/gotham_bold.otf") format("opentype");
            font-weight: 700;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: "Gotham";
            src: url("/fonts/gotham/gotham_bolditalic.otf") format("opentype");
            font-weight: 700;
            font-style: italic;
            font-display: swap;
        }

        /* убрать скругления у всех элементов */
        * {
            border-radius: 0 !important;
        }

        body, .wrapper, .content {
            max-width: 100%;
            margin: 0;
            width: 100%;
            box-sizing: border-box;
            font-family: "Gotham", sans-serif;
        }

        .product__meta {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        ::selection {
            background: #741C28;
            color: #fff;
        }

        .alert-box {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background: #741C28;
            color: #fff;
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 14px;
            display: none;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .alert-box.show {
            display: block;
            opacity: 1;
        }

        .cart-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            background: #ff3b30;
            color: #fff;
            font-size: 11px;
            padding: 2px 5px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
            line-height: 14px;
            font-weight: bold;
            transform: scale(1);
            transition: 0.25s ease;
        }

        .cart-badge.bump {
            transform: scale(1.3);
        }

        .i-favs.active {
            background-image: url('/img/icon-favs-active.svg') !important;
        }

        .badge-container {
            position: relative;
        }

        #bottom-cart-badge {
            position: absolute;
            top: 17px;
            right: 14px;
            background: #fff;
            color: #ff3b30;
            transform: scale(0.9);
        }
         .header__logo{
            max-width: 120px;
        }

        .slider__btn {
            font-size: 20px;
        }

        .category-tree__chevron {
           
            font-size: 22px;
        }
    </style>

    @yield('head')
</head>

<body>

<div class="wrapper">
    @yield('content')
    @yield('nav')
</div>

{{-- UNIVERSAL CHAT_ID SCRIPT --}}
<script>
    const tg = window.Telegram.WebApp;
    const userId = tg?.initDataUnsafe?.user?.id;
    // const userId = '69621116';

    // if (!tg || !tg.initDataUnsafe || !tg.initData) {
    //     const botUsername = "lenses_bot";
    //     const webAppUrl = encodeURIComponent(window.location.href);
    //     window.location.href = `https://t.me/${botUsername}?start=webapp&startapp=${webAppUrl}`;
    // }

    if (userId) {
        const selectors = [
            'a[href*="webapp"]',
            '#menu-home',
            '#menu-favs',
            '#menu-profile',
            '#menu-profile_header',
            '#bottom-cart-btn',
            '#cart-btn'
        ];

        selectors.forEach(sel => {
            document.querySelectorAll(sel).forEach(link => {
                if (!link.href) return;
                const base = link.href.split("?")[0];
                const params = new URLSearchParams(link.href.split("?")[1]);
                params.set("chat_id", userId);
                link.href = base + "?" + params.toString();
            });
        });
    }
</script>

<script>
    if (userId) {
        sessionStorage.setItem('chat_id', userId);
    }

    const nativeFetch = window.fetch;
    window.fetch = function (url, options = {}) {
        options.headers = options.headers || {};
        const cid = sessionStorage.getItem('chat_id');
        if (cid) {
            options.headers['X-CHAT-ID'] = cid;
        }
        return nativeFetch(url, options);
    };
</script>

<script>
    fetch('/api/webapp/check-user')
        .then(r => r.json())
        .then(res => {
            if (!res.active) {
                document.body.innerHTML = `
                <div style="
                    height:100vh;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    background:#0f172a;
                    color:white;
                    font-family:system-ui;
                    text-align:center;">
                    <div>
                        <h2>{{ __('webapp.access_denied_title') }}</h2>
                        <p>{{ __('webapp.access_denied_text') }} @exampleAdmin</p>
                        <button onclick="Telegram.WebApp.close()"
                            style="padding:12px 18px;background:#ef4444;
                            border:none;border-radius:10px;color:white;">
                            {{ __('webapp.close') }}
                </button>
            </div>
        </div>
`;
            }
        });
</script>

@yield('scripts')

</body>
</html>
