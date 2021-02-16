@extends('blublog::panel.layout.main')
@section('nav')
    <ul class="nav nav-pills nav-fill bg-light m-2">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('blublog.panel.posts.create') }}"><span class="oi oi-pencil"></span> Add
                Post</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('blublog.panel.categories.index') }}"><span
                    class="oi oi-spreadsheet"></span>
                Categories</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('blublog.panel.tags') }}"><span class="oi oi-tags"></span> Tags</a>
        </li>
    </ul>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-8">
            @foreach ($categories as $category)
                <div class="card bg-dark border-dark mb-3">
                    <div class="card-header text-white ">{{ $category->title }}
                        @include('blublog::panel.categories._editCategory')
                    </div>

                    @if ($category->descr)
                        <div class="card-body bg-light text-dark">
                            <p class="card-text">{{ $category->descr }}</p>
                        </div>
                    @endif
                    @include('blublog::panel.categories._subCategories')
                </div>
            @endforeach
        </div>
        <div class="col-lg-4">

            @can('blublog_create_categories')
                <div class="card border-dark">
                    <div class="card-body">
                        {!! Form::open(['route' => 'blublog.panel.categories.store', 'method' => 'POST', 'enctype' =>
                        'multipart/form-data']) !!}

                        {{ Form::label('title', 'Category name') }}
                        {{ Form::text('title', null, ['class' => 'form-control']) }}

                        {{ Form::label('descr', 'Description') }}
                        {{ Form::text('descr', null, ['class' => 'form-control']) }}

                        {{ Form::label('img', 'Image URL') }}
                        {{ Form::text('img', null, ['class' => 'form-control']) }}

                        {{ Form::label('slug', 'Slug') }}
                        {{ Form::text('slug', null, ['class' => 'form-control']) }}

                        {{ Form::label('parent_id', 'Parent category:') }}
                        {{ Form::select('parent_id', $all_categories, null, ['class' => 'form-control']) }}

                        {{ Form::submit('Create', ['class' => 'btn btn-primary btn-block my-2']) }}
                        {!! Form::close() !!}
                    </div>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    You can not create new category.
                </div>
            @endcan
        </div>
    </div>


@endsection
