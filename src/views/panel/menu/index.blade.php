@extends('blublog::panel.main')

@section('content')
<div class="card border-primary" style="margin-bottom:20px;">
    <div class="card-header text-white bg-primary "> {{__('blublog.add_menu')}}</div>
    <div class="card-body">
        {!! Form::open(['route' =>  ['blublog.menu.add_menu_store'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        {{ Form::label('title', __('blublog.title')) }}
        {{ Form::text('title', null, ['class' => 'form-control']) }}

        <p></p>
        {{ Form::submit(__('blublog.create'), ['class' => 'btn btn-primary btn-block']) }}
        {!! Form::close() !!}
    </div>
</div>

<div class="card border-primary"">
    <div class="card-header text-white bg-primary ">{{__('blublog.menu')}}</div>
    <div class="card-body">
        @if (isset($menus[0]->id))
        <ul class="list-group">
            @foreach ($menus as $menu)
            @if ($menu->name == blublog_setting('main_menu_name'))
            <li class="list-group-item bg-secondary text-white">
            @else
            <li class="list-group-item">
            @endif
            {{$menu->name}}
                {!! Form::open(['route' => ['blublog.menu.destroy_menu', $menu->id], 'method' => 'DELETE']) !!}
                {!! form::submit(__('blublog.delete'), ['class' => 'btn btn-danger btn-sm' ]) !!}
                {!! Form::close() !!}
                <a href="{{ route('blublog.menu.menu_items', $menu->id) }}" class="badge badge-primary">{{__('blublog.show_links')}}</a>
                <a  data-toggle="collapse" href="#addparent-{{$menu->id}}" role="button" aria-expanded="false" aria-controls="addparent-{{$menu->id}}"class="badge badge-dark">{{__('blublog.add_link')}}</a>
                <a class="badge badge-warning" data-toggle="collapse" href="#editmenu-{{$menu->id}}" role="button" aria-expanded="false" aria-controls="#editmenu-{{$menu->id}}">{{__('blublog.edit')}}</a>
                <a href="{{ route('blublog.menu.set_main_menu', $menu->id) }}" class="badge badge-info">{{__('blublog.set_main_menu')}}</a>
            </li>
            <li class="list-group-item">
                <div class="collapse" id="addparent-{{$menu->id}}">
                    <div class="card border-dark" style="margin-bottom:20px;">
                        <div class="card-header text-white bg-dark">{{__('blublog.add_link_to')}} "{{$menu->name}}"</div>
                        <div class="card-body">
                            {!! Form::open(['route' =>  ['blublog.menu.add_parent_store'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                            {{ Form::label('title', __('blublog.title')) }}
                            {{ Form::text('title', null, ['class' => 'form-control']) }}

                            {{ Form::label('url', __('blublog.url')) }}
                            {{ Form::text('url', null, ['class' => 'form-control']) }}

                            {{Form::hidden("menu_id",$menu->id)}}
                            <p></p>
                            {{ Form::submit(__('blublog.create'), ['class' => 'btn btn-dark btn-block']) }}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="collapse" id="editmenu-{{$menu->id}}">
                    <div class="card border-warning" style="margin-bottom:20px;">
                        <div class="card-header text-white bg-warning "> {{__('blublog.edit')}} {{$menu->name}}</div>
                        <div class="card-body">
                            {{ Form::model($menu, ['route' => ['blublog.menu.edit_menu_update', $menu->id ], 'method' => "PUT", 'enctype' => 'multipart/form-data']) }}
                            {{ Form::label('name', __('blublog.title')) }}
                            {{ Form::text('name', null, ['class' => 'form-control']) }}
                            {{Form::hidden("menu_id",$menu->id)}}<br>
                            {{ Form::submit(__('blublog.edit'), ['class' => 'btn btn-warning btn-block']) }}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
        @else
            <h2>{{__('blublog.no_menu')}} </h2>
        @endif
    </div>
</div>
@endsection
