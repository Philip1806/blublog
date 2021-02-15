@extends('blublog::panel.layout.main')

@section('content')
    <div class="row">
        <div class="col-lg-9">

        </div>
        <div class="col-lg-3">
            <a class="btn btn-primary btn-sm btn-block" href="{{ route('blublog.panel.posts.create') }}"><span
                    class="oi oi-pencil"></span> Add Post</a>
            <a class="btn btn-primary btn-sm btn-block" href="{{ route('blublog.panel.categories.index') }}"><span
                    class="oi oi-spreadsheet"></span> Categories</a>
            <a class="btn btn-primary btn-sm btn-block" href="{{ route('blublog.panel.tags') }}"><span
                    class="oi oi-tags"></span> Tags</a>
        </div>
    </div>
@endsection
