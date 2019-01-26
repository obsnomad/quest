@extends($vkAccountId ? 'public.layouts.vk' : 'public.layouts.app')

@section('title', 'Новые квесты в Белгороде - живые квесты в реальности от «Темная комната». Квест-комнаты в Белгороде (Губкина, 17и)')

@section('header-class', 'header-schedule')

@section('header-content')
    <div class="container">
        <h1 class="text-inverse">Расписание квестов</h1>
    </div>
@endsection

@section('content')
    <div id="react-root">
        <div class="loader"></div>
    </div>
@endsection

@push('js')
    <script>
        var bookRoute = '{{ route('schedule.book') }}';
        var vkAccountId = '{{ $vkAccountId }}';
    </script>
    <script type="text/javascript" src="/js/schedule.js?v=3"></script>
@endpush