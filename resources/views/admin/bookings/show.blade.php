@php /**
     * @var \App\Models\Game|\Illuminate\Support\Collection $game
     * @var \Illuminate\Support\ViewErrorBag $errors
     */ @endphp

@extends('admin.layouts.app')

@section('title', "Игры - {$game->name}")

@section('content_header')
    <h1>{{ $game->name }}</h1>
@stop

@section('content')
    @if($errors->count())
        <div class="alert alert-danger">
            @foreach($errors->all() as $message)
                <p>{{ $message }}</p>
            @endforeach
        </div>
    @endif
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li {!! !$adminValues->gameActiveTab ? 'class="active"' : '' !!}>
                <a href="#tab_1" data-toggle="tab">
                    <h4>Основная информация</h4>
                </a>
            </li>
            <li {!! $adminValues->gameActiveTab == 1 ? 'class="active"' : '' !!}>
                <a href="#tab_2" data-toggle="tab">
                    <h4>Таблица результатов</h4>
                </a>
            </li>
            <li {!! $adminValues->gameActiveTab == 2 ? 'class="active"' : '' !!}>
                <a href="#tab_3" data-toggle="tab">
                    <h4>Сценарий</h4>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane {{ !$adminValues->gameActiveTab ? 'active' : '' }}" id="tab_1">
                {{ Form::open([
                    'url' => $game->id ? route('admin.bookings.update', ['id' => $game->id]) : route('admin.bookings.store'),
                    'class' => 'form-horizontal row',
                    'method' => $game->id ? 'patch' : 'post',
                    'files' => true,
                ]) }}
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="col-sm-4 control-label">Название</label>
                        <div class="col-sm-8">
                            {{ Form::text('name', old('name', $game->id ? $game->name : ''), ['id' => 'name', 'class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type_id" class="col-sm-4 control-label">Формат игры</label>
                        <div class="col-sm-8">
                            {{ Form::select('type_id', $types, old('type_id', $game->typeId), ['id' => 'type_id', 'class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="start_at" class="col-sm-4 control-label">Дата проведения</label>
                        <div class="col-sm-8">
                            <div class="input-group date datetimepicker">
                                {{ Form::text('start_at', old('start_at', $game->startAt ? $game->startAt->format('d.m.Y H:i') : ''), ['id' => 'start_at', 'class' => 'form-control']) }}
                                <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="location_id" class="col-sm-4 control-label">Место проведения</label>
                        <div class="col-sm-8">
                            {{ Form::select('location_id', $locations, old('location_id', $game->locationId), ['id' => 'location_id', 'class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="image" class="col-sm-4 control-label">Изображение</label>
                        <div class="col-sm-8">
                            {{ Form::file('image', ['id' => 'image', 'class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-8 col-sm-offset-4">
                            {{ Form::checkbox('image_remove') }}
                            Удалить изображение
                        </label>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            @if($game->image)
                                <img src="{{ \Storage::url($game->imagePath) }}" alt="" style="max-width: 100%;">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-sm-12 text-right">
                            {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
            <div class="tab-pane {{ $adminValues->gameActiveTab == 1 ? 'active' : '' }}" id="tab_2">
{{--
                <div class="form-group">
                    <div class="btn-group pull-right">
                        <button class="btn {{ $adminValues->gameTableMode == 'show' ? 'btn-primary' : 'btn-default' }} table-toggle"
                                data-mode="show">Просмотр
                        </button>
                        <button class="btn {{ $adminValues->gameTableMode == 'edit' ? 'btn-primary' : 'btn-default' }} table-toggle"
                                data-mode="edit">Редактирование
                        </button>
                        <button class="btn {{ $adminValues->gameTableMode == 'picall' ? 'btn-primary' : 'btn-default' }} table-toggle"
                                data-mode="picall">Изображение
                        </button>
                        <button class="btn {{ $adminValues->gameTableMode == 'piclast' ? 'btn-primary' : 'btn-default' }} table-toggle"
                                data-mode="piclast">Изображение (последние туры)
                        </button>
                    </div>
                    <div>
                        <button class="btn btn-success" id="table-add">Добавить команду</button>
                    </div>
                </div>
                <div class="table-responsive table-toggle-target"
                     data-mode="show" {!! $adminValues->gameTableMode == 'show' ? '' : 'style="display: none;"' !!}>
                    <table class="table table-bordered table-striped table-hover no-margin">
                        <thead>
                        <tr>
                            <th width="50">Место</th>
                            <th>Команда</th>
                            <th>Заявлено игроков</th>
                            <th>Было игроков</th>
                            <th>Оплачено</th>
                            @foreach($game->type->rounds as $round)
                                @if(!is_array($round))
                                    <th class="text-center" width="50">{!! $round !!}</th>
                                @else
                                    @foreach($round['subrounds'] as $subround)
                                        <th class="text-center" width="50">{!! $subround !!}</th>
                                    @endforeach
                                    <th class="text-center" width="50">{!! $round['name'] !!}</th>
                                @endif
                            @endforeach
                            <th class="text-center" width="50">Сумма</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($game->teams as $team)
                            <tr>
                                <td class="text-right">{{ $team->position ?: '' }}</td>
                                <td>
                                    <a href="{{ route('admin.teams.show', ['id' => $team->teamId]) }}">
                                        {{ $team->teamName ?: $team->team->name }}
                                    </a>
                                </td>
                                <td>{{ $team->amountClaimed ?: 'н/д' }}</td>
                                <td>{{ $team->position ? ($team->amount ?: 'н/д') : '' }}</td>
                                <td>{{ $team->position ? ($team->money ?: 'н/д') : '' }}</td>
                                @php $num = 0; @endphp
                                @foreach($game->type->rounds as $round)
                                    @if(!is_array($round))
                                        <td class="text-center">{{ @$team->results[$num++] }}</td>
                                    @else
                                        @php $sum = 0; @endphp
                                        @foreach($round['subrounds'] as $subround)
                                            @php $sum += @$team->results[$num]; @endphp
                                            <td class="text-center">{{ @$team->results[$num++] }}</td>
                                        @endforeach
                                        <td class="text-center">{{ $team->position ? $sum : '' }}</td>
                                    @endif
                                @endforeach
                                <td class="text-center">{{ $team->position ? $team->sum : '' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive table-toggle-target"
                     data-mode="edit" {!! $adminValues->gameTableMode == 'edit' ? '' : 'style="display: none;"' !!}>
                    <table class="table table-bordered table-striped no-margin">
                        <thead>
                        <tr>
                            <th></th>
                            <th width="50">Место</th>
                            <th>Команда</th>
                            <th>Название</th>
                            <th>Заявлено игроков</th>
                            <th>Было игроков</th>
                            <th>Оплачено</th>
                            @foreach($game->type->rounds as $round)
                                @if(!is_array($round))
                                    <th class="text-center" width="50">{!! $round !!}</th>
                                @else
                                    @foreach($round['subrounds'] as $subround)
                                        <th class="text-center" width="50">{!! $subround !!}</th>
                                    @endforeach
                                    <th class="text-center" width="50">{!! $round['name'] !!}</th>
                                @endif
                            @endforeach
                            <th class="text-center" width="50">Сумма</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($game->teams->sortBy('team_name') as $team)
                            <tr>
                                <td class="text-center">
                                    <a href="#" class="fa fa-trash table-delete" data-id="{{ $team->teamId }}"></a>
                                </td>
                                <td class="text-right">{{ $team->position ?: '' }}</td>
                                <td>
                                    <a href="{{ route('admin.teams.show', ['id' => $team->teamId]) }}">
                                        {{ $team->team->name }}
                                    </a>
                                </td>
                                <td class="table-edit-field">
                                    {{ Form::text("teams[{$team->id}][team_name]", $team->teamName && $team->team->name != $team->teamName ? $team->team->name : '', ['class' => 'form-control', 'style' => 'min-width: 150px;']) }}
                                </td>
                                <td class="table-edit-field">
                                    {{ Form::text("teams[{$team->id}][amount_claimed]", $team->amountClaimed ?: '', ['class' => 'form-control', 'style' => 'width: 75px;']) }}
                                </td>
                                <td class="table-edit-field">
                                    {{ Form::text("teams[{$team->id}][amount]", $team->amount ?: '', ['class' => 'form-control', 'style' => 'width: 60px;']) }}
                                </td>
                                <td class="table-edit-field">
                                    {{ Form::text("teams[{$team->id}][money]", $team->money ?: '', ['class' => 'form-control', 'style' => 'width: 75px;']) }}
                                </td>
                                @php $num = 0; @endphp
                                @foreach($game->type->rounds as $i => $round)
                                    @if(!is_array($round))
                                        <td class="table-edit-field">
                                            {{ Form::text("teams[{$team->id}][results][$num]", @$team->results[$num++], ['class' => 'form-control', 'style' => 'width: 40px;', 'data-group' => $i]) }}
                                        </td>
                                    @else
                                        @php $sum = 0; @endphp
                                        @foreach($round['subrounds'] as $subround)
                                            @php $sum += @$team->results[$num]; @endphp
                                            <td class="table-edit-field">
                                                {{ Form::text("teams[{$team->id}][results][$num]", @$team->results[$num++], ['class' => 'form-control', 'style' => 'width: 40px;', 'data-group' => $i]) }}
                                            </td>
                                        @endforeach
                                        <td class="text-center vertical"
                                            data-group-sum="{{ $i }}">{{ $team->position ? $sum : '' }}</td>
                                    @endif
                                @endforeach
                                <td class="text-center" data-sum>{{ $team->position ? $team->sum : '' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
--}}
            </div>
            <div class="tab-pane {{ $adminValues->gameActiveTab == 2 ? 'active' : '' }}" id="tab_3">
            </div>
        </div>
    </div>

    <div id="table-add-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Добавить команду</h4>
                </div>
                <div class="modal-body">
                    <ul class="row">
                        @foreach($teams->filter() as $teamId => $teamName)
                            <li class="col-md-4 col-sm-6 col-xs-12">
                                <a href="#" data-id="{{ $teamId }}">{{ $teamName }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop

@push('css')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
          type="text/css"/>
    <style>
        .table-edit-field {
            padding: 1px !important;
        }

        #table-add-modal ul {
            padding: 0;
        }

        #table-add-modal li {
            list-style: none;
            margin: 4px 0;
        }
    </style>
@endpush

@section('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/locale/ru.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="{{ url('vendor/bootbox.min.js') }}"></script>
    <script>
        $('.datetimepicker').datetimepicker({
            format: 'DD.MM.YYYY HH:mm',
            locale: 'ru'
        });

        // Данные игры
        let game = @json($game);

        let linkTemplate = '{{ route('admin.teams.show', ['id' => '000']) }}';
    </script>
    <script type="text/javascript" src="{{ url('js/admin.table.js') }}"></script>
@endsection