@foreach ($tags as $tag)
    <a href="{{ route('blublog.front.tag', $tag->slug) }}"><span class="badge m-1 p-2 badge-dark rounded-pill"><span
                class="oi oi-tags"></span>
            {{ $tag->title }}</span></a>
@endforeach
