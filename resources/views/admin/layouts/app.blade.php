@extends('adminlte::page')

@push('css')
    <link rel="stylesheet" href="{{ url('css/admin.css') }}">
@endpush

@push('js')
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script type="text/javascript" src="{{ url('js/admin.js') }}"></script>
@endpush