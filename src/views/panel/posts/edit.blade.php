@extends('blublog::panel.layout.main')
@section('nav')
    @include('blublog::panel.posts._nav')
@endsection
@section('content')
    {{ Form::model($post, ['route' => ['blublog.panel.posts.update', $post->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
    <livewire:blublog-create-edit-post :post="$post">
        {!! Form::close() !!}
    @endsection
    @include('blublog::panel.posts._scripts')
