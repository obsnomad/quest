@php /**
     * @var \App\Models\Booking|\Illuminate\Support\Collection $booking
     * @var \Illuminate\Support\ViewErrorBag $errors
     */ @endphp

@extends('admin.layouts.app')

@section('title', "Брони - {$booking->quest->name} - {$booking->dateFormatted}")

@section('content_header')
    <h1>{{ $booking->quest->name . ' - ' . $booking->dateFormatted }}</h1>
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
            <li {!! !$adminValues->bookingActiveTab ? 'class="active"' : '' !!}>
                <a href="#tab_1" data-toggle="tab">
                    <h4>Данные брони</h4>
                </a>
            </li>
            @permissions('booking_history')
            <li {!! $adminValues->bookingActiveTab == 1 ? 'class="active"' : '' !!}>
                <a href="#tab_2" data-toggle="tab">
                    <h4>История изменений</h4>
                </a>
            </li>
            @endpermissions
        </ul>
        <div class="tab-content">
            <div class="tab-pane {{ !$adminValues->bookingActiveTab ? 'active' : '' }}" id="tab_1">
                {{ Form::open([
                    'url' => $booking->id ? route('admin.bookings.update', ['id' => $booking->id]) : route('admin.bookings.store'),
                    'class' => 'form-horizontal row',
                    'method' => $booking->id ? 'patch' : 'post',
                ]) }}
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="quest_id" class="col-sm-4 control-label">Квест</label>
                        <div class="col-sm-8">
                            {{ Form::select('quest_id', $quests, old('quest_id', $booking->questId), ['id' => 'quest_id', 'class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status_id" class="col-sm-4 control-label">Статус</label>
                        <div class="col-sm-8">
                            {{ Form::select('status_id', $statuses, old('status_id', $booking->statusId), ['id' => 'status_id', 'class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="client" class="col-sm-4 control-label">Клиент</label>
                        <div class="col-sm-8">
                            {{ Form::text('client', old('client', $booking->client->fullName), ['id' => 'client', 'class' => 'form-control', 'data-target' => '#client_id', 'data-hide' => '#client_new']) }}
                            {{ Form::hidden('client_id', old('client_id', $booking->clientId), ['id' => 'client_id']) }}
                        </div>
                    </div>
                    <div id="client_new" class="panel panel-primary" style="display: {{ old('client_id', $booking->clientId) ? 'none' : 'block' }}">
                        <div class="panel-heading">
                            <div class="panel-title">
                                Новый клиент
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="first_name" class="col-sm-4 control-label">Имя</label>
                                <div class="col-sm-8">
                                    {{ Form::text('first_name', old('first_name'), ['id' => 'first_name', 'class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="last_name" class="col-sm-4 control-label">Фамилия</label>
                                <div class="col-sm-8">
                                    {{ Form::text('last_name', old('last_name'), ['id' => 'last_name', 'class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-4 control-label">E-mail</label>
                                <div class="col-sm-8">
                                    {{ Form::email('email', old('email'), ['id' => 'email', 'class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="phone" class="col-sm-4 control-label">Телефон</label>
                                <div class="col-sm-8">
                                    {{ Form::text('phone', old('phone'), ['id' => 'phone', 'class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="vk_account_id" class="col-sm-4 control-label">Аккаунт VK</label>
                                <div class="col-sm-8">
                                    {{ Form::text('vk_account_id', old('vk_account_id'), ['id' => 'vk_account_id', 'class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="date" class="col-sm-4 control-label">Время проведения</label>
                        <div class="col-sm-8">
                            <div class="input-group date datetimepicker">
                                {{ Form::text('date', old('date', $booking->dateFormatted), ['id' => 'date', 'class' => 'form-control']) }}
                                <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="amount" class="col-sm-4 control-label">Количество игроков</label>
                        <div class="col-sm-8">
                            {{ Form::text('amount', old('amount', $booking->amount), ['id' => 'amount', 'class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date" class="col-sm-4 control-label">Цена</label>
                        <div class="col-sm-8">
                            {{ Form::text('price', old('price', $booking->price), ['id' => 'price', 'class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="text-right col-sm-12">
                            {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
            @permissions('booking_history')
            <div class="tab-pane {{ $adminValues->bookingActiveTab == 1 ? 'active' : '' }}" id="tab_2">
            </div>
            @endpermissions
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
    <script type="text/javascript"
            src="http://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.min.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.11/handlebars.min.js"></script>
    <script>
        $('.datetimepicker').datetimepicker({
            format: 'DD.MM.YYYY HH:mm',
            locale: 'ru'
        });
        var clients = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: '{!! urldecode(route('admin.clients.index', ['filter' => ['query' => '%QUERY']], false)) !!}',
                wildcard: '%QUERY'
            }
        });
        $('#client').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'clients',
            display: 'value',
            source: clients,
            templates: {
                empty: '<div class="tt-suggestion">Ничего не найдено</div>',
                suggestion: Handlebars.compile('<div>' +
                    '<strong>@{{full_name}}</strong>' +
                    '<div>@{{phone_formatted}}</div>' +
                    '<div>@{{email}}</div>' +
                    '</div>')
            }
        }).bind('typeahead:select', function (event, data) {
            $(event.target).typeahead('val', data.full_name);
            var target = $($(event.target).data('target'));
            if (target.length > 0) {
                target.val(data.id);
            }
            target = $($(event.target).data('hide'));
            if (target.length > 0) {
                target.slideUp('fast');
            }
        }).bind('change', function (event) {
            $(event.target).typeahead('val', '');
            var target = $($(event.target).data('target'));
            if (target.length > 0) {
                target.val('');
            }
            target = $($(event.target).data('hide'));
            if (target.length > 0) {
                target.slideDown('fast');
            }
        });
    </script>
@endsection