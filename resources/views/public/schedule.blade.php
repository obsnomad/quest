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
    <div class="schedule-timeline" data-fixable data-fixable-class="schedule-fixed">
        <div class="carousel-container">
            <div class="carousel-days owl-carousel owl-theme">
                @foreach($schedule->items->first() as $day => $item)
                    <a href="#" class="carousel-days-item {{ $loop->first ? 'active' : '' }}"
                       data-day="{{ $day }}">
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
    </div>
    @foreach($quests as $questGroup)
        <div class="wide-light pad">
            <div class="container">
                <h3>{{ $questGroup->first()->location->name }}</h3>
                {{ $questGroup->first()->location->address }}
            </div>
        </div>
        <div>
            <div class="container">
                <div class="schedule-quests">
                    @foreach($questGroup as $quest)
                        @php
                            /** @var \App\Models\Quest $quest */
                        @endphp
                        <div class="schedule-quests-item" data-title="{{ $quest->name }}">
                            <div class="schedule-quests-item-pic">
                                <img src="{{ $quest->thumbPath }}" alt="">
                            </div>
                            <div class="schedule-quests-item-title">
                                <a href="{{ route('quests.show', ['slug' => $quest->slug]) }}">
                                    {{ $quest->name }}
                                </a>
                                <div>
                                    {{ $quest->priceReadable }}
                                </div>
                            </div>
                            <div id="schedule-{{ $quest->id }}" class="schedule-quests-item-schedule">
                                @if($quest->working)
                                    @foreach(@$schedule->items[$quest->id]->first()->items as $time => $data)
                                        <div {!! $data->booked ? '' : 'class="schedule-quests-item-price schedule-quests-item-price-' . array_search($data->price, $schedule->prices) . '"' !!}
                                             data-time-send="{{ $schedule->items[$quest->id]->keys()->first() . ' ' . $time }}"
                                             data-time="{{ $time }}"
                                             data-day="{{ $schedule->items[$quest->id]->first()->day }}"
                                             data-price="{{ $data->price }}">
                                            {{ $time }}
                                        </div>
                                    @endforeach
                                @else
                                    <span>Скоро открытие</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <div id="booking-popup" class="popup mfp-hide">

        <h4>Запись на квест<br />«<span></span>»</h4>
        <table class="table table-bordered">
            <tr>
                <td>
                    Дата
                    <div></div>
                </td>
                <td>
                    Время
                    <div></div>
                </td>
                <td>
                    Цена
                    <div></div>
                </td>
            </tr>
        </table>
        <form action="">
            <div class="form-group">
                <label for="booking-phone">Ваш номер телефона. Мы позвоним Вам, чтобы подтвердить бронь.</label>
                <input type="text" name="phone" id="booking-phone" class="form-control" />
            </div>
            <div>
                <input type="hidden" name="time" value="" />
                <input type="submit" class="btn btn-lg btn-block btn-warning" value="Записаться" />
            </div>
        </form>
    </div>

    <div id="booking-result-popup" class="popup mfp-hide"></div>
@endsection

@push('js')
    <script>
        var schedule = JSON.parse('@json($schedule)');
    </script>
@endpush