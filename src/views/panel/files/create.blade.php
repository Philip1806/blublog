@extends('blublog::panel.main')

@section('content')
<div class="card">
        <div class="card-header">Качване на файл | Максимален размер (MAX FILESIZE): <b>{{ $filesize }}</b> (зависи от php.ini)</div>

            <div class="card-body">
                {!! Form::open(['route' => 'blublog.files.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                            <p> <b>{{__('panel.short_descr')}}:</b> </p>
                                {{ Form::text('descr', null, ['class' => 'form-control']) }} <br>

                                <input type="file" name="file" />
                                <p></p>
                                <p> {{ Form::checkbox('public', true, true) }}
                                        {{ Form::label('public', 'Публично') }} </p>
                {{ Form::submit('ЗАПИС', ['class' => 'btn btn-info btn-block']) }}
                {!! Form::close() !!}

            </div>
        </div>
@endsection
