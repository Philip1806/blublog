@extends('blublog::panel.main')

@section('navbar')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
    </ol>
</nav>
@endsection
@section('content')

<div class="collapse" id="additem-{{$menu->id}}">
    <div class="card border-primary" style="margin-bottom:20px;">
        <div class="card-header text-white bg-primary">{{__('panel.add_link_to')}} "{{$menu->name}}"</div>
        <div class="card-body">
            {!! Form::open(['route' =>  ['menu.add_parent_store'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
            {{ Form::label('title', __('panel.title')) }}
            {{ Form::text('title', null, ['class' => 'form-control']) }}

            {{ Form::label('url', __('panel.url')) }}
            {{ Form::text('url', null, ['class' => 'form-control']) }}

            {{Form::hidden("menu_id",$menu->id)}}
            <p></p>
            {{ Form::submit(__('panel.create'), ['class' => 'btn btn-primary btn-block']) }}
            {!! Form::close() !!}
        </div>
    </div>
</div>


<div class="card border-primary">
<div class="card-header text-white bg-primary ">Items of "{{$menu->name}}"
    <a href="" class="badge badge-warning">Edit</a>
    <a  data-toggle="collapse" href="#additem-{{$menu->id}}" role="button" aria-expanded="false" aria-controls="additem-{{$menu->id}}"class="badge badge-dark">{{__('panel.add_link')}}</a>
</div>
    <div class="card-body">
        @if (isset($menu->items[0]->id))
        <ul class="list-group">
            @foreach ($menu->items as $item)
            <li class="list-group-item">{{$item->label}}
                {!! Form::open(['route' => ['menu.destroy_item', $item->id], 'method' => 'DELETE']) !!}
                {!! form::submit(__('panel.delete'), ['class' => 'btn btn-sm btn-outline-danger btn-block ' ]) !!}
                {!! Form::close() !!}
                <a href="{{ route('menu.edit_item', $item->id) }}" class="badge badge-warning">Edit</a>
                @if ($item->parent == 0)
                <a class="badge badge-primary" data-toggle="collapse" href="#addchild-{{$item->id}}" role="button" aria-expanded="false" aria-controls="#addchild-{{$item->id}}">{{__('panel.add_child_link')}}</a>
                <div class="collapse" id="addchild-{{$item->id}}">
                    <div class="card card-body bg-primary text-white">
                        {!! Form::open(['route' =>  ['menu.add_child_store'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                        {{ Form::label('title', __('panel.title')) }}
                        {{ Form::text('title', null, ['class' => 'form-control']) }}

                        {{ Form::label('url', __('panel.url')) }}
                        {{ Form::text('url', null, ['class' => 'form-control']) }}
                        {{Form::hidden("menu_id",$menu->id)}}
                        {{Form::hidden("parent_id",$item->id)}}
                        <p></p>
                        {{ Form::submit(__('panel.add'), ['class' => 'btn btn-light btn-block']) }}
                        {!! Form::close() !!}
                    </div>
                </div>
                @endif
            </li>
            @endforeach
        </ul>
        @else
            <h1>{{__('panel.no_links')}} </h1><a  data-toggle="collapse" href="#additem-{{$menu->id}}" role="button" aria-expanded="false" aria-controls="additem-{{$menu->id}}"class="badge badge-primary">{{__('panel.add_link')}}</a>
        @endif
    </div>
</div>
@endsection
