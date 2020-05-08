@extends('blublog::panel.main')

@section('content')
<script src="{{ url('/') }}\blublog/js/jscolor.js"></script>

<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('panel.edit')}} {{__('panel.categories')}}</div>
    <div class="card-body text-primary">
        {{ Form::model($category, ['route' => ['blublog.categories.update', $category->id ], 'method' => "PUT", 'enctype' => 'multipart/form-data']) }}
        {{ Form::label('title', __('panel.title')) }}
        {{ Form::text('title', null, ['class' => 'form-control']) }}

        {{ Form::label('descr', __('panel.descr')) }}
        {{ Form::text('descr', null, ['class' => 'form-control']) }}


        {{ Form::label('colorcode', __('panel.colorcode')) }}
        {{ Form::text('colorcode', null, ['class' => "form-control jscolor  {onFineChange:'update(this)',required:false}" ,'id'=>'colorcode']) }}
        {{Form::hidden("rgb", null, ['id'=>'rgb'])}}

        {{ Form::label('slug', __('panel.slug')) }}
        {{ Form::text('slug', null, ['class' => 'form-control']) }}
        <br>{{__('panel.img')}}:
        <input name="file" type="file" id="file"/>
                        <br> <p></p>
        {{ Form::submit(__('panel.edit'), ['class' => 'btn btn-primary btn-block']) }}
        {!! Form::close() !!}

    </div>
</div>
<script>
    function update(picker) {
        document.getElementById('rgb').value =picker.toRGBString();
    }
</script>
@endsection
