@extends('blublog::panel.main')

@section('content')

<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('panel.edit')}} {{__('panel.users')}}</div>
    <div class="card-body text-primary">

        {{ Form::model($user, ['route' => ['blublog.users.update', $user->id ], 'method' => "PUT", 'enctype' => 'multipart/form-data']) }}
        {{ Form::label('name', 'Потребителско име:') }}
        {{ Form::text('name', null, ['class' => 'form-control']) }}

        {{ Form::label('email', 'Еmail:') }}
        {{ Form::text('email', null, ['class' => 'form-control']) }}

        {{ Form::label('descr', 'Описание:') }}
        {{ Form::text('descr', null, ['class' => 'form-control']) }}

        {{ Form::label('role', 'Роля:') }}
        {{ Form::select('role', ['Author' => 'Author', 'Moderator' => 'Moderator', 'Administrator' => 'Administrator'], null ,['class' => 'form-control']) }}

        {{ Form::label('newpassword', 'Нова парола:') }}
        {{ Form::text('newpassword', null, ['class' => 'form-control']) }}<br>

        {{ Form::label('remember_token	', 'remember_token:') }}
        {{ Form::text('remember_token	', null, ['class' => 'form-control']) }}

        {{ Form::label('created_at', 'Създаден на:') }}
        {{ Form::text('created_at', null, ['class' => 'form-control']) }}

        {{ Form::label('updated_at', 'Последно обновен на:') }}
        {{ Form::text('updated_at', null, ['class' => 'form-control']) }}
        <br>

        {{ Form::submit(__('panel.edit_post'), ['class' => 'btn btn-primary btn-block']) }}
        {!! Form::close() !!}
    </div>
</div>
@endsection
