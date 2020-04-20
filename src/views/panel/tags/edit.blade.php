@extends('blublog::panel.main')

@section('content')
<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('panel.edit')}} {{__('panel.tags')}}</div>
    <div class="card-body text-primary">
        {{ Form::model($tag, ['route' => ['blublog.tags.update', $tag->id ], 'method' => "PUT", 'enctype' => 'multipart/form-data']) }}
                        {{ Form::label('title', __('panel.title')) }}
                        {{ Form::text('title', null, ['class' => 'form-control']) }}

                        {{ Form::label('slug', __('panel.slug')) }}
                        {{ Form::text('slug', null, ['class' => 'form-control']) }}

                        {{ Form::label('descr', __('panel.descr')) }}
                        {{ Form::text('descr', null, ['class' => 'form-control']) }}
<br>
        {{ Form::submit(__('panel.edit'), ['class' => 'btn btn-primary btn-block']) }}
        {!! Form::close() !!}

    </div>
</div>
@endsection
