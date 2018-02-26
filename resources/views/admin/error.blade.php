@extends('admin.layouts.app')

@section('title', $message)

@section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title">
                {{ $message }}
            </div>
        </div>
    </div>
@stop

@push('css')
    <style>
        .position-ref {
            position: relative;
        }
        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }
        .full-height {
            height: calc(100vh - 95px);
        }
        .content {
            text-align: center;
        }
        .title {
            font-size: 36px;
            padding: 20px;
        }
    </style>
@endpush