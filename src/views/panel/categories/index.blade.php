@extends('blublog::panel.main')

@section('content')
<div class="card border-primary shadow">
        <div class="card-header text-white bg-primary">
         {{ __('blublog.add_category') }}
        </div>
        <div class="card-body">
            {!! Form::open(['route' => 'blublog.categories.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                <label for="title">{{__('blublog.title')}}</label>
                @if (!$errors->has('title'))
                <input type="title" name="title" class="form-control" value="{{ old('title')}}">
                @endif
                @if ($errors->has('title'))
                <input type="title" name="title" class="form-control is-invalid" value="{{ old('title')}}">
                <small class="text-danger">{{ $errors->first('title') }}</small>
                @endif
            </div>
            {{ Form::label('descr', __('blublog.descr')) }}
            {{ Form::text('descr', null, ['class' => 'form-control']) }}
            {{ Form::label('pick', __('blublog.colorcode')) }}
            {{ Form::text('pick', null, ['class' => "form-control jscolor  {onFineChange:'update(this)',required:false}" ,'id'=>'colorcode']) }}
            {{Form::hidden("rgb", null, ['id'=>'rgb'])}}
            <br>{{__('blublog.img')}}:
            <input name="file" type="file" id="file"/>
            <br> <p></p>
            {{ Form::submit(__('blublog.create'), ['class' => 'btn btn-primary btn-block']) }}
            {!! Form::close() !!}
        </div>
</div>
<hr>
@if (!empty($categories[0]->id))
<div class="card border-primary shadow">
    <div class="card-header text-white bg-primary">
     {{ __('blublog.all_categories') }}
    </div>
        <table class="table table-hover">
            <thead class="thead-light">
              <tr>
                <th scope="col">{{__('blublog.title')}}</th>
                <th scope="col">{{__('blublog.img')}}</th>
                <th scope="col">{{__('blublog.descr')}}</th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
                @foreach ( $categories as $category )
                <tr>
                    <td><a href="{{ route('blublog.categories.edit', $category->id) }}" >{{ $category->title }}</a> <span class="badge badge-success">{{  $category->posts()->count() }}</span></td>
                    <td>@if ($category->img)   {{ $category->img }}    @else <span class="badge badge-danger">{{__('blublog.none')}}</span> @endif</td>
                    <td>@if ($category->descr) {{ $category->descr }}  @else <span class="badge badge-danger">{{__('blublog.none')}}</span> @endif</td>
                    <td><a href="{{ route('blublog.front.category_show', $category->slug) }}"  role="button" class="btn btn-outline-primary btn-block ">{{__('blublog.view')}}</a></td>
                    <td><a href="{{ route('blublog.categories.edit', $category->id) }}" class="btn btn-outline-warning btn-block">{{__('blublog.edit')}}</a></td>
                    <td>
                    {!! Form::open(['route' => ['blublog.categories.destroy', $category->id], 'method' => 'DELETE']) !!}
                    {!! form::submit(__('blublog.delete'), ['class' => 'btn btn-outline-danger btn-block ' ]) !!}
                    {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
    </table>
</div>
@else
<hr>
<center><b>{{__('blublog.no_categories')}}</b></center>
@endif
<script src="{{ url('/') }}\blublog/js/jscolor.js"></script>
<script>
function update(picker) {
    document.getElementById('rgb').value =picker.toRGBString();
}
</script>
@endsection
