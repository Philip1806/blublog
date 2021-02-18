@extends('blublog::front.layout.main')
@section('title') Tag: {{ $tag->title }} @endsection
@section('meta')
    @if ($posts->previousPageUrl())
        <link rel="prev" href="{{ $posts->previousPageUrl() }}" />
    @endif
    @if ($posts->nextPageUrl())
        <link rel="next" href="{{ $posts->nextPageUrl() }}" />
    @endif
    <meta name="robots" content="index, follow">
@endsection


@section('header')
    <div class="jumbotron p-2">
        <div class="container text-center">
            <h1><span class="oi oi-tags"></span> {{ $tag->title }}</h1>
            <p>All posts with that tag.</p>
        </div>
    </div>
@endsection

@section('content')
    @include('blublog::front.layout._listPosts')
@endsection
