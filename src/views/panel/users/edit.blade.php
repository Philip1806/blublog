@extends('blublog::panel.main')

@section('content')

<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('blublog.edit')}} {{__('blublog.users')}}</div>
    <div class="card-body text-primary">

        {{ Form::model($user, ['route' => ['blublog.users.update', $user->id ], 'method' => "PUT", 'enctype' => 'multipart/form-data']) }}
        {{ Form::label('name', 'Потребителско име:') }}
        {{ Form::text('name', null, ['class' => 'form-control']) }}

        {{ Form::label('email', 'Еmail:') }}
        {{ Form::text('email', null, ['class' => 'form-control']) }}

        {{ Form::label('descr', 'Описание:') }}
        {{ Form::text('descr', null, ['class' => 'form-control']) }}

        {{ Form::label('role_id', 'Роля:') }}
        {{ Form::select('role_id', $user->all_roles, null ,['class' => 'form-control']) }}

        {{ Form::label('newpassword', 'Нова парола:') }}
        {{ Form::text('newpassword', null, ['class' => 'form-control']) }}<br>

        {{ Form::label('remember_token	', 'remember_token:') }}
        {{ Form::text('remember_token	', null, ['class' => 'form-control']) }}

        {{ Form::label('created_at', 'Създаден на:') }}
        {{ Form::text('created_at', null, ['class' => 'form-control']) }}

        {{ Form::label('updated_at', 'Последно обновен на:') }}
        {{ Form::text('updated_at', null, ['class' => 'form-control']) }}
        <br>

        {{ Form::submit(__('blublog.edit_post'), ['class' => 'btn btn-primary btn-block']) }}
        {!! Form::close() !!}

        <hr>
        @foreach ($user->latest_actions as $action)
        <div class="alert alert-info" role="alert">
            {{$action->message}} ({{$action->request_url}}) ({{$action->ip}})
        </div>
        @endforeach

    </div>
</div>
@endsection
