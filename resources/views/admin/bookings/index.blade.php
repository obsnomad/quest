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
            <form action="{{ URL::current() }}" method="get">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="booking-time">Дата брони:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right" id="booking-time" name="filter[date]"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="" class="hidden-xs" style="display: block;">&nbsp;</label>
                        <div class="input-group">
                            <input type="submit" value="Применить" class="btn btn-info"/>
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
                    @foreach($bookings as $booking)
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
            {{ $bookings->links() }}
        </div>
    </div>
@stop

@push('css')
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>
@endpush

@push('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/locale/ru.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script>
        $('#booking-time').daterangepicker({
            endDate: moment().add(2, 'week'),
            format: 'DD.MM.YYYY',
            locale: {
                format: "DD.MM.YYYY",
                separator: " - ",
                applyLabel: "Применить",
                cancelLabel: "Отменить",
                "fromLabel": "от",
                "toLabel": "до",
                "customRangeLabel": "Настроить",
                "daysOfWeek": [
                    "Вс",
                    "Пн",
                    "Вт",
                    "Ср",
                    "Чт",
                    "Пт",
                    "Сб"
                ],
                "monthNames": [
                    "Январь",
                    "Февраль",
                    "Март",
                    "Апрель",
                    "Май",
                    "Июнь",
                    "Июль",
                    "Август",
                    "Сентябрь",
                    "Октябрь",
                    "Ноябрь",
                    "Декабрь"
                ],
                "firstDay": 1
            }
        });
    </script>
@endpush