    {{ Form::label('title', __('blublog.title')) }}
    {{ Form::text('title', null, ['class' => 'form-control']) }}

    {{ Form::label('slug', __('blublog.slug')) }}
    {{ Form::text('slug', null, ['class' => 'form-control']) }}

    {{ Form::label('descr', __('blublog.descr')) }}
    {{ Form::text('descr', null, ['class' => 'form-control']) }}
    <br>
{{ Form::submit($button_title, ['class' => 'btn btn-primary btn-block']) }}
