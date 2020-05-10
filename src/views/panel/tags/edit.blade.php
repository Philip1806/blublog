@extends('blublog::panel.main')

@section('content')
<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('blublog.edit')}} {{__('blublog.tags')}}</div>
    <div class="card-body text-primary">
        {{ Form::model($tag, ['route' => ['blublog.tags.update', $tag->id ], 'method' => "PUT", 'enctype' => 'multipart/form-data']) }}
        @include('blublog::panel.tags._form', ['button_title' => __('blublog.edit')])
        {!! Form::close() !!}
    </div>
</div>
@endsection
