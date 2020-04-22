@extends('blublog::panel.main')

@section('content')
<div class="row">
    @include('blublog::panel.partials.colums', ['title' => __('panel.posts_this'),'val'=>$this_month_posts,'color'=>"primary"])
    @include('blublog::panel.partials.colums', ['title' => __('panel.posts_last'),'val'=>$last_month_posts,'color'=>"success"])
    @include('blublog::panel.partials.colums', ['title' => __('panel.posts_total'),'val'=>$totalposts,'color'=>"info"])
    @include('blublog::panel.partials.colums', ['title' => __('panel.comments'),'val'=>$totalcomments,'color'=>"warning"])
</div>
@if ($notpubliccomments != 0)
<div class="alert alert-warning" role="alert">
    ({{$notpubliccomments}}) {{__('panel.comments_waiting')}}
</div>
@endif
@include('blublog::panel.partials.continue_edit')
@endsection
