@extends('blublog::blublog.main')
@section('title') Tag: {{$tag->title}} @endsection
@section('meta')
@if ($posts->previousPageUrl())
<link rel="prev" href="{{$posts->previousPageUrl()}}" />
@endif
@if ($posts->nextPageUrl())
<link rel="next" href="{{$posts->nextPageUrl()}}" />
@endif
@endsection

@section('jumbotron')
<div class="jumbotron">
    <div class="container">
        <h2>{{$tag->title}}</h2>
        {{$tag->descr}}
    </div>
</div>
@endsection
@section('content')

    <div class="col-lg-9">
        <h5>All posts with tag "{{$tag->title}}":</h5><hr>
        @include('blublog::blublog.parts._listposts')

    </div>
    <div class="col-lg-3">
        @include('blublog::blublog.parts._sidebar')
    </div>
<style>
.jumbotron {
    border-color: #2780E3;
    border-bottom-style: solid;
}
</style>
@endsection
