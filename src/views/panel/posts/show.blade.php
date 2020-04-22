@extends('blublog::panel.main')

@section('navbar')
<nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('/panel') }}">{{ __('panel.home') }}</a></li>
                  <li class="breadcrumb-item"><a href="{{ url('/panel/posts') }}">{{ __('panel.posts') }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
                </ol>
</nav>
@endsection

@section('content')
@if (blublog_can_edit_post( $post->id,Auth::user()->id))
<div class="row">
        <div class="col-lg">
        <a href="{{ route('blublog.posts.edit', $post->id) }}" class="btn btn-warning btn-block">{{__('panel.edit')}}</a>
        </div>
        <div class="col-lg">
                {!! Form::open(['route' => ['blublog.posts.destroy', $post->id], 'method' => 'DELETE']) !!}
                {!! form::submit(__('panel.delete'), ['class' => 'btn btn-danger btn-block ' ]) !!}
                {!! Form::close() !!}
        </div>
        <div class="col-lg">
        <a href="{{ route('blublog.front.post_show', $post->slug) }}" class="btn btn-success btn-block">{{__('panel.view_frontend')}}</a>
        </div>
        <div class="col-lg">
                <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#ModalLong">
                {{__('panel.views')}}
                </button>

                <div class="modal fade" id="ModalLong" tabindex="-1" role="dialog" aria-labelledby="ModalLongTitle" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="ModalLongTitle">{{__('panel.views')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
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
</div>
@endif
<br>
<div class="row">
<div class="col-xl-5 col-lg-6">
                <div class="card shadow">
                <img class="card-img-top" src="{{url('/uploads/posts/')}}/thumbnail_{{ $post->img }}"  alt="Card image cap">

                  </div>
</div>
<div class="col-xl-7 col-lg-6">
                <div class="card shadow">
                                <div class="card-body">
                                <h3> {{ $post->title }} </h3>
                                <hr>
                                <p><b>{{__('panel.posted')}}</b>: {{ $post->user->name }} <b>{{__('panel.type')}}</b>: {{$post->type}}</p>
                                <span class="badge badge-success">{{__('panel.on')}} {{ $post->created_at }}</span> <span class="badge badge-success">{{__('panel.lastedit')}} {{ $post->updated_at }}</span>

                                @if ($post->status == "publish")
                                <span class="badge badge-success">{{__('panel.public')}}</span>
                                @else
                                <span class="badge badge-warning">{{__('panel.private')}}</span>
                                @endif

                                @if ($post->front)
                                <span class="badge badge-success">{{__('panel.onfrontpage')}}</span>
                                @else
                                <span class="badge badge-warning">{{__('panel.notonfrontpage')}}</span>
                                @endif

                                @if ($post->slider)
                                <span class="badge badge-success">{{__('panel.inslider')}}</span>
                                @else
                                <span class="badge badge-warning">{{__('panel.notinslider')}}</span>
                                @endif

                                @if (!is_null($post->tag_id))
                                <span class="badge badge-success">{{__('panel.maintag')}}</span>
                                @else
                                <span class="badge badge-warning">{{__('panel.nomaintag')}}</span>
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
