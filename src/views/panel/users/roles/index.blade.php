@extends('blublog::panel.layout.main')

@section('nav')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('blublog.panel.users.index') }}">Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">Roles</li>
        </ol>
    </nav>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-8">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Role Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Permissions</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <th>{{ $role->name }}</th>
                            <th>{{ $role->descr }}</th>
                            @if ($role->id != 1)
                                <td>
                                    <button type="button" class="btn btn-info btn-block btn-sm" data-toggle="modal"
                                        data-target="#showPermissionsfor{{ $role->id }}">
                                        <span class="oi oi-pencil"></span> Change
                                    </button>

                                    <div class="modal fade" id="showPermissionsfor{{ $role->id }}" data-keyboard="false"
                                        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="showPermissionsfor{{ $role->id }}Label">
                                                        Permissions for {{ $role->name }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <livewire:blublog-edit-roles-perm :role="$role">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('blublog.panel.users.roles.edit', $role->id) }}"
                                        class="btn btn-primary btn-sm btn-block" role="button" aria-pressed="true"><span
                                            class="oi oi-pencil"></span> Edit</a>
                                </td>
                                <td>
                                    {!! Form::open(['route' => ['blublog.panel.users.roles.destroy', $role->id], 'method' =>
                                    'DELETE']) !!}
                                    {!! form::submit('DELETE', ['class' => 'btn btn-danger btn-sm btn-block']) !!}
                                    {!! Form::close() !!}
                                </td>
                            @endif
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <div class="card border border-dark">
                    <div class="card-body">
                        <h3>Create new role</h3>
                        {!! Form::open(['route' => 'blublog.panel.users.roles.store', 'method' => 'POST', 'enctype' =>
                        'multipart/form-data']) !!}

                        {{ Form::label('name', 'Role name') }}
                        {{ Form::text('name', null, ['class' => 'form-control']) }}

                        {{ Form::label('descr', 'Role description') }}
                        {{ Form::text('descr', null, ['class' => 'form-control']) }}

                        <button type="button" class="btn btn-info btn-block my-2" data-toggle="modal"
                            data-target="#editpermissions">
                            <span class="oi oi-task"></span> Assign Permissions
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="editpermissions" tabindex="-1" aria-labelledby="editpermissionsLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editpermissions">Assign Permission To New Role</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @foreach ($roles[0]->permissionsBySections() as $sections)
                                            @if ($sections)
                                                <div class="card my-2">
                                                    <div class="card-body">
                                                        @foreach ($sections as $permission)
                                                            <span
                                                                class="badge badge-primary">{{ Form::checkbox($permission->permission, true) }}
                                                                {{ $permission->permission_descr }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::submit('Create', ['class' => 'btn btn-primary btn-block']) }}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    @endsection
