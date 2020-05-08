@extends('blublog::panel.main')
@section('content')
@foreach ($php_errors as $error)
    <div class="alert alert-warning" role="alert">
        {{ $error}}
    </div>
@endforeach
  <div class="row">
    @include('blublog::panel.partials.colums', ['title' => __('panel.posts_this'),'val'=>$this_month_posts,'color'=>"primary",'icon'=>"newspaper"])
    @include('blublog::panel.partials.colums', ['title' => __('panel.posts_last'),'val'=>$last_month_posts,'color'=>"success",'icon'=>"newspaper"])
    @include('blublog::panel.partials.colums', ['title' => __('panel.posts_total'),'val'=>$totalposts,'color'=>"info",'icon'=>"newspaper"])
    @include('blublog::panel.partials.colums', ['title' => __('panel.comments'),'val'=>$totalcomments,'color'=>"warning",'icon'=>"comments"])
  </div>

  <div class="row">
    @include('blublog::panel.partials.4_colums', ['title' => __('panel.logs_this'),'val'=>$this_month_logs,'color'=>"warning",'icon'=>"code"])
    @include('blublog::panel.partials.4_colums', ['title' => __('panel.posts_last'),'val'=>$last_month_posts,'color'=>"warning",'icon'=>"newspaper"])
    @include('blublog::panel.partials.4_colums', ['title' => __('panel.blublog_version'),'val'=>config('blublog.version'),'color'=>"warning",'icon'=>"cogs"])
  </div>

  @if ($notpubliccomments != 0)
  <div class="alert alert-warning" role="alert">
    ({{$notpubliccomments}}) {{__('panel.comments_waiting')}}
  </div>
  @endif
  @include('blublog::panel.partials.continue_edit')
  <div class="card border-danger shadow" style="margin-top: 20px;">
    <div class="card-header bg-danger  text-white">
    {{__('panel.site_actions')}}
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm">
                <a href="{{ route('blublog.admin.control', 0) }}" class="btn btn-outline-primary btn-block">{{__('panel.clear_cache')}}</a>
              </div>
            <div class="col-sm">
                @if (!file_exists( public_path() . '/uploads/rss.xml'))
                <span class="badge  btn-block badge-warning" style="margin-bottom:10px;">{{__('panel.no_rss')}}</span>
                @endif
            <a href="{{ route('blublog.admin.control', 1) }}" class="btn btn-outline-primary btn-block">{{__('panel.rss_generate')}}</a>
            </div>
          </div>
          <br>
          @if (file_exists( storage_path().'/framework/down'))
          <a href="{{ route('blublog.admin.control', 2) }}" class="btn btn-outline-warning btn-block">{{__('panel.turn_off_maintenance')}}</a>
          @else
           <a href="{{ route('blublog.admin.control', 2) }}" class="btn btn-outline-danger btn-block">{{__('panel.turn_on_maintenance')}}</a>
          @endif
          @if (blublog_setting('under_attack'))
          <a href="{{ route('blublog.admin.control', 3) }}" class="btn btn-outline-warning btn-block">{{__('panel.turn_off_under_attack')}}</a>
          @else
          <a href="{{ route('blublog.admin.control', 3) }}" class="btn btn-outline-danger btn-block">{{__('panel.turn_on_under_attack')}}</a>
          @endif
   </div>
  </div>
@endsection
