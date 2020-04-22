@extends('blublog::blublog.main')
@section('title'){{$page->title}} @endsection

@section('jumbotron')
<div class="jumbotron">
    <div class="container">
        <h2>{{$page->title}}</h2>
        {{$page->descr}}
    </div>
</div>
@endsection
@section('content')

@if ($page->sidebar)
    <div class="col-lg-9">
    {!!$page->content!!}
    </div>
    <div class="col-lg-3">
        @include('blublog::blublog.parts._sidebar')
    </div>
@else
    {!!$page->content!!}
@endif

<style>
.jumbotron {
    border-color: #2780E3;
    border-bottom-style: solid;
}
</style>
@endsection
