
{{ Form::label('title', __('panel.title')) }}
{{ Form::text('title', null, ['class' => 'form-control']) }}

{{ Form::label('content', __('panel.content')) }}
{{ Form::textarea('content', null, ['class' => 'form-control', 'rows' => '10']) }}
<hr>
{{ Form::label('descr',  __('panel.seodescr')) }}
{{ Form::text('descr', null, ['class' => 'form-control']) }}


{{ Form::label('slug',  __('panel.slug')) }}
{{ Form::text('slug', null, ['class' => 'form-control']) }}

{{ Form::label('img', __('panel.img')) }}
{{ Form::text('img', null, ['class' => 'form-control']) }}

{{ Form::label('tags', __('panel.tags')) }}
{{ Form::text('tags', null, ['class' => 'form-control']) }}
<hr>
<p> {{ Form::checkbox('public', true) }}
{{ Form::label('public', __('panel.public')) }} </p>
<p> {{ Form::checkbox('sidebar', true) }}
{{ Form::label('sidebar', __('panel.show_sidebar')) }} </p>
{{ Form::submit($button_title, ['class' => 'btn btn-primary btn-block']) }}
