@extends('blublog::blublog.main')
@section('title') @endsection
@section('jumbotron')
<div class="jumbotron">
    <div class="container">
        <p>{{__('blublog.search_resuts')}}</p>
        <h2>"{{$search}}"</h2>
    </div>
</div>
@endsection

@section('content')
    <div class="col-lg-9">
    @if (isset($posts[0]->id))
    @foreach ($posts as $post)
    <div class="row">
        <div class="col-sm-4">
            <img class="img-thumbnail border-primary rounded"  src="{{$post->img_url}}" alt="{{$post->title}} image">
        </div>
        <div class="col-sm-8">
            <h5 class="mt-0"><a href="{{$post->slug_url}}">{{$post->title}}</a></h5>
            <p>{{$post->excerpt}}</p>
            <small>{!!$post->STARS_HTML!!}<br>
                @foreach ($post->categories as $category)
                <a href="{{ route('blublog.front.category_show', $category->slug) }}" style="color:white"><span class="badge badge-{{$category->id}}">{{$category->title}}</span></a>
                @endforeach

                @foreach ($post->tags as $tag)
                <a href="{{ route('blublog.front.tag_show', $tag->slug) }}"><span class="badge badge-pill badge-dark">{{$tag->title}}</span></a>
                @endforeach


            </small>
        </div>
    </div>
    <hr>
    @endforeach
    @endif
    </div>
    <div class="col-lg-3">
        @include('blublog::blublog.parts._sidebar')
    </div>
@endsection
