@extends('blublog::panel.layout.main')

@section('content')
    <div class="row">
        <div class="col-lg-9">
            <div class="row">
                <div class="col-sm">
                    <div class="card bg-primary mb-2">
                        <div class="card-body">
                            <p class="lead m-0"><span class="oi oi-align-left"></span> Posts</p>
                            <h2>{{ $total_posts }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="card bg-info mb-2">
                        <div class="card-body">
                            <p class="lead m-0"><span class="oi oi-align-left"></span> My posts</p>
                            <h2>{{ $my_posts_this_month }}/{{ $my_posts_last_month }}/{{ $my_posts }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="card bg-success mb-2">
                        <div class="card-body">
                            <p class="lead m-0"><span class="oi oi-image"></span> Images</p>
                            <h2>{{ $total_images }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="card bg-warning mb-2">
                        <div class="card-body">
                            <p class="lead m-0"><span class="oi oi-comment-square"></span> Comments</p>
                            <h2>{{ $total_comments }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @if ($trending_post)
                    <div class="col-sm">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Trending post last 7 days</h5>
                                @include('blublog::panel.posts._postname',['post'=>$trending_post])
                            </div>
                        </div>
                    </div>
                @endif
                @if ($mostPopularLastMonth)
                    <div class="col-sm">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Most popular last month</h5>
                                @include('blublog::panel.posts._postname',['post'=>$mostPopularLastMonth])
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-3">
            <a class="btn btn-primary btn-sm btn-block" href="{{ route('blublog.panel.posts.create') }}"><span
                    class="oi oi-pencil"></span> Add Post</a>
            <a class="btn btn-primary btn-sm btn-block" href="{{ route('blublog.panel.categories.index') }}"><span
                    class="oi oi-spreadsheet"></span> Categories</a>
            <a class="btn btn-primary btn-sm btn-block" href="{{ route('blublog.panel.tags') }}"><span
                    class="oi oi-tags"></span> Tags</a>
            <a class="btn btn-light btn-sm btn-block" href="{{ route('blublog.rss') }}">Generate RSS</a>
            @if (blublog_is_admin())
                @foreach ($latest_logs as $log)
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">{{ $log->type }}</h4>
                        {{ $log->message }}
                        <a href="{{ route('blublog.panel.logs.show', $log->id) }}" class="btn btn-dark btn-sm"
                            role="button" aria-pressed="true"><span class="oi oi-eye"></span> Details</a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
