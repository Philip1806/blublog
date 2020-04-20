@extends('blublog::panel.main')


@section('content')
<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('panel.addpage')}}</div>
    <div class="card-body text-primary">
        {!! Form::open(['route' => 'blublog.pages.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

        {{ Form::label('title', __('panel.title')) }}
        {{ Form::text('title', null, ['class' => 'form-control']) }}

        {{ Form::label('content', __('panel.content')) }}
        {{ Form::textarea('content', null, ['class' => 'form-control', 'rows' => '10']) }}
<hr>
        {{ Form::label('descr', __('panel.seodescr')) }}
        {{ Form::text('descr', null, ['class' => 'form-control']) }}


        {{ Form::label('slug', __('panel.slug')) }}
        {{ Form::text('slug', null, ['class' => 'form-control']) }}

        {{ Form::label('img', __('panel.back_img')) }}
        {{ Form::text('img', null, ['class' => 'form-control']) }}

        {{ Form::label('tags', __('panel.tags')) }}
        {{ Form::text('tags', null, ['class' => 'form-control']) }}
<hr>
        <p> {{ Form::checkbox('public', true, true) }}
        {{ Form::label('public', __('panel.public')) }} </p>
        <p> {{ Form::checkbox('sidebar', true, true) }}
        {{ Form::label('sidebar', __('panel.show_sidebar')) }} </p>

<hr>
{{ Form::submit(__('panel.create'), ['class' => 'btn btn-primary btn-block']) }}
{!! Form::close() !!}
    </div>
</div>



@endsection
