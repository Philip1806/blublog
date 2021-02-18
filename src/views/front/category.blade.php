@extends('blublog::front.layout.main')

@section('title') Category: {{ $category->title }} @endsection

@section('meta')
    @if ($posts->previousPageUrl())
        <link rel="prev" href="{{ $posts->previousPageUrl() }}" />
    @endif
    @if ($posts->nextPageUrl())
        <link rel="next" href="{{ $posts->nextPageUrl() }}" />
    @endif
    @if (isset($category->img))
        <meta name="og:image" property="og:image" content="{{ $category->img }}" />
    @endif
    <meta name="og:title" property="og:title" content="Category: {{ $category->title }}">
    <meta name="og:description" property="og:description" content="{{ $category->descr }}">
    <meta name="og:locale" property="og:locale" content="en_EN" />
    <meta name="robots" content="index, follow">
@endsection


@section('header')
    <div class="jumbotron p-2">
        <div class="container text-center">
            <h1>{{ $category->title }}</h1>
            @if ($category->descr)
                <p>{{ $category->descr }}</p>
            @else
                <p>All posts from that category.</p>
            @endif
        </div>
    </div>
@endsection

@section('content')
    @include('blublog::front.layout._listPosts')
@endsection
