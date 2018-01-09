@extends('public.layouts.app')

@section('title', 'Новые квесты в Белгороде - живые квесты в реальности от «Тёмная комната». Квест-комнаты в Белгороде (Губкина, 17и)')

@section('header-class', 'header-main')

@section('header-content')
    <div class="container">
        <div class="text-shadow">
            <h1>Добро пожаловать<br/>в другую реальность!</h1>
            <p>
                Новые квесты в Белгороде.<br/>
                «Тёмная комната» — полное погружение<br/>
                в мир приключений!
            </p>
        </div>
    </div>
    <div class="container carousel-container">
        <div class="carousel-main owl-carousel owl-theme">
            <a href="#" class="carousel-main-item carousel-main-schedule">
                <h3>Расписание квестов</h3>
                <button class="carousel-main-highlight">Открыть расписание</button>
            </a>
            <a href="#" class="carousel-main-item carousel-main-quests">
                <h3>Лечебница</h3>
                <p>Несложная игра для поклонников квестов</p>
                <button>Узнать подробнее</button>
            </a>
            <a href="#" class="carousel-main-item carousel-main-gift">
                <h3>Оригинальный подарок</h3>
                <p>Подари близким новые впечатления!</p>
                <button>Подарить игру</button>
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="container pad">
        <h2>Квесты «Тёмной комнаты» в Белгороде</h2>
        <div class="row">
            @foreach($quests as $quest)
                <div class="col-md-4 col-sm-6 col-xs-12 quests-item">
                    <a href="#">
                        @if(@$quest->special)
                            <div class="quests-item-special {{ @$quest->specialStyle ? "quests-item-special-{$quest->specialStyle}" : '' }}">
                                {{ $quest->special }}
                            </div>
                        @endif
                        <ul class="quests-item-data" style="background-image: url({{ $quest->image }})">
                            <li>
                                <span class="fab fa-slack-hash"></span>
                                {{ $quest->level }}
                            </li>
                            <li>
                                <span class="far fa-clock"></span>
                                {{ $quest->time }}
                            </li>
                            <li>
                                <span class="far fa-user"></span>
                                {{ $quest->players }}
                            </li>
                            <li>
                                <span class="far fa-money-bill-alt"></span>
                                {{ $quest->money }}
                            </li>
                        </ul>
                        <h3>{{ $quest->name }}</h3>
                        <button>Забронировать</button>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <div class="wide-fix pad" style="background-image: url(/images/bg-fixed-main.jpg);">
        <div class="container">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <h3 class="h3-bigger">Квест — это захватывающее приключение, полное новых эмоций, и лучший способ
                    <br/><span class="higlight-text">интересно провести время!</span></h3>
            </div>
            <div class="clearfix"></div>
            <div class="col-xs-12 col-md-4 col-md-offset-2">
                Доверьте нам свои эмоции хотя бы на один час, и о полученных впечатлениях вы будете вспоминать даже годы
                спустя. Каждый наш квест — это настоящее приключение, но происходит оно не на киноэкране и не в
                компьютерной игре. Главный герой здесь — вы, вокруг вас разворачиваются невероятные события, и финал
                этой истории — непредсказуем.
            </div>
            <div class="col-xs-12 col-md-4">
                Чего бы вам хотелось в данный момент? Удивиться, посмеяться, испугаться? У нас найдутся квесты на любой
                вкус! И каждый из них может стать уникальным подарком, праздником для всей семьи или увлекательным
                развлечением для корпоратива.
            </div>
            <div class="clearfix"></div>
            <div class="text-center buttons-pad">
                <a href="#" class="btn btn-lg btn-warning"><span class="far fa-calendar-alt"></span> Перейти в
                    расписание</a>
            </div>
        </div>
    </div>
    <div class="container pad">
        <h2>Контакты</h2>
        <div>
            Квест-проект «Тёмная комната»<br />
            Адрес: г. Белгород, ул. Губника, 17и (цокольный этаж, правая сторона здания)<br />
            Бронирование по телефону: 8 (904) 080-57-47<br />
            https://vk.com/darkroomquest
        </div>
    </div>
    <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3Abe0eb2697b09f48e6a847125893ca1e5f3499b3b106cd62e2702c1d30092886e&amp;source=constructor"
            width="100%" height="472" frameborder="0"></iframe>
@endsection

@push('js')
    <script>
        $(function () {
            $('.carousel-main').owlCarousel({
                loop: true,
                items: 3,
                margin: 15,
                slideBy: 1,
                nav: true,
                dots: false,
                navText: ['&#xf104;', '&#xf105;'],
                responsive: {
                    0: {
                        items: 1
                    },
                    480: {
                        items: 2
                    },
                    768: {
                        items: 3
                    }
                }
            });
        });
    </script>
@endpush