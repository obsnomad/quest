@php
    /** @var \App\Models\Quest[]|\Illuminate\Support\Collection $quests */
    /** @var \App\Models\QuestLocation[]|\Illuminate\Support\Collection $locations */
@endphp

@extends('public.layouts.app')

@section('title', 'Новые квесты в Белгороде - живые квесты в реальности от «Темная комната». Квест-комнаты в Белгороде, 5 Августа 31.')

@section('header-class', 'header-main')

@section('header-content')
    <div class="container">
        <h1 class="text-inverse">Добро пожаловать<br/>в другую реальность!</h1>
    </div>
    <div class="container carousel-container">
        <div class="carousel-main owl-carousel owl-theme">
            <a href="{{ route('schedule') }}" class="carousel-main-item carousel-main-schedule">
                <h3 class="text-inverse">Расписание квестов</h3>
                <button class="carousel-main-highlight">Открыть расписание</button>
            </a>
            <a href="{{ route('quests.show', ['slug' => 'museum']) }}" class="carousel-main-item carousel-main-quests">
                <h3 class="text-inverse">Ночь в музее</h3>
                <p>Квест для любителей истории и живописи</p>
                <button>Узнать подробнее</button>
            </a>
            <a href="{{ route('gift') }}" class="carousel-main-item carousel-main-gift">
                <h3 class="text-inverse">Оригинальный подарок</h3>
                <p>Подари близким новые впечатления!</p>
                <button>Подарить игру</button>
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="container pad">
        <h2>Квесты «Темной комнаты» в Белгороде</h2>
        <div class="row">
            @foreach($quests as $quest)
                <div class="col-md-6 col-sm-6 col-xs-12 quests-item">
                    <a href="{{ route('quests.show', ['slug' => $quest->slug]) }}">
                        @if($quest->special)
                            <div class="quests-item-special {{ $quest->specialStyle ? "quests-item-special-{$quest->specialStyle}" : '' }}">
                                {{ $quest->special }}
                            </div>
                        @endif
                        <ul class="quests-item-data" style="background-image: url({{ $quest->picturePath }})">
                            <li>
                                <span class="fab fa-slack-hash"></span>
                                {{ $quest->levelReadable }}
                            </li>
                            <li>
                                <span class="far fa-clock"></span>
                                {{ $quest->timeReadable }}
                            </li>
                            <li>
                                <span class="far fa-user"></span>
                                {{ $quest->playersReadable }}
                            </li>
                            <li>
                                <span class="far fa-money-bill-alt"></span>
                                {{ $quest->priceReadable }}
                            </li>
                        </ul>
                        <h3>{{ $quest->name }}</h3>
                        @if($quest->working)
                            <button>Забронировать</button>
                        @else
                            <p>Скоро открытие!</p>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <div class="wide-fix text-inverse pad" style="background-image: url(/images/bg-fixed-main.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-8 offset-md-2">
                    <h3 class="h3-bigger text-inverse">Квест — это захватывающее приключение, полное новых эмоций, и
                        лучший способ
                        <br/><span class="higlight-text">интересно провести время!</span></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-4 offset-md-2">
                    Доверьте нам свои эмоции хотя бы на один час, и о полученных впечатлениях вы будете вспоминать даже
                    годы
                    спустя. Каждый наш квест — это настоящее приключение, но происходит оно не на киноэкране и не в
                    компьютерной игре. Главный герой здесь — вы, вокруг вас разворачиваются невероятные события, и финал
                    этой истории — непредсказуем.
                </div>
                <div class="col-xs-12 col-md-4">
                    Чего бы вам хотелось в данный момент? Удивиться, посмеяться, испугаться? У нас найдутся квесты на
                    любой
                    вкус! И каждый из них может стать уникальным подарком, праздником для всей семьи или увлекательным
                    развлечением для корпоратива.
                </div>
            </div>
            <div class="text-center buttons-pad">
                <a href="{{ route('schedule') }}" class="btn btn-lg btn-warning">
                    <span class="far fa-calendar-alt"></span>
                    Перейти в расписание
                </a>
            </div>
        </div>
    </div>
    <div class="pad">
        <div class="container">
            <h2>Контакты</h2>
            <div class="quests-address">
                <div class="fa fa-map-marker-alt"></div>
                <div>г. Белгород, ул. 5 Августа, 31</div>
                <div>цокольный этаж, вход со двора в офис №4, между подъездами 1 и 2</div>
            </div>
            <div class="quests-address">
                <div class="fa fa-phone"></div>
                <div><a href="tel:+{{ preg_replace('/\D+/', '', config('app.phone')) }}"
                        target="_blank">{{ config('app.phone') }}</a></div>
            </div>
            <div class="quests-address">
                <div class="fab fa-vk"></div>
                <div><a href="{{ config('app.vk') }}" target="_blank">{{ config('app.vk') }}</a></div>
            </div>
        </div>
    </div>
    <div id="map"></div>
@endsection

@push('js')
    <script type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
    <script>
        $('.carousel-main').owlCarousel({
            loop: true,
            items: 3,
            margin: 15,
            slideBy: 1,
            nav: false,
            dots: false,
            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                960: {
                    items: 3
                }
            }
        });

        let coords = [50.583106, 36.576454];
        ymaps.ready(function () {
            let map = new ymaps.Map('map', {
                center: coords,
                zoom: 17,
                controls: ['zoomControl', 'geolocationControl']
            });
            let place = new ymaps.Placemark(coords, {
                hintContent: 'Тёмная комната'
            }, {
                preset: 'islands#redIcon'
            });
            map.behaviors.disable(['scrollZoom', 'drag']);
            map.geoObjects.add(place);
        });
    </script>
@endpush