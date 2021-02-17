<div class="row">
    <div class="col-lg-9">
        <table class="table">
            <thead class="thead thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($comments as $comment)
                    <tr>
                        <th>{{ $comment->name }}</th>
                        <td>{{ $comment->email }}</td>
                        <td><a data-toggle="modal" data-target="#editComment{{ $comment->id }}"
                                class="btn btn-primary btn-sm"><span class="oi oi-eye"></span> Details</a>
                        </td>
                        @can('blublog_approve_comments', $comment)
                            <td><a wire:click="togglePublic('{{ $comment->id }}')"
                                    class="btn btn-{{ $comment->public ? 'success' : 'danger' }} btn-sm" role="button"
                                    aria-pressed="true">
                                    {!! $comment->public ? '<span class="oi oi-thumb-down"></span> Hide' : '<span
                                        class="oi oi-thumb-up"></span> Approve' !!}</a>
                            </td>
                        @endcan
                        @can('blublog_delete_comments', $comment)
                            <td><a wire:click="delete('{{ $comment->id }}')" class="btn btn-danger btn-sm" role="button"
                                    aria-pressed="true"><span class="oi oi-circle-x"></span> Delete</a>
                            </td>
                        @endcan
                    </tr>
                    <div class="modal fade" id="editComment{{ $comment->id }}" tabindex="-1"
                        aria-labelledby="editComment{{ $comment->id }}Label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editComment{{ $comment->id }}Label">Comment by
                                        {{ $comment->name }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    {{ Form::model($comment, ['route' => ['blublog.panel.comments.update', $comment->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                                    <p> {{ Form::checkbox('public', true) }}
                                        {{ Form::label('public', 'Public') }} </p>

                                    {{ Form::label('name', __('blublog.name')) }}
                                    {{ Form::text('name', null, ['class' => 'form-control']) }}

                                    {{ Form::label('email', 'Email') }}
                                    {{ Form::text('email', null, ['class' => 'form-control']) }}

                                    {{ Form::label('body', 'Comment') }}
                                    {{ Form::textarea('body', null, ['class' => 'form-control', 'rows' => '5']) }}

                                </div>
                                @can('blublog_edit_comments', $comment)
                                    <div class="modal-footer">
                                        {{ Form::submit('Save changes', ['class' => 'btn btn-primary']) }}
                                    </div>
                                @endcan

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>

                @empty
                    <tr>
                        <th>No comments.</th>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $comments->links() }}
    </div>
    <div class="col-lg-3">
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <div class="input-group-text"><span class="oi oi-magnifying-glass"></span></div>
            </div>
            <input wire:model="search" type="search" class="form-control" id="inlineFormInputGroup"
                placeholder="Search...">
        </div>
    </div>
</div>
