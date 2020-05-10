@extends('blublog::panel.main')

@section('content')
<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('panel.edit')}} {{__('panel.tags')}}</div>
    <div class="card-body text-primary">
        {{ Form::model($tag, ['route' => ['blublog.tags.update', $tag->id ], 'method' => "PUT", 'enctype' => 'multipart/form-data']) }}
        @include('blublog::panel.tags._form', ['button_title' => __('panel.edit')])
        {!! Form::close() !!}
    </div>
</div>
@endsection
