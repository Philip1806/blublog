@forelse ($posts as $post)
    <div class="card mb-3">
        <div class="row no-gutters">
            <div class="col-lg-4">
                <a href="{{ route('blublog.front.single', $post->slug) }}"><img class="card-img"
                        src="{{ $post->thumbnailUrl() }}" alt="..."></a>
            </div>
            <div class="col-lg-8">
                <div class="card-body">
                    <h5 class="card-title"><a
                            href="{{ route('blublog.front.single', $post->slug) }}">{{ $post->title }}</a></h5>
                    <p class="card-text">{{ $post->seo_descr }}</p>
                    <p class="card-text"><small class="text-muted">
                            <span class="badge badge-primary p-1">
                                <span class="oi oi-eye"></span> {{ $post->views }}
                            </span>
                            <span class="badge badge-primary p-1">
                                <span class="oi oi-thumb-up"></span> {{ $post->likes }}
                            </span>
                            <span class="badge badge-dark text-white p-1">
                                <span class="oi oi-timer"></span> {{ $post->created_at->diffForHumans() }}
                            </span></small>
                    </p>
                </div>
            </div>
        </div>
    </div>

@empty
    <p class="lead">No posts.</p>
@endforelse
@if (!isset($noPagination))
    {{ $posts->links() }}
@endif
