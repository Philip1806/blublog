
{!! Form::open(['route' => 'blublog.roles.update', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
<div class="container">
    @if ($role_id == "new")
    {{ Form::label('name', 'Role name') }}
    {{ Form::text("name", null, ['class' => 'form-control']) }}
    {{ Form::label("descr", 'Role description') }}
    {{ Form::text("descr", null, ['class' => 'form-control']) }}<br>
    @endif
    <div class="row">
      <div class="col-sm">
        @include('blublog::panel.settings._permission_card', ['permissions' => $role->posts, 'title'=>__('blublog.posts')])
      </div>
      <div class="col-sm">
        @include('blublog::panel.settings._permission_card', ['permissions' => $role->comments, 'title'=>__('blublog.comments')])
      </div>
    </div>
    <div class="row mt-2">
        <div class="col-sm">
            @include('blublog::panel.settings._permission_card', ['permissions' => $role->tags_cat_pages, 'title'=>__('blublog.tags') . ', ' . __('blublog.categories') . ', ' . __('blublog.pages')])
        </div>
        <div class="col-sm">
            @include('blublog::panel.settings._permission_card', ['permissions' => $role->others, 'title'=>__('blublog.others')])
        </div>
      </div>
</div>
{{Form::hidden("role_id",$role_id)}}
@if ($role_id == "new")
{{ Form::submit(__('blublog.create'), ['class' => 'mt-2 btn btn-info btn-block']) }}
@else
{{ Form::submit(__('blublog.save'), ['class' => 'mt-2 btn btn-info btn-block']) }}
@endif
{!! Form::close() !!}
