<a href="{{ route('blublog.panel.posts.show', $post->id) }}"> {{ $post->title }}
    <a href="{{ route('blublog.front.single', $post->slug) }}" class="badge badge-dark" target="_blank"><span
            class="oi oi-share-boxed"></span>
        View</a>
</a>
