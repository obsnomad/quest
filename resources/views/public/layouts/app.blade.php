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
        <div class="container">
            <div class="navbar-right pull-right">
                <a href="https://vk.com/darkroomquest" target="_blank" class="fab fa-vk"></a>
{{--
                <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-instagram"></a>
--}}
            </div>
            Забронировать игру: <span>+7 (951) 762-2665</span>
        </div>
    </div>

    <div class="navbar navbar-default" data-fixable data-fixable-class="navbar-inverse" data-fixable-remove-class="navbar-default">
        <div class="container">
            <nav>
                <a href="{{ route('index') }}">
                    <img src="images/logo.svg" alt="Квест-проект «Темная комната»" class="logo" />
                </a>
                {!! $menuIcon->asUl(['class' => 'navbar-right']) !!}
                {!! $menuMain->asUl(['id' => 'navbar-navigation-menu', 'class' => 'navbar-navigation-menu navbar-right']) !!}
            </nav>
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
</body>
</html>
