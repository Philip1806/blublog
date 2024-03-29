@extends('blublog::front.layout.main')
@section('title')
    {{ $post->seo_title }}
@endsection

@section('meta')
    <!-- Open Graph / Facebook -->
    <meta name="og:title" property="og:title" content="{{ $post->seo_title }}">
    <meta name="og:description" property="og:description" content="{{ $post->seo_descr }}">
    <meta name="og:image" property="og:image" content="{{ $post->thumbnailUrl() }}" />
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
    <meta property="twitter:image" content="{{ $post->thumbnailUrl() }}">
    <style>
        .display-comment .display-comment {
            margin-left: 40px
        }
    </style>
@endsection


@section('header')
    <div class="jumbotron p-2">
        <div class="container text-center">
            <h1>{{ $post->title }}</h1>
        </div>
    </div>
@endsection

@section('content')
    @if ($post->file and $post->file->is_video)
        <div class="embed-responsive embed-responsive-16by9">
            <video controls poster="{{ $post->file->imageSizeUrl('mid') }}">
                <source src="{{ $post->getFileUrl() }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    @else
        <img src="{{ $post->getFileUrl() }}" class="img-fluid mb-2" alt="{{ $post->title }} image">
    @endif
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
            @include('blublog::front.layout._listTags', ['tags' => $post->tags])
        </div>
    </div>
    @include('blublog::front.comments._comments', ['tags' => $post->tags])
    @if ($post->similar)
        @include('blublog::front.layout._listPosts', ['posts' => $post->similar, 'noPagination' => true])
    @endif
@endsection
