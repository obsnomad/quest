@extends('public.layouts.app')

@section('content')
    <div class="main-banner">
        <div class="container">
            <h2>Добро пожаловать в Тёмную комнату!</h2>
        </div>
    </div>
    <div class="container">
        <div class="site-panel">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <img src="images/main-pic-1.jpg" alt="">
                </div>
                <div class="col-sm-6 col-xs-12">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </div>
            </div>
        </div>
        <div class="site-panel">
            @foreach($schedule as $day_number => $day)
                <div>{{ $day_number }}</div>
                @foreach($day as $price_value => $price)
                    <div>{{ $price_value }} руб.</div>
                    @foreach($price as $time)
                        <button>{{ $time->time }}</button>
                    @endforeach
                @endforeach
            @endforeach
        </div>
    </div>
@endsection
