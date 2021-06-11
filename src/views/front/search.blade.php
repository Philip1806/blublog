@extends('blublog::front.layout.main')
@section('header')
    <div class="jumbotron p-2">
        <div class="container text-center">
            <h1>Search result</h1>
            @include('blublog::front.layout._listTags')
        </div>
    </div>
@endsection

@section('content')
    @include('blublog::front.layout._listPosts', ['noPagination' => false])
@endsection
