@extends('blublog::panel.main')

@section('navbar')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/panel') }}">{{ __('blublog.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/panel/posts') }}">{{ __('blublog.posts') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{__('blublog.add_post')}}</li>
    </ol>
</nav>
@endsection

@section('content')
<div id = "alert_placeholder"></div>
{!! Form::open(['route' => 'blublog.posts.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
<div class="modal fade bd-example-modal-lg"  id="imgModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="form-group">
              <div class="card-body">
                  <label for="exampleFormControlFile1">{{__('blublog.file_input')}}</label>
                  <input type="file"  name="file"   id="file" class="form-control-file" id="exampleFormControlFile1">
                  <hr>
                  <input type="text" class="form-control" id="searchfor" placeholder="Search for file here">
                  <br><input type="button" class="btn btn-info " onclick="searchforfile()" value="Search">
                  <hr>
                  <p id="infopanel">{{__('blublog.latest_img')}}</p>
                  <div id="gallery" class="row text-center text-lg-left"></div>
              </div>
          </div>
      </div>
    </div>
</div>

<div class="card border-danger" style="margin-bottom:20px;">
    <div class="card-header  text-white bg-danger">{{__('blublog.title')}}</div>
    <div class="card-body text-primary">
        {{ Form::text('title', null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="row">
        <div class="col-xl-9">

            @include('blublog::panel.posts._content')

            <div class="card border-danger" style="margin-top:10px;">
                <div class="card-header  text-white bg-danger">{{__('blublog.categories')}}</div>
                <div class="card-body text-primary">
                    <select  id="select2-multi" class="form-control select2-multi" name="categories[]" multiple="multiple" >
                        @foreach($categories as $category)
                        <option value='{{ $category->id }}'> {{ $category->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="card border-primary" style="margin-top:10px;">
                <div class="card-header  text-white bg-primary">{{__('blublog.tags')}}</div>
                <div class="card-body text-primary">
                    <select  id="select3-multi" class="form-control select2-multi" name="tags[]" multiple="multiple" >
                        @foreach($tags as $tag)
                        <option value='{{ $tag->id }}'> {{ $tag->title }}</option>
                            @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            @include('blublog::panel.posts._action', ['button_title' => __('blublog.add_post')])
            <div class="card border-primary" style="margin-top:10px;">
                <div class="card-header  text-white bg-primary">{{__('blublog.settings')}}</div>
                <div class="card-body text-primary">
                    {{Form::checkbox('comments', null, true)}} {{__('blublog.allow_comments')}}<br>
                    {{Form::checkbox('slider', null)}} {{__('blublog.slider')}}<br>
                    {{Form::checkbox('front', null)}} {{__('blublog.front_page')}}<br>
                    {{Form::checkbox('recommended', null)}} {{__('blublog.recommended')}}
                </div>
            </div>
            @include('blublog::panel.partials._maintag')
        </div>
</div>
@include('blublog::panel.partials._seoanddescr')
{!! Form::close() !!}

@include('blublog::panel.partials._postjs')
<script>
$(".select2-multi").select2();
$(".select3-multi").select2();
</script>

@endsection
