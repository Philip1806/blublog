@extends('blublog::panel.main')

@section('navbar')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
    </ol>
</nav>
@endsection
@section('content')
<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('panel.edit')}} "{{$item->label}}"</div>
    <div class="card-body text-primary">
        {{ Form::model($item, ['route' => ['menu.edit_item_update', $item->id ], 'method' => "PUT", 'enctype' => 'multipart/form-data']) }}
        {{ Form::label('label', __('panel.title')) }}
        {{ Form::text('label', null, ['class' => 'form-control']) }}
        {{ Form::label('url', __('panel.url')) }}
        {{ Form::text('url', null, ['class' => 'form-control']) }}


        {{Form::hidden("item_id",$item->id)}}
        <br>
        {{ Form::submit(__('panel.edit'), ['class' => 'btn btn-primary btn-block']) }}
        {!! Form::close() !!}

    </div>
</div>


@endsection
