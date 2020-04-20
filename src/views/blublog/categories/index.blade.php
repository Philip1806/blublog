@extends('blublog::blublog.main')
@section('title') Category: {{$category->title}} @endsection
@section('meta')
@if ($posts->previousPageUrl())
<link rel="prev" href="{{$posts->previousPageUrl()}}" />
@endif
@if ($posts->nextPageUrl())
<link rel="next" href="{{$posts->nextPageUrl()}}" />
@endif

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website" />
@if (isset($category->img))
<meta name="og:image" property="og:image" content="{{url('/uploads/categories/')}}/{{$category->img}}" />
@endif
<meta name="og:title" property="og:title" content="Category: {{ $category->title }}">
<meta name="og:description" property="og:description" content="{{ $category->descr }}">
<meta name="og:site_name" property="og:site_name" content="{!!blublog_setting('site_name')!!}" >
<meta name="og:url" property="og:url" content="{{ url('/') }}/categories/{{ $category->slug }}" />
<meta name="og:locale" property="og:locale" content="bg_BG" />
<meta name="robots" content="index, follow">
@endsection

@section('jumbotron')
<div class="jumbotron" style="padding:100px;">
    <div class="container text-white">
        <h2>{{$category->title}}</h2>
        {{$category->descr}}
    </div>
</div>
@endsection
@section('content')

    <div class="col-lg-9">
        <h5>All posts from "{{$category->title}}":</h5><hr>
        @include('blublog::blublog.parts._listposts')

    </div>
    <div class="col-lg-3">
        @include('blublog::blublog.parts._sidebar')
    </div>
<style>
.jumbotron {
    background-image: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url("{{url('/uploads/categories/')}}/{{$category->img}}");
    background-size: cover;
    background-repeat: no-repeat;
    border-color: {{$category->colorcode}};
    border-bottom-style: solid;
    border-width: 5px;
}
</style>
@endsection
