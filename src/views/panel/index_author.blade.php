@extends('blublog::panel.main')

@section('content')
  <div class="row">
    @include('blublog::panel.partials.colums', ['title' => __('blublog.my_posts_this'),'val'=>$this_month_posts,'color'=>"primary",'icon'=>"list"])
    @include('blublog::panel.partials.colums', ['title' => __('blublog.my_posts_last'),'val'=>$last_month_posts,'color'=>"success",'icon'=>"list"])
    @include('blublog::panel.partials.colums', ['title' => __('blublog.my_posts_total'),'val'=>$myposts,'color'=>"info",'icon'=>"list"])
    @include('blublog::panel.partials.colums', ['title' => __('blublog.posts_total'),'val'=>$totalposts,'color'=>"warning",'icon'=>"list"])
  </div>
  @include('blublog::panel.partials._message', ['message'=>blublog_setting('author_message_html'),'title' => __('blublog.message_authors')])
  @include('blublog::panel.partials.continue_edit')
@endsection
