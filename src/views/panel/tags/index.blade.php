@extends('blublog::panel.main')

@section('content')
<div class="card border-primary shadow">
        <div class="card-header text-white bg-primary">
         {{ __('panel.add_tag') }}
        </div>
        <div class="card-body">
                {!! Form::open(['route' => 'blublog.tags.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                                {{ Form::label('title', __('panel.title')) }}
                                {{ Form::text('title', null, ['class' => 'form-control']) }}

                                {{ Form::label('descr', __('panel.descr')) }}
                                {{ Form::text('descr', null, ['class' => 'form-control']) }}
<p></p>
                {{ Form::submit(__('panel.create'), ['class' => 'btn btn-primary btn-block']) }}
                {!! Form::close() !!}
        </div>
</div>



<br>
<div class="card border-primary shadow">
    <div class="card-header text-white bg-primary">
     {{ __('panel.tags') }}
    </div>
    <div class="card-body">
        @if (!empty($tags[0]->id))
        <table class="table table-hover">
                <tbody>
                        @foreach ( $tags as $tag )
                        <tr>
                                <td><a href="{{ route('blublog.tags.edit', $tag->id) }}" >{{ $tag->title }}</a></td>
                                <td><a href=""  role="button" class="btn btn-outline-primary btn-block ">{{__('panel.view')}}</a></td>
                                <td><a href="{{ route('blublog.tags.edit', $tag->id) }}" class="btn btn-outline-warning btn-block">{{__('panel.edit')}}</a></td>
                                <td>
                                {!! Form::open(['route' => ['blublog.tags.destroy', $tag->id], 'method' => 'DELETE']) !!}
                                {!! form::submit(__('panel.delete'), ['class' => 'btn btn-outline-danger btn-block ' ]) !!}
                                {!! Form::close() !!}
                                </td>
                        </tr>
                        @endforeach

                </tbody>
        </table>
        {!! $tags->links(); !!}
        <hr>
        @else
        <hr>
        <center> <b>Няма добавени категории</b> </center>
        @endif
    </div>
</div>


@endsection
