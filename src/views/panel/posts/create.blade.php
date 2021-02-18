@extends('blublog::panel.layout.main')
@section('nav')
    @include('blublog::panel.posts._nav')
@endsection
@section('content')
    {!! Form::open(['route' => 'blublog.panel.posts.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    @livewire('blublog-create-edit-post')
    {!! Form::close() !!}
@endsection
@include('blublog::panel.posts._scripts')
