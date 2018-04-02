@extends('public.layouts.app')

@section('title', 'Новые квесты в Белгороде - живые квесты в реальности от «Темная комната». Квест-комнаты в Белгороде (Губкина, 17и)')

@section('header-class', 'header-schedule')

@section('header-content')
    <div class="container">
        <div class="text-shadow">
            <h1>Подарочные карты на любые квесты!</h1>
        </div>
    </div>
@endsection

@section('content')
    <div id="react-root">
        <div class="loader"></div>
    </div>
@endsection

@push('js')
@endpush