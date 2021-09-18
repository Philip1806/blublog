<div class="row">
    <div class="col-lg-8">
        @if ($tags->count())
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Posts</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tags as $tag)
                        <tr>
                            <th>{{ $tag->title }}</th>
                            <th>{{ $tag->posts->count() }}</th>
                            @can('blublog_edit_tags', $tag)
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal"
                                        data-target="#edituser{{ $tag->id }}">
                                        <span class="oi oi-pencil"></span> Edit
                                    </button>
                                    <div class="modal fade" id="edituser{{ $tag->id }}" tabindex="-1"
                                        aria-labelledby="edituser{{ $tag->id }}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="edituser{{ $tag->id }}Label">Edit
                                                        {{ $tag->name }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ Form::model($tag, ['route' => ['blublog.panel.tags.update', $tag->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}

                                                    {{ Form::label('title', 'Tag name') }}
                                                    {{ Form::text('title', null, ['class' => 'form-control']) }}

                                                    {{ Form::label('slug', 'Slug') }}
                                                    {{ Form::text('slug', null, ['class' => 'form-control']) }}

                                                    {{ Form::label('img', 'Image URL') }}
                                                    {{ Form::text('img', null, ['class' => 'form-control']) }}

                                                    {{ Form::submit('Edit', ['class' => 'btn btn-primary btn-block mt-2']) }}

                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            @endcan
                            @can('blublog_delete_tags', $tag)
                                <td>
                                    <button wire:click="delete('{{ $tag->id }}')" class="btn btn-danger btn-sm btn-block">
                                        <span class="oi oi-circle-x"></span> Delete</button>
                                </td>
                            @endcan
                        </tr>
            @endforeach

            </tbody>
            </table>
            {{ $tags->links() }}
        @else
            <div class="alert alert-info" role="alert">
                No found tags.
            </div>
            @endif
        </div>
        <div class="col-lg-4">
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text"><span class="oi oi-magnifying-glass"></span></div>
                </div>
                <input wire:model="search" type="search" class="form-control" id="inlineFormInputGroup"
                    placeholder="Search for tag...">

            </div>

            @can('blublog_create_tags')
                <div class="card">
                    <div class="card-body border border-dark">
                        <form wire:submit.prevent="createTag" action="/" method="POST">
                            <div class="form-group">
                                <label for="tagTitle">{{ __('Name') }}</label>

                                <input wire:model="tagTitle" id="tagTitle" type="text"
                                    class="form-control @error('tagTitle') is-invalid @enderror" name="tagTitle"
                                    value="{{ old('tagTitle') }}" autocomplete="tagTitle" autofocus>

                                @error('tagTitle')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            {{ Form::label('tagSlug', 'Slug') }}
                            <input wire:model="tagSlug" type="text" class="form-control">
                            {{ Form::label('tagImg', 'Image URL') }}
                            <input wire:model="tagImg" type="text" class="form-control">

                            <button class="btn btn-primary btn-sm btn-block my-2">
                                Create</button>
                        </form>
                    </div>
                </div>
            @endcan

        </div>
    </div>
