@extends('blublog::panel.main')

@section('content')
  <div class="row">
    @include('blublog::panel.partials.colums', ['title' => __('panel.my_posts_this'),'val'=>$this_month_posts,'color'=>"primary",'icon'=>"newspaper"])
    @include('blublog::panel.partials.colums', ['title' => __('panel.my_posts_last'),'val'=>$last_month_posts,'color'=>"success",'icon'=>"newspaper"])
    @include('blublog::panel.partials.colums', ['title' => __('panel.my_posts_total'),'val'=>$myposts,'color'=>"info",'icon'=>"newspaper"])
    @include('blublog::panel.partials.colums', ['title' => __('panel.posts_total'),'val'=>$totalposts,'color'=>"warning",'icon'=>"newspaper"])
  </div>
  @include('blublog::panel.partials.continue_edit')
@endsection
