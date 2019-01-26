<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#000000">
    <meta name="msapplication-TileColor" content="#000000">
    <meta name="theme-color" content="#000000">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="/css/fontawesome-all.min.css" rel="stylesheet">
@stack('css')

<!-- Scripts -->
    <script>
        window.Laravel = @json([
            'csrfToken' => csrf_token(),
        ]);
    </script>
</head>
<body>

<header class="@yield('header-class')">
    <div class="header-additional">
        <div class="container d-flex justify-content-between">
            <div>
                Забронировать игру: <span>{{ config('app.phone') }}</span>
            </div>
            <div>
                <a href="{{ config('app.vk') }}" target="_blank" class="fab fa-vk"></a>
            </div>
        </div>
    </div>

    <div class="navbar-fixable" data-fixable>
        <div class="container position-relative">
            <div class="row d-block">
                <nav class="navbar navbar-expand-md navbar-light">
                    <a href="{{ route('index') }}" class="navbar-brand">
                        <img src="/images/logo.svg" alt="Квест-проект «Темная комната»" class="logo"/>
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbar-navigation-menu" aria-controls="navbar-navigation-menu" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="fas fa-bars"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbar-navigation-menu">
                        {!! $menuMain->asUl(['class' => 'navbar-nav ml-auto']) !!}
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <div class="header-content">
        @yield('header-content')
    </div>
</header>

<div class="body-container">
    @yield('content')
</div>

<footer>
    <div class="container">
        &copy; 2018 {{ ($date = date('Y')) > 2018 ? "- $date" : '' }} Квест-проект «Темная комната»
    </div>
</footer>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@stack('js')
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function () {
            try {
                w.yaCounter48634697 = new Ya.Metrika({
                    id: 48634697,
                    clickmap: true,
                    trackLinks: true,
                    accurateTrackBounce: true,
                    webvisor: true
                });
            } catch (e) {
            }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () {
                n.parentNode.insertBefore(s, n);
            };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else {
            f();
        }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript>
    <div><img src="https://mc.yandex.ru/watch/48634697" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript>
<!-- /Yandex.Metrika counter -->
</body>
</html>
