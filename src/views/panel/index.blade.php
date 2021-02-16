@extends('blublog::panel.layout.main')

@section('content')
    <div class="row">
        <div class="col-lg-9">

            <div class="row">
                <div class="col-sm">
                    <div class="card bg-primary">
                        <div class="card-body">
                            <p class="lead m-0"><span class="oi oi-align-left"></span> Posts</p>
                            <h2>{{ $total_posts }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="card bg-info">
                        <div class="card-body">
                            <p class="lead m-0"><span class="oi oi-align-left"></span> My posts</p>
                            <h2>{{ $my_posts }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="card bg-success">
                        <div class="card-body">
                            <p class="lead m-0"><span class="oi oi-image"></span> Images</p>
                            <h2>{{ $total_images }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="card bg-warning">
                        <div class="card-body">
                            <p class="lead m-0"><span class="oi oi-comment-square"></span> Comments</p>
                            <h2>{{ $total_comments }}</h2>
                        </div>
                    </div>
                </div>
            </div>
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
