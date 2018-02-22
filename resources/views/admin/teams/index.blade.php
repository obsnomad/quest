@php /** @var \App\Models\Team[]|\Illuminate\Support\Collection $teams */ @endphp

@extends('admin.layouts.app')

@section('title', 'Команды')

@section('content_header')
    <h1>Команды</h1>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover no-margin">
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Количество игр</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($teams as $team)
                        <tr ondblclick="location.href = '{{ route('admin.teams.show', ['id' => $team->id]) }}'">
                            <td>
                                <a href="{{ route('admin.teams.show', ['id' => $team->id]) }}">
                                    {{ $team->name }}
                                </a>
                            </td>
                            <td>{{ $team->gamesCount }} {!! $team->gamesCountMissed ?
                              " <span class=\"label label-danger\">-{$team->gamesCountMissed}</span>" : '' !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $teams->links() }}
        </div>
    </div>
@stop

@push('css')
@endpush

@push('js')
@endpush