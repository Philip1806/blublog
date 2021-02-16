<div>
    {{ Form::model($user, ['route' => ['blublog.panel.users.update', $user->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}

    {{ Form::label('name', 'Role name') }}
    {{ Form::text('name', null, ['class' => 'form-control']) }}

    {{ Form::label('email', 'Email') }}
    {{ Form::text('email', null, ['class' => 'form-control']) }}

    {{ Form::label('new_password', 'New password') }}
    {{ Form::password('new_password', ['class' => 'form-control']) }}

    @if (isset($all_roles))
        {{ Form::label('role_id', 'Role:') }}
        {{ Form::select('role_id', $all_roles, null, ['class' => 'form-control']) }}

    @endif

    {{ Form::submit('Edit', ['class' => 'btn btn-primary btn-block mt-2']) }}

    {!! Form::close() !!}
</div>
