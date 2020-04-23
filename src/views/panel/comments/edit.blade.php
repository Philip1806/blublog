@extends('blublog::panel.main')


@section('content')

<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('panel.edit')}} {{__('panel.comment')}}</div>
    <div class="card-body text-primary">
        {{ Form::model($comment, ['route' => ['blublog.comments.update', $comment->id ], 'method' => "PUT", 'enctype' => 'multipart/form-data']) }}
        <p> {{ Form::checkbox('public', true) }}
                {{ Form::label('public', 'Oдобрен') }} </p>

                        {{ Form::label('name', __('panel.name')) }}
                        {{ Form::text('name', null, ['class' => 'form-control']) }}

                        {{ Form::label('created_at', __('panel.created_at')) }}
                        {{ Form::text('created_at', null, ['class' => 'form-control']) }}

                        {{ Form::label('email', 'Email') }}
                        {{ Form::text('email', null, ['class' => 'form-control']) }}

                        {{ Form::label('ip', 'IP:') }}
                        {{ Form::text('ip', null, ['class' => 'form-control']) }}

                        {{ Form::label('body', __('panel.comment')) }}
                        {{ Form::textarea('body', null, ['class' => 'form-control', 'rows' => '5']) }}
        <br>
        {{ Form::submit(__('panel.edit'), ['class' => 'btn btn-primary btn-block']) }}
        {!! Form::close() !!}<br>
        {!! Form::open(['route' => ['blublog.comments.destroy', $comment->id], 'method' => 'DELETE']) !!}
        {!! form::submit(__('panel.delete'), ['class' => 'btn btn-danger btn-block ' ]) !!}
        {!! Form::close() !!}
    </div>
</div>
@endsection
