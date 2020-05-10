@extends('blublog::panel.main')


@section('content')
<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('panel.addpage')}}</div>
    <div class="card-body text-primary">
        {!! Form::open(['route' => 'blublog.pages.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        @include('blublog::panel.pages._form', ['button_title' => __('panel.create')])
        {!! Form::close() !!}
    </div>
</div>
@endsection
