@if ($category->children)
    <ul class="list-group list-group-flush">
        @foreach ($category->children as $subCategory)
            <li class="list-group-item"><span class="oi oi-arrow-right"></span><a
                    href="{{ route('blublog.front.category', $subCategory->slug) }}" style="text-decoration: none;">
                    {{ $subCategory->title }}</a>
                @if ($subCategory->children)
                    @include('blublog::front.layout._category', ['category' => $subCategory])
                @endif
            </li>
        @endforeach
    </ul>
@endif
