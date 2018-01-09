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
            <div class="navbar-right">
                <a href="#" class="fab fa-vk"></a>
                <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-instagram"></a>
            </div>
            Забронировать игру: <span>+7 (904) 080-57-47</span>
        </div>
    </div>

    <div class="navbar navbar-default" data-fixable data-fixable-class="navbar-inverse" data-fixable-remove-class="navbar-default">
        <div class="container">
            <nav>
                <img src="images/logo.svg" alt="Квест-проект «Тёмная комната»" class="logo" />
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
        &copy; 2018 {{ ($date = date('Y')) > 2018 ? "- $date" : '' }} Квест-проект «Тёмная комната»
    </div>
</footer>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@stack('js')
</body>
</html>
