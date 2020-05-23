@extends('blublog::blublog.main')
@section('title')Posts by {{$posts->author->name}} @endsection
@section('meta')
<meta name="og:url" property="og:url" content="{{ url('/') }}" />
<meta name="og:locale" property="og:locale" content="en_EN" />
<meta name="robots" content="index, follow">
@endsection

@section('jumbotron')
<div class="jumbotron">
    <div class="container text-center">
        Posts by:
        <h1>{{$posts->author->name}}</h1>
    </div>
</div>
@endsection

@section('content')
<div class="col-lg-9">
    @include('blublog::blublog.parts._listposts')
</div>
<div class="col-lg-3">
    @include('blublog::blublog.parts._sidebar')
</div>
@endsection
