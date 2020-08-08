
{{ Form::label('title', __('blublog.title')) }}
{{ Form::text('title', null, ['class' => 'form-control']) }}

{{ Form::label('content', __('blublog.content')) }}
{{ Form::textarea('content', null, ['class' => 'form-control', 'id' => 'editor','rows' => '10']) }}
<hr>
{{ Form::label('descr',  __('blublog.seodescr')) }}
{{ Form::text('descr', null, ['class' => 'form-control']) }}


{{ Form::label('slug',  __('blublog.slug')) }}
{{ Form::text('slug', null, ['class' => 'form-control']) }}

{{ Form::label('img', __('blublog.img')) }}
{{ Form::text('img', null, ['class' => 'form-control']) }}

{{ Form::label('tags', __('blublog.tags')) }}
{{ Form::text('tags', null, ['class' => 'form-control']) }}
<hr>
<p> {{ Form::checkbox('public', true) }}
{{ Form::label('public', __('blublog.public')) }} </p>
<p> {{ Form::checkbox('sidebar', true) }}
{{ Form::label('sidebar', __('blublog.show_sidebar')) }} </p>
{{ Form::submit($button_title, ['class' => 'btn btn-primary btn-block']) }}
