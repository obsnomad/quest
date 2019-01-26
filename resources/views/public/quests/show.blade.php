@php
    /** @var \App\Models\Quest $quest */
    /** @var \Illuminate\Support\Collection $pictures */
@endphp

@extends('public.layouts.app')

@section('title', 'Новые квесты в Белгороде - живые квесты в реальности от «Темная комната». Квест-комнаты в Белгороде (Губкина, 17и)')

@section('header-class', 'header-quest')

@section('header-content')
    @if($pictures->count())
        <div class="carousel-quest owl-carousel owl-theme">
            @foreach($pictures as $picture)
                <div class="carousel-quest-item" style="background-image: url({{ $picture }})"></div>
            @endforeach
        </div>
    @else
        <div class="container">
            <h1 class="text-inverse">Квест «{{ $quest->name }}»</h1>
        </div>
    @endif
@endsection

@section('content')
    <div class="container pad quests">
        @if($pictures->count())
            <h1>Квест «{{ $quest->name }}»</h1>
        @endif
        @if($quest->summary)
            <h2 class="quests-summary">{{ $quest->summary }}</h2>
        @endif
        <div class="quests-features">
            <div class="quests-features-item">
                <img src="/images/icon-stars.svg" alt="">
                {{ $quest->levelFullReadable }}
            </div>
            <div class="quests-features-item">
                <img src="/images/icon-clock.svg" alt="">
                Квест длится {{ $quest->timeReadable }}
            </div>
            <div class="quests-features-item">
                <img src="/images/icon-players.svg" alt="">
                {{ $quest->playersReadable }}
            </div>
        </div>
        <div class="pad">
            <h3 class="quests-title">
                Легенда квеста
            </h3>
            <div class="quests-description">
                {!! $quest->descriptionHtml !!}
            </div>
        </div>
        <div id="react-root">
            <div class="loader"></div>
        </div>
        <div class="pad">
            <div class="row">
                <div class="col-md-5 col-xs-12">
                    <h3 class="quests-title">
                        Адрес квеста
                    </h3>
                    <div class="quests-address">
                        <div class="fa fa-map-marker-alt"></div>
                        <div>{{ $quest->location->address }}</div>
                        <div>{{ $quest->location->description }}</div>
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
                <div class="col-md-7 col-xs-12">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
    <script>
        $('.carousel-quest').owlCarousel({
            loop: true,
            items: 1,
            margin: 0,
            slideBy: 1,
            nav: true,
            dots: true,
            navText: ['<span class="fa fa-chevron-left"></span>', '<span class="fa fa-chevron-right"></span>'],
        });

        var map,
            place,
            coords = @json([$quest->location->lat, $quest->location->lon]);
        ymaps.ready(function () {
            map = new ymaps.Map('map', {
                center: coords,
                zoom: 17,
                controls: ['zoomControl', 'geolocationControl']
            });
            place = new ymaps.Placemark(coords, {}, {
                preset: 'islands#redIcon'
            });
            map.behaviors.disable('scrollZoom');
            map.geoObjects.add(place);
        });

        var scheduleUrl = '{{ $quest->scheduleUrl }}';
        var bookRoute = '{{ route('schedule.book') }}';
    </script>
    <script type="text/javascript" src="/js/schedule-single.js?v=3"></script>
@endpush