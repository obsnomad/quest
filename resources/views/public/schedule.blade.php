@extends('public.layouts.app')

@section('title', 'Новые квесты в Белгороде - живые квесты в реальности от «Темная комната». Квест-комнаты в Белгороде (Губкина, 17и)')

@section('header-class', 'header-schedule')

@section('header-content')
    <div class="container">
        <div class="text-shadow">
            <h1>Расписание квестов</h1>
        </div>
    </div>
@endsection

@section('content')
    <div class="carousel-container">
        <div class="carousel-days owl-carousel owl-theme">
            @foreach($schedule->items->first() as $item)
                <a href="#" class="carousel-days-item {{ $loop->first ? 'active' : '' }}">
                    <h3>{{ $item->weekDay }}</h3>
                    <span>{{ $item->day }}</span>
                </a>
            @endforeach
        </div>
    </div>
    <div class="container">
        <ul class="schedule-prices">
            @foreach($schedule->prices as $price)
                <li class="schedule-prices-{{ $loop->index }}">
                    {{ $price }} р.
                </li>
            @endforeach
        </ul>
    </div>
    <div class="wide-light pad">
        <div class="container">
            <h3>Квесты на 5 Августа</h3>
            г. Белгород, ул. 5 Августа, 31 (цокольный этаж, вход со двора в офис №4)
        </div>
    </div>
    <div>
        <div class="container">
            <div class="schedule-quests">
                <div class="schedule-quests-item">

                </div>
            </div>
        </div>
    </div>
    <div class="wide-light pad">
        <div class="container">
            <h3>Квесты на Губкина</h3>
            г. Белгород, ул. Губкина, 17и (цокольный этаж, правая сторона здания) - скоро открытие
        </div>
    </div>
    <div>
        <div class="container">

        </div>
    </div>
@endsection

@push('js')
    <script>
        $('.carousel-days').owlCarousel({
            loop: false,
            items: 10,
            margin: 0,
            slideBy: 1,
            nav: false,
            dots: false,
            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 5
                },
                768: {
                    items: 10
                }
            }
        });
        $('.carousel-days-item ').click(function (e) {
            e.preventDefault();
            $('.carousel-days-item ').removeClass('active');
            $(this).addClass('active');
        });
    </script>
@endpush