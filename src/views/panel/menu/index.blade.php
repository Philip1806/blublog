@extends('blublog::panel.main')

@section('navbar')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
    </ol>
</nav>
@endsection
@section('content')
<div class="card border-primary" style="margin-bottom:20px;">
    <div class="card-header text-white bg-primary "> {{__('panel.add_menu')}}</div>
        <div class="card-body">
            {!! Form::open(['route' =>  ['menu.add_menu_store'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
            {{ Form::label('title', __('panel.title')) }}
            {{ Form::text('title', null, ['class' => 'form-control']) }}

            <p></p>
            {{ Form::submit(__('panel.create'), ['class' => 'btn btn-primary btn-block']) }}
            {!! Form::close() !!}
    </div>
</div>


<div class="card border-primary"">
<div class="card-header text-white bg-primary ">{{__('panel.menu')}}</div>
    <div class="card-body">
        @if (isset($menus[0]->id))
        <ul class="list-group">
            @foreach ($menus as $menu)
            <li class="list-group-item">{{$menu->name}}
                {!! Form::open(['route' => ['menu.destroy_menu', $menu->id], 'method' => 'DELETE']) !!}
                {!! form::submit(__('panel.delete'), ['class' => 'btn btn-danger btn-sm' ]) !!}
                {!! Form::close() !!}
                <a href="{{ route('menu.menu_items', $menu->id) }}" class="badge badge-primary">{{__('panel.show_links')}}</a>
                <a  data-toggle="collapse" href="#addparent-{{$menu->id}}" role="button" aria-expanded="false" aria-controls="addparent-{{$menu->id}}"class="badge badge-dark">{{__('panel.add_link')}}</a>
                <a class="badge badge-warning" data-toggle="collapse" href="#editmenu-{{$menu->id}}" role="button" aria-expanded="false" aria-controls="#editmenu-{{$menu->id}}">{{__('panel.edit')}}</a>
            </li>
            <li class="list-group-item">
                <div class="collapse" id="addparent-{{$menu->id}}">
                    <div class="card border-dark" style="margin-bottom:20px;">
                        <div class="card-header text-white bg-dark">{{__('panel.add_link_to')}} "{{$menu->name}}"</div>
                        <div class="card-body">
                            {!! Form::open(['route' =>  ['menu.add_parent_store'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                            {{ Form::label('title', __('panel.title')) }}
                            {{ Form::text('title', null, ['class' => 'form-control']) }}

                            {{ Form::label('url', __('panel.url')) }}
                            {{ Form::text('url', null, ['class' => 'form-control']) }}

                            {{Form::hidden("menu_id",$menu->id)}}
                            <p></p>
                            {{ Form::submit(__('panel.create'), ['class' => 'btn btn-dark btn-block']) }}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="collapse" id="editmenu-{{$menu->id}}">
                    <div class="card border-warning" style="margin-bottom:20px;">
                        <div class="card-header text-white bg-warning "> {{__('panel.edit')}} {{$menu->name}}</div>
                            <div class="card-body">
                                {{ Form::model($menu, ['route' => ['menu.edit_menu_update', $menu->id ], 'method' => "PUT", 'enctype' => 'multipart/form-data']) }}
                                {{ Form::label('name', __('panel.title')) }}
                                {{ Form::text('name', null, ['class' => 'form-control']) }}
                                {{Form::hidden("menu_id",$menu->id)}}<br>
                                {{ Form::submit(__('panel.edit'), ['class' => 'btn btn-warning btn-block']) }}
                                {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
        @else
            <h2>{{__('panel.no_menu')}} </h2>
        @endif
    </div>
</div>



<script>
















</script>
@endsection
