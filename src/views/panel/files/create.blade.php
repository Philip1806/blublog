@extends('blublog::panel.main')

@section('content')
<div class="card border-primary shadow">
        <div class="card-header text-white bg-primary">{{__('panel.uploading_file')}} | {{__('panel.max_size')}} <b>{{ $filesize }}</b> ({{__('panel.php_ini')}})</div>

        <div class="card-body">
            {!! Form::open(['route' => 'blublog.files.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

            <p> <b>{{__('panel.descr')}}:</b> </p>
            {{ Form::text('descr', null, ['class' => 'form-control']) }} <br>

            <input type="file" name="file" />
            <p></p>
            <p> {{ Form::checkbox('public', true, true) }}
            {{ Form::label('public', __('panel.public')) }} </p>

            {{ Form::submit('ЗАПИС', ['class' => 'btn btn-info btn-block']) }}
            {!! Form::close() !!}
        </div>
</div>
@endsection
