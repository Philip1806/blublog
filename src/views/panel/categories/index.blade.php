@extends('blublog::panel.main')

@section('content')
<div class="card border-primary shadow">
        <div class="card-header text-white bg-primary">
         {{ __('panel.add_category') }}
        </div>
        <div class="card-body">
                {!! Form::open(['route' => 'blublog.categories.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="title">{{__('panel.title')}}</label>
                                @if (!$errors->has('title'))
                                <input type="title" name="title" class="form-control" value="{{ old('title')}}">
                                @endif
                                @if ($errors->has('title'))
                                <input type="title" name="title" class="form-control is-invalid" value="{{ old('title')}}">
                                <small class="text-danger">{{ $errors->first('title') }}</small>
                                @endif
                </div>
                                {{ Form::label('descr', __('panel.descr')) }}
                                {{ Form::text('descr', null, ['class' => 'form-control']) }}
                                <br>{{__('panel.img')}}:
                                <input name="file" type="file" id="file"/>
                <br> <p></p>
                {{ Form::submit(__('panel.create'), ['class' => 'btn btn-primary btn-block']) }}
                {!! Form::close() !!}
        </div>

</div>
<hr>
@if (!empty($categories[0]->id))
<div class="card border-primary shadow">
    <div class="card-header text-white bg-primary">
     {{ __('panel.all_categories') }}
    </div>
        <table class="table table-hover">
            <thead class="thead-light">
              <tr>
                <th scope="col">{{__('panel.title')}}</th>
                <th scope="col">{{__('panel.img')}}</th>
                <th scope="col">{{__('panel.descr')}}</th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
                    @foreach ( $categories as $category )
                    <tr>
                            <td><a href="{{ route('blublog.categories.edit', $category->id) }}" >{{ $category->title }}</a></td>
                            <td>@if ($category->img)   {{ $category->img }}    @else <span class="badge badge-danger">{{__('panel.none')}}</span> @endif</td>
                            <td>@if ($category->descr) {{ $category->descr }}  @else <span class="badge badge-danger">{{__('panel.none')}}</span> @endif</td>
                            <td><a href=""  role="button" class="btn btn-outline-primary btn-block ">{{__('panel.view')}}</a></td>
                            <td><a href="{{ route('blublog.categories.edit', $category->id) }}" class="btn btn-outline-warning btn-block">{{__('panel.edit')}}</a></td>
                            <td>
                            {!! Form::open(['route' => ['blublog.categories.destroy', $category->id], 'method' => 'DELETE']) !!}
                            {!! form::submit(__('panel.delete'), ['class' => 'btn btn-outline-danger btn-block ' ]) !!}
                            {!! Form::close() !!}
                            </td>
                    </tr>
                    @endforeach
            </tbody>
    </table>


</div>

<hr>
<h1>{{ __('panel.categories_stats') }}:</h1>
              @foreach ( $categories as $category )
              <div class="card shadow">
                        <div class="card-header">
                        <a href="{{ route('blublog.categories.edit', $category->id) }}" >{{ $category->title }}</a>
                        </div>
                        <div class="card-body">

                          <p class="card-text">{{__('panel.numb-posts')}}
                                        @if ($category->posts()->count() >= 1)
                                        <span class="badge badge-success">{{  $category->posts()->count() }}</span>
                                        @else
                                        <span class="badge badge-danger">{{__('panel.none')}}</span>
                                        @endif</p>
                          <p class="card-text">{{__('panel.total_posts')}}
                                        @if ($category->views >= 1)
                                        <span class="badge badge-success">{{  $category->views }}</span>
                                        @else
                                        <span class="badge badge-danger">{{__('panel.none')}}</span>
                                        @endif
                        </p>
                        @if ($category->mostviewsid)
                        <p class="card-text">
                        {{__('panel.most_po_po_mai')}} <span class="badge badge-light"><a href=""  >{{  $category->mostviewstitle }}</a></span>
                                </p>
                         @endif

                        </div>

                </div><br>
              @endforeach
@else
<hr>
<center> <b>Няма добавени категории</b> </center>
@endif

@endsection
