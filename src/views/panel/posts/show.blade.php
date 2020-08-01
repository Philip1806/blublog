@extends('blublog::panel.main')

@section('navbar')
<nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('/panel') }}">{{ __('blublog.home') }}</a></li>
                  <li class="breadcrumb-item"><a href="{{ url('/panel/posts') }}">{{ __('blublog.posts') }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
                </ol>
</nav>
@endsection

@section('content')
@can('update', $post)
<div class="row">
    <div class="col-lg">
    <a href="{{ route('blublog.posts.edit', $post->id) }}" class="btn btn-warning btn-block">{{__('blublog.edit')}}</a>
    </div>
    @can('delete', $post)
    <div class="col-lg">
            {!! Form::open(['route' => ['blublog.posts.destroy', $post->id], 'method' => 'DELETE']) !!}
            {!! form::submit(__('blublog.delete'), ['class' => 'btn btn-danger btn-block ' ]) !!}
            {!! Form::close() !!}
    </div>
    @endcan
    <div class="col-lg">
    <a href="{{ route('blublog.front.post_show', $post->slug) }}" class="btn btn-success btn-block">{{__('blublog.view_frontend')}}</a>
    </div>
    @can('view_stats', $post)
    <div class="col-lg">
        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#ModalLong">
        {{__('blublog.stats')}}
        </button>
        <div class="modal fade" id="ModalLong" tabindex="-1" role="dialog" aria-labelledby="ModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLongTitle">{{__('blublog.stats')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {!! blublog_draw_stars(5) !!} ({{$post->rating_votes['five_star']}})<br>
                        {!! blublog_draw_stars(4) !!} ({{$post->rating_votes['four_star']}})<br>
                        {!! blublog_draw_stars(3) !!} ({{$post->rating_votes['three_star']}})<br>
                        {!! blublog_draw_stars(2) !!} ({{$post->rating_votes['two_star']}})<br>
                        {!! blublog_draw_stars(1) !!} ({{$post->rating_votes['one_star']}})
                        <hr>
                        <h3>{{__('blublog.views')}} ({{$post->views()->count()}})</h3>
                        @foreach ($post->views()->latest()->get() as $view)
                        <div class="alert alert-info" role="alert">
                        {{$view->ip}} | <i>{{$view->agent}}</i> | <b> {{$view->created_at}}</b>
                        </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
@endcan



<br>
<div class="row">
    <div class="col-xl-5 col-lg-6">
        <div class="card shadow">
            <img class="card-img-top" src="{{$post->img_thumb_url}}"  alt="Card image cap">
        </div>
    </div>
    <div class="col-xl-7 col-lg-6">
        <div class="card shadow">
            <div class="card-body">
                <h3>{{ $post->title }}</h3>
                <p><b>{{__('blublog.posted')}}</b>: {{ $post->user->name }} <b>{{__('blublog.type')}}</b>: {{$post->type}}</p>
                <span class="badge badge-success">{{__('blublog.on')}} {{ $post->created_at }}</span> <span class="badge badge-success">{{__('blublog.lastedit')}} {{ $post->updated_at }}</span>

                @if ($post->status == "publish")
                <span class="badge badge-success">{{__('blublog.public')}}</span>
                @else
                <span class="badge badge-warning">{{__('blublog.private')}}</span>
                @endif

                @if ($post->front)
                <span class="badge badge-success">{{__('blublog.onfrontpage')}}</span>
                @else
                <span class="badge badge-warning">{{__('blublog.notonfrontpage')}}</span>
                @endif

                @if ($post->slider)
                <span class="badge badge-success">{{__('blublog.inslider')}}</span>
                @else
                <span class="badge badge-warning">{{__('blublog.notinslider')}}</span>
                @endif

                @if (!is_null($post->tag_id))
                <span class="badge badge-success">{{__('blublog.maintag')}}</span>
                @else
                <span class="badge badge-warning">{{__('blublog.nomaintag')}}</span>
                @endif

                @foreach ( $post->categories as  $category)
                <span class="badge badge-primary">{{ $category->title }} </span>
                @endforeach

                @foreach ( $post->tags as  $tag)
                <span class="badge badge-info">{{ $tag->title }} </span>
                @endforeach

                <blockquote class="blockquote">
                <p class="mb-0">{{ $post->headlight }}</p>
                </blockquote>
            </div>
        </div>
    </div>
</div>


<div class="col-xl-12" style="margin-top:50px;">
        <div class="card shadow">

                <div class="card-body">
                                {!! $post->content !!}
                </div>
        </div>
</div>

@endsection
