@extends('blublog::panel.layout.main')
@section('nav')
    @include('blublog::panel.posts._nav')
@endsection
@section('content')
    @livewire('blublog-tags')
@endsection
