@php
    /** @var \App\Models\Quest[]|\Illuminate\Support\Collection $quests */
@endphp

@extends('public.layouts.app')

@section('title', 'Новые квесты в Белгороде - живые квесты в реальности от «Темная комната». Квест-комнаты в Белгороде (Губкина, 17и)')

@section('header-class', 'header-schedule')

@section('header-content')
    <div class="container">
        <h1 class="text-inverse">Наши квесты</h1>
    </div>
@endsection

@section('content')
    <div class="container pad">
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
@endsection

@push('js')
@endpush