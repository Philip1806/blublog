    {{ Form::label('title', __('panel.title')) }}
    {{ Form::text('title', null, ['class' => 'form-control']) }}

    {{ Form::label('slug', __('panel.slug')) }}
    {{ Form::text('slug', null, ['class' => 'form-control']) }}

    {{ Form::label('descr', __('panel.descr')) }}
    {{ Form::text('descr', null, ['class' => 'form-control']) }}
    <br>
{{ Form::submit($button_title, ['class' => 'btn btn-primary btn-block']) }}
