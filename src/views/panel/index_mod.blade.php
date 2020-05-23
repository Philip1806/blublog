@extends('blublog::panel.main')

@section('content')
<div class="row">
    @include('blublog::panel.partials.colums', ['title' => __('blublog.posts_this'),'val'=>$this_month_posts,'color'=>"primary",'icon'=>"list"])
    @include('blublog::panel.partials.colums', ['title' => __('blublog.posts_last'),'val'=>$last_month_posts,'color'=>"success",'icon'=>"list"])
    @include('blublog::panel.partials.colums', ['title' => __('blublog.posts_total'),'val'=>$totalposts,'color'=>"info",'icon'=>"list"])
    @include('blublog::panel.partials.colums', ['title' => __('blublog.comments'),'val'=>$totalcomments,'color'=>"warning",'icon'=>"comment-square"])
</div>
@if ($notpubliccomments != 0)
<div class="alert alert-warning" role="alert">
    ({{$notpubliccomments}}) {{__('blublog.comments_waiting')}}
</div>
@endif
@include('blublog::panel.partials._message', ['message'=>blublog_setting('moderator_message_html'),'title' => __('blublog.message_mod')])
@include('blublog::panel.partials.continue_edit')
@endsection
