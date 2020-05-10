@extends('blublog::panel.main')

@section('content')

<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('panel.edit')}} {{__('panel.page')}}</div>
    <div class="card-body text-primary">

        {{ Form::model($post, ['route' => ['blublog.pages.update', $post->id ], 'method' => "PUT", 'enctype' => 'multipart/form-data']) }}
        @include('blublog::panel.pages._form', ['button_title' => __('panel.edit')])
        {!! Form::close() !!}

    </div>
</div>



@endsection
