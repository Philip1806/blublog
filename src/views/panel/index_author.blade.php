@extends('blublog::panel.main')

@section('content')
  <div class="row">
    @include('blublog::panel.partials.colums', ['title' => __('panel.my_posts_this'),'val'=>$this_month_posts,'color'=>"primary"])
    @include('blublog::panel.partials.colums', ['title' => __('panel.my_posts_last'),'val'=>$last_month_posts,'color'=>"success"])
    @include('blublog::panel.partials.colums', ['title' => __('panel.my_posts_total'),'val'=>$myposts,'color'=>"info"])
    @include('blublog::panel.partials.colums', ['title' => __('panel.posts_total'),'val'=>$totalposts,'color'=>"warning"])
  </div>
  @include('blublog::panel.partials.continue_edit')
@endsection
