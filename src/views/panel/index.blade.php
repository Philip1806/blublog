@extends('blublog::panel.main')
@section('content')
@foreach ($php_errors as $error)
    <div class="alert alert-warning" role="alert">
        {{ $error}}
    </div>
@endforeach
  <div class="row">
    @include('blublog::panel.partials.colums', ['title' => __('blublog.posts_this'),'val'=>$this_month_posts,'color'=>"primary",'icon'=>"list"])
    @include('blublog::panel.partials.colums', ['title' => __('blublog.posts_last'),'val'=>$last_month_posts,'color'=>"success",'icon'=>"list"])
    @include('blublog::panel.partials.colums', ['title' => __('blublog.posts_total'),'val'=>$totalposts,'color'=>"info",'icon'=>"list"])
    @include('blublog::panel.partials.colums', ['title' => __('blublog.comments'),'val'=>$totalcomments,'color'=>"warning",'icon'=>"comment-square"])
  </div>

  <div class="row">
    @include('blublog::panel.partials.4_colums', ['title' => __('blublog.logs_this'),'val'=>$this_month_logs,'color'=>"warning",'icon'=>"code"])
    @include('blublog::panel.partials.4_colums', ['title' => __('blublog.posts_last'),'val'=>$last_month_posts,'color'=>"warning",'icon'=>"list"])
    @include('blublog::panel.partials.4_colums', ['title' => __('blublog.blublog_version'),'color'=>"warning",'icon'=>"cog", 'version' =>true])
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
                <span class="badge  btn-block badge-warning" style="margin-bottom:10px;">{{__('blublog.no_rss')}}</span>
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
