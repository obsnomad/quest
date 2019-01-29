@extends('public.layouts.app')

@section('title', 'Новые квесты в Белгороде - живые квесты в реальности от «Темная комната». Квест-комнаты в Белгороде (Губкина, 17и)')

@section('header-class', 'header-schedule')

@section('header-content')
    <div class="container">
        <h1 class="text-inverse">Подарочные карты на любую игру!</h1>
    </div>
@endsection

@section('content')
    <div class="container pad">
        <h2>Идеальный подарок - это заряд положительных эмоций!</h2>
        <p>
            Именно поэтому у нас есть подарочные сертификаты на квесты.
        </p>
        <p>
            Вам не придется долго думать, что же подарить, а получатель подарка сам решит,
            когда и на какой квест пойти. Всего одна карта откроет двери в другую реальность.
            Спешите дарить впечатления!
        </p>
    </div>
    <div class="wide-fix text-inverse pad" style="background-image: url(/images/bg-certificate.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-md-4 certificate-style">
                    <p>
                        Подарочным сертификатом могут воспользоваться ваши друзья или даже вы сами (мы допускаем и такой
                        вариант).
                    </p>
                    <p>
                        Сертификат действует на абсолютно любой доступный в расписании сеанс в независимости от его
                        стоимости.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="container pad">
        <div class="row">
            <div class="offset-md-3 col-md-6 text-center">
                <h2>Приобрести сертификат</h2>
                <p>
                    Вы можете позвонить нам или оставить свои контактные данные, чтобы мы могли связаться
                    с Вами и вместе выбрать удобный способ покупки.
                </p>
                <div id="react-root">
                    <div class="loader"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        var giftRoute = '{{ route('gift.send') }}';
    </script>
    <script type="text/javascript" src="/js/certificate.js?v=4"></script>
@endpush