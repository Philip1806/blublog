<button type="button" class="btn btn-primary btn-block btn-sm" data-toggle="modal"
    data-target="#editUser{{ $role->id }}">
    <span class="oi oi-pencil"></span> Edit
</button>
<div class="modal fade" id="editUser{{ $role->id }}" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUser{{ $role->id }}Label">
                    Edit role {{ $role->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ Form::model($role, ['route' => ['blublog.panel.users.roles.update', $role->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}

                {{ Form::label('name', 'Role name') }}
                {{ Form::text('name', null, ['class' => 'form-control']) }}

                {{ Form::label('descr', 'Role description') }}
                {{ Form::text('descr', null, ['class' => 'form-control']) }}
                {{ Form::submit('Edit', ['class' => 'btn btn-primary btn-block mt-4']) }}

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
