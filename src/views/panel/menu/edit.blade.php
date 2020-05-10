@extends('blublog::panel.main')

@section('content')
<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('blublog.edit')}} "{{$item->label}}"</div>
    <div class="card-body text-primary">
        {{ Form::model($item, ['route' => ['menu.edit_item_update', $item->id ], 'method' => "PUT", 'enctype' => 'multipart/form-data']) }}
        {{ Form::label('label', __('blublog.title')) }}
        {{ Form::text('label', null, ['class' => 'form-control']) }}
        {{ Form::label('url', __('blublog.url')) }}
        {{ Form::text('url', null, ['class' => 'form-control']) }}

        {{Form::hidden("item_id",$item->id)}}
        <br>
        {{ Form::submit(__('blublog.edit'), ['class' => 'btn btn-primary btn-block']) }}
        {!! Form::close() !!}

    </div>
</div>
@endsection
