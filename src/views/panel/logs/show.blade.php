@extends('blublog::panel.main')

@section('navbar')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">{{ __('panel.Home') }}</li>
    </ol>
</nav>
@endsection
@section('content')
<br>
<div class="card">
    @if ($log->type == "error")
    <div class="card-header text-white bg-danger">
    @elseif($log->type == "alert")
    <div class="card-header text-white bg-warning">
    @else
    <div class="card-header text-white bg-primary">
    @endif
    {{ __('panel.event') }} №{{ $log->id }} {{ __('panel.type') }}: {{ $log->type }}
    </div>
    <ul class="list-group list-group-flush">
    <li class="list-group-item"><strong>{{$log->message}}</strong></li>
    <li class="list-group-item">{{$log->user_agent}}</li>
    <li class="list-group-item">{{$log->request_url}}</li>
    <li class="list-group-item">{{$log->referer}}</li>
    <li class="list-group-item">{{$log->lang}}</li>
    <li class="list-group-item">
        <p><a class="btn btn-primary" data-toggle="collapse" href="#Collapse1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">{{ __('panel.show_all_data') }}</a></p>
        <div class="collapse multi-collapse" id="Collapse1">
            <div class="card card-body">
                {{$log->data}}
            </div>
        </div>
    </li>
    </ul>
</div>

@endsection
