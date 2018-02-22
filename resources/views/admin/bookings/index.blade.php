@php /** @var \App\Models\Booking[]|\Illuminate\Support\Collection $bookings */ @endphp

@extends('admin.layouts.app')

@section('title', 'Брони')

@section('content_header')
    <div class="pull-right">
        <a href="{{ route('admin.bookings.create') }}" class="btn btn-success">Создать</a>
    </div>
    <h1>Игры</h1>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover no-margin">
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Дата</th>
                        <th>Место</th>
                        <th>Количество команд</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bookings as $game)
                        <tr ondblclick="location.href = '{{ route('admin.bookings.show', ['id' => $game->id]) }}'">
                            <td>
                                <a href="{{ route('admin.bookings.show', ['id' => $game->id]) }}">
                                    @if($game->type->image)
                                        <img src="{{ $game->type->image }}" alt="" style="margin-right: 5px;">
                                    @endif
                                    {{ $game->name }}
                                </a>
                            </td>
                            <td>{{ $game->startAt->format('d.m.Y H:i') }}</td>
                            <td>{{ $game->location->name }}</td>
                            <td>{{ $game->teamsCount }} {!! $game->teamsCountMissed && $game->startAt < \Carbon\Carbon::now() ?
                              " <span class=\"label label-danger\">-{$game->teamsCountMissed}</span>" : '' !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $bookings->links() }}
        </div>
    </div>
@stop

@push('css')
@endpush

@push('js')
@endpush