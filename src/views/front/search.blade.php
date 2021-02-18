@extends('blublog::front.layout.main')
@section('header')
    <div class="jumbotron p-2">
        <div class="container text-center">
            <h1>Search result</h1>
            @foreach ($tags as $tag)
                <a href="{{ route('blublog.front.tag', $tag->slug) }}"><span
                        class="badge m-1 p-2 badge-dark rounded-pill"><span class="oi oi-tags"></span>
                        {{ $tag->title }}</span></a>
            @endforeach
        </div>
    </div>
@endsection

@section('content')
    @include('blublog::front.layout._listPosts', ['noPagination' => false])
@endsection
