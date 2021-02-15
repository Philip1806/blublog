@extends('blublog::panel.layout.main')

@section('nav')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('blublog.panel.users.index') }}">Users</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blublog.panel.users.roles') }}">Roles</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $role->name }}</li>
        </ol>
    </nav>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-8">
            <livewire:blublog-edit-roles-perm :role="$role">
        </div>
        <div class="col-lg-4">
            {{ Form::model($role, ['route' => ['blublog.panel.users.roles.update', $role->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}

            {{ Form::label('name', 'Role name') }}
            {{ Form::text('name', null, ['class' => 'form-control']) }}

            {{ Form::label('descr', 'Role description') }}
            {{ Form::text('descr', null, ['class' => 'form-control']) }}
            {{ Form::submit('Edit', ['class' => 'btn btn-primary btn-block mt-2']) }}

            {!! Form::close() !!}
        </div>
    </div>

@endsection
