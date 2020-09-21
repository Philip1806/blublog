@extends('blublog::panel.main')
@section('navbar')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ blublog_panel_url('/tags') }}">{{ __('blublog.add_tag') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ blublog_panel_url('/posts/create') }}">{{ __('blublog.add_post') }}</a></li>
    </ol>
</nav>
@endsection
@section('content')

@if ($logs->count() > 3)
    <a class="my-2 btn btn-warning btn-block" data-toggle="collapse" href="#collapseLogs" role="button" aria-expanded="false" aria-controls="collapseLogs">
        {{__('blublog.latest_important_logs', ['number' => $logs->count()])}}
    </a>

  <div class="my-2 collapse" id="collapseLogs">
    <div class="card card-body">
        @foreach ($logs as $log)
        @if ($log->type == "alert")
            <div class="alert alert-warning" role="alert">
        @else
            <div class="alert alert-danger" role="alert">
        @endif
            <a href="{{ route('blublog.logs.show', $log->id) }}">{{ $log->message}}</a>
        </div>
        @endforeach
    </div>
  </div>
@endif
@foreach ($php_errors as $error)
    <div class="alert alert-warning" role="alert">
        {{ $error}}
    </div>
@endforeach
  <div class="row">
    @include('blublog::panel.partials.colums', ['title' => __('blublog.posts_this'),'val'=>$this_month_posts,'color'=>"primary",'icon'=>"excerpt"])
    @include('blublog::panel.partials.colums', ['title' => __('blublog.posts_last'),'val'=>$last_month_posts,'color'=>"success",'icon'=>"excerpt"])
    @include('blublog::panel.partials.colums', ['title' => __('blublog.posts_total'),'val'=>$totalposts,'color'=>"secondary",'icon'=>"excerpt"])
    @include('blublog::panel.partials.colums', ['title' => __('blublog.comments'),'val'=>$totalcomments,'color'=>"warning",'icon'=>"comment-square"])
  </div>

  <div class="row">
    @include('blublog::panel.partials.4_colums', ['title' => __('blublog.logs_this'),'val'=>$this_month_logs,'color'=>"warning",'icon'=>"code"])
    @include('blublog::panel.partials.4_colums', ['title' => __('blublog.files'),'val'=>$totalfiles,'color'=>"info",'icon'=>"file"])
    @include('blublog::panel.partials.4_colums', ['title' => __('blublog.blublog_version'),'color'=>"danger",'icon'=>"cog", 'version' =>true])
  </div>

  @if ($notpubliccomments != 0)
  <div class="alert alert-warning" role="alert">
    ({{$notpubliccomments}}) {{__('blublog.comments_waiting')}}
  </div>
  @endif
  @include('blublog::panel.partials.continue_edit')
  <div class="card border-danger shadow" style="margin-top: 20px;">
    <div class="card-header bg-danger  text-white">
    {{__('blublog.site_actions')}}
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm">
                <a href="{{ route('blublog.admin.control', 0) }}" class="btn btn-outline-primary btn-block">{{__('blublog.clear_cache')}}</a>
              </div>
            <div class="col-sm">
                @if (!file_exists( public_path() . '/blublog-uploads/rss.xml'))
                <span class="badge  btn-block badge-info" style="margin-bottom:10px;">{{__('blublog.no_rss')}}</span>
                @endif
            <a href="{{ route('blublog.admin.control', 1) }}" class="btn btn-outline-primary btn-block">{{__('blublog.rss_generate')}}</a>
            </div>
          </div>
          <br>
          @if (file_exists( storage_path().'/framework/down'))
          <a href="{{ route('blublog.admin.control', 2) }}" class="btn btn-outline-warning btn-block">{{__('blublog.turn_off_maintenance')}}</a>
          @else
           <a href="{{ route('blublog.admin.control', 2) }}" class="btn btn-outline-danger btn-block">{{__('blublog.turn_on_maintenance')}}</a>
          @endif
          @if (blublog_setting('under_attack'))
          <a href="{{ route('blublog.admin.control', 3) }}" class="btn btn-outline-warning btn-block">{{__('blublog.turn_off_under_attack')}}</a>
          @else
          <a href="{{ route('blublog.admin.control', 3) }}" class="btn btn-outline-danger btn-block">{{__('blublog.turn_on_under_attack')}}</a>
          @endif
   </div>
  </div>
@endsection
