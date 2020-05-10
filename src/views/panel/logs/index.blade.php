@extends('blublog::panel.main')

@section('content')
<br>
<div class="card border-primary ">
    <div class="card-header text-white bg-primary">{{__('panel.logs')}}</div>
    <div class="card-body text-primary">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-error-tab" data-toggle="tab" href="#nav-error" role="tab" aria-controls="nav-error" aria-selected="true">
                Errors ({{$error_logs->total()}})</a>
              <a class="nav-item nav-link" id="nav-visit-tab" data-toggle="tab" href="#nav-visit" role="tab" aria-controls="nav-visit" aria-selected="false">
                Visits ({{$visit_logs->total()}})</a>
              <a class="nav-item nav-link" id="nav-bot-tab" data-toggle="tab" href="#nav-bot" role="tab" aria-controls="nav-bot" aria-selected="false">
                Bots visits ({{$bot_logs->total()}})</a>
              <a class="nav-item nav-link" id="nav-alert-tab" data-toggle="tab" href="#nav-alert" role="tab" aria-controls="nav-alert" aria-selected="false">
                Alerts ({{$alert_logs->total()}})</a>
              <a class="nav-item nav-link" id="nav-info-tab" data-toggle="tab" href="#nav-info" role="tab" aria-controls="nav-info" aria-selected="false">
                Info ({{$info_logs->total()}})</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-error" role="tabpanel" aria-labelledby="nav-error-tab">
                @include('blublog::panel.logs._tablelog', ['logs' => $error_logs])
            </div>
            <div class="tab-pane fade" id="nav-visit" role="tabpanel" aria-labelledby="nav-visit-tab">
                @include('blublog::panel.logs._tablelog', ['logs' => $visit_logs])
            </div>
            <div class="tab-pane fade" id="nav-bot" role="tabpanel" aria-labelledby="nav-bot-tab">
                @include('blublog::panel.logs._tablelog', ['logs' => $bot_logs])
            </div>
            <div class="tab-pane fade" id="nav-alert" role="tabpanel" aria-labelledby="nav-alert-tab">
                @include('blublog::panel.logs._tablelog', ['logs' => $alert_logs])
            </div>
            <div class="tab-pane fade" id="nav-info" role="tabpanel" aria-labelledby="nav-info-tab">
                @include('blublog::panel.logs._tablelog', ['logs' => $info_logs])
            </div>
        </div>
    </div>
</div>
@endsection
