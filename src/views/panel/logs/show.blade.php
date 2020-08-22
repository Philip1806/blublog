@extends('blublog::panel.main')

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
    {{ __('blublog.event') }} №{{ $log->id }} {{ __('blublog.type') }}: {{ $log->type }}
    </div>
    <ul class="list-group list-group-flush">
    <li class="list-group-item"><strong>{{$log->message}}</strong></li>
    <li class="list-group-item">{{$log->ip}} ({{blublog_get_username_from_id($log->user_id,true)}})</li>
    <li class="list-group-item">{{$log->user_agent}}</li>
    <li class="list-group-item">{{urldecode($log->request_url)}}</li>
    <li class="list-group-item">{{urldecode($log->referer)}}</li>
    <li class="list-group-item">{{$log->lang}}</li>
    <li class="list-group-item">
        <p><a class="btn btn-primary" data-toggle="collapse" href="#Collapse1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">{{ __('blublog.show_all_data') }}</a></p>
        <div class="collapse multi-collapse" id="Collapse1">
            <div class="card card-body">
                {{$log->data}}
            </div>
        </div>
    </li>
    </ul>
</div>

@endsection
