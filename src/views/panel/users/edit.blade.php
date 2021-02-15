@extends('blublog::panel.main')

@section('content')
    <div class="card border-primary">
        <div class="card-header text-white bg-primary">{{ __('blublog.edit') }} {{ __('blublog.users') }}</div>
        <div class="card-body text-primary">

            {{ Form::model($user, ['route' => ['blublog.users.update', $user->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
            {{ Form::label('name', __('blublog.name')) }}
            {{ Form::text('name', null, ['class' => 'form-control']) }}

            {{ Form::label('full_name', __('blublog.full_name')) }}
            {{ Form::text('full_name', null, ['class' => 'form-control']) }}

            {{ Form::label('descr', __('blublog.descr')) }}
            {{ Form::text('descr', null, ['class' => 'form-control']) }}

            {{ Form::label('img_url', __('blublog.img_url')) }}
            {{ Form::text('img_url', null, ['class' => 'form-control']) }}

            @if (blublog_is_admin())
                {{ Form::label('email', 'Еmail:') }}
                {{ Form::text('email', null, ['class' => 'form-control']) }}
                {{ Form::label('role_id', 'Роля:') }}
                {{ Form::select('role_id', $user->all_roles, null, ['class' => 'form-control']) }}
            @endif

            {{ Form::label('newpassword', 'Нова парола:') }}
            {{ Form::text('newpassword', null, ['class' => 'form-control']) }}<br>


            <br>

            {{ Form::submit(__('blublog.edit_post'), ['class' => 'btn btn-primary btn-block']) }}
            {!! Form::close() !!}
            @if (blublog_is_admin())
                @foreach ($user->latest_actions as $action)
                    <hr>
                    <div class="alert alert-info" role="alert">
                        {{ $action->message }} ({{ $action->request_url }}) ({{ $action->ip }})
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
