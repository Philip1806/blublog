@extends('blublog::front.layout.main')
@section('title') {{ $post->seo_title }} @endsection

@section('meta')
    <!-- Open Graph / Facebook -->
    <meta name="og:title" property="og:title" content="{{ $post->seo_title }}">
    <meta name="og:description" property="og:description" content="{{ $post->seo_descr }}">
    <meta name="og:image" property="og:image" content="{{ $post->imageURL() }}" />
    <meta name="og:type" property="og:type" content="article">
    <meta name="og:published_time" property="og:published_time" content="{{ $post->created_at }}">
    <meta name="og:article:section" property="og:article:section" content="{{ $post->categories[0]->title }}">
    @if (isset($post->tags[0]->id))
        @foreach ($post->tags as $tag)
            <meta name="og:article:tag" property="og:article:tag" content="{{ $tag->title }}">
        @endforeach
    @endif
    <meta name="og:locale" property="og:locale" content="en_EN" />
    <meta name="robots" content="index, follow">
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ route('blublog.front.single', $post->slug) }}">
    <meta property="twitter:title" content="{{ $post->title }}">
    <meta property="twitter:description" content="{{ $post->seo_descr }}">
    <meta property="twitter:image" content="{{ $post->imageURL() }}">
@endsection


@section('header')
    <div class="jumbotron p-2">
        <div class="container text-center">
            <h1>{{ $post->title }}</h1>
        </div>
    </div>
@endsection

@section('content')
    <img src="{{ $post->imageURL() }}" class="img-fluid mb-2" alt="{{ $post->title }} image">

    {!! $post->content !!}

    <div class="row my-3">
        <div class="col-lg-4">
            <span class="badge badge-primary p-1">
                <span class="oi oi-eye"></span> {{ $post->views }}
            </span>
            <a href="{{ route('blublog.front.like', $post->slug) }}" class="badge badge-primary p-1">
                <span class="oi oi-thumb-up"></span> {{ $post->likes }} (Click to like)
            </a>
        </div>
        <div class="col-lg-8 text-right">
            <small class="text-muted">Posted {{ $post->created_at->diffForHumans() }}</small>
        </div>
    </div>
    <div class="card border-dark my-2">
        <div class="card-body">
            @foreach ($post->categories as $category)
                <a href="{{ route('blublog.front.category', $category->slug) }}"><span
                        class="badge m-1 p-2 badge-primary badge-{{ $category->id }} rounded-pill"><span
                            class="oi oi-spreadsheet"></span> {{ $category->title }}</span></a>
            @endforeach
            @foreach ($post->tags as $tag)
                <a href="{{ route('blublog.front.tag', $tag->slug) }}"><span
                        class="badge m-1 p-2 badge-dark rounded-pill"><span class="oi oi-tags"></span>
                        {{ $tag->title }}</span></a>
            @endforeach
        </div>
    </div>
@endsection
