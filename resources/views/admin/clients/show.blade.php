@php /**
     * @var \App\Models\Client|\Illuminate\Support\Collection $client
     * @var \Illuminate\Support\ViewErrorBag $errors
     */ @endphp

@extends('admin.layouts.app')

@section('title', $title)

@section('content_header')
    <div class="pull-right">
        <a href="{{ route('admin.clients.index') }}" class="btn btn-primary">Список</a>
    </div>
    <h1>{{ $title }}</h1>
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
            <li {!! !$adminValues->clientActiveTab ? 'class="active"' : '' !!}>
                <a href="#tab_1" data-toggle="tab">
                    <h4>Данные клиента</h4>
                </a>
            </li>
            @if($client->id && $client->bookings->count())
                <li {!! $adminValues->clientActiveTab == 1 ? 'class="active"' : '' !!}>
                    <a href="#tab_2" data-toggle="tab">
                        <h4>История посещений</h4>
                    </a>
                </li>
            @endif
        </ul>
        <div class="tab-content">
            <div class="tab-pane {{ !$adminValues->clientActiveTab ? 'active' : '' }}" id="tab_1">
                {{ Form::open([
                    'url' => $client->id ? route('admin.clients.update', ['id' => $client->id]) : route('admin.clients.store'),
                    'class' => 'form-horizontal row',
                    'method' => $client->id ? 'patch' : 'post',
                ]) }}
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="first_name" class="col-sm-4 control-label">Имя</label>
                        <div class="col-sm-8">
                            {{ Form::text('first_name', old('first_name', $client->firstName), ['id' => 'first_name', 'class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="last_name" class="col-sm-4 control-label">Фамилия</label>
                        <div class="col-sm-8">
                            {{ Form::text('last_name', old('last_name', $client->lastName), ['id' => 'last_name', 'class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="email" class="col-sm-4 control-label">E-mail</label>
                        <div class="col-sm-8">
                            {{ Form::email('email', old('email', $client->email), ['id' => 'email', 'class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="col-sm-4 control-label">Телефон</label>
                        <div class="col-sm-8">
                            {{ Form::text('phone', old('phone', $client->phoneFormatted), ['id' => 'phone', 'class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="vk_account_id" class="col-sm-4 control-label">Аккаунт VK</label>
                        <div class="col-sm-8">
                            {{ Form::text('vk_account_id', old('vk_account_id', @$client->vkAccountLink
                             ? 'https://vk.com/' . $client->vkAccountLink
                             : $client->vkAccountId), ['id' => 'vk_account_id', 'class' => 'form-control']) }}
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
            @if($client->id && $client->bookings)
                <div class="tab-pane {{ $adminValues->clientActiveTab == 1 ? 'active' : '' }}" id="tab_2">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover no-margin">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Квест</th>
                                <th>Клиент</th>
                                <th>Количество игроков</th>
                                <th>Статус</th>
                                <th>Дата</th>
                                <th>Цена</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($client->bookings as $booking)
                                <tr ondblclick="location.href = '{{ route('admin.bookings.show', ['id' => $booking->id]) }}'">
                                    <td>
                                        <a href="{{ route('admin.bookings.show', ['id' => $booking->id]) }}">
                                            {{ $booking->id }}
                                        </a>
                                    </td>
                                    <td>{{ $booking->quest->name }}</td>
                                    <td>{{ $booking->client->fullName }}</td>
                                    <td>{{ $booking->amount }}</td>
                                    <td>
                                <span class="label label-{{ $booking->status->labelClass }}">
                                {{ $booking->status->name }}
                                </span>
                                    </td>
                                    <td>{{ $booking->date }}</td>
                                    <td>{{ $booking->price }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
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

        function setClient(event, id, value) {
            $(event.target).typeahead('val', value);
            var target = $($(event.target).data('target'));
            if (target.length > 0) {
                target.val(id);
            }
            target = $($(event.target).data('hide'));
            if (target.length > 0) {
                id > 0 ? target.slideUp('fast') : target.slideDown('fast');
            }
        }

        if ($('#client_id').val().length === 0 && $('#client_new').is(':hidden') === true) {
            $('#client_new').show();
        }
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
            setClient(event, data.id, data.full_name);
        }).bind('change', function (event) {
            setClient(event, '', '');
        });
    </script>
@endsection