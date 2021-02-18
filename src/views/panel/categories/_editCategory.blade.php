{!! Form::open(['route' => ['blublog.panel.categories.destroy', $category->id], 'method' => 'DELETE']) !!}
<div class="btn-group btn-group-sm" role="group">
    @can('blublog_edit_categories')
        <button type="button" class="btn btn-primary" data-toggle="modal"
            data-target="#editCategory{{ $category->id }}"><span class="oi oi-pencil"></span> Edit</button>
    @endcan
    @can('blublog_delete_categories')
        <button type="submit" class="btn btn-danger"><span class="oi oi-circle-x"></span> Delete</button>
    @endcan
</div>
{!! Form::close() !!}
@can('blublog_edit_categories')
    <div class="modal fade text-dark" id="editCategory{{ $category->id }}" tabindex="-1"
        aria-labelledby="editCategory{{ $category->id }}Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {{ Form::model($category, ['route' => ['blublog.panel.categories.update', $category->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategory{{ $category->id }}Label">Edit {{ $category->title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ Form::label('title', 'Category name') }}
                    {{ Form::text('title', null, ['class' => 'form-control', 'id' => 'title' . $category->id]) }}

                    {{ Form::label('descr', 'Description') }}
                    {{ Form::text('descr', null, ['class' => 'form-control', 'id' => 'descr' . $category->id]) }}

                    {{ Form::label('img', 'Image URL') }}
                    {{ Form::text('img', null, ['class' => 'form-control', 'id' => 'img' . $category->id]) }}

                    {{ Form::label('slug', 'Slug') }}
                    {{ Form::text('slug', null, ['class' => 'form-control', 'id' => 'slug' . $category->id]) }}

                    {{ Form::label('parent_id', 'Parent category:') }}
                    {{ Form::select('parent_id', $all_categories, null, ['class' => 'form-control', 'id' => 'parent_id' . $category->id]) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    {{ Form::submit('Save changes', ['class' => 'btn btn-primary']) }}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endcan
