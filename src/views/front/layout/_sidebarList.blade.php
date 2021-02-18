@foreach ($posts as $post)
    <div class="card mb-2">
        <img src="{{ $post->thumbnailUrl() }}" class="card-img-top" alt="{{ $post->title }} thumbnail">
        <div class="card-body">
            <h5 class="card-title"><a style="text-decoration: none;"
                    href="{{ route('blublog.front.single', $post->slug) }}">{{ $post->title }}</a></h5>
            <p class="card-text">{{ $post->seo_title }}</p>
        </div>
    </div>
@endforeach
