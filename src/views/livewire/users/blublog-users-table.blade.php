<div class="row">
    <div class="col-lg-9">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Role</th>
                    <th scope="col">Email</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <th>{{ $user->name }}</th>
                        <th>
                            @if (isset($user->blublogRoles->first()->name))
                                {{ $user->blublogRoles->first()->name }}
                            @else
                                <div class="alert alert-info" role="alert">
                                    User don't have blog role. They can't use the blog panel.
                                </div>
                            @endif
                        </th>
                        <th>{{ $user->email }}</th>
                        <td>
                            @can('blublog_edit_users', $user)
                                <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal"
                                    data-target="#edituser{{ $user->id }}">
                                    <span class="oi oi-pencil"></span> Edit
                                </button>
                                <div class="modal fade" id="edituser{{ $user->id }}" tabindex="-1"
                                    aria-labelledby="edituser{{ $user->id }}Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="edituser{{ $user->id }}Label">Edit
                                                    {{ $user->name }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                @include('blublog::panel.users._editUser')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                        </td>
                        <td>
                            @can('blublog_delete_users')
                                @if (Auth::user()->id != $user->id)

                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        @if (blublog_is_admin())
                                            @if (isset($user->blublogRoles->first()->name))
                                                <button wire:click="banFromBlog('{{ $user->id }}')" class="btn btn-warning">
                                                    <span class="oi oi-ban"></span> Remove Access
                                                </button>
                                            @endif
                                        @endif
                                        <button wire:click="removeUser('{{ $user->id }}')" class="btn btn-danger">
                                            <span class="oi oi-circle-x"></span> DELETE</button>
                                    </div>

                                @endif
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <th>Nothing fould.</th>
                    </tr>
                @endforelse

            </tbody>
        </table>
        {{ $users->links() }}
    </div>
    <div class="col-lg-3">
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <div class="input-group-text"><span class="oi oi-magnifying-glass"></span></div>
            </div>
            <input wire:model="search" type="search" class="form-control" id="inlineFormInputGroup"
                placeholder="Search for user...">

        </div>
        @can('blublog_create_users')
            <div class="card border-dark">
                <div class="card-body">
                    {!! Form::open(['route' => 'blublog.panel.users.store', 'method' => 'POST', 'enctype' =>
                    'multipart/form-data']) !!}

                    {{ Form::label('name', 'Username') }}
                    {{ Form::text('name', null, ['class' => 'form-control']) }}

                    {{ Form::label('role_id', 'Role:') }}
                    {{ Form::select('role_id', $all_roles, null, ['class' => 'form-control']) }}

                    {{ Form::label('email', 'Email') }}
                    {{ Form::text('email', null, ['class' => 'form-control']) }}

                    {{ Form::label('password', 'Password') }}
                    {{ Form::text('password', null, ['class' => 'form-control']) }}

                    {{ Form::submit('Create User', ['class' => 'btn btn-primary btn-block my-2']) }}
                    {!! Form::close() !!}
                </div>
            </div>
        @endcan

    </div>
</div>
