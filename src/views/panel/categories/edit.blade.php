@extends('blublog::panel.main')

@section('content')
<script src="{{ url('/') }}\blublog/js/jscolor.js"></script>

<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('blublog.edit')}} {{__('blublog.categories')}}</div>
    <div class="card-body text-primary">
        {{ Form::model($category, ['route' => ['blublog.categories.update', $category->id ], 'method' => "PUT", 'enctype' => 'multipart/form-data']) }}
        {{ Form::label('title', __('blublog.title')) }}
        {{ Form::text('title', null, ['class' => 'form-control']) }}

        {{ Form::label('descr', __('blublog.descr')) }}
        {{ Form::text('descr', null, ['class' => 'form-control']) }}


        {{ Form::label('colorcode', __('blublog.colorcode')) }}
        {{ Form::text('colorcode', null, ['class' => "form-control jscolor  {onFineChange:'update(this)',required:false}" ,'id'=>'colorcode']) }}
        {{Form::hidden("rgb", null, ['id'=>'rgb'])}}

        {{ Form::label('slug', __('blublog.slug')) }}
        {{ Form::text('slug', null, ['class' => 'form-control']) }}
        <br>{{__('blublog.img')}}:
        <input name="file" type="file" id="file"/>
                        <br> <p></p>
        {{ Form::submit(__('blublog.edit'), ['class' => 'btn btn-primary btn-block']) }}
        {!! Form::close() !!}

    </div>
</div>
<script>
    function update(picker) {
        document.getElementById('rgb').value =picker.toRGBString();
    }
</script>
@endsection
