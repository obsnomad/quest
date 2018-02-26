@php
    /** @var \App\Models\Client[]|\Illuminate\Support\Collection $clients */
    /** @var Illuminate\Support\Collection $vkAccounts */
@endphp

@extends('admin.layouts.app')

@section('title', 'Клиенты')

@section('content_header')
    <div class="pull-right">
        <a href="{{ route('admin.clients.create') }}" class="btn btn-success">Создать</a>
    </div>
    <h1>Клиенты</h1>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <form action="{{ URL::current() }}" method="get">
                <div class="col-xs-12">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" id="query" name="filter[query]"
                                   value="{{ @$filter['query'] }}"/>
                            <span class="input-group-btn">
                            <input type="submit" value="Поиск" class="btn btn-info"/>
                            </span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="box">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover no-margin">
                    <thead>
                    <tr>
                        <th>Имя</th>
                        <th>E-mail</th>
                        <th>Телефон</th>
                        <th>Аккаунт VK</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clients as $client)
                        <tr ondblclick="location.href = '{{ route('admin.clients.show', ['id' => $client->id]) }}'">
                            <td>
                                <a href="{{ route('admin.clients.show', ['id' => $client->id]) }}">
                                    {{ $client->fullName }}
                                </a>
                            </td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->phoneFormatted }}</td>
                            <td>
                                {!! $vkAccounts && $vkAccounts->offsetExists($client->vkAccountId)
                                ? Html::link('https://vk.com/' . $vkAccounts[$client->vkAccountId]['screen_name'], false, ['target' => '_blank'])
                                : $client->vkAccountId !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $clients->links() }}
        </div>
    </div>
@stop

@push('css')
@endpush

@push('js')
@endpush