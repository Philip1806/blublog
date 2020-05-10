@extends('blublog::panel.main')


@section('content')
<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('blublog.addpage')}}</div>
    <div class="card-body text-primary">
        {!! Form::open(['route' => 'blublog.pages.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        @include('blublog::panel.pages._form', ['button_title' => __('blublog.create')])
        {!! Form::close() !!}
    </div>
</div>
@endsection
